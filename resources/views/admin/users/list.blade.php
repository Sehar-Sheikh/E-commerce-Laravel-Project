@extends('admin.layouts.app')

@section('content')
    <section class="content">
        <div class="container-fluid">
            @include('admin.message')
            <div class="card">
                <div class="container-fluid p-3">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h2 class="m-2">Users</h2>
                        </div>
                        <div class="col-sm-6 text-right">
                            <a href="{{ route('users.create') }}" class="btn btn-primary m-2">New User</a>
                        </div>
                    </div>
                    <div class="row">
                        <div id="errorBox"></div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h5>List</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!--DataTables-->
                                    <div class="table-responsive">
                                        <table id="tblData"
                                            class="table table-bordered table-striped dataTable dtr-inline">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Status</th>
                                                    <th>Roles</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJS')
    <script>
        $(function() {
            // Initialize Select2 for the specific element
            $('#select2').select2();
        });


        $(document).ready(function() {
            var table = $('#tblData').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('users.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        "data": "status",
                        "render": function(data, type, row) {
                            if (data == 1) {
                                return '<svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                            } else {
                                return '<svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                            }
                        },
                        className: "text-center",
                    },
                    {
                        data: 'roles',
                        name: 'roles'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        bSortable: false,
                        className: "text-center",
                    },
                ],
                order: [
                    [0, "desc"]
                ],
            });

            $('body').on('click', '#btnDel', function() {
                var id = $(this).data('id');
                if (confirm('Delete Data' + id + '?') == true) {
                    var route = "{{ route('users.delete', ':id') }}";
                    route = route.replace(':id', id);
                    $.ajax({
                        url: route,
                        type: "delete",
                        success: function(res) {
                            console.log(res);
                            $("#tblData").DataTable().ajax.reload();
                        },
                        error: function(res) {
                            $('#errorBox').html('<div class="alert alert-danger">' + response
                                .message + '</div>');
                        }
                    });
                } else {
                    //do nothing
                }
            });
        });
    </script>
@endsection
