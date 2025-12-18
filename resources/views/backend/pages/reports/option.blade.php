@extends('backend.layouts.master')

@section('title')
    Billing Report - Admin Panel
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection

@section('admin-content')
<style>
    svg:not(:root) {
        overflow: hidden;
        width: 15px;
    }
</style>
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Billing Report</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><span>Billing Report</span></li>
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
                        <h4 class="header-title">Billing Report</h4>
                        <div class="flex mb-3">
                            <form id="billingReportForm" method="POST" action="{{ route('admin.reports.billing.fetch') }}">
                                @csrf
                                <div class="col-4">
                                    <label for="dateFrom" class="form-label">From Date <span class="text-danger">*</span></label>
                                    <input type="date" id="dateFrom" name="dateFrom" class="form-control" required>
                                </div>
                                <div class="col-4">
                                    <label for="dateTo" class="form-label">To Date <span class="text-danger">*</span></label>
                                    <input type="date" id="dateTo" name="dateTo" class="form-control" required>
                                </div>
                                <div class="col-4 mt-4">
                                    <button type="submit" class="btn btn-primary mt-2">Search</button>
                                </div>
                            </form>
                        </div>
                        <div class="clearfix"></div>
                        <div id="billingReportResult" class="mt-4"></div>
                    </div>
                </div>
            </div>
            <!-- data table end -->
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Start datatable js -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#billingReportForm').on('submit', function(event) {
                event.preventDefault(); // Prevent form from submitting normally

                // Collect form data
                var formData = {
                    _token: "{{ csrf_token() }}",
                    dateFrom: $('#dateFrom').val(),
                    dateTo: $('#dateTo').val(),
                };

                // Perform AJAX request
                $.ajax({
                    url: "{{ route('admin.reports.billing.fetch') }}",
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#billingReportResult').html(response);
                    },
                    error: function(response) {
                        $('#billingReportResult').html('<div class="alert alert-danger">Something went wrong.</div>');
                    }
                });
            });
        });
    </script>
@endsection