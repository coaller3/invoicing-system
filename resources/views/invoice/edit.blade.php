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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Client <b style="color: red;">*</b></label>
                                    <input type="text" class="form-control" value="{{ $datas->client?->name ?? '' }}" name="client" disabled>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Invoice Number <b style="color: red;">*</b></label>
                                    <input type="text" class="form-control" value="{{ $datas->invoice_number }}" name="invoice_number" disabled>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Total <b style="color: red;">*</b></label>
                                    <input type="text" class="form-control" value="{{ number_format($datas->total, 2) }}" name="total" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Invoice Project List</h3>
                        <div style="float:right;">
                            <div style="display: inline-block;">
                                @if($datas->status !== 'PAID')
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add-project-modal">
                                        Add New Project
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Rate / Hour</th>
                                        <th>Total Hours</th>
                                        @if($datas->status !== 'PAID')
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoice_projects as $item)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>{{ $item->project?->name ?? '' }}</td>
                                        <td>{!! nl2br($item->project?->name ?? '') !!}</td>
                                        <td>{{ number_format($item->rate, 2) }}</td>
                                        <td>{{ $item->duration }}</td>
                                        @if($datas->status !== 'PAID')
                                        <td>
                                            <button class="btn btn-danger" data-route="{{url('invoice-projects')}}/{{$item->id}}" data-csrf="{{ csrf_token() }}" onclick="removeData(this)">
                                                Delete
                                            </button>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer" align="center">

                        <a href="{{ url('invoices') }}" type="button" class="btn btn-primary">Back</a>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Project Modal -->
    <div class="modal fade" id="add-project-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Details Edit</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="add_project_form" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Project <b style="color: red;">*</b></label>
                                    <select class="form-control" name="project_id" id="project_id" required>
                                        <option value="">Select Project</option>
                                        @if($projects->count() > 0)
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
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

        $('#add_project_form').submit(function(e) {

            e.preventDefault();
            var form = $(this)[0];
            var formData = new FormData(this);
            var buttons = [
                $(this).find('button[type="submit"]'),
            ];

            handleAjaxRequest({

                url: "{{ url('invoice-projects') }}" + "/{{ $datas->id }}",
                data: formData,
                button: buttons,
                form: form,
                loadingTitle: 'Adding Project......',
                successTitle: 'Project Added',
                redirectUrl: "{{ url('invoices') }}" + "/{{ $datas->id }}",
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
