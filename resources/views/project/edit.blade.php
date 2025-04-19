@extends('layout.app')

@section('customstyle')
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Project Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('projects') }}">Project</a></li>
                    <li class="breadcrumb-item active">Project Details</li>
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
                        <h3 class="card-title">Project Details</h3>
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
                                        <label>Client <b style="color: red;">*</b></label>
                                        <select class="form-control" name="client_id" required>
                                            <option value="">Select Client</option>
                                            @if($clients->count() > 0)
                                                @foreach($clients as $client)
                                                    <option value="{{ $client->id }}" {{ $datas->client_id == $client->id ? "selected" : "" }}>{{ $client->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Rate / Hour <b style="color: red;">*</b></label>
                                        <input type="number" class="form-control" value="{{ $datas->rate }}" name="rate" step="0.01" onchange="setThreeNumberDecimal" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Total Hours <b style="color: red;">*</b></label>
                                        <input type="number" class="form-control" value="{{ $datas->duration }}" name="duration" step="0.5" min="0.5" onchange="setThreeNumberDecimal" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea type="text" class="form-control" rows="5" name="description" required>{{ $datas->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer" align="center">

                            <a href="{{ url('projects') }}" type="button" class="btn btn-primary">Back</a>

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

                url: "{{ url('projects') }}" + "/{{ $datas->id }}",
                data: formData,
                button: buttons,
                form: form,
                loadingTitle: 'Updating Project......',
                successTitle: 'Project Updated',
                redirectUrl: "{{ url('projects') }}" + "/{{ $datas->id }}",
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
