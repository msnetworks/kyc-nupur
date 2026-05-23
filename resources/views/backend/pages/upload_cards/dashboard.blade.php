@extends('backend.layouts.master')

@section('title')
Upload Card Dashboard - Admin Panel
@endsection

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<style>
    .upload-card-summary .card {
        border-radius: 6px;
        min-height: 112px;
    }

    .upload-card-summary .summary-label {
        color: #555;
        font-size: 13px;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .upload-card-summary .summary-count {
        color: #111;
        font-size: 28px;
        font-weight: 700;
        line-height: 1;
    }

    #uploadCardDashboardTable,
    #uploadCardAgentTable,
    #uploadCardAgentTable_wrapper {
        width: 100% !important;
    }
</style>
@endsection

@section('admin-content')
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Upload Card Dashboard</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><span>Upload Card Dashboard</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>

<div class="main-content-inner">
    <div class="row mt-4 upload-card-summary">
        <div class="col-xl col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="summary-label">Total Cases</div>
                    <div class="summary-count" data-summary="total">{{ $totalSum['total'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="summary-label">Inprogress</div>
                    <div class="summary-count" data-summary="inprogress">{{ $totalSum['inprogressTotal'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="summary-label">Closed</div>
                    <div class="summary-count" data-summary="closed">{{ $totalSum['closedTotal'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="summary-label">Positive Verified</div>
                    <div class="summary-count" data-summary="positive-verified">{{ $totalSum['positiveVerifiedTotal'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="summary-label">Negative Verified</div>
                    <div class="summary-count" data-summary="negative-verified">{{ $totalSum['negativeVerifiedTotal'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="uploadCardDashboardFilter">
                        <div class="row">
                            <div class="form-group col-md-3 col-sm-12">
                                <label for="agent">Agent</label>
                                <select name="agent" id="agent" class="form-control">
                                    <option value="">--Select--</option>
                                    @foreach ($agentLists as $id => $user)
                                    <option value="{{ $id }}">{{ $user }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 col-sm-12">
                                <label for="fromDate">From Date</label>
                                <input type="date" class="form-control" id="fromDate" name="fromDate">
                            </div>
                            <div class="form-group col-md-3 col-sm-12">
                                <label for="toDate">To Date</label>
                                <input type="date" class="form-control" id="toDate" name="toDate">
                            </div>
                            <div class="form-group col-md-3 col-sm-12">
                                <input type="submit" class="form-control btn btn-sm btn-primary mt-4" value="Filter">
                            </div>
                        </div>
                    </form>

                    <div id="uploadCardDashboardTable" class="data-tables">
                        @include('backend.pages.upload_cards.dashboardtable')
                    </div>
                </div>
            </div>
        </div>
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
    function initUploadCardDashboardTable() {
        if ($('#uploadCardAgentTable').length) {
            $('#uploadCardAgentTable').DataTable({
                responsive: true,
                autoWidth: false,
                destroy: true
            });
        }
    }

    function updateUploadCardSummary() {
        var values = $('#uploadCardSummaryValues');

        if (!values.length) {
            return;
        }

        $('[data-summary="total"]').text(values.data('total') || 0);
        $('[data-summary="inprogress"]').text(values.data('inprogress') || 0);
        $('[data-summary="closed"]').text(values.data('closed') || 0);
        $('[data-summary="positive-verified"]').text(values.data('positiveVerified') || 0);
        $('[data-summary="negative-verified"]').text(values.data('negativeVerified') || 0);
    }

    $(document).ready(function() {
        initUploadCardDashboardTable();

        $('#uploadCardDashboardFilter').on('submit', function(event) {
            event.preventDefault();

            if ($('#agent').val() === '' && $('#fromDate').val() === '' && $('#toDate').val() === '') {
                alert('Please select a date range or agent to filter.');
                return false;
            }

            $.ajax({
                url: "{{ route('admin.upload-cards.dashboard.filter') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    agent: $('#agent').val(),
                    fromDate: $('#fromDate').val(),
                    toDate: $('#toDate').val()
                },
                success: function(response) {
                    $('#uploadCardDashboardTable').html(response);
                    updateUploadCardSummary();
                    initUploadCardDashboardTable();
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                    alert('An error occurred while fetching the upload card records. Please try again.');
                }
            });
        });
    });
</script>
@endsection
