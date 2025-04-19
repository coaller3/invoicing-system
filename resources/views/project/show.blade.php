@extends('layout.app')

@section('customstyle')
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Show Product</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('products') }}">Product List</a></li>
                    <li class="breadcrumb-item active">Show Product</li>
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
                        <h3 class="card-title">Show Product</h3>
                    </div>
                    <form id="product_form" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Name: </label>
                                        <label>{{ $product->name }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Price: </label>
                                        <label>{{ number_format($product->price, 2) }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Details: </label>
                                        <label>{!! nl2br($product->details) !!}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Publish: </label>
                                        <label>{{ $product->publish == 1 ? 'Yes' : 'No' }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer" align="center">
                            <a href="{{ url('products') }}" type="button" class="btn btn-primary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('pagespecificscripts')
@endsection
