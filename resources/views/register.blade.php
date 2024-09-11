@extends('parent')

@section('title', __('cms.register'))

@section('content')
    @include('components.breadcrumb', ['label' => __('cms.register')])
    <!-- Start Account Register Area -->
    <div class="account-login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1 col-12">
                    <div class="register-form">
                        <div class="title">
                            <h3>No Account? Register</h3>
                            <p>Registration takes less than a minute but gives you full control over your orders.</p>
                        </div>
                        <form class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input class="form-control" type="text" id="first_name">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input class="form-control" type="text" id="last_name">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">E-mail Address</label>
                                    <input class="form-control" type="email" id="email">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="phone_number">Phone Number</label>
                                    <input class="form-control" type="text" id="phone_number">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input class="form-control" type="password" id="password">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input class="form-control" type="password" id="password_confirmation">
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn" type="button" onclick="register()">Register</button>
                            </div>
                            <p class="outer-link">Already have an account? <a href="{{ route('login') }}">Login Now</a>
                            </p>
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
        function register() {
            axios.post('{{ route('register') }}', {
                first_name: document.getElementById('first_name').value,
                last_name: document.getElementById('last_name').value,
                email: document.getElementById('email').value,
                phone_number: document.getElementById('phone_number').value,
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value,
            }).then(function(response) {
                toastr.success(response.data.message);
                window
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
