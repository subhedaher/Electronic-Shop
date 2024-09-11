@extends('cms.parent')

@section('title', __('cms.products'))
@section('main-title', __('cms.products'))
@section('breadcrumb-main', __('cms.product'))
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
                            <h3 class="card-title">Edit Product</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="custom-select" id="category_id">
                                        <option></option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected($product->category_id == $category->id)>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Name"
                                        value="{{ $product->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="number" class="form-control" id="price" placeholder="Enter Price"
                                        value="{{ $product->price }}">
                                </div>
                                <div class="form-group">
                                    <label for="details">Details</label>
                                    <textarea class="form-control" id="details" cols="30" rows="10" placeholder="Enter Details">{{ $product->details }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="quantity">Stock Quantity</label>
                                    <input type="number" class="form-control" id="quantity" placeholder="Enter Quantity"
                                        value="{{ $product->stock_quantity }}">
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
                                <button type="button" class="btn btn-primary"
                                    onclick="updateProduct('{{ route('products.update', $product) }}')">Save</button>
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
        function updateProduct(route) {
            let images = document.getElementById('images');
            let data = new FormData();
            data.append('_method', 'PUT');
            data.append('category_id', document.getElementById('category_id').value);
            data.append('name', document.getElementById('name').value);
            data.append('price', document.getElementById('price').value);
            data.append('details', document.getElementById('details').value);
            data.append('quantity', document.getElementById('quantity').value);
            if (images == undefined) {
                data.append('images[]', '');
            } else {
                for (let image of images.files) {
                    data.append('images[]', image);
                }
            }

            axios.post(route, data)
                .then(function(response) {
                    toastr.success(response.data.message);
                    window.location.href = '{{ route('products.index') }}';
                }).catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>
@endsection
