@extends('layout.app')

@section('customstyle')
<!-- JQVMap -->
<link rel="stylesheet" href="{{ asset('assets/plugins/jqvmap/jqvmap.min.css') }}">
@stop

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">

            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>

            <div class="col-sm-2">
            </div>

            <div class="col-sm-4" align="left">

                <div class="btn-group">

                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        Year : {{ app('request')->input('year') == "" ? date('Y') : app('request')->input('year') }}
                    </button>

                    <ul class="dropdown-menu" style="max-height: 300px; overflow-y: auto;">

                        <li>
                            <a class="dropdown-item" href="{{ url('dashboard') }}?year=all&month=">
                                All
                            </a>
                        </li>

                        @foreach($year_list as $item)
                            <li>
                                <a class="dropdown-item" href="{{ url('dashboard') }}?year={{ $item }}&month={{ $month }}">
                                    {{ $item }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                &nbsp;

                @if($year != "" && $year != 'all')

                    <div class="btn-group">

                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            Month : {{ app('request')->input('month') == "" ? 'All' : app('request')->input('month') }}
                        </button>

                        <ul class="dropdown-menu" style="max-height: 300px; overflow-y: auto;">

                            <li>
                                <a class="dropdown-item" href="{{ url('dashboard') }}?year={{ $year }}&month=">
                                    All
                                </a>
                            </li>

                            @foreach($month_list as $item)

                                <li>
                                    <a class="dropdown-item" href="{{ url('dashboard') }}?year={{ $year }}&month={{ $item }}">
                                        {{ $item }}
                                    </a>
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif

            </div>

        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $client }}</h3>

                        <h4><br></h4>

                        <p>Total Clients</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $project }}</h3>

                        <h4><br></h4>

                        <p>Total Projects</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $outstanding }}</h3>

                        <h4><br></h4>

                        <p>Total Outstanding Invoices</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format($total, 2) }}</h3>

                        <h4><br></h4>

                        <p>Total Income</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('pagespecificscripts')
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('assets/plugins/sparklines/sparkline.js') }}"></script>
<script>

    $(function () {

    });

</script>

@endsection
