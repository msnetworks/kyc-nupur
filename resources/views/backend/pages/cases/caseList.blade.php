@extends('backend.layouts.master')

@section('title')
Cases - Admin Panel
@endsection

{{-- @section('styles')
<!-- Start datatable css -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection --}}
@section('admin-content')
<style>
    .pagination-container .sm\:flex {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
    }
    .pagination-container nav svg {
        width: 1em!important;
        height: 1em!important;
        vertical-align: middle;
        display: inline-block;
    }
    .pagination-container p{
        margin-left: 65px;
    }
</style>

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Cases</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><span>All Cases</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>
<!-- page title area end -->
<div class="clearfix"></div>
<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body p-0">
                    <h4 class="header-title float-left">Cases List</h4>
                    
                    
                    <div class="clearfix"></div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary text-white btn-sm" id="getSelectedIds">Assign</button>
                            
                            <a class="btn btn-warning text-white" href="{{ route('admin.case.export.excel',['status' => $status, 'user_id' => $user_id]) }}">Export Cases</a>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" id="searchInput" class="form-control" style="border: 2px solid #818080;" placeholder="Search by Internal Code, Name, or Mobile...">
                        </div>
                        <div class="col-md-5"></div>
                        <div class="col-md-3 text-right">
                            <label for="recordsPerPage" class="d-inline"> Record Per Page :</label>
                            <select id="recordsPerPage" class="d-inline">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                    </div>
                    @include('backend.layouts.partials.messages')             
                    <div id="caseTableContainer">
                        @include('backend.pages.cases.caseTable', ['cases' => $cases, 'assign' => $assign])
                    </div>
                    
                    
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.case.assignAgent') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assign Case</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-12 col-sm-12">
                        <input name="case_fi_type_id" class="case_fi_type_id" type="hidden">
                        <label for="name">Assign To</label>
                        <select id="userSelect" name="user_id" class="custom-select" required>
                            <option value="">--Select Option--</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12 col-sm-12">
                        <label for="name">Assignment Time :</label>
                        <input name="ScheduledVisitDate" class="custom-select" value="{{ date('Y-m-d H:i:s') }}" id="ScheduledVisitDate" type="datetime-local">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Assign case</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="resolveCaseModel" tabindex="-1" role="dialog" aria-labelledby="resolveCaseModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.case.resolveCase') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Resolve Case</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-12 col-sm-12">
                        <input name="case_fi_type_id" class="case_fi_type_id" type="hidden">
                        <label for="name">Sub Status</label>
                        <select id="caseSubSelect" name="sub_status" class="custom-select" required>
                            <option value="">--Select Option--</option>
                        </select>
                    </div>
                    @php
                    $currentYear = now()->year; // Current year
                        $startYear = $currentYear - 5; // Last 5 years

                        $financialYears = [];
                        for ($year = $startYear; $year <= $currentYear; $year++) {
                            $financialYears[] = "{$year}-" . ($year + 1);
                        }
                        @endphp
                    <div id="fi_itr">
                        <!-- Existing rows will be appended here -->
                    </div>
                    <p id="remarks-limit-message" style="color: red; display: none;">You can only add up to 5 rows.</p>
                    <p id="remarks-validation-message" style="color: red; display: none;">Please fill all fields in the existing blocks before adding a new one.</p>
                    <button id="add-remarks-row" class="btn btn-primary m-2 d-none">Add Row</button>
                    
                    <div class="form-group col-md-12 col-sm-12">
                        <label for="name">Remarks :</label>
                        <textarea class="form-control" name="consolidated_remarks" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Resolve Case</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="verifiedCaseModel" tabindex="-1" role="dialog" aria-labelledby="verifiedCaseModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.case.verifiedCase') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Verified Case</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-12 col-sm-12">
                        <input name="case_fi_type_id" class="case_fi_type_id" type="hidden">
                        <label for="name">FeedBack Status</label>
                        <select id="feedbackSelect" name="feedback_status" class="custom-select" required>
                            <option value="">--Select Option--</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12 col-sm-12">
                        <label for="name">Sub Status</label>
                        <select id="verifiedCaseSubSelect" name="sub_status" class="custom-select" required>
                            <option value="">--Select Option--</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12 col-sm-12">
                        <label for="name">Remarks :</label>
                        <textarea class="form-control" name="remarks" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Verified Case</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="consolidatedRemarksModel" tabindex="-1" role="dialog" aria-labelledby="consolidatedRemarksModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Consolidated remarks</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12 col-sm-12">
                    <input name="case_fi_type_id" class="case_fi_type_id" type="hidden">
                    <label for="name">Consolidated remarks :</label>
                    <textarea class="form-control consolidated_remarks" name="consolidated_remarks" readonly required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<div class="modal fade modal-lg" id="caseHistoryModel" tabindex="-1" role="dialog" aria-labelledby="caseHistoryModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Case History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12 col-sm-12">
                    <input name="case_fi_type_id" class="case_fi_type_id" type="hidden">
                    <label for="name">Consolidated remarks :</label>
                    <textarea class="form-control consolidated_remarks" name="consolidated_remarks" readonly required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="caseReinitiatesModel" tabindex="-1" role="dialog" aria-labelledby="caseReinitiatesModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.case.reinitatiate.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Case Reinitiates</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body htmlFormReinitatiateCaseSection">

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary text-white btn-sm" id="getSelectedIds">Reinitiates</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="viewFormEditModel" tabindex="-1" role="dialog" aria-labelledby="viewFormEditModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Form Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="fiTypeModel" tabindex="-1" role="dialog" aria-labelledby="fiTypeModelModelLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Fi Types</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12 col-sm-12">
                    <input name="case_tableid" class="case_tableid" id="case_tableid" type="hidden">
                    <label for="name">Refrence Number :</label>
                    <input type="text" id="ref_no" value="" class="form-control" disabled>
                </div>
                <div class="form-group col-md-12 col-sm-12">
                    <input name="case_fi_type_id" class="case_fi_type_id" type="hidden">
                    <label for="name">Applicant Name :</label>
                    <input type="text" id="app_name" value="" class="form-control" disabled>
                </div>
                <div class="form-group col-md-12 col-sm-12">
                    <label for="name">Fi Types :</label>
                    <select name="fi_type" id="fi_type" class="form-control">
                        <option value="">--Select Fi Type--</option>
                        @foreach ($fitype as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-12 col-sm-12">
                    <input type="submit" id="updateFiTypeButton" class="btn btn-success" value="Submit">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="productModel" tabindex="-1" role="dialog" aria-labelledby="productModelModelLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product Update</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12 col-sm-12">
                    <input name="product_tableid" class="product_tableid" id="product_tableid" type="hidden">
                    <label for="pro_ref_no">Refrence Number :</label>
                    <input type="text" id="pro_ref_no" value="" class="form-control" disabled>
                </div>
                <div class="form-group col-md-12 col-sm-12">
                    <label for="pro_app_name">Applicant Name :</label>
                    <input type="text" id="pro_app_name" value="" class="form-control" disabled>
                </div>
                <div class="form-group col-md-12 col-sm-12">
                        <label for="productSelect">Product <span class="text-danger">*</span></label>
                        <select id="productSelect" name="product_id" class="form-control" required>
                            <option value="">--Select Option--</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-12 col-sm-12">
                    <input type="submit" id="updateProductButton" class="btn btn-success" value="Submit">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="branchChangeModel" tabindex="-1" role="dialog" aria-labelledby="branchChangeModelLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Branch Code</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12 col-sm-12">
                    <input name="case_tableid" class="case_tableid" id="bc_case_tableid" type="hidden">
                    <label for="name">Refrence Number :</label>
                    <input type="text" id="bc_ref_no" value="" class="form-control" disabled>
                </div>
                <div class="form-group col-md-12 col-sm-12">
                    <input name="case_fi_type_id" class="case_fi_type_id" type="hidden">
                    <label for="name">Applicant Name :</label>
                    <input type="text" id="bc_app_name" value="" class="form-control" disabled>
                </div>
                <div class="form-group col-md-12 col-sm-12">
                    <label for="name">Branch Code :</label>
                    <select name="branch_code" id="bc_branch_code" class="form-control">
                        <option value="">--Select Branch Code--</option>
                        @foreach ($branchcode as $item)
                        <option value="{{ $item->id }}">{{ $item->branch_name.' ('.$item->branch_code.')' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-12 col-sm-12">
                    <input type="submit" id="bc_updateFiTypeButton" class="btn btn-success" value="Submit">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Add this modal after the existing modals, before the Image Modal -->
<div class="modal fade" id="geoLimitModel" tabindex="-1" role="dialog" aria-labelledby="geoLimitModelLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Geo Limit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12 col-sm-12">
                    <input name="case_tableid" class="case_tableid" id="gl_case_tableid" type="hidden">
                    <label for="name">Reference Number :</label>
                    <input type="text" id="gl_ref_no" value="" class="form-control" disabled>
                </div>
                <div class="form-group col-md-12 col-sm-12">
                    <label for="name">Applicant Name :</label>
                    <input type="text" id="gl_app_name" value="" class="form-control" disabled>
                </div>
                <div class="form-group col-md-12 col-sm-12">
                    <label for="name">Geo Limit (KM) :</label>
                    <select id="gl_geo_limit" name="geo_limit" class="custom-select">
                        <option value="">--Select Option--</option>
                        <option value="Local">Local</option>
                        <option value="Outstation">Outstation</option>
                    </select>
                </div>
                <div class="form-group col-md-12 col-sm-12">
                    <input type="submit" id="gl_updateGeoLimitButton" class="btn btn-success" value="Submit">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- The Image Modal -->
<div id="myimgModal" class="modal imgmodal">

  <!-- The Close Button -->
  <span class="closebtn">&times;</span>

  <!-- Modal Content (The Image) -->
  <img class="modal-content imgmodal-content" id="img01">

</div>

@endsection


@section('scripts')
<script>
    var baseUrl = "{{ url('/') }}";
    $(document).on('click', '.productModel', function(e) {
        var bankId = $(this).data('bank_id');
        var customGetPath = "{{ route('admin.case.item','ID')}}";
        customGetPath = customGetPath.replace('ID', bankId);
        $.ajax({
            url: customGetPath,
            type: 'GET',
            success: function(response) {
                var select = $('#productSelect');
                select.empty(); // Clear any existing options
                select.append('<option value="">--Select Option--</option>'); // Add default option

                $.each(response, function(key, products) {
                    $.each(products, function(index, product) {
                        console.log(product);
                        var option = $('<option></option>')
                            .attr('value', product.product_id)
                            .text(product.name + ' (' + product.product_code + ')');
                        select.append(option);
                    });
                });
            },
            error: function() {
                alert('Request failed');
            }
        });
    });
    $(document).on('click', '.productModel', function(e) {
        $('#productModel').modal('show');
        
        let product_tableid = $(this).data('row');
        
        let pro_app_name = $(this).data('app_name');
        let pro_ref_no = $(this).data('ref_no');
        if(pro_app_name != ''){
            $('#pro_app_name').val(pro_app_name);
        }
        if(ref_no != ''){
            $('#pro_ref_no').val(pro_ref_no);
        }
        if(product_tableid != ''){
            $('#product_tableid').val(product_tableid);
        }
        
        var url = "{{ route('admin.case.reinitatiateCaseNew','CASE_ID')}}";
        url = url.replace('CASE_ID', product_tableid);
    });
</script>
<script>
    $(document).on('click', '.fiTypeModel', function() {
        $('#fiTypeModel').modal('show');
        
        let case_tableid = $(this).data('row');
        
        let app_name = $(this).data('app_name');
        let ref_no = $(this).data('ref_no');
        if(app_name != ''){
            $('#app_name').val(app_name);
        }
        if(ref_no != ''){
            $('#ref_no').val(ref_no);
        }
        if(case_tableid != ''){
            $('#case_tableid').val(case_tableid);
        }
        
        var url = "{{ route('admin.case.reinitatiateCaseNew','CASE_ID')}}";
        url = url.replace('CASE_ID', case_tableid);
    });
</script>
<script>
    $(document).on('click', '.branchChangeModel', function() {
        $('#branchChangeModel').modal('show');
        
        let case_tableid = $(this).data('row');
        
        let app_name = $(this).data('app_name');
        let ref_no = $(this).data('ref_no');
        if(app_name != ''){
            $('#bc_app_name').val(app_name);
        }
        if(ref_no != ''){
            $('#bc_ref_no').val(ref_no);
        }
        if(case_tableid != ''){
            $('#bc_case_tableid').val(case_tableid);
        }
        
        var url = "{{ route('admin.case.reinitatiateCaseNew','CASE_ID')}}";
        url = url.replace('CASE_ID', case_tableid);
    });
</script>
<script>
    $(document).ready(function() {
    // Assuming the form has an element with id #fi_type_id and a submit button
    $('#updateFiTypeButton').on('click', function(e) {
        e.preventDefault();

        var caseTableId = $('#case_tableid').val(); // Assuming #case_tableid holds the id value
        var fiTypeId = $('#fi_type').val(); // Assuming #fi_type_id holds the fi_type_id value
        var url = "{{ route('admin.case.fitype','CASE_ID')}}";
        url = url.replace('CASE_ID', caseTableId);
        // Send AJAX request
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                case_tableid: caseTableId,
                fi_type_id: fiTypeId
            },
            success: function(response) {
                if(response.success) {
                    alert('Field updated successfully!');
                    location.reload();
                } else {
                    alert('An error occurred while updating.');
                }
            },
            error: function(xhr, status, error) {
                alert('Request failed: ' + error);
            }
        });
    });
    $('#updateProductButton').on('click', function(e) {
        e.preventDefault();

        var caseTableId = $('#product_tableid').val(); // Assuming #case_tableid holds the id value
        var product = $('#productSelect').val(); // Assuming #fi_type_id holds the fi_type_id value
        var url = "{{ route('admin.case.product','CASE_ID')}}";
        url = url.replace('CASE_ID', caseTableId);
        // Send AJAX request
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                case_tableid: caseTableId,
                product_id: product
            },
            success: function(response) {
                if(response.success) {
                    alert('Field updated successfully!');
                    location.reload();
                } else {
                    alert('An error occurred while updating.');
                }
            },
            error: function(xhr, status, error) {
                alert('Request failed: ' + error);
            }
        });
    });
    $('#bc_updateFiTypeButton').on('click', function(e) {
        e.preventDefault();

        var caseTableId = $('#bc_case_tableid').val(); // Assuming #case_tableid holds the id value
        var branchCode = $('#bc_branch_code').val(); // Assuming #fi_type_id holds the fi_type_id value
        var url = "{{ route('admin.case.branchcode','CASE_ID')}}";
        url = url.replace('CASE_ID', caseTableId);
        // Send AJAX request
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                case_tableid: caseTableId,
                branch_code: branchCode
            },
            success: function(response) {
                if(response.success) {
                    alert('Field updated successfully!');
                    location.reload();
                } else {
                    alert('An error occurred while updating.');
                }
            },
            error: function(xhr, status, error) {
                alert('Request failed: ' + error);
            }
        });
    });
});

