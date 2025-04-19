@extends('layout.app')

@section('customstyle')
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Invoice Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('invoices') }}">Invoice</a></li>
                    <li class="breadcrumb-item active">Invoice Details</li>
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
                        <h3 class="card-title">Invoice Details</h3>
                    </div>
                    <form id="invoice_form" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Client <b style="color: red;">*</b></label>
                                        <select class="form-control" name="client_id" id="client_id" required>
                                            <option value="">Select Client</option>
                                            @if($clients->count() > 0)
                                                @foreach($clients as $client)
                                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Project <b style="color: red;">*</b></label>
                                        <select class="form-control select2" multiple="multiple" name="project_id[]" id="project_id" required>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer" align="center">

                            <a href="{{ url('invoices') }}" type="button" class="btn btn-primary">Back</a>

                            &emsp;

                            <button type="submit" class="btn btn-success">Submit</button>

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

        $('#project_id').select2({
            placeholder: "Select Projects",
            allowClear: true,
        });

        // Handle client change
        $('#client_id').on('change', function() {

            var clientId = $(this).val();
            var projectSelect = $('#project_id');

            // Reset project select
            projectSelect.empty();

            if(clientId) {
                $.ajax({
                    url: "{{ url('invoices-get-project') }}/" + clientId,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        if(response.projects.length > 0) {
                            response.projects.forEach(function(project) {
                                projectSelect.append(new Option(project.name, project.id));
                            });
                        }
                        projectSelect.trigger('change');
                    }
                });
            }
        });

        $('#invoice_form').submit(function(e) {

            e.preventDefault();
            var form = $(this)[0];
            var formData = new FormData(this);
            var buttons = [
                $(this).find('button[type="submit"]'),
                $(this).find('a.btn-primary'),  // Back button
                // Add more buttons if needed
            ];

            handleAjaxRequest({

                url: "{{ url('invoices') }}",
                data: formData,
                button: buttons,
                form: form,
                loadingTitle: 'Creating Invoice......',
                successTitle: 'Invoice Created',
                redirectUrl: "{{ url('invoices') }}",
                redirectPage: true,
                beforeAjax: function() {
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
