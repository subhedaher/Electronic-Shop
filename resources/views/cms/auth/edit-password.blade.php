@extends('cms.parent')

@section('title', __('cms.editPassword'))

@section('main-title', __('cms.editPassword'))
@section('breadcrumb-main', __('cms.password'))
@section('breadcrumb-sub', __('cms.edit'))

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Password</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="oldPassword">Old Password</label>
                                    <input type="password" class="form-control" id="oldPassword"
                                        placeholder="Enter Old Password">
                                </div>
                                <div class="form-group">
                                    <label for="newPassword">New Password</label>
                                    <input type="password" class="form-control" id="newPassword"
                                        placeholder="Enter New Password">
                                </div>
                                <div class="form-group">
                                    <label for="newPassword_confirmation">New Password Confirmation</label>
                                    <input type="password" class="form-control" id="newPassword_confirmation"
                                        placeholder="Enter New Password Confirmation">
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="button" onclick="updatePassword()" class="btn btn-primary">Save</button>
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
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    <script>
        function updatePassword() {
            axios.put('{{ route('auth.updatePassword') }}', {
                    oldPassword: document.getElementById('oldPassword').value,
                    newPassword: document.getElementById('newPassword').value,
                    newPassword_confirmation: document.getElementById('newPassword_confirmation').value,
                })
                .then(function(response) {
                    toastr.success(response.data.message);
                    document.getElementById('data').reset();
                }).catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>
@endsection
