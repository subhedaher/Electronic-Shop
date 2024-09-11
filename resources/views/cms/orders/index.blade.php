@extends('cms.parent')

@section('title', __('cms.orders'))
@section('main-title', __('cms.orders'))
@section('breadcrumb-main', __('cms.orders'))
@section('breadcrumb-sub', __('cms.read'))

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex" style="justify-content: space-between;align-items: center">
                            <h3 class="card-title">Read Orders</h3>
                            <a href="{{ route('pdf.orders') }}" class="btn btn-primary">Print All Pdf</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Name</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Payment Method</th>
                                        <th>Created</th>
                                        <th>Settings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ ++$loop->index }}</td>
                                            <td>{{ $order->user->first_name }}</td>
                                            <td>${{ $order->total }}.00</td>
                                            <td>{{ $order->status }}</td>
                                            <td>{{ $order->payment_method }}</td>
                                            <td>{{ $order->created_at->diffForHumans() }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a type="button" href="{{ route('orderForAdmin', $order->id) }}"
                                                        class="btn btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            {{ $orders->links() }}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('scripts')
    <script></script>
@endsection