</script>
<script>
        // Handle individual row checkbox click event
        $('.selectRow').click(function() {
            // If not all checkboxes are checked, uncheck "Select All"
            if ($('.selectRow:checked').length !== $('.selectRow').length) {
                $('#selectAll').prop('checked', false);
            }
            // If all checkboxes are checked, check "Select All"
            if ($('.selectRow:checked').length === $('.selectRow').length) {
                $('#selectAll').prop('checked', true);
            }
        });
</script>
<script>
    $(document).on('click', '.resolveCase', function() {
        let getRow = $(this).attr('data-row');
        let getFitype = $(this).attr('data-fitype');
        let customGetPath = "{{ route('admin.users.caseStatus', ['type' => 1]) }}";
        $.ajax({
            url: customGetPath,
            type: 'GET',
            success: function(response) {
                var select = $('#caseSubSelect');
                select.empty(); // Clear any existing options
                select.append('<option value="">--Select Option--</option>'); // Add default option
                $.each(response, function(key, users) {
                    $.each(users, function(index, user) {
                        var option = $('<option></option>')
                            .attr('value', user.id)
                            .text(user.name);
                        select.append(option);
                    });
                    $(".case_fi_type_id").val(getRow);
                    // if(getFitype == 12 || getFitype == 13){
                    if(getFitype == 12){
                        itrremarks();
                        $('#add-remarks-row').removeClass('d-none');
                    }else{
                        $('#fi_itr').html('');
                        $('#add-remarks-row').addClass('d-none');
                    }
                    $('#resolveCaseModel').modal('show');
                });
            },
            error: function() {
                alert('Request failed');
            }
        });
    });
