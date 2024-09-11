@extends('parent')

@section('title', __('cms.login'))

@section('content')
    @include('components.breadcrumb', [
        'label' => __('cms.login'),
    ])
    <!-- Start Account Login Area -->
    <div class="account-login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1 col-12">
                    <form class="card login-form" method="post">
                        <div class="card-body">
                            <div class="title">
                                <h3>Login Now</h3>
                                <p>You can login using your social media account or email address.</p>
                            </div>
                            <div class="social-login">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-12"><a class="btn facebook-btn"
                                            href="javascript:void(0)"><i class="lni lni-facebook-filled"></i> Facebook
                                            login</a></div>
                                    <div class="col-lg-4 col-md-4 col-12"><a class="btn twitter-btn"
                                            href="javascript:void(0)"><i class="lni lni-twitter-original"></i> Twitter
                                            login</a></div>
                                    <div class="col-lg-4 col-md-4 col-12"><a class="btn google-btn"
                                            href="javascript:void(0)"><i class="lni lni-google"></i> Google login</a>
                                    </div>
                                </div>
                            </div>
                            <div class="alt-option">
                                <span>Or</span>
                            </div>
                            <div class="form-group input-group">
                                <label for="email">Email</label>
                                <input class="form-control" type="email" id="email">
                            </div>
                            <div class="form-group input-group">
                                <label for="password">Password</label>
                                <input class="form-control" type="password" id="password">
                            </div>
                            <div class="d-flex flex-wrap justify-content-between bottom-content">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input width-auto" id="remember_me">
                                    <label class="form-check-label" for="remember_me">Remember me</label>
                                </div>
                                <a class="lost-pass" href="{{ route('forgotPassword') }}">Forgot password?</a>
                            </div>
                            <div class="button">
                                <button class="btn" type="button" onclick="login()">Login</button>
                            </div>
                            <p class="outer-link">Don't have an account? <a href="{{ route('register') }}">Register here
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Account Login Area -->
@endsection

@section('scripts')
    <script>
        function login() {
            axios.post('{{ route('login') }}', {
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                remember_me: document.getElementById('remember_me').checked,
            }).then(function(response) {
                toastr.success(response.data.message);
                window.location.href = '{{ route('index') }}';
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
