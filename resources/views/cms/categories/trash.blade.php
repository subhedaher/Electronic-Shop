@extends('cms.parent')

@section('title', __('cms.categories'))
@section('main-title', __('cms.categories'))
@section('breadcrumb-main', __('cms.categories'))
@section('breadcrumb-sub', __('cms.read'))

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Read Trash Categories</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Active</th>
                                        <th>Image</th>
                                        <th>Settings</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trashCategories as $category)
                                        <tr>
                                            <td>{{ ++$loop->index }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->description }}</td>
                                            <td>{{ $category->statusActive }}</td>
                                            <td><img src="{{ Storage::url($category->image) }}" width="70"
                                                    alt=""></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button"
                                                        onclick="restore('{{ route('categories.restore', $category->id) }}' , this)"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-trash-restore"></i>
                                                    </button>
                                                    <button type="button"
                                                        onclick="forceDelete('{{ route('categories.forceDelete', $category->id) }}', this)"
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
                            {{ $trashCategories->links() }}
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
        function restore(route, ref) {
            axios.put(route)
                .then(function(response) {
                    toastr.success(response.data.message);
                    ref.closest('tr').remove();
                })
                .catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }

        function forceDelete(route, ref) {
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
