@extends('parent')

@section('title', __('cms.createPassword'))

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
                                    <label for="password">New Password</label>
                                    <input class="form-control" type="password" id="password">
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">New Password Confirmation</label>
                                    <input class="form-control" type="password" id="password_confirmation">
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn" type="button" onclick="changePassword()">Save</button>
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
        function changePassword() {
            axios.put('{{ route('auth.changePassword') }}', {
                token: '{{ $token }}',
                email: '{{ $email }}',
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value,
            }).then(function(response) {
                toastr.success(response.data.message);
                window.location.href = '{{ route('login') }}';
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
