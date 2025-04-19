@extends('layout.app')

@section('customstyle')
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Invoice List</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Invoice</li>
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
                        <h3 class="card-title">Invoice</h3>
                        <div style="float:right;">
                            <div style="display: inline-block;">
                                <a href="{{ url('invoices/create') }}" type="button" class="btn btn-block btn-success btn-sm">Create New Invoice</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Client</th>
                                        <th>Invoice Number</th>
                                        <th>Total</th>
                                        <th>Project</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datas as $item)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>{{ $item->client?->name ?? '' }}</td>
                                        <td>{{ $item->invoice_number }}</td>
                                        <td>{{ number_format($item->total, 2) }}</td>
                                        <td>
                                            <span style="display:none;">{{ date('Y-m-d h:i A', strtotime($item->created_at)) }}</span>
                                            {{ date('d-m-Y h:i A', strtotime($item->created_at)) }}
                                        </td>
                                        <td>
                                            <a class="btn btn-primary" href="{{url('invoices')}}/{{$item->id}}">
                                                Edit
                                            </a>

                                            &nbsp;

                                            <button class="btn btn-danger" data-route="{{url('invoices')}}/{{$item->id}}" data-csrf="{{ csrf_token() }}" onclick="removeData(this)">
                                                Delete
                                            </button>

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('pagespecificscripts')
@endsection
