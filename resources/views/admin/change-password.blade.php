@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Change Password</h1>
                </div>
                <div class="col-sm-6 text-right">
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('admin.message')
            <form action="" method="POST" id="changePasswordForm" name="changePasswordForm">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="old_password">Old Password</label>
                                    <input type="password" name="old_password" id="old_password" class="form-control"
                                        placeholder="Old Password">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="new_password">New Password</label>
                                    <input type="password" name="new_password" id="new_password" class="form-control"
                                        placeholder="New Password">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                                        placeholder="Confirm Password">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJS')
    <script>
        $("#changePasswordForm").submit(function(event) {
            event.preventDefault();
            $("#submit").prop('disabled', true);

            $.ajax({
                url: '{{ route('admin.processChangePassword') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
            $("#submit").prop('disabled', false);

                    if (response.status == true) {
                        window.location.href = "{{ route('admin.showChangePasswordForm') }}";

                    } else {
                        var errors = response.errors;
                        if (errors.old_password) {
                            $("#old_password").addClass('is-invalid')
                                .siblings('p').html(errors.old_password)
                                .addClass('invalid-feedback');
                        } else {
                            $("#old_password").removeClass('is-invalid').siblings('p').html('')
                                .removeClass('invalid-feedback');
                        }
                        if (errors.new_password) {
                            $("#new_password").addClass('is-invalid')
                                .siblings('p').html(errors.new_password)
                                .addClass('invalid-feedback');
                        } else {
                            $("#new_password").removeClass('is-invalid').siblings('p').html('')
                                .removeClass('invalid-feedback');
                        }
                        if (errors.confirm_password) {
                            $("#confirm_password").addClass('is-invalid')
                                .siblings('p').html(errors.confirm_password)
                                .addClass('invalid-feedback');
                        } else {
                            $("#confirm_password").removeClass('is-invalid').siblings('p').html('')
                                .removeClass('invalid-feedback');
                        }

                    }
                }
            });
        });
    </script>
@endsection
