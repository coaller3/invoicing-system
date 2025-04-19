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
                                        @if(Auth::user()->role == 'admin')
                                        <th>Client</th>
                                        @endif
                                        <th>Invoice Number</th>
                                        <th>Total</th>
                                        <th>Paid Date</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datas as $item)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        @if(Auth::user()->role == 'admin')
                                        <td>{{ $item->client?->name ?? '' }}</td>
                                        @endif
                                        <td>{{ $item->invoice_number }}</td>
                                        <td>{{ number_format($item->total, 2) }}</td>
                                        <td>
                                            <span style="display:none;">{{ $item->paid_date ? date('Y-m-d', strtotime($item->paid_date)) : '' }}</span>
                                            {{ $item->paid_date ? date('d-m-Y', strtotime($item->paid_date)) : '' }}
                                        </td>
                                        <td>
                                            <span style="display:none;">{{ date('Y-m-d h:i A', strtotime($item->created_at)) }}</span>
                                            {{ date('d-m-Y h:i A', strtotime($item->created_at)) }}
                                        </td>
                                        <td>
                                            <a class="btn btn-primary" href="{{url('invoices')}}/{{$item->id}}">
                                                Edit
                                            </a>

                                            &nbsp;

                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#paid-date-modal" data-id="{{ $item->id }}" data-paid-date="{{ $item->paid_date }}" data-invoice-number="{{ $item->invoice_number }}">
                                                Update Paid Date
                                            </button>

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

    <!-- Update Paid Date Modal -->
    <div class="modal fade" id="paid-date-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="paid_date_form" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Paid Date <b style="color: red;">*</b></label>
                                    <input type="date" class="form-control" name="paid_date" id="paid_date" required>
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

        $('#paid-date-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var invoiceId = button.data('id');
            var paidDate = button.data('paid-date');
            var invoiceNumber = button.data('invoice-number');

            $('#paid_date').val(paidDate);

            $('#paid_date_form').data('invoice-id', invoiceId);

            $(this).find('.modal-title').html('Invoice Update (<b>' + invoiceNumber + '</b>)');
        });

        $('#paid_date_form').submit(function(e) {

            e.preventDefault();
            var form = $(this)[0];
            var formData = new FormData(this);
            var invoiceId = $(this).data('invoice-id');
            var buttons = [
                $(this).find('button[type="submit"]'),
            ];

            handleAjaxRequest({

                url: "{{ url('invoices') }}/" + invoiceId,
                data: formData,
                button: buttons,
                form: form,
                loadingTitle: 'Updating Info......',
                successTitle: 'Info Updated',
                redirectUrl: "{{ url('invoices') }}",
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
