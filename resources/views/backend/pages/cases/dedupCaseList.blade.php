@extends('backend.layouts.master')

@section('title')
Cases - Admin Panel
@endsection

@section('styles')
<!-- Start datatable css -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection


@section('admin-content')

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

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title float-left">Dedup Cases List</h4>
                    <p class="float-right mb-2">



                        <button type="button" class="btn btn-primary text-white btn-sm" id="getSelectedIds">Assign</button>
                        <a class="btn btn-warning text-white" href="{{ route('admin.case.export.excel','a') }}">Export Cases</a>

                    </p>
                    <div class="clearfix"></div>
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')


                        <table class="text-center" id="exampleTable">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="8%"><input type="checkbox" id="selectAll"></th>
                                    <th width="8%">App Id</th>
                                    <th width="8%">Internal Code</th>
                                    <th width="8%">Name</th>
                                    <th width="8%">Mobile Number</th>
                                    <th width="10%">Address</th>
                                    <th width="8%">FIType</th>
                                    <th width="8%">Scheduled Date</th>
                                    <th width="8%">Agent</th>
                                    <th width="8%">Status</th>
                                    <th width="8%">SubStatus</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cases as $case)

                                <tr>
                                    <td class="text-center"><input type="checkbox" class="selectRow" value="{{ $case->id }}"></td>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{ $case->getCase->refrence_number ?? '' }}</td>
                                    <td>{{ $case->getCase->applicant_name ?? '' }}</td>
                                    <td>{{ $case->mobile ?? '' }}</td>
                                    <td>{{ $case->address ?? '' }}</td>

                                    @php
                                    $fiType = $case->getFiType->name ?? null;
                                    $bank = $case->getCase->getBank->name ?? null;
                                    $product = $case->getCase->getProduct->name ?? null;

                                    $columnValue = null;

                                    if($bank){
                                    $columnValue = $bank;
                                    }

                                    if($product){
                                    if($columnValue){
                                    $columnValue .= ' ';
                                    }
                                    $columnValue .= $product;
                                    }

                                    if($fiType){
                                    if($columnValue){
                                    $columnValue .= ' ';
                                    }
                                    $columnValue .= $fiType;
                                    }
                                    @endphp

                                    <td>{{ $columnValue  }} </td>
                                    <td>{{ humanReadableDate($case->scheduled_visit_date) }}</td>
                                    <td>{{ $case->getUser->name ?? '' }}</td>
                                    <td>{{ get_status($case->status) }}</td>
                                    <td>{{ $case->getCaseStatus->name ?? '' }} </td>
                                    <td>
                                        @if(isset($assign) && !$assign)
                                        <a href="{{ route('admin.case.viewCase', $case->id) }}"><img src="{{URL::asset('backend/assets/images/icons/user.png')}}" title="View"></img></a>
                                        @endif

                                        @if(isset($assign) && $assign)
                                        <a href="{{ route('admin.case.viewCaseAssign', $case->id) }}"><img src="{{URL::asset('backend/assets/images/icons/user.png')}}" title="View"></img></a>
                                        @endif

                                        <!-- <a href="{{ route('admin.case.editCase', $case->id) }}"><img src="{{URL::asset('backend/assets/images/icons/edit.png')}}" title="Edit"></img></a>
                                        <a href="javascript:void(0)" data-row="{{ $case->id }}" class="assignSingle"><img src="{{URL::asset('backend/assets/images/icons/stock_task-assigned-to.png')}}" title="Assign"></img></a>
                                        <a href="javascript:void(0)" data-row="{{ $case->id }}" class="resolveCase"><img src="{{URL::asset('backend/assets/images/icons/change_status.png')}}" title="Resolve"></img></a>
                                        <a href="javascript:void(0)" data-row="{{ $case->id }}" class="verifiedCase"><img src="{{URL::asset('backend/assets/images/icons/checkbox.png')}}" title="Verified"></img></a>
                                        <a href="javascript:void(0)" data-row="{{ $case->id }}" class="consolidatedRemarks"><img src="{{URL::asset('backend/assets/images/icons/page_white_text_width.png')}}" title="Consolidated remarks"></img></a>
                                        <a href="{{ route('admin.case.upload.image', $case->id) }}"><img src="{{URL::asset('backend/assets/images/icons/uploadImage.png')}}" title="Upload"></img></a> -->
                                        <a href="javascript:void(0)" data-row="{{ $case->id }}" class="caseClose"><img src="{{URL::asset('backend/assets/images/icons/Close.gif')}}" title="Case close"></img></a>
                                        <a href="javascript::void(0)" class="viewForm" data-row="{{ $case->id }}"><img src="{{URL::asset('backend/assets/images/icons/verified_cases.png')}}" title="View Form"></img></a>
                                        <!-- <a href="javascript:void(0)" data-row="{{ $case->id }}" class="cloneCase"><img src="{{URL::asset('backend/assets/images/icons/add.png')}}" title="clone case"></img></a>
                                        <a href="javascript:void(0)" data-row="{{ $case->id }}" class="caseReinitiates"><img src="{{URL::asset('backend/assets/images/icons/page_white_text_width.png')}}" title="Reinitiates Case"></img></a>
                                        <a href="javascript::void(0)" class="viewFormEdit" data-row="{{ $case->id }}"><img src="{{URL::asset('backend/assets/images/icons/edit.png')}}" title="View Form Edit"></img></a>
                                        <a href="javascript:void(0)" data-row="{{ $case->id }}" class="HoldCase"><img src="{{URL::asset('backend/assets/images/icons/HoldCase.png')}}" title="Hold case"></img></a> -->

                                        <!-- <a href="{{ route('admin.case.zip.download', $case->id) }}"><img src="{{URL::asset('backend/assets/images/icons/downloads.png')}}" title="Download Zip"></img></a> -->


                                        <a href="{{ route('admin.case.export.pdf', $case->id) }}"><img src="{{URL::asset('backend/assets/images/icons/Pdf.png')}}" title="Download PDF"></img></a>
                                        <!-- <a href="{{ route('admin.case.dedup-case', $case->id) }}" target="__blank"><img src="{{URL::asset('backend/assets/images/icons/view.png')}}" title="show orignal case"></img></a> -->




                                        <!-- <a class="btn btn-success text-white" href="{{ route('admin.case.edit', $case->id) }}">Edit</a>

                                        <a class="btn btn-danger text-white" href="{{ route('admin.case.edit', $case->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $case->id }}').submit();">
                                            Delete
                                        </a>

                                        <form id="delete-form-{{ $case->id }}" action="{{ route('admin.case.destroy', $case->id) }}" method="POST" style="display: none;">
                                            @method('DELETE')
                                            @csrf
                                        </form> -->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                        <textarea class="form-control" name="consolidated_remarks" required></textarea>
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
<div class="modal fade" id="viewFormModel" tabindex="-1" role="dialog" aria-labelledby="viewFormModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
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