</script>
<script>
        $(document).on('click', '#getSelectedIds', function() {
            var selectedIds = [];
            $('.selectRow:checked').each(function() {
                selectedIds.push($(this).val());
            });
            var selectedIdsJson = JSON.stringify(selectedIds);
            if (selectedIds.length == 0 || selectedIds.length == undefined || selectedIds.length == 'undefined') {
                alert('No case selected.');
                return false;
            }

            if (selectedIds.length > 0) {

                var customGetPath = "{{ route('admin.users.agent','1')}}";
                $.ajax({
                    url: customGetPath,
                    type: 'GET',
                    success: function(response) {
                        var select = $('#userSelect');
                        select.empty(); // Clear any existing options
                        select.append('<option value="">--Select Option--</option>'); // Add default option
                        $.each(response, function(key, users) {
                            $.each(users, function(index, user) {
                                var option = $('<option></option>')
                                    .attr('value', user.id)
                                    .text(user.name);
                                select.append(option);
                            });

                            $(".case_fi_type_id").val(selectedIdsJson);
                            $('#exampleModal').modal('show');
                        });
                    },
                    error: function() {
                        alert('Request failed');
                    }
                });

            } else {
                alert('No case selected.');
            }
        });
</script>
<script>
        //For Single Row POPUP
        $(document).on('click', '.assignSingle', function() {
            var selectedIds = [];
            let getRow = $(this).attr('data-row');
            selectedIds.push(getRow);
            let customGetPath = "{{ route('admin.users.agent','1')}}";
            let selectedIdsJson = JSON.stringify(selectedIds);
            $.ajax({
                url: customGetPath,
                type: 'GET',
                success: function(response) {
                    var select = $('#userSelect');
                    select.empty(); // Clear any existing options
                    select.append('<option value="">--Select Option--</option>'); // Add default option
                    $.each(response, function(key, users) {
                        $.each(users, function(index, user) {
                            var option = $('<option></option>')
                                .attr('value', user.id)
                                .text(user.name);
                            select.append(option);
                        });

                        $(".case_fi_type_id").val(selectedIdsJson);
                        $('#exampleModal').modal('show');
                    });
                },
                error: function() {
                    alert('Request failed');
                }
            });
        });
