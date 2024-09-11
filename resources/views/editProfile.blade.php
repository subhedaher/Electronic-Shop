@extends('parent')

@section('title', __('cms.editProfile'))

@section('content')
    @include('components.breadcrumb', ['label' => __('cms.editProfile')])
    <!-- Start Account Register Area -->
    <div class="account-login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1 col-12">
                    <div class="register-form">
                        <form class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input class="form-control" type="text" id="first_name"
                                        value="{{ auth('user')->user()->first_name }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input class="form-control" type="text" id="last_name"
                                        value="{{ auth('user')->user()->last_name }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">E-mail Address</label>
                                    <input class="form-control" type="email" id="email"
                                        value="{{ auth('user')->user()->email }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="phone_number">Phone Number</label>
                                    <input class="form-control" type="text" id="phone_number"
                                        value="{{ auth('user')->user()->phone_number }}">
                                </div>
                            </div>
                            <div class="button">
                                <button class="btn" type="button" onclick="updateProfile()">Save</button>
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
        function updateProfile() {
            axios.put('{{ route('updateProfile') }}', {
                first_name: document.getElementById('first_name').value,
                last_name: document.getElementById('last_name').value,
                email: document.getElementById('email').value,
                phone_number: document.getElementById('phone_number').value,
            }).then(function(response) {
                toastr.success(response.data.message);
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
