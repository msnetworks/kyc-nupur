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
<div class="main-content-inner" bis_skin_checked="1">
    <div class="row" bis_skin_checked="1">
        <!-- data table start -->
        <div class="col-12 mt-5" bis_skin_checked="1">
            <div class="card" bis_skin_checked="1">
                <div class="card-body" bis_skin_checked="1">
                    @if($is_edit_case)
                    <h4 class="header-title">Edit Case</h4>
                    @else
                    <h4 class="header-title">View Case</h4>
                    @endif



                    <form action="{{ route('admin.case.modifyCase',$case->id)}}" method="POST" name="caseEdit" id="editCase">
                        @csrf
                        <input type="hidden" name="fi_type_id" value="{{  $case->fi_type_id ?? ''  }}" />
                        <input type="hidden" name="case_fi_id" value="{{  $case->id ?? ''  }}" />
                        <input type="hidden" name="case_id" value="{{  $case->getCase->id ?? ''  }}" />
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <table cellspacing="0" class="table table-bordered" width="100%">
                                        <tbody>
                                            <tr>
                                                <td align="left" valign="middle"><strong> Application Number : </strong></td>
                                                <td align="left" valign="middle">{{ $case->getCase->refrence_number ?? '' }}</td>
                                                <td align="left" valign="middle"><strong>Type of FI :</strong></td>
                                                <td align="left" valign="middle"> {{ $case->getFiType->name }} </td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle"><strong>Status :</strong></td>
                                                <td align="left" valign="middle">{{ ucfirst(get_status($case->status)) }}</td>

                                                <td align="left" valign="middle"><strong>Sub Status :</strong></td>
                                                <td align="left" valign="middle">{{ $case->sub_status ?? '-' }} </td>
                                            </tr>

                                            <tr>

                                                <td align="left" valign="middle"><strong> Assign To : </strong></td>
                                                <td align="left" valign="middle">{{ $case->getUser->name ?? 'Not Assigned' }}</td>
                                                
                                                <td align="left" valign="middle"><strong>Created Date :</strong></td>
                                                <td align="left" valign="middle">{{ $case->created_at ? date('d-m-Y', strtotime($case->created_at)) : '' }} </td>


                                            </tr>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <table cellspacing="0" class="table table-bordered" width="100%">
                                        <tbody>
                                            <tr>
                                                <th valign="middle" colspan="2"></th>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle"><strong> Applicant Name : </strong></td>
                                                <td align="left" valign="middle">
                                                    <input type="text" class="form-control" name="applicant_name" value="{{ $case->getCase->applicant_name ?? '' }}" />
                                                </td>
                                            </tr>
                                            @if($case->fi_type_id == 7)
                                            <div id="fieldrv" class="fieldSet" style="display:{{ $case->fi_type_id == 7 ? 'block' : 'none'}};">
                                                <h4>RV</h4>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <input class="form-control" value="{{ $case->address }}" name="address" value="{{ $case->address }}" type="text" placeholder="RV Address">
                                                    </div>
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <input class="form-control" value="{{ $case->pincode }}" name="pincode" value="{{ $case->pincode }}" type="number" placeholder="RV Pincode (6 digits)" pattern="\d{6}">
                                                    </div>
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <input class="form-control" value="{{ $case->mobile }}" name="mobile" value="{{ $case->mobile }}" type="number" placeholder="RV Phone Number (10 digits)" pattern="\d{10}">
                                                    </div>
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <input class="form-control" value="{{ $case->land_mark }}" name="land_mark" value="{{ $case->land_mark }}" type="text" placeholder="RV Landmark">
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if($case->fi_type_id == 8)
                                            <div id="fieldbv" class="fieldSet" style="display:{{ $case->fi_type_id == 8 ? 'block' : 'none'}};">
                                            <h4>BV</h4>
                                            <div class="form-row">
                                                <div class="form-group col-md-6 col-sm-12">
                                                    <input class="form-control" value="{{ $case->address }}" name="address" type="text" placeholder="BV Address">
                                                </div>
                                                <div class="form-group col-md-6 col-sm-12">
                                                    <input class="form-control" value="{{ $case->pincode }}" name="pincode" type="number" placeholder="BV Pincode (6 digits)" pattern="\d{6}">
                                                </div>
                                                <div class="form-group col-md-6 col-sm-12">
                                                    <input class="form-control" value="{{ $case->mobile }}" name="mobile" type="number" placeholder="BV Phone Number (10 digits)" pattern="\d{10}">
                                                </div>
                                                <div class="form-group col-md-6 col-sm-12">
                                                    <input class="form-control" value="{{ $case->land_mark }}" name="land_mark" type="text" placeholder="BV Landmark">
                                                </div>
                                            </div>
                                            </div>
                                            @endif
                                            @if($case->fi_type_id == 12)
                                            <div id="fielditr" class="fieldSet" style="display:{{ $case->fi_type_id == 12 ? 'block' : 'none'}};">
                                            <h4>ITR</h4>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <input class="form-control" value="{{ $case->pan_number }}" name="pan_number" type="text" placeholder="ITR Pancard">
                                                    </div>
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <input class="form-control" value="{{ $case->assessment_year }}" name="assessment_year" type="text" placeholder="ITR Assessment Year">
                                                    </div>
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <input class="form-control" value="{{ $case->mobile }}" name="mobile" type="number" placeholder="ITR Mobile Number (10 digits)" pattern="\d{10}">
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if($case->fi_type_id == 13)
                                            <div id="fieldbanking" class="fieldSet" style="display:{{ $case->fi_type_id == 13 ? 'block' : 'none'}};">
                                            <h4>Banking</h4>
                                            <div class="form-row">
                                                <div class="form-group col-md-6 col-sm-12">
                                                    <input class="form-control" value="accountnumber" name="accountnumber" type="text" placeholder="Banking Account Number">
                                                </div>
                                                <div class="form-group col-md-6 col-sm-12">
                                                    <input class="form-control" value="mobile" name="mobile" type="number" placeholder="Banking Mobile Number (10 digits)" pattern="\d{10}">
                                                </div>
                                                <div class="form-group col-md-6 col-sm-12">
                                                    <input class="form-control" value="bank_name" name="bank_name" type="text" placeholder="Verify Bank Name">
                                                </div>
                                            </div>
                                            </div>
                                            @endif
                                            @if($case->fi_type_id == 14)
                                            <div id="fieldform_16" class="fieldSet" style="display:{{ $case->fi_type_id == 14 ? 'block' : 'none'}};">
                                            <h4>Form 16</h4>
                                            <div class="form-row">
                                                <div class="form-group col-md-6 col-sm-12">
                                                    <input class="form-control" value="{{ $case->pan_number }}" name="pan_number" type="text" placeholder="Pan Card">
                                                </div>
                                                <div class="form-group col-md-6 col-sm-12">
                                                    <input class="form-control" value="{{ $case->assessment_year }}" name="assessment_year" type="text" placeholder="Assignment Years">
                                                </div>
                                                <div class="form-group col-md-6 col-sm-12">
                                                    <input class="form-control" value="{{ $case->mobile }}" name="mobile" type="number" placeholder="Applicant's Mobile Number (10 digits)" pattern="\d{10}">
                                                </div>
                                            </div>
                                            </div>
                                            @endif
                                            @if($case->fi_type_id == 17)
                                            <div id="fieldpan_card" class="fieldSet" style="display:{{ $case->fi_type_id == 17 ? 'block' : 'none'}};">
                                                <h4>Pancard</h4>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <input class="form-control" value="{{ $case->pan_number }}" name="pan_number" type="text" placeholder="Pan Card">
                                                    </div>
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <input class="form-control" value="{{ $case->aadhar_card }}" name="aadhar_number" type="number" placeholder="Aadhar Number">
                                                    </div>
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <input class="form-control" value="{{ $case->mobile }}" name="mobile" type="number" placeholder="Mobile Number (10 digits)" pattern="\d{10}">
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            {{-- <tr>
                                                <td align="left" valign="middle"><strong>Loan Amount</strong></td>
                                                <td align="left" valign="middle"><input type="text" class="form-control" name="amount" value="{{ $case->getCase->amount ?? 0 }}" /> </td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle"><strong>Geo Limit</strong></td>
                                                <td align="left" valign="middle">
                                                    <select id="geo_limit" name="geo_limit" class="custom-select">
                                                        <option value="">--Select Option--</option>
                                                        <option value="Local" @if($case->getCase->geo_limit == 'Local') selected @endif>Local</option>
                                                        <option value="Outstation" @if($case->getCase->geo_limit == 'Outstation') selected @endif>Outstation</option>
                                                    </select></td>
                                            </tr> --}}
                                            <tr>
                                                <td align="left" valign="middle"><strong>Remarks</strong></td>
                                                <td align="left" valign="middle">
                                                    <textarea name="remarks" rows="2" cols="20" id="remarks" class="form-control" placeholder="Remarks">{{ $case->getCase->remarks ?? '' }}</textarea>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 d-flex justify-content-center mt-2 gap-5">
                            <a href="javascript:history.back()" class="btn btn-sm btn-primary"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                            @if($is_edit_case)
                            <button class="btn btn-sm btn-success ml-2 updateCase" type="submit" name="submit"> Update </button>
                            @endif

                        </div>
                    </form>


                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>

<div class="main-content-inner">

    <div class="row">

    </div>
</div>
@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        $('.updateCase').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            var formData = form.serializeArray();
            let rowId = form.find('input[name="case_fi_id"]').val();
            let actionPath = "{{ route('admin.case.modifyCase','ID')}}";
            actionPath = actionPath.replace('ID', rowId);
            $.ajax({
                url: actionPath,
                type: 'POST',
                data: formData,
                success: function(response) {
                    alert('case update successfully');
                    location.reload();
                },
                error: function() {
                    alert('Request failed');
                }
            });
        });
    });
</script>

@endsection