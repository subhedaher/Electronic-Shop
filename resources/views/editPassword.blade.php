@extends('parent')

@section('title', __('cms.editPassword'))

@section('content')
    @include('components.breadcrumb', ['label' => __('cms.editPassword')])
    <!-- Start Account Register Area -->
    <div class="account-login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1 col-12">
                    <div class="register-form">
                        <form class="row" id="data">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="oldPassword">Old Password</label>
                                    <input class="form-control" type="password" id="oldPassword">
                                </div>
                                <div class="form-group">
                                    <label for="newPassword">New Password</label>
                                    <input class="form-control" type="password" id="newPassword">
                                </div>
                                <div class="form-group">
                                    <label for="newPassword_confirmation">New Password Confirmation</label>
                                    <input class="form-control" type="password" id="newPassword_confirmation">
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn" type="button" onclick="updatePassword()">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Account Register Area -->
@endsection

@section('scripts')
    <script>
        function updatePassword() {
            axios.put('{{ route('updatePassword') }}', {
                oldPassword: document.getElementById('oldPassword').value,
                newPassword: document.getElementById('newPassword').value,
                newPassword_confirmation: document.getElementById('newPassword_confirmation').value,
            }).then(function(response) {
                toastr.success(response.data.message);
                document.getElementById('data').reset();
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