</script>

<script>

        function itrremarks() {
            // Check the current number of blocks
            var blockCount = $('.remarks-row-block').length;

            // Allow adding rows only if the count is less than 5
            if (blockCount < 5) {
                // Validate all existing blocks
                var allValid = true;
                $('.remarks-row-block').each(function () {
                    $(this).find('select').each(function () {
                        if ($(this).val() === "") {
                            allValid = false;
                        }
                    });
                    $(this).find('.fi_type_remarks').each(function () {
                        if ($(this).val() === "") {
                            allValid = false;
                        }
                    });
                });

                if (allValid) {
                    var form = `
                        <div class="col-md-12 col-sm-12 p-2 remarks-row-block" style="border: 2px solid #ced4da;">
                            <div class="form-group col-md-12 col-sm-12">
                                <label for="financial_year">Financial Year/Date</label>
                                <!--select name="financial_year[]" class="custom-select" required>
                                    <option value="">--Select Year--</option>
                                    @foreach($financialYears as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select-->
                                <input type="text" name="financial_year[]" class="form-control" placeholder="Enter Year/Date">
                            </div>
                            <div class="form-group col-md-12 col-sm-12">
                                <label for="fi_remarks">Remarks</label>
                                <textarea class="form-control fi_type_remarks" name="fi_remarks[]" required></textarea>
                            </div>
                            <button class="btn btn-danger remove-remarks-row">Remove Row</button>
                        </div>`;
                    $('#fi_itr').append(form);

                    // Hide messages if validation passes
                    $('#remarks-limit-message, #remarks-validation-message').hide();
                } else {
                    // Show a validation message if not all fields are filled
                    $('#remarks-validation-message').show();
                }
            } else {
                // Show a limit message if the max rows are reached
                $('#remarks-limit-message').show();
            }
        }

        // Add a new remarks row on button click
        $(document).on('click', '#add-remarks-row', function () {
            itrremarks();
        });
