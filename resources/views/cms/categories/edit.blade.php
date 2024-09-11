@extends('cms.parent')

@section('title', __('cms.categories'))
@section('main-title', __('cms.categories'))
@section('breadcrumb-main', __('cms.category'))
@section('breadcrumb-sub', __('cms.edit'))

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Category</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Name"
                                        value="{{ $category->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" class="form-control" id="description"
                                        placeholder="Enter Description" value="{{ $category->description }}">
                                </div>
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="image">
                                            <label class="custom-file-label" for="image">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" @checked($category->active) class="form-check-input"
                                        id="active">
                                    <label class="form-check-label" for="active">Active</label>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary"
                                    onclick="updateCategory('{{ route('categories.update', $category->slug) }}')">Save</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>
        $(function() {
            bsCustomFileInput.init();
        });

        function updateCategory(route) {
            let image = document.getElementById('image').files[0];
            let data = new FormData();
            data.append('_method', 'PUT');
            data.append('name', document.getElementById('name').value);
            data.append('description', document.getElementById('description').value);
            if (image == undefined) {
                data.append('image', '')
            } else {
                data.append('image', document.getElementById('image').files[0]);
            }
            data.append('active', document.getElementById('active').checked ? 1 : 0);
            axios.post(route, data)
                .then(function(response) {
                    toastr.success(response.data.message);
                    window.location.href = '{{ route('categories.index') }}';
                }).catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>
@endsection
