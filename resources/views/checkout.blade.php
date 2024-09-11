@extends('parent')

@section('title', __('cms.checkout'))

@section('content')

    @include('components.breadcrumb', [
        'label' => __('cms.checkout'),
    ])

    <section class="checkout-wrapper section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="checkout-steps-form-style-1">
                        <ul id="accordionExample">
                            <li>
                                <h6 class="title" data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                    aria-expanded="true" aria-controls="collapseThree">Your Personal Details </h6>
                                <section class="checkout-steps-form-content collapse show" id="collapseThree"
                                    aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="single-form form-default">
                                                <label>User Name</label>
                                                <div class="row">
                                                    <div class="col-md-6 form-input form">
                                                        <input type="text" placeholder="First Name"
                                                            value="{{ auth('user')->user()->first_name }}"
                                                            @disabled(true)>
                                                    </div>
                                                    <div class="col-md-6 form-input form">
                                                        <input type="text" placeholder="Last Name"
                                                            value="{{ auth('user')->user()->last_name }}"
                                                            @disabled(true)>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="single-form form-default">
                                                <label>Email Address</label>
                                                <div class="form-input form">
                                                    <input type="text" placeholder="Email Address"
                                                        value="{{ auth('user')->user()->email }}"
                                                        @disabled(true)>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="single-form form-default">
                                                <label>Phone Number</label>
                                                <div class="form-input form">
                                                    <input type="text" placeholder="Phone Number"
                                                        value="{{ auth('user')->user()->phone_number }}"
                                                        @disabled(true)>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="single-form button">
                                                <button class="btn" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseFour" aria-expanded="false"
                                                    aria-controls="collapseFour">next
                                                    step</button>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </li>
                            <li>
                                <h6 class="title collapsed" data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                    aria-expanded="false" aria-controls="collapseFour">Shipping Address</h6>
                                <section class="checkout-steps-form-content collapse" id="collapseFour"
                                    aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="single-form form-default">
                                                <label>Country</label>
                                                <div class="form-input form">
                                                    <input type="text" placeholder="Country" id="country">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="single-form form-default">
                                                <label>City</label>
                                                <div class="form-input form">
                                                    <input type="text" placeholder="City" id="city">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="single-form form-default">
                                                <label>Post Code</label>
                                                <div class="form-input form">
                                                    <input type="text" placeholder="Post Code" id="post_code">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="single-form form-default">
                                                <label>Address Details</label>
                                                <div class="form-input form">
                                                    <input type="text" placeholder="Address Details"
                                                        id="address_details">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="steps-form-btn button">
                                                <button class="btn" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseThree" aria-expanded="false"
                                                    aria-controls="collapseThree">previous</button>
                                                {{-- <a href="javascript:void(0)" class="btn btn-alt">Save & Continue</a> --}}
                                                <button class="btn" onclick="order()"
                                                    style="background-color: #081828">Pay
                                                    Now</button>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </li>
                            <li>
                                <h6 class="title collapsed" data-bs-toggle="collapse" data-bs-target="#collapsefive"
                                    aria-expanded="false" aria-controls="collapsefive">Payment Info</h6>
                                <section class="checkout-steps-form-content collapse" id="collapsefive"
                                    aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="checkout-payment-form">
                                                <div class="single-form form-default">
                                                    <label>Cardholder Name</label>
                                                    <div class="form-input form">
                                                        <input type="text" placeholder="Cardholder Name">
                                                    </div>
                                                </div>
                                                <div class="single-form form-default">
                                                    <label>Card Number</label>
                                                    <div class="form-input form">
                                                        <input id="credit-input" type="text"
                                                            placeholder="0000 0000 0000 0000">
                                                        <img src="{{ asset('assets/images/payment/card.png') }}"
                                                            alt="card">
                                                    </div>
                                                </div>
                                                <div class="payment-card-info">
                                                    <div class="single-form form-default mm-yy">
                                                        <label>Expiration</label>
                                                        <div class="expiration d-flex">
                                                            <div class="form-input form">
                                                                <input type="text" placeholder="MM">
                                                            </div>
                                                            <div class="form-input form">
                                                                <input type="text" placeholder="YYYY">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="single-form form-default">
                                                        <label>CVC/CVV <span><i
                                                                    class="mdi mdi-alert-circle"></i></span></label>
                                                        <div class="form-input form">
                                                            <input type="text" placeholder="***">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="single-form form-default button">
                                                    <button class="btn">pay now</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkout-sidebar">
                        <div class="checkout-sidebar-coupon">
                            <p>Appy Coupon to get discount!</p>
                            <form>
                                <div class="single-form form-default">
                                    <div class="form-input form">
                                        <input type="text" id="couponValue" placeholder="Coupon Code">
                                    </div>
                                    <div class="button">
                                        <button onclick="coupon()" type="button" class="btn">apply</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="checkout-sidebar-price-table mt-30">
                            <h5 class="title">Pricing Table</h5>

                            <div class="sub-total-price">
                                <div class="total-price">
                                    <p class="value">Subtotal Price:</p>
                                    <p class="price">${{ $subTotal }}.00</p>
                                </div>
                            </div>

                            <div class="sub-total-price">
                                <div class="total-price">
                                    <p class="value">Shipping:</p>
                                    <p class="price">Free</p>
                                </div>
                            </div>

                            <div class="sub-total-price">
                                <div class="total-price">
                                    <p class="value">Tax:</p>
                                    <p class="price">$1.00</p>
                                </div>
                            </div>

                            <div class="total-payable">
                                @if (Session::has('coupon'))
                                    <div class="payable-price">
                                        <p class="value">Coupon:</p>
                                        <p class="price">${{ $subTotal * Session::get('coupon')->discount * 0.01 }}.00</p>
                                    </div>
                                @endif
                                <div class="payable-price">

                                    <p class="value">Total:</p>
                                    @if (Session::has('coupon'))
                                        <p class="price">
                                            ${{ $subTotal - $subTotal * Session::get('coupon')->discount * 0.01 + 1 }}.00
                                        </p>
                                    @else
                                        <p class="price">
                                            ${{ $subTotal + 1 }}.00
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="checkout-sidebar-banner mt-30">
                            <a href="product-grids.html">
                                <img src="https://via.placeholder.com/400x330" alt="#">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        function coupon() {
            axios.post('{{ route('coupon') }}', {
                name: document.getElementById('couponValue').value,
            }).then(function(response) {
                toastr.success(response.data.message);
                window.location.href = '{{ route('checkout.index') }}';
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }

        function order() {
            axios.post('{{ route('order') }}', {
                total: '{{ $subTotal + 1 }}',
                country: document.getElementById('country').value,
                city: document.getElementById('city').value,
                post_code: document.getElementById('post_code').value,
                address_details: document.getElementById('address_details').value,
            }).then(function(response) {
                toastr.success(response.data.message);
                window.location.href = '{{ route('orders') }}';
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
