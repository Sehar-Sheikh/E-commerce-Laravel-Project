@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div id="errorBox"></div>
        <div class="col-3.5">
            <form method="POST" action="{{route('users.permissions.update', $permission->id)}}">
                @method('patch')
                @csrf
                <div class="card m-3">
                    <div class="card-header">
                        <div class="card-title">
                            <h5>Update</h5>
                        </div>
                        <a class="float-right btn btn-primary btn-sm m-0" href="{{route('users.permissions.index')}}">Back</a>
                    </div>
                    <div class="card-body">
                    <div class="form-group">
                <label for="name" class="form-label">Name <span class="text-danger"></span></label>
                <input type="text" class="form-control" name="name" placeholder="Enter Permission Name" value="{{ucfirst($permission->name)}}">
                @if ($errors->has('name'))
                <span class="text-danger">{{$errors->first('name')}}</span>
                @endif
                </div>

                    </div>
                    <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection




