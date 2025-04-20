@extends('layout.app')

@section('customstyle')
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Client List</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Client</li>
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
                        <h3 class="card-title">Client</h3>
                        <div style="float:right;">
                            <div style="display: inline-block;">
                                <a href="{{ url('clients/create') }}" type="button" class="btn btn-block btn-success btn-sm">Add Client</a>
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
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Company</th>
                                        <th>Address</th>
                                        @if(Auth::user()->role == 'ADMIN')
                                            <th>Belongs to</th>
                                        @endif
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datas as $item)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->phone }}</td>
                                        <td>{{ $item->company }}</td>
                                        <td>{!! nl2br($item->address) !!}</td>
                                        @if(Auth::user()->role == 'ADMIN')
                                            <td>{{ $item->user?->name ?? '' }}</td>
                                        @endif
                                        <td>
                                            <span class="badge {{ $item->status == 'ACTIVE' ? 'badge-success' : 'badge-danger' }}" style="font-size: 16px;">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <a class="btn btn-primary" href="{{url('clients')}}/{{$item->id}}">
                                                Edit
                                            </a>

                                            &nbsp;

                                            <button class="btn btn-danger" data-route="{{url('clients')}}/{{$item->id}}" data-csrf="{{ csrf_token() }}" onclick="removeData(this)">
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
