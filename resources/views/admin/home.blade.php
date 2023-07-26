@extends('layouts.admin.app')

@section('content')

<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="page-breadcrumb">
<div class="row">
    <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title">Dashboard</h4>
    </div>
</div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">

    <div class="row">
        {{Auth::guard('admin')->user()->name}}
        DASHBOARD
    </div>
    
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->



@endsection