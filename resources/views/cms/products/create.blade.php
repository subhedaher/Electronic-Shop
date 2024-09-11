@extends('cms.parent')

@section('title', __('cms.products'))
@section('main-title', __('cms.products'))
@section('breadcrumb-main', __('cms.product'))
@section('breadcrumb-sub', __('cms.create'))

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Create Product</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="data" mu>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="custom-select" id="category_id">
                                        <option></option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Name">
                                </div>
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="number" class="form-control" id="price" placeholder="Enter Price">
                                </div>
                                <div class="form-group">
                                    <label for="details">Details</label>
                                    <textarea class="form-control" id="details" cols="30" rows="10" placeholder="Enter Details"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="quantity">Stock Quantity</label>
                                    <input type="number" class="form-control" id="quantity" placeholder="Enter Quantity">
                                </div>
                                <div class="form-group">
                                    <label for="images">Images</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" accept="image/*" id="images"
                                                multiple>
                                            <label class="custom-file-label" for="images">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary" onclick="addProduct()">Add</button>
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
    <script>
        function addProduct() {
            let images = document.getElementById('images').files;
            let data = new FormData();
            data.append('category_id', document.getElementById('category_id').value);
            data.append('name', document.getElementById('name').value);
            data.append('price', document.getElementById('price').value);
            data.append('details', document.getElementById('details').value);
            data.append('quantity', document.getElementById('quantity').value);
            for (let image of images) {
                data.append('images[]', image);
            }
            axios.post('{{ route('products.store') }}', data)
                .then(function(response) {
                    toastr.success(response.data.message);
                    document.getElementById('data').reset();
                }).catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>
@endsection
