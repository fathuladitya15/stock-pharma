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
        <div class="card bg-primary sale-widget flex-fill">
            <div class="card-body d-flex align-items-center">
                <span class="sale-icon bg-white text-primary">
                    <i class="ti ti-file-text fs-24"></i>
                </span>
                <div class="ms-2">
                    <p class="text-white mb-1">Total Product</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                        <h4 class="text-white">{{ $countProduct }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-secondary sale-widget flex-fill">
            <div class="card-body d-flex align-items-center">
                <span class="sale-icon bg-white text-secondary">
                    <i class="ti ti-repeat fs-24"></i>
                </span>
                <div class="ms-2">
                    <p class="text-white mb-1">Total Stock</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                        <h4 class="text-white">{{ $totalStock }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-teal sale-widget flex-fill">
            <div class="card-body d-flex align-items-center">
                <span class="sale-icon bg-white text-teal">
                    <i class="ti ti-gift fs-24"></i>
                </span>
                <div class="ms-2">
                    <p class="text-white mb-1">Total Purchase</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                        <h4 class="text-white">{{ $totalPurchase }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 d-flex">
        <div class="card bg-info sale-widget flex-fill">
            <div class="card-body d-flex align-items-center">
                <span class="sale-icon bg-white text-info">
                    <i class="ti ti-brand-pocket fs-24"></i>
                </span>
                <div class="ms-2">
                    <p class="text-white mb-1">Total Supplier</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                        <h4 class="text-white">{{ $totalSuppliers }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
