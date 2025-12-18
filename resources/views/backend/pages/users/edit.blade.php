@extends('backend.layouts.master')

@section('title')
Agent Edit - Admin Panel
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
                <h4 class="page-title pull-left">Agent Edit</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.users.index') }}">All Users</a></li>
                    <li><span>Edit Agent - {{ $user->name }}</span></li>
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
                    <h4 class="header-title">Edit Agent - {{ $user->name }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="username">User Name</label>
                                <input type="text" class="form-control" id="username" name="username" readonly placeholder="Enter username" value="{{ $user->username }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ $user->name }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="email">Agent Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="{{ $user->email }}">
                            </div>
                            
                            @php
                                $adminList = is_string($user->admin_access) && strpos($user->admin_access, ',') !== false 
                                    ? explode(',', $user->admin_access) 
                                    : [$user->admin_access];
                            @endphp
                            {{-- <div class="form-group col-md-6 col-sm-6">
                                <label for="bank">Assign Bank</label>
                                <select name="banks_assign[]" id="bank" class="form-control select2" multiple>
                                    <option value="">--Select Banks--</option>
                                     @foreach ($banks as $bank)
                                     <option value="{{ $bank->id }}" {{ in_array($bank->id, $assignedBanks) ? 'selected' : '' }}>
                                         {{ $bank->name }}
                                     </option>
                                 @endforeach
                                </select>
                            </div> --}}
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="banks_assign">Assign Reporting Admin</label>
                                <select name="admin_access[]" id="admin_access" class="form-control select2" multiple>
                                    <option value="">--Select Assign Admin--</option>
                                    @foreach ($accessAdmin as $admin)
                                        @if($admin['id'] != 1)
                                        <option value="{{ $admin['id'] }}"  {{ in_array($admin['id'], $adminList) ? 'selected' : '' }}>{{ $admin['name'] . ' ('.$admin['bank_name'].')' }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter Password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save Agent</button>
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