</script>
<script>
        // Remove a row when "Remove Row" is clicked
        $(document).on('click', '.remove-remarks-row', function () {
            $(this).closest('.remarks-row-block').remove();

            // Hide messages when a block is removed
            if ($('.remarks-row-block').length < 5) {
                $('#remarks-limit-message').hide();
            }
            $('#remarks-validation-message').hide();
        });
</script>

<script>
        $(document).on('click', '.verifiedCase', function() {
            let getRow = $(this).attr('data-row');
            let customGetPath = "{{ route('admin.users.caseStatus', ['type' => 1, 'parent_id' => '4']) }}";
            $.ajax({
                url: customGetPath,
                type: 'GET',
                success: function(response) {
                    var select = $('#feedbackSelect');
                    select.empty(); // Clear any existing options
                    select.append('<option value="">--Select Option--</option>'); // Add default option
                    $.each(response, function(key, users) {
                        $.each(users, function(index, user) {
                            var option = $('<option></option>')
                                .attr('value', user.id)
                                .text(user.name);
                            select.append(option);
                        });
                        $(".case_fi_type_id").val(getRow);
                        $('#verifiedCaseModel').modal('show');
                    });
                },
                error: function() {
                    alert('Request failed');
                }
            });
        });
    </script>
<script>
        $(document).on('change', '#feedbackSelect', function() {
            var parent_id = $(this).val();
            if (parent_id.length > 0) {
                var url = "{{ route('admin.users.caseStatus', ['type' => 1, 'parent_id' => 'PARENT_ID']) }}";
                url = url.replace('PARENT_ID', parent_id);
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        var select = $('#verifiedCaseSubSelect');
                        select.empty(); // Clear any existing options
                        select.append('<option value="">--Select Option--</option>'); // Add default option
                        $.each(response, function(key, users) {
                            $.each(users, function(index, user) {
                                var option = $('<option></option>')
                                    .attr('value', user.id)
                                    .text(user.name);
                                select.append(option);
                            });
                        });
                    },
                    error: function() {
                        alert('Request failed');
                    }
                });

            } else {
                alert('No case selected.');
            }

        });
    </script>
