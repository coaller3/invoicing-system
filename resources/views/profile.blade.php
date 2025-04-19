@extends('layout.app')

@section('customstyle')
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">User Profile</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" value="{{ $datas->name }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" value="{{ $datas->email }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Role</label>
                                    <input type="text" class="form-control" value="{{ $datas->role }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Status</label>
                                    <input type="text" class="form-control" value="{{ $datas->status }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer" align="center">

                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#change-password-modal">
                            Change Password
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="change-password-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change Password</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="password_form" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" class="form-control" name="new_password" id="new_password"  placeholder="Enter New Password" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Enter Confirm Password" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('pagespecificscripts')
<script>
    $(function(){

        $('#password_form').submit(function(e) {

            e.preventDefault();
            var form = $(this)[0];
            var formData = new FormData(this);
            var buttons = [
                $(this).find('button[type="submit"]'),
                $(this).find('a.btn-primary'),  // Back button
                // Add more buttons if needed
            ];

            handleAjaxRequest({

                url: "{{ url('users') }}" + "/{{ $datas->id }}/change_password",
                data: formData,
                button: buttons,
                form: form,
                loadingTitle: 'Changing Password......',
                successTitle: 'Password Changed',
                redirectUrl: "{{ url('profile') }}" + "/{{ $datas->id }}",
                redirectPage: true,
                beforeAjax: function() {

                    if($('#new_password').val() != $('#confirm_password').val()){
                        Swal.fire(
                            'Error!',
                            "Confirm Password Not Match!",
                            'error'
                        )
                        return false;
                    }

                    formData.append("_method", "PUT");

                    // You can do any other pre-AJAX tasks here
                    // Return false to prevent AJAX call
                    // Return true or undefined to proceed
                    return true;

                }

            });

        });
    })
</script>
@endsection
