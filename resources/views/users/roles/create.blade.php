@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid p-4">
        <div id="errorBox"></div>
        <form action="{{ route('users.roles.store') }}" method="POST">
            @csrf
            <div class="card m-2">
                <div class="card-header">
                    <div class="card-title">
                        <h4>Create Roles</h4>
                    </div>
                    <a class="float-right btn btn-primary btn-sm m-0" href="{{ route('users.roles.index') }}">Back</a>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="form-label">Name <span class="text-danger"></span></label>
                        <input type="text" name="name" class="form-control" placeholder="for e.g Manager"
                            value="{{ old('name') }}">
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
                                    <th>
                                        <input type="checkbox" id="all_permission" name="all_permission">
                                    </th>
                                    <th>Name</th>
                                    <th>Guard</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save Role</button>
                </div>
            </div>
        </form>
    @endsection

    @section('customJS')
        <script>

            $(document).ready(function() {
                // Check 'admin.dashboard' checkbox by default
                $('[value="admin.dashboard"]').prop('checked', true);

                var table = $('#tblData').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    bPaginate: false,
                    bFilter: false,
                    ajax: "{{ route('users.permissions.index') }}",
                    columns: [{
                            data: 'chkBox',
                            name: 'chkBox',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'guard_name',
                            name: 'guard_name'
                        },
                    ],
                    order: [
                        [0, "desc"]
                    ],
                    drawCallback: function(settings) {
                        // Always keep 'admin.dashboard' checked
                        $('[value="admin.dashboard"]').prop('checked', true);
                    }
                });

                // Check/uncheck all function
                $('[name="all_permission"]').on('click', function() {
                    // Always keep 'admin.dashboard' checked
                    $('[value="admin.dashboard"]').prop('checked', true);

                    if ($(this).is(":checked")) {
                        $.each($('.permission'), function() {
                            if ($(this).val() !== "admin.dashboard") {
                                $(this).prop('checked', true);
                            }
                        });
                    } else {
                        // Unchecking all other checkboxes except 'admin.dashboard'
                        $.each($('.permission'), function() {
                            if ($(this).val() !== "admin.dashboard") {
                                $(this).prop('checked', false);
                            }
                        });
                    }
                });
            });
        </script>
    @endsection