<script>
        $(document).on('click', '.consolidatedRemarks', function() {
            let case_id = $(this).attr('data-row');
            var url = "{{ route('admin.case.getCase','CASE_ID')}}";
            url = url.replace('CASE_ID', case_id);

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $(".consolidated_remarks").val(response.case_fi_type.consolidated_remarks);
                    $(".case_fi_type_id").val(case_id);
                    $('#consolidatedRemarksModel').modal('show');

                },
                error: function() {
                    alert('Request failed');
                }
            });

        });
    </script>
<script>
        $(document).on('click', '.caseHistory', function() {
            let case_id = $(this).attr('data-row');
            var url = "{{ route('admin.case.getcaseHistory','CASE_ID')}}";
            url = url.replace('CASE_ID', case_id);

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $("#caseHistoryModel").find('.modal-body').html(response.viewData);
                    $('#caseHistoryModel').modal('show');

                },
                error: function() {
                    alert('Request failed');
                }
            });

        });
    </script>
<script>
        $(document).on('click', '.caseClose', function() {
            if (confirm('Are you sure to close this case')) {
                let case_id = $(this).attr('data-row');
                var url = "{{ route('admin.case.close','CASE_ID')}}";
                url = url.replace('CASE_ID', case_id);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            alert(response.success);
                            // window.location.href = "{{ route('admin.dashboard') }}";
                            location.reload();
                        } else {
                            alert('Unable to close the case.');
                        }

                    },
                    error: function() {
                        alert('Request failed');
                    }
                });

            }

        });
