@extends('backend.layouts.master')

@section('title')
Upload Card Edit - Admin Panel
@endsection

@section('admin-content')
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Upload Card Edit</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.upload-cards.index') }}">All Upload Cards</a></li>
                    <li><span>Edit Upload Card - {{ $uploadCard->name }}</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>

<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Edit Upload Card - {{ $uploadCard->name }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.upload-cards.update', $uploadCard->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="use_type">Use</label>
                                <select class="form-control" id="use_type" name="use_type">
                                    <option value="">Select Use</option>
                                    <option value="web" {{ old('use_type', $uploadCard->use_type) == 'web' ? 'selected' : '' }}>Web</option>
                                    <option value="mobile_app" {{ old('use_type', $uploadCard->use_type) == 'mobile_app' ? 'selected' : '' }}>Mobile App</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="employee_code">Employee Code</label>
                                <input type="text" class="form-control" id="employee_code" name="employee_code" placeholder="Enter Employee Code" value="{{ old('employee_code', $uploadCard->employee_code) }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ old('name', $uploadCard->name) }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="mobile_no">Mobile No</label>
                                <input type="text" class="form-control" id="mobile_no" name="mobile_no" placeholder="Enter Mobile No" value="{{ old('mobile_no', $uploadCard->mobile_no) }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="aadhaar_card">Aadhaar Card</label>
                                <input type="text" class="form-control" id="aadhaar_card" name="aadhaar_card" placeholder="Enter Aadhaar Card" value="{{ old('aadhaar_card', $uploadCard->aadhaar_card) }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="agent_id">Agent</label>
                                <select class="form-control" id="agent_id" name="agent_id">
                                    <option value="">Select Agent</option>
                                    @forelse(\App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}" {{ old('agent_id', $uploadCard->agent_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @empty
                                        <option value="">No agents available</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">Select Status</option>
                                    @forelse($statuses as $status)
                                        <option value="{{ $status->id }}" {{ old('status', $uploadCard->status) == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                    @empty
                                        <option value="">No statuses available</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="photo">Upload Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                @if ($uploadCard->photo)
                                <div class="mt-2">
                                    <img src="{{ asset($uploadCard->photo) }}" alt="{{ $uploadCard->name }}" width="80" height="80" style="object-fit: cover;">
                                </div>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-sm-12">
                                <label for="address">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="4" placeholder="Enter Address">{{ old('address', $uploadCard->address) }}</textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                        <a href="{{ route('admin.upload-cards.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
