@extends('cms.parent')

@section('title', __('cms.editProfile'))

@section('main-title', __('cms.editProfile'))
@section('breadcrumb-main', __('cms.profile'))
@section('breadcrumb-sub', __('cms.edit'))

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Profile</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="full_name">Full Name</label>
                                    <input type="text" class="form-control" id="full_name" placeholder="Enter Full Name"
                                        value="{{ auth()->user()->full_name }}">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="Enter Email"
                                        value="{{ auth()->user()->email }}">
                                </div>
                                <div class="form-group">
                                    <label for="phone_number">Phone Number</label>
                                    <input type="text" class="form-control" id="phone_number"
                                        placeholder="Enter Phone Number" value="{{ auth()->user()->phone_number }}">
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" placeholder="Enter Address"
                                        value="{{ auth()->user()->address }}">
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary" onclick="updateProfile()">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('scripts')
    <script>
        function updateProfile() {
            axios.put('{{ route('auth.updateProfile') }}', {
                    full_name: document.getElementById('full_name').value,
                    email: document.getElementById('email').value,
                    phone_number: document.getElementById('phone_number').value,
                    address: document.getElementById('address').value,
                })
                .then(function(response) {
                    toastr.success(response.data.message);
                }).catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>
@endsection
