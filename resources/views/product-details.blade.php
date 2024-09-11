@extends('parent')

@section('title', __('cms.productDetails'))

@section('style')
    <style>
        .cont-images {
            width: 100%;
        }

        .image {
            max-width: 100%;
            height: 100px;
            object-fit: cover;
        }

        .image-c {
            max-width: 100%;
            height: 400px;
            object-fit: cover;
        }
    </style>
@endsection

@section('content')
    <!-- Start Item Details -->

    @include('components.breadcrumb', [
        'label' => 'Product Details',
    ])

    <section class="item-details section">
        <div class="container">
            <div class="top-area">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12 col-12">
                        <div class="product-images">
                            <main id="gallery">
                                <div class="main-img cont-images">
                                    <img class="image-c" src="{{ Storage::url($images[0]->url) }}" id="current"
                                        alt="#">
                                </div>
                                <div class="images .cont-images">
                                    @foreach ($images as $image)
                                        <img src="{{ Storage::url($image->url) }}" class="img image" alt="#">
                                    @endforeach
                                </div>
                            </main>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-12">
                        <div class="product-info">
                            <h2 class="title">{{ $product->name }}</h2>
                            <p class="category"><i class="lni lni-tag"></i> Category: {{ $product->category->name }}
                            </p>
                            @if ($product->offer)
                                <h3 class="price">
                                    ${{ $product->price - $product->price * $product->offer->discount * 0.01 }}<span>${{ $product->price }}</span>
                                </h3>
                            @else
                                <h3 class="price">${{ $product->price }}</h3>
                            @endif
                            <div class="bottom-content">
                                <div class="row align-items-end">
                                    <div class="col-lg-4 col-md-4 col-12">
                                        <div class="button cart-button">
                                            <button class="btn"
                                                onclick="addToCart('{{ route('createCartProducts', $product->id) }}')"
                                                style="width: 100%;">Add to Cart</button>
                                        </div>
                                    </div>
                                    @if ($countv < 1)
                                        <div class="col-lg-4 col-md-4 col-12">
                                            <div class="wish-button">
                                                <button id="btn" class="btn" onclick="store()"><i
                                                        class="lni lni-heart"></i> To
                                                    Favorite</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-details-info">
                <div class="single-block">
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="info-body custom-responsive-margin">
                                <h4>Details</h4>
                                <p>{{ $product->details }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-12">
                        <div class="single-block give-review">
                            <h4>{{ round($avg) }} (Overall)</h4>
                            <ul>
                                <li>
                                    <span>5 stars - {{ $reviews5 }}</span>
                                    <i class="lni lni-star-filled"></i>
                                    <i class="lni lni-star-filled"></i>
                                    <i class="lni lni-star-filled"></i>
                                    <i class="lni lni-star-filled"></i>
                                    <i class="lni lni-star-filled"></i>
                                </li>
                                <li>
                                    <span>4 stars - {{ $reviews4 }}</span>
                                    <i class="lni lni-star-filled"></i>
                                    <i class="lni lni-star-filled"></i>
                                    <i class="lni lni-star-filled"></i>
                                    <i class="lni lni-star-filled"></i>
                                    <i class="lni lni-star"></i>
                                </li>
                                <li>
                                    <span>3 stars - {{ $reviews3 }}</span>
                                    <i class="lni lni-star-filled"></i>
                                    <i class="lni lni-star-filled"></i>
                                    <i class="lni lni-star-filled"></i>
                                    <i class="lni lni-star"></i>
                                    <i class="lni lni-star"></i>
                                </li>
                                <li>
                                    <span>2 stars - {{ $reviews2 }}</span>
                                    <i class="lni lni-star-filled"></i>
                                    <i class="lni lni-star-filled"></i>
                                    <i class="lni lni-star"></i>
                                    <i class="lni lni-star"></i>
                                    <i class="lni lni-star"></i>
                                </li>
                                <li>
                                    <span>1 star - {{ $reviews1 }}</span>
                                    <i class="lni lni-star-filled"></i>
                                    <i class="lni lni-star"></i>
                                    <i class="lni lni-star"></i>
                                    <i class="lni lni-star"></i>
                                    <i class="lni lni-star"></i>
                                </li>
                            </ul>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn review-btn" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Leave a Review
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-8 col-12">
                        <div class="single-block">
                            <div class="reviews">
                                <h4 class="title">Latest Reviews</h4>
                                @foreach ($reviews as $review)
                                    <div class="single-review">
                                        <img src="https://via.placeholder.com/150x150" alt="#">
                                        <div class="review-info">
                                            <h4>{{ $review->subject }}
                                                <span>{{ $review->user->first_name . ' ' . $review->user->last_name }}
                                                </span>
                                            </h4>
                                            <ul class="stars">
                                                @for ($i = 0; $i < $review->rating; $i++)
                                                    <li><i class="lni lni-star-filled"></i></li>
                                                @endfor
                                                @for ($i = 0; $i < 5 - $review->rating; $i++)
                                                    <li><i class="lni lni-star"></i></li>
                                                @endfor
                                            </ul>
                                            <p>{{ $review->review }}...</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Item Details -->

    <!-- Review Modal -->
    <div class="modal fade review-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Leave a Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="subject">Subject</label>
                                    <input class="form-control" type="text" id="subject">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="rating">Rating</label>
                                    <select class="form-control" id="rating">
                                        <option value="5">5 Stars</option>
                                        <option value="4">4 Stars</option>
                                        <option value="3">3 Stars</option>
                                        <option value="2">2 Stars</option>
                                        <option value="1">1 Star</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="review">Review</label>
                            <textarea class="form-control" id="review" rows="8"></textarea>
                        </div>
                    </div>
                </form>
                <div class="modal-footer button">
                    <button type="button" onclick="rating('{{ route('rating', $product->id) }}')" class="btn">Submit
                        Review</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Review Modal -->
@endsection

@section('scripts')
    <script type="text/javascript">
        const current = document.getElementById("current");
        const opacity = 0.6;
        const imgs = document.querySelectorAll(".img");
        imgs[0].style.opacity = opacity;
        imgs.forEach(img => {
            img.addEventListener("click", (e) => {
                imgs.forEach(img => {
                    img.style.opacity = 1;
                });
                current.src = e.target.src;
                e.target.style.opacity = opacity;
            });
        });

        function store() {
            axios.post('{{ route('favoriteProducts') }}', {
                user_id: '{{ auth('user')->user()->id ?? 0 }}',
                product_id: '{{ $product->id }}'
            }).then(function(response) {
                toastr.success(response.data.message);
                document.getElementById('btn').remove();
                let value = document.getElementById('countProductsFavorite').innerText
                document.getElementById('countProductsFavorite').innerText = parseInt(value) + 1;
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

        function rating(route) {
            axios.post(route, {
                    subject: document.getElementById('subject').value,
                    rating: document.getElementById('rating').value,
                    review: document.getElementById('review').value,
                })
                .then(function(response) {
                    toastr.success(response.data.message);
                    window.location.href = '{{ route('product', $product->slug) }}'
                }).catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>
@endsection
