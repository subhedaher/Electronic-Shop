@extends('cms.parent')

@section('title', __('cms.roles'))
@section('main-title', __('cms.roles'))
@section('breadcrumb-main', __('cms.role'))
@section('breadcrumb-sub', __('cms.edit'))

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Role</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>User Type</label>
                                    <select class="custom-select" id="guard">
                                        <option></option>
                                        @foreach ($guards as $guard)
                                            <option value="{{ $guard }}" @selected($guard == $role->guard_name)>
                                                {{ $guard }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Name"
                                        value="{{ $role->name }}">
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary"
                                    onclick="updateRole('{{ route('roles.update', $role->id) }}')">Save</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('scripts')
    <script>
        function updateRole(route) {
            axios.put(route, {
                guard: document.getElementById('guard').value,
                name: document.getElementById('name').value,
            }).then(function(response) {
                toastr.success(response.data.message);
                window.location.href = '{{ route('roles.index') }}';
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
