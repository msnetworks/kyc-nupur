@extends('backend.layouts.master')

@section('title')
Upload Card - Admin Panel
@endsection

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection

@section('admin-content')
@php $usr = Auth::guard('admin')->user(); @endphp

<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Upload Card</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><span>All Upload Cards</span></li>
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
                    <h4 class="header-title float-left">Upload Card List</h4>
                    @if ($usr->can('upload_card.create'))
                    <p class="float-right mb-2">
                        <a class="btn btn-primary text-white" href="{{ route('admin.upload-cards.create') }}">Create Upload Card</a>
                    </p>
                    @endif
                    <div class="clearfix"></div>
                    @if (!empty($filters['agent_id']) || !empty($filters['status']))
                    <div class="alert alert-info">
                        Showing upload cards
                        @if (!empty($selectedAgent))
                            for agent <strong>{{ $selectedAgent->name }}</strong>
                        @elseif (($filters['agent_id'] ?? '') === 'unassigned')
                            for agent <strong>Unassigned</strong>
                        @endif
                        @if (!empty($selectedStatus))
                            with status <strong>{{ $selectedStatus->name }}</strong>
                        @endif
                        <a href="{{ route('admin.upload-cards.index') }}" class="float-right">Clear Filter</a>
                    </div>
                    @endif
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table id="dataTable" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="5%">Sl</th>
                                    <th width="10%">Photo</th>
                                    <th width="10%">Use</th>
                                    <th width="15%">Employee Code</th>
                                    <th width="15%">Name</th>
                                    <th width="12%">Mobile No</th>
                                    <th width="18%">Aadhaar Card</th>
                                    <th width="15%">Agent</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($uploadCards as $uploadCard)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        @if ($uploadCard->photo)
                                        <img src="{{ asset($uploadCard->photo) }}" alt="{{ $uploadCard->name }}" width="50" height="50" style="object-fit: cover;">
                                        @endif
                                    </td>
                                    <td>{{ $uploadCard->use_type == 'mobile_app' ? 'Mobile App' : 'Web' }}</td>
                                    <td>{{ $uploadCard->employee_code }}</td>
                                    <td>{{ $uploadCard->name }}</td>
                                    <td>{{ $uploadCard->mobile_no }}</td>
                                    <td>{{ $uploadCard->aadhaar_card }}</td>
                                    <td>{{ $uploadCard->agent ? $uploadCard->agent->name : 'N/A' }}</td>
                                    <td>{{ $uploadCard->masterStatus ? $uploadCard->masterStatus->name : 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#viewUploadCardModal-{{ $uploadCard->id }}">View</a>
                                                <a class="dropdown-item" href="{{ route('admin.upload-cards.download-pdf', $uploadCard->id) }}">Download PDF</a>

                                                @if ($usr->can('upload_card.edit'))
                                                <a class="dropdown-item" href="{{ route('admin.upload-cards.edit', $uploadCard->id) }}">Edit</a>
                                                <a class="dropdown-item change-status" href="javascript:void(0)"
                                                    data-action="{{ route('admin.upload-cards.update-status', $uploadCard->id) }}"
                                                    data-status="{{ $uploadCard->status }}"
                                                    data-name="{{ $uploadCard->name }}">Change Status</a>
                                                @endif

                                                @if ($usr->can('upload_card.delete'))
                                                <a class="dropdown-item" href="{{ route('admin.upload-cards.destroy', $uploadCard->id) }}" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this upload card?')) document.getElementById('delete-form-{{ $uploadCard->id }}').submit();">Delete</a>
                                                <form id="delete-form-{{ $uploadCard->id }}" action="{{ route('admin.upload-cards.destroy', $uploadCard->id) }}" method="POST" style="display: none;">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @foreach ($uploadCards as $uploadCard)
                        <div class="modal fade" id="viewUploadCardModal-{{ $uploadCard->id }}" tabindex="-1" role="dialog" aria-labelledby="viewUploadCardModalLabel-{{ $uploadCard->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewUploadCardModalLabel-{{ $uploadCard->id }}">Upload Card Details - {{ $uploadCard->employee_code }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-3 text-center mb-3">
                                                @if ($uploadCard->photo)
                                                <img src="{{ asset($uploadCard->photo) }}" alt="{{ $uploadCard->name }}" class="img-fluid rounded" style="max-height: 180px; object-fit: cover;">
                                                @else
                                                <div class="border p-4 text-muted">No Photo</div>
                                                @endif
                                            </div>
                                            <div class="col-md-9">
                                                <h5 class="mb-3">{{ $uploadCard->name }}</h5>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <th width="30%">Employee Code</th>
                                                                <td>{{ $uploadCard->employee_code }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Use</th>
                                                                <td>{{ $uploadCard->use_type == 'mobile_app' ? 'Mobile App' : 'Web' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Mobile No</th>
                                                                <td>{{ $uploadCard->mobile_no }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Aadhaar Card</th>
                                                                <td>{{ $uploadCard->aadhaar_card }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Agent</th>
                                                                <td>{{ $uploadCard->agent ? $uploadCard->agent->name : 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Status</th>
                                                                <td>{{ $uploadCard->masterStatus ? $uploadCard->masterStatus->name : 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Created On</th>
                                                                <td>{{ $uploadCard->created_at ? $uploadCard->created_at->format('d-m-Y h:i A') : 'N/A' }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h5 class="mb-3">Address</h5>
                                                <div class="border p-3">{{ $uploadCard->address }}</div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h5 class="mb-3">Status Flow</h5>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <th width="30%">Verified By</th>
                                                                <td>{{ optional(optional($uploadCard->commonFlow)->verifier)->name ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Verified On</th>
                                                                <td>{{ optional($uploadCard->commonFlow)->verified_on ? \Carbon\Carbon::parse($uploadCard->commonFlow->verified_on)->format('d-m-Y h:i A') : 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Closed By</th>
                                                                <td>{{ optional(optional($uploadCard->commonFlow)->closer)->name ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Closed On</th>
                                                                <td>{{ optional($uploadCard->commonFlow)->closed_on ? \Carbon\Carbon::parse($uploadCard->commonFlow->closed_on)->format('d-m-Y h:i A') : 'N/A' }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="{{ route('admin.upload-cards.download-pdf', $uploadCard->id) }}" class="btn btn-primary">Download PDF</a>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="changeStatusForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeStatusModalLabel">Change Upload Card Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Upload Card: <strong id="changeStatusCardName"></strong></p>
                    <div class="form-group">
                        <label for="change_status">Status</label>
                        <select class="form-control" id="change_status" name="status" required>
                            <option value="">Select Status</option>
                            @foreach ($statusOptions as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
<script>
    if ($('#dataTable').length) {
        $('#dataTable').DataTable({
            responsive: true
        });
    }

    $(document).on('click', '.change-status', function() {
        $('#changeStatusForm').attr('action', $(this).data('action'));
        $('#changeStatusCardName').text($(this).data('name'));
        $('#change_status').val($(this).data('status'));
        $('#changeStatusModal').modal('show');
    });
</script>
@endsection
