<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BooksLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class BookController extends Controller
{
    public function index()
    {
        $book = Book::with('category')->get();
        return DataTables::of($book)
            ->addColumn('categories_name', function ($book) {
                $list = [];
                for ($i=0; $i<count($book->category); $i++) {
                    $list[] = $book->category[$i]->name;
                }
                return implode(', ', $list);
            })
            ->toJson();
    }

    protected function validasiData($data)
    {
        $pesan = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama'
        ];
        return validator($data, [
            'title' => 'required',
            'description' => 'required',
            'author' => 'required',
            'cover' => 'required',
        ], $pesan);
    }

    public function uploadFile($files)
    {
        $file_mime = $files->getMimeType();
        $mimetype = [
            'image/jpeg', 'image/png'
        ];

        if (in_array($file_mime, $mimetype)) {
            $name = pathinfo($files->getClientOriginalName(),PATHINFO_FILENAME);
            $ext = $files->getClientOriginalExtension();
            $filename = $name.'-'. uniqid().'.'.$ext;
            $upload = $files->move('cover_images', $filename);
            if ($upload) {
                return [
                    'status' => true,
                    'filename' => $filename
                ];
            }
        } else {
            return [
                'status' => false,
            ];
        }
    }

    public function store(Request $request)
    {
        $data = array(
            'title' => $request->title,
            'description' => $request->description,
            'author' => $request->author,
            'cover' => $request->cover,
        );

        $validasi = $this->validasiData($data);

        if($validasi->passes()){
            $book = new Book();
            $book->title = $request->title;
            $book->description = $request->description;
            $book->author = $request->author;


            $upload = $this->uploadFile($request->cover);
            if ($upload['status'] === true) {
                $name = $upload['filename'];
                $book->cover = $name;
            }

            if ($book->save()) {
                $this->addBookToLibrary($book->id, $request->category);
                $msg = ['success' => 'Berhasil ditambahkan!'];
            } else {
                $msg = ['error' => 'Gagal menyimpan!'];
            }

        }else{
            $pesan = $validasi->getMessageBag()->messages();
            $err = array();
            foreach ($pesan as $key=>$item) {
                $err[] = $item[0];
            }
            $msg = ['error' => $err];
        }

        return response()->json($msg);
    }

    public function update(Request $request, $id)
    {
        $book = Book::where('id', $id)->first();
        $book->title = $request->title;
        $book->description = $request->description;
        $book->author = $request->author;
        $upload = ($request->cover !== null) ? $this->uploadFile($request->cover) : '';
        if ($upload && $upload['status'] == true) {
            $this->removeFile($book->cover);
            $name = $upload['filename'];
            $book->cover = $name;
        }

        $this->addBookToLibrary($book->id, $request->category);
        $msg = $book->update() ? ['success' => 'Berhasil diupdate!'] : ['error' => 'Gagal!'];
        return response()->json($msg);
    }

    public function delete($id)
    {
        $book = Book::where('id', $id)->first();
        $this->removeFile($book->cover);
        $msg = $book->delete() ? ['success' => 'Berhasil dihapus!'] : ['error' => 'Gagal!'];
        return response()->json($msg);
    }

    public function removeFile($filename)
    {
        $file_path = url('cover_images/'. $filename);
        if(File::exists($file_path)) {
            File::delete($file_path);
        }
    }

    public function addBookToLibrary($id, $category)
    {
        foreach ($category as $item) {
            $library = BooksLibrary::firstOrCreate([
                'book_id' => $id,
                'category_id' => $item
            ]);
        }

    }
}
