@extends('parent')

@section('title', __('cms.favoriteProducts'))

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

    @include('components.breadcrumb', [
        'label' => __('cms.favoriteProducts'),
    ])

    <!-- Start Product Grids -->
    <section class="product-grids section">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-12">
                    <div class="product-grids-head">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-grid" role="tabpanel"
                                aria-labelledby="nav-grid-tab">
                                <div class="row" id="start">
                                    @foreach ($favoriteProducts as $favoriteProduct)
                                        <div class="col-lg-4 col-md-6 col-12" id="p-{{ $favoriteProduct->id }}">
                                            <!-- Start Single Product -->
                                            <div class="single-product">
                                                <div class="product-image">
                                                    <div class="cont-images">
                                                        <img class="image"
                                                            src="{{ Storage::url($favoriteProduct->product->product_images[0]->url) }}"
                                                            alt="#">
                                                    </div>
                                                    <button class="btn"
                                                        onclick="deleteFavoriteProduct('{{ route('deleteFavoriteProducts', $favoriteProduct->id) }}' , {{ $favoriteProduct->id }})">
                                                        <span
                                                            style="background: #f73232;border-radius: 2px;font-size: 12px;color: #fff;font-weight: bold;position: absolute;top: 0;padding: 5px 10px;right: 0;z-index: 22;"><i
                                                                class="lni lni-cross-circle"></i></span></button>
                                                    <div class="button">
                                                        <button
                                                            onclick="addToCart('{{ route('createCartProducts', $favoriteProduct->product->id) }}')"
                                                            class="btn"><i class="lni lni-cart"></i>
                                                            Add to Cart</button>
                                                    </div>
                                                </div>
                                                <div class="product-info">
                                                    <span
                                                        class="category">{{ $favoriteProduct->product->category->name }}</span>
                                                    <h4 class="title">
                                                        <a
                                                            href="{{ route('product', $favoriteProduct->product->slug) }}">{{ $favoriteProduct->product->name }}</a>
                                                    </h4>
                                                    <ul class="review">
                                                        @if (count($favoriteProduct->product->ratings) > 0)
                                                            @for ($i = 0; $i < round($favoriteProduct->product->ratings[0]->avg_rating); $i++)
                                                                <li><i class="lni lni-star-filled"></i></li>
                                                            @endfor
                                                            @for ($i = 0; $i < 5 - round($favoriteProduct->product->ratings[0]->avg_rating); $i++)
                                                                <li><i class="lni lni-star"></i></li>
                                                            @endfor
                                                            <li><span>{{ round($favoriteProduct->product->ratings[0]->count_rating) }}.0
                                                                    Review(s)</span>
                                                            </li>
                                                        @else
                                                            <li><i class="lni lni-star"></i></li>
                                                            <li><i class="lni lni-star"></i></li>
                                                            <li><i class="lni lni-star"></i></li>
                                                            <li><i class="lni lni-star"></i></li>
                                                            <li><i class="lni lni-star"></i></li>
                                                            <li><span>0.0
                                                                    Review(s)</span>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                    <div class="price">
                                                        @if ($favoriteProduct->product->offer && $favoriteProduct->product->offer->active)
                                                            <span>${{ $favoriteProduct->product->price - $favoriteProduct->product->price * ($favoriteProduct->product->offer->discount * 0.01) }}.00</span>
                                                        @else
                                                            <span>${{ $favoriteProduct->product->price }}.00</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Single Product -->
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row" id="end">
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <!-- Pagination -->
                                        <div class="pagination left">
                                            <ul class="pagination-list">
                                                {{ $favoriteProducts->links() }}
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
    <script type="text/javascript">
        function deleteFavoriteProduct(route, id) {
            axios.delete(route)
                .then(function(response) {
                    toastr.success(response.data.message);
                    document.getElementById(`p-${id}`).remove();
                    let value = document.getElementById('countProductsFavorite').innerText
                    document.getElementById('countProductsFavorite').innerText = parseInt(value) - 1;
                }).catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }

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
    </script>
@endsection
