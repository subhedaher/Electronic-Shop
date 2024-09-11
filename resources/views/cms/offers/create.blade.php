@extends('cms.parent')

@section('title', __('cms.offers'))
@section('main-title', __('cms.offers'))
@section('breadcrumb-main', __('cms.offer'))
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
                            <h3 class="card-title">Create Offer</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Product</label>
                                    <select class="custom-select" id="product_id">
                                        <option></option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Name">
                                </div>
                                <div class="form-group">
                                    <label for="discount">Discount (%)</label>
                                    <input type="number" class="form-control" id="discount" placeholder="Enter Discount">
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="active">
                                    <label class="form-check-label" for="active">Active</label>
                                </div>

                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary" onclick="addOffer()">Add</button>
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
        function addOffer() {
            axios.post('{{ route('offers.store') }}', {
                product_id: document.getElementById('product_id').value,
                name: document.getElementById('name').value,
                discount: document.getElementById('discount').value,
                active: document.getElementById('active').checked,
            }).then(function(response) {
                toastr.success(response.data.message);
                document.getElementById('data').reset();
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
