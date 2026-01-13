@extends('backend.layouts.master')

@section('title')
    Role Page - Admin Panel
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
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Roles</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><span>All Roles</span></li>
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
                        <h4 class="header-title">Reports List</h4>
                        {{-- <p class="float-right mb-2">
                        @if (Auth::guard('admin')->user()->can('role.create'))
                            <a class="btn btn-primary text-white" href="{{ route('admin.roles.create') }}">Create New Role</a>
                        @endif
                    </p> --}}
                        <div class="flex mb-3">
                            <form id="submitform" action="{{ route('fetchreport') }}" method="POST"
                                class="row g-3 align-items-center">
                                @csrf
                                <div class="col-4">
                                    <label for="verify_type" class="form-label">Verify Type <span class="text-danger">*</span></label>
                                    <select id="verify_type" name="verify_type" class="form-control form-select">
                                        <option value="-1">All</option>
                                        @foreach($status as $option)
                                        <option value="{{$option->id}}">{{ucfirst($option->name)}}</option>
                                        @endforeach
                                        {{-- <option value="218">Positively Verified</option>
                                        <option value="219">Negatively Verified</option>
                                        <option value="220">Closed</option>
                                        <option value="221">HOLD</option>
                                        <option value="223">InProgress</option> --}}
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label for="dateFrom" class="form-label">From Date <span class="text-danger">*</span></label>
                                    <input type="date" id="dateFrom" name="dateFrom" class="form-control" required>
                                </div>

                                <div class="col-4">
                                    <label for="dateTo" class="form-label">To Date <span class="text-danger">*</span></label>
                                    <input type="date" id="dateTo" name="dateTo" class="form-control" required>
                                </div>

                                <div class="col-4 mt-4">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                        <div class="clearfix"></div>
                        <div id="responseMessage" class="mt-3"></div>
                        <div class="data-tables" id="recordtable">

                        </div>
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
        /*================================
            datatable active
            ==================================*/
        if ($('#dataTable').length) {
            $('#dataTable').DataTable({
                responsive: true
            });
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#submitform').on('submit', function(event) {
                event.preventDefault(); // Prevent form from submitting normally

                // Collect form data
                var formData = {
                    _token: "{{ csrf_token() }}", // CSRF Token
                    verify_type: $('#verify_type').val(),
                    dateFrom: $('#dateFrom').val(),
                    dateTo: $('#dateTo').val(),
                };

                // Perform AJAX request
                $.ajax({
                    url: "{{ route('fetchreport') }}", // Route defined in Laravel
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        // Handle success response
                        // $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                        // console.log(response);
                        $('#recordtable').html(response);
                    },
                    error: function(response) {
                    }
                });
            });
        });
    </script>
    <script>
        $(document).on("click", '#export-button', function(event) {
            event.preventDefault(); // Prevent the default behavior

            const fromDate = $('#dateFrom').val();
            const toDate = $('#dateTo').val();
            const verify_type = $('#verify_type').val();

            var exportUrl = `{{ route('export.cases') }}?dateFrom=${fromDate}&dateTo=${toDate}&verify_type=${verify_type}`;

            window.location.href = exportUrl;

            // setTimeout(function() {
            //     window.history.back();
            // }, 2000);
        });
    </script>
@endsection
