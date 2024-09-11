@extends('cms.parent')

@section('title', __('cms.products'))
@section('main-title', __('cms.products'))
@section('breadcrumb-main', __('cms.products'))
@section('breadcrumb-sub', __('cms.read'))

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Read Products</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>Quantity</th>
                                        <th>Category</th>
                                        <th>Admin</th>
                                        <th>Rating</th>
                                        <th>Settings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{ ++$loop->index }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->price }}</td>
                                            <td>{{ $product->offer->discount ?? '-' }}
                                                {{ $product->offer ? '%' : '' }}</td>
                                            <td>{{ $product->stock_quantity }}</td>
                                            <td>{{ $product->category->name }}</td>
                                            <td>{{ $product->admin->full_name }}</td>
                                            <td>
                                                {{ round($product->ratings_avg_rating) }}
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a type="button" href="{{ route('products.show', $product->slug) }}"
                                                        class="btn btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a type="button" href="{{ route('products.edit', $product->slug) }}"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button"
                                                        onclick="deleteProduct('{{ route('products.destroy', $product) }}' , this)"
                                                        class="btn btn-danger">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            @can('Trash-Products')
                                <a href="{{ route('products.trash') }}" class="btn btn-primary">Trash</a>
                            @endcan
                            {{ $products->links() }}
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
    <script>
        function deleteProduct(route, ref) {
            axios.delete(route)
                .then(function(response) {
                    toastr.success(response.data.message);
                    ref.closest('tr').remove();
                })
                .catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>
@endsection
