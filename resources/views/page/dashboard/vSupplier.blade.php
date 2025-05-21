@extends('vMaster')
@section('title','Dashboard')
@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-2">
    <div class="mb-3">
        <h1 class="mb-1">Welcome, {{ Str::title($name) }}</h1>
    </div>

</div>


<div class="row">
    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-warning sale-widget flex-fill">
            <div class="card-body d-flex align-items-center">
                <span class="sale-icon bg-white text-warning">
                    <i class="ti ti-clipboard fs-24"></i>
                </span>
                <div class="ms-2">
                    <p class="text-white mb-1">waiting for confirmation</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                        <h4 class="text-white">{{ $POwait }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-info sale-widget flex-fill">
            <div class="card-body d-flex align-items-center">
                <span class="sale-icon bg-white text-info">
                    <i class="ti ti-truck fs-24"></i>
                </span>
                <div class="ms-2">
                    <p class="text-white mb-1">Shipped</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                        <h4 class="text-white">{{ $POshipped }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-success sale-widget flex-fill">
            <div class="card-body d-flex align-items-center">
                <span class="sale-icon bg-white text-success">
                    <i class="ti ti-check fs-24"></i>
                </span>
                <div class="ms-2">
                    <p class="text-white mb-1">Order Successful</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                        <h4 class="text-white">{{ $POcomplete }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-danger sale-widget flex-fill">
            <div class="card-body d-flex align-items-center">
                <span class="sale-icon bg-white text-danger">
                    <i class="fas fa-times fs-24"></i>
                </span>
                <div class="ms-2">
                    <p class="text-white mb-1">Order Cancelled</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                        <h4 class="text-white">{{ $POcanceled }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