</script>
<script>
        $(document).on('click', '.viewFormEdit', function(e) {
            e.preventDefault();
            let case_id = $(this).attr('data-row');
            var url = "{{ route('admin.case.viewForm.modify','CASE_ID')}}";
            url = url.replace('CASE_ID', case_id);
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $("#viewFormEditModel").find('.modal-body').html(response.viewData);
                    $('#viewFormEditModel').modal('show');
                },
                error: function() {
                    alert('Request failed');
                }
            });
        });
</script>
<script>
        $(document).on('click', '.cloneCase', function() {
            if (confirm('Are you sure to clone this case')) {
                let case_id = $(this).attr('data-row');
                var url = "{{ route('admin.case.clone','CASE_ID')}}";
                url = url.replace('CASE_ID', case_id);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            alert(response.success);
                            // window.location.href = "{{ route('admin.dashboard') }}";
                            location.reload();
                        } else {
                            alert('Unable to close the case.');
                        }

                    },
                    error: function() {
                        alert('Request failed');
                    }
                });

            }

        });
    </script>
<script>
        $(document).on('click', '.HoldCase', function() {
            if (confirm('Are you sure to hold this case')) {
                let case_id = $(this).attr('data-row');
                var url = "{{ route('admin.case.hold','CASE_ID')}}";
                url = url.replace('CASE_ID', case_id);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            alert(response.success);
                            // window.location.href = "{{ route('admin.dashboard') }}";
                            location.reload();
                        } else {
                            alert('Unable to close the case.');
                        }

                    },
                    error: function() {
                        alert('Request failed');
                    }
                });

            }

        });
    </script>
<script>
        $(document).on('click', '.CaseDelete', function() {
            if (confirm('Are you sure to Delete this case')) {
                let case_id = $(this).attr('data-row');
                var url = "{{ route('admin.case.delete','CASE_ID')}}";
                url = url.replace('CASE_ID', case_id);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            alert(response.success);
                            // window.location.href = "{{ route('admin.dashboard') }}";
                            location.reload();
                        } else {
                            alert('Unable to Delete the case.');
                        }

                    },
                    error: function() {
                        alert('Request failed');
                    }
                });

            }

        });
</script>
<script>
        $(document).on('click', '.caseReinitiates', function() {
            let case_id = $(this).attr('data-row');
            var url = "{{ route('admin.case.reinitatiateCaseNew','CASE_ID')}}";
            url = url.replace('CASE_ID', case_id);

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $(".htmlFormReinitatiateCaseSection").html('');
                    $(".htmlFormReinitatiateCaseSection").html(response.htmlFormReinitatiateCase);
                    $('#caseReinitiatesModel').modal('show');

                },
                error: function() {
                    alert('Request failed');
                }
            });

        });
    // });
</script>

<script>
    function printformFunction() {
        var divToPrint = document.getElementById('outprint'); // Get the table element
        var newWindow = window.open('', '', 'width=800,height=600'); // Open a new window
        newWindow.document.write('<html><head><title>Print Table</title>');
        newWindow.document.write('<style>table, th, td {border: 1px solid black; border-collapse: collapse; padding: 10px;}</style>'); // Optional: Add CSS for the table
        newWindow.document.write('</head><body>');
        newWindow.document.write(divToPrint.outerHTML); // Add the table's HTML to the new window
        newWindow.document.write('</body></html>');
        newWindow.document.close(); // Close the document
        newWindow.print(); // Trigger print
    }
