@extends('parent')

@section('title', __('cms.orders'))
@section('style')
    <style>
        .quantity-container {
            display: flex;
            align-items: center;
        }

        .btnIn {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
            width: 40px;
            height: 40px;
        }

        button:disabled {
            background-color: #d3d3d3;
            cursor: not-allowed;
        }

        input.quantity-input {
            width: 40px;
            text-align: center;
            font-size: 18px;
            border: 1px solid #ccc;
            margin: 0;
            width: 40px;
            height: 40px;
        }
    </style>
@endsection

@section('content')

    @include('components.breadcrumb', [
        'label' => __('cms.orders'),
    ])
    @if ($orders_count > 0)
        <!-- Shopping Cart -->
        <div class="shopping-cart section">
            <div class="container">
                <div class="cart-list-head">
                    <!-- Cart List Title -->
                    <div class="cart-list-title">
                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-12">
                                <p>Order Number</p>
                            </div>
                            <div class="col-lg-3 col-md-3 col-12">
                                <p>Products</p>
                            </div>
                            <div class="col-lg-3 col-md-2 col-12">
                                <p>Total</p>
                            </div>
                            <div class="col-lg-2 col-md-2 col-12">
                                <p>Status</p>
                            </div>
                            <div class="col-lg-1 col-md-2 col-12">
                                <p>Payment Method</p>
                            </div>
                            <div class="col-lg-1 col-md-2 col-12">
                                <p>Created</p>
                            </div>
                        </div>
                    </div>
                    <!-- End Cart List Title -->
                    <!-- Cart Single List list -->
                    @foreach ($orders as $order)
                        <div class="cart-single-list">
                            <div class="row align-items-center">
                                <div class="col-lg-2 col-md-2 col-12">
                                    <p>{{ $loop->index + 1 }}</p>
                                </div>
                                <div class="col-lg-3 col-md-2 col-12">
                                    <ul>
                                        @foreach ($order->order_detailes as $detailes)
                                            <li>{{ $detailes->quantity }} -> {{ $detailes->product->name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-lg-3 col-md-2 col-12">
                                    <p>${{ $order->total }}.00</p>
                                </div>
                                <div class="col-lg-2 col-md-2 col-12">
                                    <p>{{ $order->status }}</p>
                                </div>
                                <div class="col-lg-1 col-md-2 col-12">
                                    <p>{{ $order->payment_method }}</p>
                                </div>
                                <div class="col-lg-1 col-md-2 col-12">
                                    <p>{{ $order->created_at->diffForHumans() }}</p>
                                </div>

                            </div>
                        </div>
                    @endforeach
                    <!-- End Single List list -->
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            if ('{{ session('message') }}') {
                toastr.success('{{ session('message') }}');
            }
        });

        function updateQuantity(route, cartId, change) {
            axios.put(route, {
                    chage: change
                })
                .then(function(response) {
                    const quantityInput = document.getElementById(`quantity-${cartId}`);
                    const totalElement = document.getElementById(`total-${cartId}`);
                    const pricePerUnit = parseFloat(totalElement.innerText) / parseInt(quantityInput.value);
                    const disCountElement = document.getElementById(`discount-${cartId}`);
                    let discountPerUnit = 0;
                    if (disCountElement != null) {
                        discountPerUnit = parseFloat(disCountElement.innerText) / parseInt(quantityInput.value);
                    }

                    let currentQuantity = parseInt(quantityInput.value);
                    let newQuantity = currentQuantity + change;

                    if (newQuantity < 1) {
                        newQuantity = 1;
                    }

                    quantityInput.value = newQuantity;
                    totalElement.innerText = Math.ceil(newQuantity * pricePerUnit);
                    if (disCountElement != null) {
                        disCountElement.innerText = Math.floor((newQuantity * discountPerUnit));
                    } else {
                        document.getElementById('disNo').innerText = '-';
                    }

                    const total = document.querySelectorAll(".total");
                    const discount = document.querySelectorAll(".discount");

                    let subTotal = 0;
                    total.forEach(element => {
                        subTotal += parseInt(element.innerText);
                    });

                    discount.forEach(element => {
                        subTotal -= parseInt(element.innerText);
                    });

                    let sub = document.getElementById('sub');
                    let pay = document.getElementById('pay');
                    sub.innerText = '$' + subTotal + '.00';
                    pay.innerText = '$' + (subTotal + 1) + '.00';
                }).catch(function(error) {
                    toastr.error(error.response.data.message);
                });

        }
    </script>
@endsection
