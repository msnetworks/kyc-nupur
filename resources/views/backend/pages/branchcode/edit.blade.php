@extends('backend.layouts.master')

@section('title')
Bank Edit - Admin Panel
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .form-check-label {
        text-transform: capitalize;
    }
</style>
@endsection


@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Product Create</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.branchcodes.index') }}">All Branches</a></li>
                    <li><span>Edit Branch - {{ $branch->branch_name }}</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Edit Product - {{ $branch->branch_name }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.branchcodes.update', $branch->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Bank <span class="text-danger">*</span></label>
                                <select class="custom-select selectBank" name="bank_id" id="selectBank" required>
                                    <option value="">--Select Option--</option>
                                    @foreach ($banks as $data)
                                    <option value="{{ $data['id'] }}" {{ $data['id'] == $branch->bank_id ? 'selected' : '' }}>{{ $data['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Branch Code</label>
                                <input type="text" class="form-control" id="branch_code" value="{{ $branch->branch_code }}" name="branch_code" placeholder="Enter Bank Code">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Branch Name</label>
                                <input type="text" class="form-control" id="branch_name" value="{{$branch->branch_name}}" name="branch_name" placeholder="Enter Bank Code">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Area</label>
                                <input type="text" class="form-control" id="area" value="{{$branch->area}}" name="area" placeholder="Enter Bank Code">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save Product</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>
@endsection