@extends('parent')

@section('title', __('cms.forgotPassword'))

@section('content')
    @include('components.breadcrumb', [
        'label' => __('cms.forgotPassword'),
    ])

    <!-- Start Account Login Area -->
    <div class="account-login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1 col-12">
                    <form class="card login-form" id="data">
                        <div class="card-body">
                            <div class="title">
                                <p>You forgot your password? Here you can easily retrieve a new password.</p>
                            </div>
                            <div class="form-group input-group">
                                <label for="email">Email</label>
                                <input class="form-control" type="email" id="email">
                            </div>
                            <div class="button">
                                <button class="btn" type="button" onclick="sendEmail()">Request new password</button>
                            </div>
                            <p class="outer-link">remember password?<a href="#">Login</a>
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
        function sendEmail() {
            axios.post('{{ route('sendEmail') }}', {
                    email: document.getElementById('email').value
                })
                .then(function(response) {
                    toastr.sucess(response.data.message);
                    document.getElementById('data').reset();
                }).catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>
@endsection
