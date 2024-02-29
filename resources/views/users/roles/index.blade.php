@extends('admin.layouts.app')

@section('content')

<div class="container-fluid p-4">
    <h2 class="container m-1">Roles</h2><br>
    <div id="errorBox"></div>
        <div class="card m-2">
            <div class="card-header">
                <div class="card-title">
                    <h5>List</h5>
                </div>
                <a class="float-right btn btn-primary btn-sm m-0" href="{{route('users.roles.create')}}"><i class="fas fa-plus"></i> Add</a>
            </div>
            <div class="card-body">
                <!--DataTables-->
                <div class="table-responsive">
                    <table id="tblData" class="table table-bordered table-striped dataTable dtr-inline">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Users</th>
                                <th>Permissions</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    <div class="row">

    </div>
</div>
@endsection

@section('customJS')
<script>
       $(document).ready(function() {
        var table = $('#tblData').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            bPaginate: false,
            bFilter: true,
            ajax: "{{route('users.roles.index')}}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'users_count',
                    name: 'users_count'
                },
                {
                    data: 'permissions_count',
                    name: 'permissions_count'
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
            ]
        });
        $('body').on('click', '#btnDel', function() {
            //confirmation
            var id = $(this).data('id');
            if (confirm('Delete Data' + id + '?') == true) {
                var route = "{{route('users.roles.destroy',':id')}}";
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
            //execute del function
        });
    });
</script>
@endsection
