@extends('layout.app')

@section('customstyle')
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Client Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('clients') }}">Client</a></li>
                    <li class="breadcrumb-item active">Client Details</li>
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
                        <h3 class="card-title">Client Details</h3>
                    </div>
                    <form id="edit_form" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Name <b style="color: red;">*</b></label>
                                        <input type="text" class="form-control" value="{{ $datas->name }}" name="name" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Email <b style="color: red;">*</b></label>
                                        <input type="email" class="form-control" value="{{ $datas->email }}" name="email" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Phone <b style="color: red;">*</b></label>
                                        <input type="number" class="form-control" value="{{ $datas->phone }}" name="phone" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Company <b style="color: red;">*</b></label>
                                        <input type="text" class="form-control" value="{{ $datas->company }}" name="company" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Status <b style="color: red;">*</b></label>
                                        <select class="form-control" name="status" required>
                                            <option value="">Select Status</option>
                                            <option {{ $datas->status == 'ACTIVE' ? "selected" : "" }}>ACTIVE</option>
                                            <option {{ $datas->status == 'DEACTIVE' ? "selected" : "" }}>DEACTIVE</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Address <b style="color: red;">*</b></label>
                                        <textarea type="text" class="form-control" rows="5" name="address" required>{{ $datas->address }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer" align="center">

                            <a href="{{ url('clients') }}" type="button" class="btn btn-primary">Back</a>

                            &emsp;

                            <button type="submit" class="btn btn-success">Save</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('pagespecificscripts')
<script>
    $(function(){

        $('#edit_form').submit(function(e) {

            e.preventDefault();
            var form = $(this)[0];
            var formData = new FormData(this);
            var buttons = [
                $(this).find('button[type="submit"]'),
                $(this).find('a.btn-primary'),  // Back button
                // Add more buttons if needed
            ];

            handleAjaxRequest({

                url: "{{ url('clients') }}" + "/{{ $datas->id }}",
                data: formData,
                button: buttons,
                form: form,
                loadingTitle: 'Updating Info......',
                successTitle: 'Info Updated',
                redirectUrl: "{{ url('clients') }}" + "/{{ $datas->id }}",
                redirectPage: true,
                beforeAjax: function() {

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
