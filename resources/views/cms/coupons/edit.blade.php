@extends('cms.parent')

@section('title', __('cms.coupons'))
@section('main-title', __('cms.coupons'))
@section('breadcrumb-main', __('cms.coupon'))
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
                            <h3 class="card-title">Edit Coupon</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Name"
                                        value="{{ $coupon->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="discount">Discount (%)</label>
                                    <input type="number" class="form-control" id="discount" placeholder="Enter Discount"
                                        value="{{ $coupon->discount }}">
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="active"
                                        @checked($coupon->active)>
                                    <label class="form-check-label" for="active">Active</label>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary"
                                    onclick="updateCoupon('{{ route('coupons.update', $coupon->id) }}')">Save</button>
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
        function updateCoupon(route) {
            axios.put(route, {
                name: document.getElementById('name').value,
                discount: document.getElementById('discount').value,
                active: document.getElementById('active').checked,
            }).then(function(response) {
                toastr.success(response.data.message);
                window.location.href = '{{ route('coupons.index') }}';
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
