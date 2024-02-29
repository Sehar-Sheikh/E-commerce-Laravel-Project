@extends('admin.layouts.app')

@section('content')

<div class="container-fluid p-4">
    <div class="card m-2">
        <div class="card-header">
            <div class="card-title">
                <h4>Role Details</h4>
            </div>
            <a class="float-right btn btn-primary btn-sm m-0" href="{{route('users.roles.index')}}">Back</a>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="name" class="form-label">Name <span class="text-danger"></span></label>
                <input type="text" name="name" class="form-control" placeholder="for e.g Manager"
                    value="{{ucfirst($role->name)}}" disabled>
                @if ($errors->has('name'))
                <span class="text-danger">{{$errors->first('name')}}</span>
                @endif
            </div>
            <div class="container m-1">
                <label for="name" class="form-label">Assigning Permissions <span class="text-danger"></span></label>
            </div>
            <!--DataTables-->
            <div class="table-responsive">
                <table id="tblData" class="table table-bordered table-striped dataTable dtr-inline">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Guard</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @endsection

   @section('customJS')
      <script>
        var table = $('#tblData').DataTable({
            responsive: true, processing: true, serverSide: true, autoWidth: false, bPaginate:false, bFilter:true,
            ajax: "{{route('users.roles.show' ,$role->id) }}",
            columns: [{
                    data: 'id',
                    name: 'id',
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
            ]
        });</script>
   @endsection
