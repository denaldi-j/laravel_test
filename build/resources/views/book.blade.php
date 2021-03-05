@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title font-weight-bold" style="font-size: 18px; vertical-align: middle">Book</span>
                        <button class="btn btn-sm btn-primary float-right" id="add_book"><i class="fa fa-plus-circle"></i> Add new book</button>
                    </div>
                </div>
            </div>
        </div>

        <table class="table" id="book_table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Cover</th>
                <th scope="col" width="50%">Title</th>
                <th scope="col" width="20%">author</th>
                <th scope="col" width="20%">Category</th>
                <th scope="col" width="20%">actions</th>
            </tr>
            </thead>
        </table>

        <div class="modal fade" id="book_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Books</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="book_form" enctype="multipart/form-data"> @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label for="name" class="col-form-label">Title:</label>
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="col-form-label">Author:</label>
                                        <input type="text" class="form-control" id="author" name="author" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="col-form-label">Description:</label>
                                        <textarea class="form-control" id="description" name="description" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="col-form-label">Cover Image:</label>
                                        <input type="file" class="form-control" id="cover" name="cover">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label class="">Select Category:</label>
                                    <div style="overflow-y: auto; max-height: 350px;" id="category_list">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#book_table').DataTable({
                ajax: '{{ url("book") }}',
                columns: [
                    { data: 'cover',
                        render: function ($data) {
                            return '<img height="150" src="{{ url('cover_images') }}/'+$data+'" alt="Image"/>'
                        }
                    },
                    { data: 'title',
                        render: function (data, type, row) {
                            return '<h5><a href="{{ url("book/detail") }}/'+row.id+'">'+data+'</a></h5>'+
                                '<p>'+row.description+'</p>';
                        }
                    },
                    { data: 'author' },
                    { data: 'categories_name' },
                    { data: 'id', class: 'text-center',
                        render: function (data) {
                            return '<a href="{{ url("book/update") }}/'+data+'" id="book_edit" class="mr-2 text-warning" title="edit"><i class="fa fa-edit mr-1"></i>edit</a><br>'+
                                '<a href="{{ url("book/delete") }}/'+data+'" id="book_delete" class="text-danger" title="delete"><i class="fa fa-trash mr-1"></i>delete</a>';
                        }
                    }
                ]
            });

            $('#add_book').click(function () {
                $('#book_modal').modal('toggle');
                $('#book_form').attr('action', '{{ url("book/add") }}');
            });

            $('#book_form').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'post',
                    data: new FormData($(this)[0]),
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        console.log(response);
                    }
                }).done(function () {
                    table.ajax.reload();
                    $('#book_modal').modal('hide');
                });
            });

            $('#book_table tbody').on('click', '#book_delete', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result) {
                        $.ajax({
                            url: $(this).attr('href'),
                            type: 'delete',
                            data: { _token: '{{ csrf_token() }}'},
                            success: function (response) {
                                console.log(response);
                            }
                        }).done(function () {
                            table.ajax.reload();
                        });
                    }
                });
            });

            $('#book_table tbody').on('click', '#book_edit', function (e) {
                e.preventDefault();
                var data = table.row($(this).parents('tr')).data();
                $('#book_modal').modal('toggle');
                $('#title').val(data.title);
                $('#author').val(data.author);
                $('#description').val(data.description);
                $.each(data.category, function (k,v) {
                    $('input .form-check-input #'+v.id).prop('checked', true);
                });
                $('#book_form').attr('action', $(this).attr('href'));
            });

            $('.modal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
            });

            $('.modal').on('shown.bs.modal', function() {
                loadCategory();
            });

            function loadCategory() {
                $.ajax({
                    url: '{{ url("category/list") }}',
                    success: function (data) {
                        $.each(data, function (k,v) {
                            $('#category_list').append('<div class="form-check">\n' +
                                '  <input class="form-check-input" type="checkbox" name="category[]" value="'+v.id+'" id="'+v.id+'">\n' +
                                '  <label class="form-check-label" for="'+v.id+'">\n' + v.name+
                                '  </label>\n' +
                                '</div>');
                        });
                    }
                });
            }
        });
    </script>
@endsection
