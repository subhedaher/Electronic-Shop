@extends('cms.parent')

@section('title', __('cms.products'))
@section('main-title', __('cms.products'))
@section('breadcrumb-main', __('cms.product'))
@section('breadcrumb-sub', __('cms.show'))

@section('content')
    <section class="content">

        <!-- Default box -->
        <div class="card card-solid">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <h3 class="d-inline-block d-sm-none">LOWA Men’s Renegade GTX Mid Hiking Boots Review</h3>
                        <div class="col-12">
                            <img src="{{ Storage::url($images[0]->url) }}" id="current" class="product-image"
                                alt="Product Image">
                        </div>
                        <div class="col-12 product-image-thumbs">
                            @foreach ($images as $image)
                                <div class="product-image-thumb active"><img src="{{ Storage::url($image->url) }}"
                                        alt="Product Image" class="imgs"></div>
                            @endforeach

                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <h3 class="my-3">{{ $product->name }}</h3>
                        <p>{{ $product->details }}</p>
                        <hr>
                        <div class="card">
                            <h5 class="card-header">Info</h5>
                            <div class="card-body">
                                <p>Category: <strong>{{ $product->category->name }}</strong></p>
                                <hr>
                                <p>Price: <strong>{{ $product->price }}</strong></p>
                                <hr>
                                <p>Quantity: <strong>{{ $product->stock_quantity }}</strong></p>
                                <hr>
                                <p>Admin: <strong>{{ $product->admin->full_name }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
@endsection

@section('scripts')
    <script>
        let current = document.getElementById('current');
        let imgs = document.querySelectorAll('.imgs');
        let opacity = 0.6;
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
    </script>
@endsection
