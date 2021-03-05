@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <span class="card-title font-weight-bold" style="font-size: 18px; vertical-align: middle">Category</span>
                    <button class="btn btn-sm btn-primary float-right" id="add_category"><i class="fa fa-plus-circle"></i> Add new category</button>
                </div>
            </div>
        </div>
    </div>
    <table class="table" id="category_table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Category</th>
            <th scope="col" width="20%">actions</th>
        </tr>
        </thead>
    </table>

    <div class="modal fade" id="category_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="category_form"> @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="col-form-label">Category:</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" required>
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
            var table = $('#category_table').DataTable({
                ajax: '{{ url("category") }}',
                columns: [
                    { data: 'name' },
                    { data: 'id', class: 'text-center',
                        render: function (data) {
                            return '<a href="{{ url("category/update") }}/'+data+'" id="category_edit" class="mr-2 text-warning" title="edit"><i class="fa fa-edit mr-1"></i>edit</a>'+
                                '<a href="{{ url("category/delete") }}/'+data+'" id="category_delete" class="text-danger" title="delete"><i class="fa fa-trash mr-1"></i>delete</a>';
                        }
                    }
                ]
            });

            $('#add_category').click(function () {
                $('#category_modal').modal('toggle');
                $('#category_form').attr('action', '{{ url("category/add") }}');
            });

            $('#category_form').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'post',
                    data: $(this).serialize(),
                    success: function (response) {
                        console.log(response);
                    }
                }).done(function () {
                    table.ajax.reload();
                    $('#category_modal').modal('hide');
                });
            });

            $('#category_table tbody').on('click', '#category_delete', function (e) {
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

            $('#category_table tbody').on('click', '#category_edit', function (e) {
                e.preventDefault();
                var data = table.row($(this).parents('tr')).data();
                $('#category_modal').modal('toggle');
                $('#category_name').val(data.name);
                $('#category_form').attr('action', $(this).attr('href'));
            });

            $('.modal').on('hidden.bs.modal', function(){
                $(this).find('form')[0].reset();
            });
        });
    </script>
@endsection
