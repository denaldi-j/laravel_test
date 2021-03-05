<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        return DataTables::of($category)->toJson();
    }

    protected function validasiData($data)
    {
        $pesan = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute tidak boleh sama'
        ];
        return validator($data, [
            'name' => 'required|unique:categories',
        ], $pesan);
    }

    public function store(Request $request)
    {
        $data = array(
            'name' => $request->category_name,
        );

        $validasi = $this->validasiData($data);
        if($validasi->passes()){
            $category = new Category();
            $category->name = $request->category_name;
            $msg = $category->save() ? ['success' => 'Berhasil ditambahkan!'] : ['error' => 'Gagal menyimpan!'];
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

    public function get()
    {
        $category = Category::all();
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::where('id', $id)->first();
        $category->name = $request->category_name;
        $msg = $category->update() ? ['success' => 'Berhasil diupdate!'] : ['error' => 'Gagal!'];
        return response()->json($msg);
    }

    public function delete($id)
    {
        $category = Category::where('id', $id)->delete();
        $msg = $category ? ['success' => 'Berhasil dihapus!'] : ['error' => 'Gagal!'];
        return response()->json($msg);
    }
}
