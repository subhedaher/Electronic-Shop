@extends('parent')

@section('title', __('cms.carts'))

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
        'label' => __('cms.carts'),
    ])
    @if ($countCarts > 0)
        <!-- Shopping Cart -->
        <div class="shopping-cart section">
            <div class="container">
                <div class="cart-list-head">
                    <!-- Cart List Title -->
                    <div class="cart-list-title">
                        <div class="row">
                            <div class="col-lg-1 col-md-1 col-12">
                            </div>
                            <div class="col-lg-3 col-md-3 col-12">
                                <p>Product Name</p>
                            </div>
                            <div class="col-lg-3 col-md-2 col-12">
                                <p>Quantity</p>
                            </div>
                            <div class="col-lg-2 col-md-2 col-12">
                                <p>Subtotal</p>
                            </div>
                            <div class="col-lg-2 col-md-2 col-12">
                                <p>Discount</p>
                            </div>
                            <div class="col-lg-1 col-md-2 col-12">
                                <p>Remove</p>
                            </div>
                        </div>
                    </div>
                    <!-- End Cart List Title -->
                    <!-- Cart Single List list -->
                    @foreach ($productsCart as $cart)
                        <div class="cart-single-list" id="c-{{ $cart->id }}">
                            <div class="row align-items-center">
                                <div class="col-lg-1 col-md-1 col-12">
                                    <img src="{{ Storage::url($cart->product->product_images[0]->url) }}" alt="#">
                                </div>
                                <div class="col-lg-3 col-md-3 col-12">
                                    <h5 class="product-name"><a href="{{ route('product', $cart->product->slug) }}">
                                            {{ $cart->product->name }}</a></h5>
                                    <p class="product-des">
                                        <span><em>Type:</em> {{ $cart->product->category->name }}</span>
                                    </p>
                                </div>
                                <div class="col-lg-3 col-md-2 col-12">
                                    <div class="quantity-container">
                                        <button class="decrease btnIn"
                                            onclick="updateQuantity('{{ route('updateCart', $cart->id) }}',{{ $cart->id }}, -1)">-</button>
                                        <input type="text" class="quantity-input" id="quantity-{{ $cart->id }}"
                                            value="{{ $cart->quantity }}" readonly>
                                        <button class="increase btnIn"
                                            onclick="updateQuantity('{{ route('updateCart', $cart->id) }}',{{ $cart->id }}, 1)">+</button>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-12">
                                    <p>$<span class="total"
                                            id="total-{{ $cart->id }}">{{ $cart->quantity * $cart->product->price }}</span>.00
                                    </p>
                                </div>
                                <div class="col-lg-2 col-md-2 col-12">
                                    <p>
                                        @if ($cart->product->offer && $cart->product->offer->active)
                                            $<span class="discount" id="discount-{{ $cart->id }}">
                                                {{ floor($cart->product->offer->discount * $cart->product->price * 0.01 * $cart->quantity) }}
                                            </span>.00
                                        @else
                                            <span id="disNo">-</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-lg-1 col-md-2 col-12">
                                    <form method="POST" action="{{ route('deleteCart', $cart->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn" type="submit"><i class="lni lni-close"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- End Single List list -->
                </div>
                <div class="row">
                    <div class="col-12">
                        <!-- Total Amount -->
                        <div class="total-amount">
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="right">
                                        <ul>
                                            <li>Cart Subtotal<span id="sub">${{ $subTotal }}.00</span></li>
                                            <li>Shipping<span>Free</span></li>
                                            <li>Tax<span>$1.00</span></li>
                                            <li class="last">You Pay<span id="pay">${{ $subTotal + 1 }}.00</span>
                                            </li>
                                        </ul>
                                        <div class="button">
                                            <a href="{{ route('checkout.index') }}" class="btn">Checkout</a>
                                            <a href="{{ route('products') }}" class="btn btn-alt">Continue shopping</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ End Total Amount -->
                    </div>
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
