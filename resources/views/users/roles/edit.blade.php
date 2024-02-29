@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid p-4">
        <div id="errorBox"></div>
        <form action="{{ route('users.roles.update', $role->id) }}" method="POST">
            @method('patch')
            @csrf
            <div class="card m-2">
                <div class="card-header">
                    <div class="card-title">
                        <h4>Edit Role</h4>
                    </div>
                    <a class="float-right btn btn-primary btn-sm m-0" href="{{ route('users.roles.index') }}">Back</a>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="form-label">Name <span class="text-danger"></span></label>
                        <input type="text" name="name" class="form-control" placeholder="for e.g Manager" value="{{ ucfirst($role->name) }}" disabled>
                        <input type="hidden" name="name" value="{{ $role->name }}">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>

                    <div class="container m-1">
                        <label for="name" class="form-label">Assigning Permissions <span
                                class="text-danger"></span></label>
                    </div>
                    <!--DataTables-->
                    <div class="table-responsive">
                        <table id="tblData" class="table table-bordered table-striped dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <input type="checkbox" id="all_permission" name="all_permission">
                                    </th>
                                    <th>Name</th>
                                    <th>Guard</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td class="text-center">
                                            <!-- Add the text-center class here -->
                                            @if ($permission->name == 'admin.dashboard')
                                                <input type="checkbox" name="permission[{{ $permission->name }}]"
                                                    value="{{ $permission->name }}" checked onclick='return false;'>
                                            @else
                                                <?php
                                                $checked = in_array($permission->name, $rolePermissions) ? 'checked' : '';
                                                ?>
                                                <input type='checkbox' name='permission[{{ $permission->name }}]'
                                                    value='{{ $permission->name }}' class='permission' {{ $checked }}>
                                            @endif
                                        </td>
                                        <td>{{ $permission->name }}</td>
                                        <td>{{ $permission->guard_name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Role</button>
                </div>
            </div>
        </form>
    @endsection

    @section('customJS')
        <script>
            $(document).ready(function() {
                //check uncheck all function
                $(document).on('click', '[name="all_permission"]', function() {
                    if ($(this).is(":checked")) {
                        $.each($('.permission'), function() {
                            if ($(this).val() != "home") {
                                $(this).prop('checked', true);
                            }
                        });
                    } else {
                        $.each($('.permission'), function() {
                            if ($(this).val() != "home") {
                                $(this).prop('checked', false);
                            }
                        });
                    }
                });
            });
        </script>
    @endsection
