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
                    <li><a href="{{ route('admin.banks.index') }}">All banks</a></li>
                    <li><span>Edit Bank - {{ $bank->name }}</span></li>
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
                    <h4 class="header-title">Edit Product - {{ $bank->name }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.banks.update', $bank->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Bank Name</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $bank->name) }}" required>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Branch Code</label>
                                <input type="text" id="branch_code" name="branch_code" class="form-control" value="{{ old('branch_code', $bank->branch_code) }}" required>
                            </div>
                        </div>
                        <label for="name">Products</label>
                        @foreach($products as $product)
                        <div>
                            <input type="checkbox" name="bank_products[]" id="product_{{ $product->id }}" value="{{ $product->id }}" {{ in_array($product->id, $bank->products->pluck('id')->toArray()) ? 'checked' : '' }}>
                            <label for="product_{{ $product->id }}">{{ $product->name }}</label>
                        </div>
                        @endforeach
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