@endsection


@section('scripts')
<!-- Start datatable js -->
{{-- <script src="https://code.jquery.com/jquery-3.7.1.js"></script> --}}




<script>
    // Wait for the DOM to be ready
    $(document).ready(function() {
        // Handle the "Select All" checkbox click event
        $('#selectAll').click(function() {
            // Set the checked property for all row checkboxes based on "Select All" checkbox
            $('.selectRow').prop('checked', this.checked);
        });

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

        //For Multiple Row POPUP
        // Handle the "Get Selected IDs" button click event
        $('#getSelectedIds').click(function() {
            // Create an array to store selected IDs
            var selectedIds = [];
            // Loop through checked checkboxes and get their values (IDs)
            $('.selectRow:checked').each(function() {
                selectedIds.push($(this).val());
            });

            // Convert the array of selected IDs to JSON format
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
                                console.log(user);
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

        //For Single Row POPUP
        $('.assignSingle').click(function() {
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
                            console.log(user);
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

        $('.resolveCase').click(function() {
            let getRow = $(this).attr('data-row');
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
                            console.log(user);
                            var option = $('<option></option>')
                                .attr('value', user.id)
                                .text(user.name);
                            select.append(option);
                        });
                        $(".case_fi_type_id").val(getRow);
                        $('#resolveCaseModel').modal('show');
                    });
                },
                error: function() {
                    alert('Request failed');
                }
            });
        });

        $('.verifiedCase').click(function() {
            let getRow = $(this).attr('data-row');
            let customGetPath = "{{ route('admin.users.caseStatus', ['type' => 1, 'parent_id' => '0']) }}";
            $.ajax({
                url: customGetPath,
                type: 'GET',
                success: function(response) {
                    var select = $('#feedbackSelect');
                    select.empty(); // Clear any existing options
                    select.append('<option value="">--Select Option--</option>'); // Add default option
                    $.each(response, function(key, users) {
                        $.each(users, function(index, user) {
                            console.log(user);
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
        $('#feedbackSelect').change(function() {
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
                                console.log(user);
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
        $('.consolidatedRemarks').click(function() {
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
        $('.caseClose').click(function() {
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
                            window.location.href = "{{ route('admin.dashboard') }}";

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


        $('.viewForm').click(function(e) {
            e.preventDefault();
            let case_id = $(this).attr('data-row');
            var url = "{{ route('admin.case.viewForm','CASE_ID')}}";
            url = url.replace('CASE_ID', case_id);

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $("#viewFormModel").find('.modal-body').html(response.viewData);
                    $('#viewFormModel').modal('show');
                },
                error: function() {
                    alert('Request failed');
                }
            });

        });


        $('.viewFormEdit').click(function(e) {
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

        $('.cloneCase').click(function() {
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
                            window.location.href = "{{ route('admin.dashboard') }}";

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
        $('.HoldCase').click(function() {
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
                            window.location.href = "{{ route('admin.dashboard') }}";

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

        $('.caseReinitiates').click(function() {
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
    });
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
@endsection