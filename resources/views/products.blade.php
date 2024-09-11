@extends('parent')

@section('title', __('cms.category'))

@php
    use Carbon\Carbon;
@endphp

@section('style')
    <style>
        .cont-images {
            width: 100%;
        }

        .image {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
        }
    </style>
@endsection

@section('content')
    <!-- Start Product Grids -->
    <section class="product-grids section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-12">
                    <!-- Start Product Sidebar -->
                    <div class="product-sidebar">
                        <!-- Start Single Widget -->
                        <div class="single-widget search">
                            <h3>Search Product</h3>
                            <form id="ss">
                                <input type="text" placeholder="Search Here..." id="product">
                                <button onclick="search()" type="button"><i class="lni lni-search-alt"></i></button>
                            </form>
                        </div>
                        <!-- End Single Widget -->
                        <!-- Start Single Widget -->
                        <div class="single-widget">
                            <h3>Filter by Category</h3>
                            @foreach ($categories as $category)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $category->id }}"
                                        id="{{ $category->id }}">
                                    <label class="form-check-label" for="{{ $category->id }}">
                                        {{ $category->name }} ({{ $category->products_count }})
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- End Single Widget -->
                        <!-- Start Single Widget -->
                        <div class="single-widget condition">
                            <h3>Filter by Price</h3>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="50-100" id="r1">
                                <label class="form-check-label" for="r1">
                                    $50 - $100 ({{ $productsCount50100 }})
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="100-500" id="r2">
                                <label class="form-check-label" for="r2">
                                    $100 - $500 ({{ $productsCount100500 }})
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="500-1000" id="r3">
                                <label class="form-check-label" for="r3">
                                    $500 - $1,000 ({{ $productsCount5001000 }})
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="1000-5000" id="r4">
                                <label class="form-check-label" for="r4">
                                    $1,000 - $5,000 ({{ $productsCount10005000 }})
                                </label>
                            </div>
                        </div>
                        <!-- End Single Widget -->
                    </div>
                    <!-- End Product Sidebar -->
                </div>
                <div class="col-lg-9 col-12">
                    <div class="product-grids-head">
                        <div class="product-grid-topbar">
                            <div class="row align-items-center">
                                <div class="col-lg-7 col-md-8 col-12">
                                    <div class="product-sorting">
                                        <label for="sorting">Sort by:</label>
                                        <select class="form-control" id="sorting">
                                            <option>Popularity</option>
                                            <option>Low - High Price</option>
                                            <option>High - Low Price</option>
                                            <option>Average Rating</option>
                                            <option>A - Z Order</option>
                                            <option>Z - A Order</option>
                                        </select>
                                        <h3 class="total-show-product">Showing: <span>1 - 12 items</span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-grid" role="tabpanel"
                                aria-labelledby="nav-grid-tab">
                                <div class="row" id="start">
                                    @foreach ($products as $product)
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <!-- Start Single Product -->
                                            <div class="single-product">
                                                <div class="product-image">
                                                    <div class="cont-images">
                                                        <img class="image"
                                                            src="{{ Storage::url($product->product_images[0]->url) }}"
                                                            alt="#">
                                                    </div>
                                                    @if ($product->offer && $product->offer->active)
                                                        <span
                                                            style="background: #f73232;border-radius: 2px;font-size: 12px;color: #fff;font-weight: bold;position: absolute;top: 0;padding: 5px 10px;right: 0;z-index: 22;">-{{ $product->offer->discount }}%</span>
                                                    @endif
                                                    @if (Carbon::now()->diffInDays($product->created_at) < 7)
                                                        <span class="new-tag">New</span>
                                                    @endif
                                                    <div class="button">
                                                        <button
                                                            onclick="addToCart('{{ route('createCartProducts', $product->id) }}')"
                                                            class="btn"><i class="lni lni-cart"></i>
                                                            Add to Cart</button>
                                                    </div>
                                                </div>
                                                <div class="product-info">
                                                    <span class="category">{{ $product->category->name }}</span>
                                                    <h4 class="title">
                                                        <a
                                                            href="{{ route('product', $product->slug) }}">{{ $product->name }}</a>
                                                    </h4>
                                                    <ul class="review">
                                                        @for ($i = 0; $i < round($product->ratings_avg_rating); $i++)
                                                            <li><i class="lni lni-star-filled"></i></li>
                                                        @endfor
                                                        @for ($i = 0; $i < 5 - round($product->ratings_avg_rating); $i++)
                                                            <li><i class="lni lni-star"></i></li>
                                                        @endfor
                                                        <li><span>{{ $product->ratings_count }}
                                                                Review(s)</span>
                                                        </li>
                                                    </ul>
                                                    <div class="price">
                                                        @if ($product->offer && $product->offer->active)
                                                            <span>${{ $product->price - $product->price * ($product->offer->discount * 0.01) }}.00</span>
                                                            <span class="discount-price">${{ $product->price }}.00</span>
                                                        @else
                                                            <span>${{ $product->price }}.00</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Single Product -->
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <!-- Pagination -->
                                        <div class="pagination left">
                                            <ul class="pagination-list">
                                                {{ $products->links() }}
                                            </ul>
                                        </div>
                                        <!--/ End Pagination -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Product Grids -->
@endsection

@section('scripts')
    <script>
        function addToCart(route) {
            axios.post(route)
                .then(function(response) {
                    toastr.success(response.data.message);
                    if (response.data.qua) {
                        let val = document.getElementById('countProductsCarts').innerText
                        document.getElementById('countProductsCarts').innerText = parseInt(val) + 1;
                    }
                }).catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }

        function search() {
            axios.post('{{ route('search') }}', {
                name: document.getElementById('product').value
            }).then(function(response) {
                window.location.href = response.data.message;
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }

        $(document).ready(function() {
            let categories = document.querySelectorAll('.form-check-input');
            let categoriesArray = [];
            categories.forEach(element => {
                element.addEventListener('change', function() {
                    if (element.checked) {
                        if (!categoriesArray.includes(element.value)) {
                            categoriesArray.push(element.value);
                        }
                    } else {
                        const index = categoriesArray.indexOf(element.value);
                        if (index > -1) {
                            categoriesArray.splice(index, 1);
                        }
                    }

                    $.ajax({
                        type: 'GET',
                        url: '{{ route('products') }}',
                        data: {
                            categories: categoriesArray
                        },

                        success: function(data) {
                            let productContainer = $('#start');
                            productContainer.empty();
                            data.forEach(product => {
                                console.log(product);
                            })
                        }
                    });
                });
            });
        });
    </script>
@endsection