</script>
<!-- Start datatable js -->
<script>
    $(document).on('click', '#selectAll', function() {
        $('.selectRow').prop('checked', this.checked);
    });
</script>
<script>
$(document).on('click', '.zoomimg', function(){
    var imgname = $(this).data('img');
  $('#img01').attr('src', '{{asset('')}}'+imgname);
  $("#myimgModal").fadeIn();
})
$(".closebtn").click(function(){
        $("#myimgModal").fadeOut();
});
</script>
<script>
    // 1. Define fetchCases globally
function fetchCases(page = 1, is_pagination = false) {
    let keyword = $('#searchInput').val();
    let perPage = $('#recordsPerPage').val();
    let status = "{{ $status }}";
    let user_id = "{{ $user_id }}";
    var is_pagination = (is_pagination === true) ? 1 : false;

    $.ajax({
        url: `{{ route('admin.case.caseStatus', ['status' => '__STATUS__', 'user_id' => '__USERID__']) }}`.replace('__STATUS__', status).replace('__USERID__', user_id),
        type: 'GET',
        data: { 
            search: keyword, 
            perPage: perPage, 
            is_pagination: is_pagination, 
            page: page // Pass page as a parameter
        },
        success: function(data) {
            $('#caseTableContainer').html(data);
        },
        error: function() {
            alert('Error fetching data.');
        }
    });
}

// 2. Pagination click event (delegated, outside document.ready)
$(document).on('click', '.pagination-container a', function(e) {
    e.preventDefault();
    let href = $(this).attr('href');
    let page = 1;
    if (href) {
        let match = href.match(/page=(\d+)/);
        if (match) {
            page = match[1];
        }
    }
    fetchCases(page, true);
});

// 3. Other events inside document.ready
$(document).ready(function() {
    // Search input keyup event
    $(document).on('keyup', '#searchInput', function() {
        fetchCases();
    });

    // Records per page change event
    $(document).on('change', '#recordsPerPage', function() {
        fetchCases();
    });

});
    
    </script>


<script>
    $(document).on('click', '.geoLimitModel', function() {
        $('#geoLimitModel').modal('show');
        
        let case_tableid = $(this).data('row');
        let app_name = $(this).data('app_name');
        let ref_no = $(this).data('ref_no');
        let geo_limit = $(this).data('geo_limit');
        
        if(app_name != ''){
            $('#gl_app_name').val(app_name);
        }
        if(ref_no != ''){
            $('#gl_ref_no').val(ref_no);
        }
        
        // Reset select to default first
        $('#gl_geo_limit').val('');
        
        // Only set value if geo_limit exists and is not empty
        if(geo_limit && geo_limit !== '' && geo_limit !== null && geo_limit !== undefined) {
            $('#gl_geo_limit').val(geo_limit);
        }
        
        if(case_tableid != ''){
            $('#gl_case_tableid').val(case_tableid);
        }
    });
</script>

<script>
    $(document).ready(function() {
        // ...existing code...
        
        $('#gl_updateGeoLimitButton').on('click', function(e) {
            e.preventDefault();

            var caseTableId = $('#gl_case_tableid').val();
            var geoLimit = $('#gl_geo_limit').val();
            var url = "{{ route('admin.case.geolimit','CASE_ID')}}";
            url = url.replace('CASE_ID', caseTableId);
            
            // Validate geo limit selection
            if(!geoLimit) {
                alert('Please select a geo limit option.');
                return;
            }
            
            // Send AJAX request
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    case_tableid: caseTableId,
                    geo_limit: geoLimit
                },
                success: function(response) {
                    if(response.success) {
                        alert('Geo limit updated successfully!');
                        location.reload();
                    } else {
                        alert('An error occurred while updating.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Request failed: ' + error);
                }
            });
        });
    });
</script>

@endsection