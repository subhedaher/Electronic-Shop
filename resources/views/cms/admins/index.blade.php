@extends('cms.parent')

@section('title', __('cms.admins'))
@section('main-title', __('cms.admins'))
@section('breadcrumb-main', __('cms.admins'))
@section('breadcrumb-sub', __('cms.read'))

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Read Admins</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Full Name</th>
                                        <th>Role</th>
                                        <th>Phone Number</th>
                                        <th>Address</th>
                                        <th>Settings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admins as $admin)
                                        <tr>
                                            <td>{{ ++$loop->index }}</td>
                                            <td>{{ $admin->full_name }}</td>
                                            <td>{{ $admin->roles[0]->name ?? '-' }}</td>
                                            <td>{{ $admin->phone_number }}</td>
                                            <td>{{ $admin->address }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a type="button" href="{{ route('admins.edit', $admin) }}"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button"
                                                        onclick="deleteAdmin('{{ route('admins.destroy', $admin) }}' , this)"
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
                            {{ $admins->links() }}
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
        function deleteAdmin(route, ref) {
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
