@extends('parent')

@section('title', __('cms.contact'))

@section('content')
    @include('components.breadcrumb', [
        'label' => __('cms.contact'),
    ])

    <!-- Start Contact Area -->
    <section id="contact-us" class="contact-us section">
        <div class="container">
            <div class="contact-head">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title">
                            <h2>Contact Us</h2>
                            <p>There are many variations of passages of Lorem
                                Ipsum available, but the majority have suffered alteration in some form.</p>
                        </div>
                    </div>
                </div>
                <div class="contact-info">
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-12">
                            <div class="single-info-head">
                                <!-- Start Single Info -->
                                <div class="single-info">
                                    <i class="lni lni-map"></i>
                                    <h3>Address</h3>
                                    <ul>
                                        <li>44 Shirley Ave. West Chicago,<br> IL 60185, USA.</li>
                                    </ul>
                                </div>
                                <!-- End Single Info -->
                                <!-- Start Single Info -->
                                <div class="single-info">
                                    <i class="lni lni-phone"></i>
                                    <h3>Call us on</h3>
                                    <ul>
                                        <li><a href="tel:+18005554400">+1 800 555 44 00 (Toll free)</a></li>
                                        <li><a href="tel:+321556667890">+321 55 666 7890</a></li>
                                    </ul>
                                </div>
                                <!-- End Single Info -->
                                <!-- Start Single Info -->
                                <div class="single-info">
                                    <i class="lni lni-envelope"></i>
                                    <h3>Mail at</h3>
                                    <ul>
                                        <li><a href="mailto:support@shopgrids.com">support@shopgrids.com</a>
                                        </li>
                                        <li><a href="mailto:career@shopgrids.com">career@shopgrids.com</a></li>
                                    </ul>
                                </div>
                                <!-- End Single Info -->
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-12 col-12">
                            <div class="contact-form-head">
                                <div class="form-main">
                                    <form id="form-data" class="form">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-12">
                                                <div class="form-group">
                                                    <input id="name" type="text" placeholder="Your Name">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-12">
                                                <div class="form-group">
                                                    <input id="subject" type="text" placeholder="Your Subject">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-12">
                                                <div class="form-group">
                                                    <input id="email" type="email" placeholder="Your Email">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-12">
                                                <div class="form-group">
                                                    <input id="phone_number" type="text" placeholder="Your Phone">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group message">
                                                    <textarea id="message" placeholder="Your Message"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group button">
                                                    <button type="button" onclick="sendMessage()" class="btn">Send
                                                        Message</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ End Contact Area -->
@endsection

@section('scripts')
    <script>
        function sendMessage() {
            axios.post('{{ route('message') }}', {
                    name: document.getElementById('name').value,
                    subject: document.getElementById('subject').value,
                    email: document.getElementById('email').value,
                    phone_number: document.getElementById('phone_number').value,
                    message: document.getElementById('message').value,
                })
                .then(function(response) {
                    toastr.success(response.data.message);
                    document.getElementById('form-data').reset();
                }).catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>
@endsection
