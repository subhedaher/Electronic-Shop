@extends('cms.parent')

@section('title', __('cms.offers'))
@section('main-title', __('cms.offers'))
@section('breadcrumb-main', __('cms.offer'))
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
                            <h3 class="card-title">Edit Offer</h3>
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
                                            <option value="{{ $product->id }}" @selected($product->id == $offer->product_id)>
                                                {{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" value="{{ $offer->name }}" class="form-control" id="name"
                                        placeholder="Enter Name">
                                </div>
                                <div class="form-group">
                                    <label for="discount">Discount (%)</label>
                                    <input type="number" value="{{ $offer->discount }}" class="form-control" id="discount"
                                        placeholder="Enter Discount">
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" @checked($offer->active) class="form-check-input"
                                        id="active">
                                    <label class="form-check-label" for="active">Active</label>
                                </div>

                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary"
                                    onclick="updateOffer('{{ route('offers.update', $offer->id) }}')">Save</button>
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
        function updateOffer(route) {
            axios.put(route, {
                product_id: document.getElementById('product_id').value,
                name: document.getElementById('name').value,
                discount: document.getElementById('discount').value,
                active: document.getElementById('active').checked,
            }).then(function(response) {
                toastr.success(response.data.message);
                window.location.href = '{{ route('offers.index') }}';
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
