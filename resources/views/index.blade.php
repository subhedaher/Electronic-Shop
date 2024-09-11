@extends('parent')

@section('title', __('cms.home'))

@php
    use Carbon\Carbon;
@endphp

@section('style')
    <style>
        .image-small {
            max-width: 100%;
            height: 50px;
            object-fit: cover;
        }

        .image-pop {
            max-width: 100%;
            height: 80px;
            object-fit: cover;
        }

        .image {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .cont-images {
            width: 100%;
        }

        .image-c {
            max-width: 220px;
            height: 220px;
            object-fit: contain;
        }
    </style>
@endsection

@section('content')
    <!-- Start Hero Area -->
    <section class="hero-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12 custom-padding-right">
                    <div class="slider-head">
                        <!-- Start Hero Slider -->
                        <div class="hero-slider">
                            <!-- Start Single Slider -->
                            @foreach ($offers as $offer)
                                <div class="single-slider"
                                    style="background-image: url({{ Storage::url($offer->product->product_images[0]->url) }});">
                                    <div class="content">
                                        <h2><span>{{ $offer->name }}</span>
                                            {{ $offer->product->name }}
                                        </h2>
                                        <p>{{ $offer->product->details }}</p>
                                        <h3><span>Combo
                                                Only:</span>{{ ceil($offer->product->price - $offer->product->price * $offer->discount * 0.01) }}
                                        </h3>
                                        <div class="button">
                                            <a href="{{ route('products') }}" class="btn">{{ __('cms.shopNow') }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <!-- End Single Slider -->
                        </div>
                        <!-- End Hero Slider -->
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-12 md-custom-padding">
                            <!-- Start Small Banner -->
                            <div class="hero-small-banner"
                                style="background-image: url('{{ Storage::url($popular_brands[0]->product_images[0]->url) }}');">
                                <div class="content">
                                    <h2>
                                        <span>Top Popular Brand</span>
                                        {{ $popular_brands[0]->name }}
                                    </h2>
                                    @if ($popular_brands[0]->offer && $popular_brands[0]->offer->active)
                                        <h3>${{ $popular_brands[0]->price - floor($popular_brands[0]->offer->discount * $popular_brands[0]->price * 0.01) }}.00
                                        </h3>
                                    @else
                                        <h3>${{ $product->price }}.00</h3>
                                    @endif
                                </div>
                            </div>
                            <!-- End Small Banner -->
                        </div>
                        <div class="col-lg-12 col-md-6 col-12 md-custom-padding">
                            <!-- Start Small Banner -->
                            <div class="hero-small-banner style2">
                                <div class="content">
                                    <h2>Weekly Sale!</h2>
                                    <p>Saving up to 50% off all online store items this week.</p>
                                    <div class="button">
                                        <a class="btn" href="{{ route('products') }}">{{ __('cms.shopNow') }}</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Start Small Banner -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Hero Area -->

    <!-- Start Featured Categories Area -->
    <section class="featured-categories section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h2>Featured Categories</h2>
                        <p>There are many variations of passages of Lorem Ipsum available, but the majority have
                            suffered alteration in some form.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($categories as $category)
                    <div class="col-lg-4 col-md-6 col-12">
                        <!-- Start Single Category -->
                        <div class="single-category">
                            <h3 class="heading">{{ $category->name }}</h3>
                            <ul>
                                @foreach ($category->products as $product)
                                    <li><a href="{{ route('product', $product->slug) }}">{{ $product->name }}</a></li>
                                @endforeach
                            </ul>
                            <div class="cont-images">
                                <img class="image-c" src="{{ Storage::url($category->image) }}" alt="#">
                            </div>
                        </div>
                        <!-- End Single Category -->
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- End Features Area -->

    <!-- Start Trending Product Area -->
    <section class="trending-product section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h2>Trending Product</h2>
                        <p>There are many variations of passages of Lorem Ipsum available, but the majority have
                            suffered alteration in some form.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($products as $product)
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="single-product">
                            <!-- Start Single Product -->
                            <div class="product-image">
                                <div class="cont-images">
                                    <img class="image" src="{{ Storage::url($product->product_images[0]->url) }}"
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
                                    <button onclick="addToCart('{{ route('createCartProducts', $product->id) }}')"
                                        class="btn"><i class="lni lni-cart"></i> Add to
                                        Cart</button>
                                </div>
                            </div>
                            <div class="product-info">
                                <span class="category">{{ $product->category->name }}</span>
                                <h4 class="title">
                                    <a href="{{ route('product', $product->slug) }}">{{ $product->name }}</a>
                                </h4>
                                <ul class="review">
                                    @for ($i = 0; $i < round($product->ratings_avg_rating); $i++)
                                        <li><i class="lni lni-star-filled"></i></li>
                                    @endfor
                                    @for ($i = 0; $i < 5 - round($product->ratings_avg_rating); $i++)
                                        <li><i class="lni lni-star"></i></li>
                                    @endfor
                                    <li><span>{{ $product->ratings_count }} Review(s)</span></li>
                                </ul>
                                <div class="price">
                                    @if ($product->offer && $product->offer->active)
                                        <span>${{ ceil($product->price - $product->price * ($product->offer->discount * 0.01)) }}.00</span>
                                        <span class="discount-price">${{ $product->price }}.00</span>
                                    @else
                                        <span>${{ $product->price }}.00</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <!-- End Single Product -->
            </div>
        </div>
    </section>
    <!-- End Trending Product Area -->

    <!-- Start Special Offer -->
    <section class="special-offer section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h2>Special Offer</h2>
                        <p>There are many variations of passages of Lorem Ipsum available, but the majority have
                            suffered alteration in some form.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="row">
                    @foreach ($offers as $offer)
                        <div class="col-lg-3 col-md-6 col-12">
                            <!-- Start Single Product -->
                            <div class="single-product">
                                <div class="product-image">
                                    <div class="cont-images">
                                        <img class="image"
                                            src="{{ Storage::url($offer->product->product_images[0]->url) }}"
                                            alt="#">
                                    </div>
                                    <div class="button">
                                        <button
                                            onclick="addToCart('{{ route('createCartProducts', $offer->product->id) }}')"
                                            class="btn"><i class="lni lni-cart"></i> Add
                                            to
                                            Cart</button>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <span class="category">{{ $offer->product->category->name }}</span>
                                    <h4 class="title">
                                        <a
                                            href="{{ route('product', $offer->product->slug) }}">{{ $offer->product->name }}</a>
                                    </h4>
                                    <ul class="review">
                                        @if (count($offer->product->ratings) > 0)
                                            @for ($i = 0; $i < round($offer->product->ratings[0]->avg_rating); $i++)
                                                <li><i class="lni lni-star-filled"></i></li>
                                            @endfor
                                            @for ($i = 0; $i < 5 - round($offer->product->ratings[0]->avg_rating); $i++)
                                                <li><i class="lni lni-star"></i></li>
                                            @endfor
                                            <li><span>{{ $offer->product->ratings[0]->count_rating }}
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
                                        <span>{{ ceil($offer->product->price - $offer->product->price * $offer->discount * 0.01) }}</span>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Product -->
                        </div>
                    @endforeach
                </div>
                <!-- Start Banner -->
            </div>
        </div>
        </div>
    </section>
    <!-- End Special Offer -->

    <!-- Start Home Product List -->
    <section class="home-product-list section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-12 custom-responsive-margin">
                    <h4 class="list-title">Best Sellers</h4>
                    <!-- Start Single List -->
                    @foreach ($bestSellers as $product)
                        <div class="single-list">
                            <div class="list-image">
                                <a href="{{ route('product', $product->slug) }}">
                                    <div class="cont-images">
                                        <img class="image-small"
                                            src="{{ Storage::url($product->product_images[0]->url) }}" alt="#">
                                    </div>
                                </a>
                            </div>
                            <div class="list-info">
                                <h3>
                                    <a href="{{ route('product', $product->slug) }}">{{ $product->name }}</a>
                                </h3>
                                <span>
                                    @if ($product->offer && $product->offer->active)
                                        {{ $product->price - floor($product->offer->discount * $product->price * 0.01) }}
                                    @else
                                        {{ $product->price }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                    <!-- End Single List -->
                </div>
                <div class="col-lg-4 col-md-4 col-12 custom-responsive-margin">
                    <h4 class="list-title">New Arrivals</h4>
                    @foreach ($new_arrivals as $product)
                        <div class="single-list">
                            <div class="list-image">
                                <a href="{{ route('product', $product->slug) }}">
                                    <div class="cont-images">
                                        <img class="image-small"
                                            src="{{ Storage::url($product->product_images[0]->url) }}" alt="#">
                                    </div>
                                </a>
                            </div>
                            <div class="list-info">
                                <h3>
                                    <a href="{{ route('product', $product->slug) }}">{{ $product->name }}</a>
                                </h3>
                                <span>
                                    @if ($product->offer && $product->offer->active)
                                        {{ $product->price - floor($product->offer->discount * $product->price * 0.01) }}
                                    @else
                                        {{ $product->price }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-lg-4 col-md-4 col-12">
                    <h4 class="list-title">Top Rated</h4>
                    <!-- Start Single List -->
                    @foreach ($top_rated as $product)
                        <div class="single-list">
                            <div class="list-image">
                                <a href="{{ route('product', $product->slug) }}">
                                    <div class="cont-images">
                                        <img class="image-small"
                                            src="{{ Storage::url($product->product_images[0]->url) }}" alt="#">
                                    </div>
                                </a>
                            </div>
                            <div class="list-info">
                                <h3>
                                    <a href="{{ route('product', $product->slug) }}">{{ $product->name }}</a>
                                </h3>
                                <span>
                                    @if ($product->offer && $product->offer->active)
                                        {{ $product->price - floor($product->offer->discount * $product->price * 0.01) }}
                                    @else
                                        {{ $product->price }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- End Home Product List -->

    <!-- Start Brands Area -->
    <div class="brands">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-12 col-12">
                    <h2 class="title">Popular Brands</h2>
                </div>
            </div>
            <div class="brands-logo-wrapper">
                <div class="brands-logo-carousel d-flex align-items-center justify-content-between">
                    @foreach ($popular_brands as $product)
                        <div class="brand-logo">
                            <div class="cont-images">
                                <img class="image-pop" src="{{ Storage::url($product->product_images[0]->url) }}"
                                    alt="#">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- End Brands Area -->

    <!-- Start Shipping Info -->
    <section class="shipping-info">
        <div class="container">
            <ul>
                <!-- Free Shipping -->
                <li>
                    <div class="media-icon">
                        <i class="lni lni-delivery"></i>
                    </div>
                    <div class="media-body">
                        <h5>Free Shipping</h5>
                        <span>On order over $99</span>
                    </div>
                </li>
                <!-- Money Return -->
                <li>
                    <div class="media-icon">
                        <i class="lni lni-support"></i>
                    </div>
                    <div class="media-body">
                        <h5>24/7 Support.</h5>
                        <span>Live Chat Or Call.</span>
                    </div>
                </li>
                <!-- Support 24/7 -->
                <li>
                    <div class="media-icon">
                        <i class="lni lni-credit-cards"></i>
                    </div>
                    <div class="media-body">
                        <h5>Online Payment.</h5>
                        <span>Secure Payment Services.</span>
                    </div>
                </li>
                <!-- Safe Payment -->
                <li>
                    <div class="media-icon">
                        <i class="lni lni-reload"></i>
                    </div>
                    <div class="media-body">
                        <h5>Easy Return.</h5>
                        <span>Hassle Free Shopping.</span>
                    </div>
                </li>
            </ul>
        </div>
    </section>
    <!-- End Shipping Info -->
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


        tns({
            container: '.hero-slider',
            slideBy: 'page',
            autoplay: true,
            autoplayButtonOutput: false,
            mouseDrag: true,
            gutter: 0,
            items: 1,
            nav: false,
            controls: true,
            controlsText: ['<i class="lni lni-chevron-left"></i>', '<i class="lni lni-chevron-right"></i>'],
        });

        tns({
            container: '.brands-logo-carousel',
            autoplay: true,
            autoplayButtonOutput: false,
            mouseDrag: true,
            gutter: 15,
            nav: false,
            controls: false,
            responsive: {
                0: {
                    items: 1,
                },
                540: {
                    items: 3,
                },
                768: {
                    items: 5,
                },
                992: {
                    items: 6,
                }
            }
        });
    </script>
    <script>
        const finaleDate = new Date("February 15, 2023 00:00:00").getTime();

        const timer = () => {
            const now = new Date().getTime();
            let diff = finaleDate - now;
            if (diff < 0) {
                document.querySelector('.alert').style.display = 'block';
            }

            let days = Math.floor(diff / (1000 * 60 * 60 * 24));
            let hours = Math.floor(diff % (1000 * 60 * 60 * 24) / (1000 * 60 * 60));
            let minutes = Math.floor(diff % (1000 * 60 * 60) / (1000 * 60));
            let seconds = Math.floor(diff % (1000 * 60) / 1000);

            days <= 99 ? days = `0${days}` : days;
            days <= 9 ? days = `00${days}` : days;
            hours <= 9 ? hours = `0${hours}` : hours;
            minutes <= 9 ? minutes = `0${minutes}` : minutes;
            seconds <= 9 ? seconds = `0${seconds}` : seconds;

            document.querySelector('#days').textContent = days;
            document.querySelector('#hours').textContent = hours;
            document.querySelector('#minutes').textContent = minutes;
            document.querySelector('#seconds').textContent = seconds;

        }
        timer();
        setInterval(timer, 1000);
    </script>
    </script>
@endsection
