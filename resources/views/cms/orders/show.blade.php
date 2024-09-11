@extends('cms.parent')

@section('title', __('cms.orders'))
@section('main-title', __('cms.order'))
@section('breadcrumb-main', __('cms.order'))
@section('breadcrumb-sub', __('cms.read'))

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" style="display: flex;justify-content: space-between;align-items: center">
                            <h3 class="card-title">Read Order - {{ $order->id }}</h3>
                            <a href="{{ route('pdf.order', $order->id) }}" class="btn btn-primary">Print Pdf</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="card col-6 col-sm-6">
                                    <h5 class="card-header">User Detailes</h5>
                                    <div class="card-body">
                                        <p>First Name: <strong>{{ $order->user->first_name }}</strong></p>
                                        <hr>
                                        <p>Last Name: <strong>{{ $order->user->last_name }}</strong></p>
                                        <hr>
                                        <p>Email: <strong>{{ $order->user->email }}</strong></p>
                                        <hr>
                                        <p>Phone Number: <strong>{{ $order->user->phone_number }}</strong></p>
                                        <hr>
                                    </div>
                                </div>
                                <div class="card col-6 col-sm-6">
                                    <h5 class="card-header">Order Detailes</h5>
                                    <div class="card-body">
                                        <p>Status: <strong>{{ $order->status }}</strong></p>
                                        <hr>
                                        <p>Total: <strong>${{ $order->total }}.00</strong></p>
                                        <hr>
                                        <p>Payment Method: <strong>{{ $order->payment_method }}</strong></p>
                                        <hr>
                                        <p>Created: <strong>{{ $order->created_at->diffForHumans() }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->order_detailes as $detailes)
                                        <tr>
                                            <td>{{ ++$loop->index }}</td>
                                            <td>{{ $detailes->product->name }}</td>
                                            <td>{{ $detailes->quantity }}</td>
                                            <td>{{ $detailes->price }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="col-12 col-sm-6">
                                <hr>
                                <div class="card">
                                    <h5 class="card-header">Shipping Address Detailes</h5>
                                    <div class="card-body">
                                        <p>Country: <strong>{{ $order->shipping_address->country }}</strong></p>
                                        <hr>
                                        <p>City: <strong>{{ $order->shipping_address->city }}</strong></p>
                                        <hr>
                                        <p>Post Code: <strong>{{ $order->shipping_address->post_code }}</strong></p>
                                        <hr>
                                        <p>Address Details:
                                            <strong>{{ $order->shipping_address->address_details }}</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @if ($order->status === 'pending')
                                <button type="button"
                                    onclick="changeStatus('{{ route('changeStatus', $order->id) }}','delivering')"
                                    class="btn bg-gradient-primary">Delivering</button>
                                <button type="button"
                                    onclick="changeStatus('{{ route('changeStatus', $order->id) }}','completed')"
                                    class="btn bg-gradient-success">Completed</button>
                                <button type="button"
                                    onclick="changeStatus('{{ route('changeStatus', $order->id) }}','canceled')"
                                    class="btn bg-gradient-danger">Canceled</button>
                            @elseif ($order->status === 'delivering')
                                <button type="button"
                                    onclick="changeStatus('{{ route('changeStatus', $order->id) }}','completed')"
                                    class="btn bg-gradient-success">Completed</button>
                                <button type="button"
                                    onclick="changeStatus('{{ route('changeStatus', $order->id) }}','canceled')"
                                    class="btn bg-gradient-danger">Canceled</button>
                            @endif
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('scripts')
    <script>
        function changeStatus(route, change) {
            axios.put(route, {
                status: change,
            }).then(function(response) {
                toastr.success(response.data.message);
                window.location.href = '{{ route('ordersForAdmin') }}';
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
