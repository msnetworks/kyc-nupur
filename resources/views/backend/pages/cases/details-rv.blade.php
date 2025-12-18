<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'View Detail - RV')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('backend.layouts.partials.styles')
    @yield('styles')
</head>
@section('title')
    View Detail - RV
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection


@section('admin-content')
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Cases Detail</h4>
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
                    <div class="card-body p-0">
                        <h4 class="header-title float-left">View Cases Detail</h4>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <span class="pull-right" height="30px;">
                                <a href="javascript:void(0)" onclick="printformFunction()">Click here to print</a>
                            </span>
                            <table class="table table-bordered" id="outprint">
                                <tbody>
                                    <tr>
                                        <td style="width: 50%; border:none;font-size:22px;color:#0094ff; text-align:center" colspan="3">
                                            <img alt="TIGER 4 INDIA LTD" src="{{ asset('images/logo.jpg') }}">
                                        </td>
                                        <td class="address_text align-middle text-white" style="width: 50%;background: #3fbaf7; text-align:center" colspan="3">
                                            <h4> TIGER 4 INDIA LTD </h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-center">
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
                                            $columnValue .= $fiType == 'BV' ? 'BUSINESS VERIFICATION' : ($fiType == 'RV' ? 'RESIDENCE VERIFICATION' : $fiType);
                                            }
                                            @endphp
                                            {{ $columnValue }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Reference No.</td>
                                        <td style="width: 16.66%;">{{ $case->getCase->refrence_number ?? '' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Branch Code</td>
                                        <td style="width: 16.66%;">{{ isset($case->getCase->getBranch->branch_code) ? optional($case->getCase->getBranch)->branch_code . ' ('.optional($case->getCase->getBranch)->branch_name . ')' : '' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Customer Name</td>
                                        <td style="width: 16.66%;">{{ $case->getCase->applicant_name ?? '' }}</td>
                                    </tr>

                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Case Creation Login Details</td>
                                        <td style="width: 16.66%;">{{ $case->getCase->created_at ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Contact No.</td>
                                        <td style="width: 16.66%;">{{ $case->mobile ?? '' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Product Name</td>
                                        <td style="width: 16.66%;">{{ $case->getCase->getProduct->name ?? 'NA' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Loan Amount</td>
                                        <td style="width: 16.66%;">{{ $case->getCase->amount ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Dealer Code</td>
                                        <td style="width: 16.66%;">{{ $case->dealer_code ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Landline</td>
                                        <td style="width: 16.66%;">{{ $case->landline ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Address</td>
                                        <td style="width: 16.66%;">{{ $case->address ?? '' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">{{ $case->pincode != 0 ? 'Pincode' :'' }}</td>
                                        <td style="width: 16.66%;">{{ $case->pincode != 0 ? $case->pincode :'' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">City</td>
                                        <td style="width: 16.66%;">{{ $case->city ?? '' }}</td>
                                    </tr>
                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center"><strong>Residence
                                                Verification Format</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Address Confirmed </td>
                                        <td style="width: 16.66%;">{{ $case->address_confirmed ?? 'NO' }} &nbsp; </td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Address Confirmed By</td>
                                        <td class="BVstyle ng-binding ng-hide">{{ $case->address_confirmed_by ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff"></td>
                                        <td></td>
                                    </tr>
                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center"><strong>The
                                                following information should be obtained if the applicant/colleagues are
                                                contacted in the office </strong></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Applicant Name</td>
                                        <td style="width: 16.66%;">
                                            {{ $case->applicant_name ?? $case->getCase->applicant_name }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Permanent Address/Phone</td>
                                        <td style="width: 16.66%;">{{ $case->permanent_address ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Person Met</td>
                                        <td style="width: 16.66%;">{{ $case->person_met ?? 'NA' }} </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Relationship</td>
                                        <td style="width: 16.66%;">{{ $case->relationship ?? 'NA' }} </td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">No of Residents in the House</td>
                                        <td style="width: 16.66%;">{{ $case->no_of_residents_in_house ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Years at current Residence</td>
                                        <td style="width: 16.66%;">{{ $case->years_at_current_residence ?? 'NA' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">No of Earning Family Members</td>
                                        <td class="BVstyle ng-binding ng-hide">
                                            {{ $case->no_of_earning_family_members ?? 'NA' }}</td>
                                            <td style="width: 16.66%; font-weight: 600; color:#0094ff">Residence Status</td>
                                        <td class="BVstyle ng-binding ng-hide">{{ $case->residence_status ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Approx Rent</td>
                                        <td style="width: 16.66%;">{{ $case->approx_rent ?? 'NA' }}</td>
                                    </tr>
                                    <tr>
                                    

                                    </tr>
                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center"><strong>Verifier's Observations</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Location </td>
                                        <td style="width: 16.66%;">{{ $case->location ?? 'NA' }} </td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Locality</td>
                                        <td style="width: 16.66%;">{{ $case->locality ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Accomodation Type</td>
                                        <td style="width: 16.66%;">{{ $case->accommodation_type ?? 'NA' }} </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Interior Conditions</td>
                                        <td style="width: 16.66%;">{{ $case->interior_conditions ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Assets Seen</td>
                                        <td class="BVstyle ng-binding ng-hide"> {{ $case->assets_seen ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Nearest Landmark</td>
                                        <td style="width: 16.66%;">{{ $case->nearest_landmark ?? 'NA' }} </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Area</td>
                                        <td class="BVstyle ng-binding ng-hide">{{ $case->area ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Standard of Living</td>
                                        <td style="width: 16.66%;">{{ $case->standard_of_living ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff"></td>
                                        <td></td>
                                    </tr>

                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center"><strong>If the
                                                house is locked,the following information needs to be obtained from the
                                                Neighbour/Third Party.</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Applicant Name</td>
                                        <td style="width: 16.66%;">{{ $case->applicant_name ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Person Met</td>
                                        <td style="width: 16.66%;">{{ $case->person_met ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Relationship</td>
                                        <td style="width: 16.66%;">{{ $case->locked_relationship ?? 'NA' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Applicant Age(Approx)</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">{{ $case->applicant_age ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">No. of Residents in House</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">
                                            {{ $case->residence_status_others ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Years Lived at this Residence</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">
                                            {{ $case->years_at_current_residence ?? 'NA' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Occupation</td>
                                        <td style="width: 16.66%;">{{ $case->occupation ?? '0000' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Years Lived at this Residence other</td>
                                        <td style="width: 16.66%;">{{ $case->years_at_current_residence_others ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff"></td>
                                        <td></td>
                                    </tr>
                                    
                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center"><strong>If the
                                                address is not confirmed then the following information needs to be
                                                filled.</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%;">{{ $case->untraceable == 'true' ? 'Untraceable' : ''}}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Reason</td>
                                        <td style="width: 16.66%;">{{ $case->reason_of_untraceable }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Result of Calling</td>
                                        <td style="width: 16.66%;">
                                            '{{ $case->reason_of_calling }}'</td>
                                        <td style="width: 16.66%;"></td>
                                    </tr>
                                    <tr>
                                       <td style="width: 16.66%; font-weight: 600; color:#0094ff">
                                            <b>{{ $case->untraceable == 'false' ? 'Mismatch in Residence Address' : ''}}</b>
                                        </td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Is Applicant Known to the person</td>
                                        <td style="width: 16.66%;">{{ $case->is_applicant_know_to_person }} </td>
                                        <td colspan="3"></td>
                                    </tr>

                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">To Whom Does Address Belong ?</td>
                                        <td style="width: 16.66%;">{{ $case->to_whom_does_address_belong }}</td>
                                        <td colspan="4" style="width: 16.66%;"></td>
                                    </tr>
                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center"><strong>The
                                                following is based on Verifier Observations</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Verifier's Name</td>
                                        <td style="width: 16.66%;">{{ optional($case->getUserVerifiersName)->name ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Verification Conducted at</td>
                                        <td style="width: 16.66%;">{{ $case->verification_conducted_at ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Proof attached</td>
                                        <td style="width: 16.66%;">{{ $case->proof_attached ?? 'NA' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Type of Proof</td>
                                        <td style="width: 16.66%;">{{ $case->type_of_proof ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Date of Visit</td>
                                        <td style="width: 16.66%;">{{ $case->date_of_visit ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Time of Visit</td>
                                        <td style="width: 16.66%;">{{ $case->time_of_visit ?? 'NA' }}</td>
                                    </tr>

                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center">
                                            <strong>Updations</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%;">Address</td>
                                        <td colspan="5"></td>
                                    </tr>
                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center"><strong>NEGATIVE
                                                FEATURES</strong></td>
                                    </tr>
                                    <tr ng-hide="BVCase.VerifiedType==219 || BVCase.SubStatusId!=536">
                                        <td colspan="6" class="ng-binding">
                                            {{ in_array($case->status, [2, 4]) ? 'Recommended : Address and Details Confirmed' : (in_array($case->status, [3, 5]) ? 'Not Recommended : ' . ($case->negative_feedback_reason ?? (isset($case->getCaseStatus->name) ? $case->getCaseStatus->name : '')) : 'NA') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Visit Conducted </td>
                                        <td style="width: 16.66%;">
                                            {{ $case->visit_conducted ?? (isset($case->getCaseStatus->parent_id) && $case->getCaseStatus->parent_id == 113 ? (isset($case->getCaseStatus->name) ? isset($case->getCaseStatus->name) : '') : (isset($case->getStatus->name) ? $case->getStatus->name : '')) }}
                                        </td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Reason </td>
                                        <td style="width: 16.66%;">{{ $case->negative_feedback_reason ?? 'NA' }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center"><strong>Applicant Photos</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">
                                            <div class="row">
                                                @if (!empty($case->image_1))
                                                    <div class="col-sm-6 col-md-4 col-lg-3 image_1">
                                                        <img alt="img" class="zoomimg"
                                                            data-img="{{ $case->image_1 }}"
                                                            style='width:300px;float:left; height:300px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;'
                                                            src="{{ asset($case->image_1) }}" />
                                                    </div>
                                                @endif
                                                @if (!empty($case->image_2))
                                                    <div class="col-sm-6 col-md-4 col-lg-3 image_2">
                                                        <img alt="img" class="zoomimg"
                                                            data-img="{{ $case->image_2 }}"
                                                            style='width:300px;float:left; height:300px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;'
                                                            src="{{ asset($case->image_2) }}" />
                                                    </div>
                                                @endif
                                                @if (!empty($case->image_3))
                                                    <div class="col-sm-6 col-md-4 col-lg-3 image_3">
                                                        <img alt="img" class="zoomimg"
                                                            data-img="{{ $case->image_3 }}"
                                                            style='width:300px;float:left; height:300px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;'
                                                            src="{{ asset($case->image_3) }}" />
                                                    </div>
                                                @endif
                                                @if (!empty($case->image_4))
                                                    <div class="col-sm-6 col-md-4 col-lg-3 image_4">
                                                        <img alt="img" class="zoomimg"
                                                            data-img="{{ $case->image_4 }}"
                                                            style='width:300px;float:left; height:300px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;'
                                                            src="{{ asset($case->image_4) }}" />
                                                    </div>
                                                @endif
                                                @if (!empty($case->image_5))
                                                    <div class="col-sm-6 col-md-4 col-lg-3 image_5">
                                                        <img alt="img" class="zoomimg"
                                                            data-img="{{ $case->image_5 }}"
                                                            style='width:300px;float:left; height:300px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;'
                                                            src="{{ asset($case->image_5) }}" />
                                                    </div>
                                                @endif
                                                @if (!empty($case->image_6))
                                                    <div class="col-sm-6 col-md-4 col-lg-3 image_6">
                                                        <img alt="img" class="zoomimg"
                                                            data-img="{{ $case->image_6 }}"
                                                            style='width:300px;float:left; height:300px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;'
                                                            src="{{ asset($case->image_6) }}" />
                                                    </div>
                                                @endif
                                                @if (!empty($case->image_7))
                                                    <div class="col-sm-6 col-md-4 col-lg-3 image_7">
                                                        <img alt="img" class="zoomimg"
                                                            data-img="{{ $case->image_7 }}"
                                                            style='width:300px;float:left; height:300px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;'
                                                            src="{{ asset($case->image_7) }}" />
                                                    </div>
                                                @endif
                                                @if (!empty($case->image_8))
                                                    <div class="col-sm-6 col-md-4 col-lg-3 image_8">
                                                        <img alt="img" class="zoomimg"
                                                            data-img="{{ $case->image_8 }}"
                                                            style='width:300px;float:left; height:300px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;'
                                                            src="{{ asset($case->image_8) }}" />
                                                    </div>
                                                @endif
                                                @if (!empty($case->image_9))
                                                    <div class="col-sm-6 col-md-4 col-lg-3 image_9">
                                                        <img alt="img" class="zoomimg"
                                                            data-img="{{ $case->image_9 }}"
                                                            style='width:300px;float:left; height:300px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;'
                                                            src="{{ asset($case->image_9) }}" />
                                                    </div>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center">
                                            <strong>Location</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Latitude</td>
                                        <td style="width: 16.66%;">{{ $case->latitude ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Longitude</td>
                                        <td style="width: 16.66%;">{{ $case->longitude ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Address</td>
                                        <td style="width: 16.66%;">{{ $case->latlong_address ?? 'NA' }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">
                                            @if (!empty($case->latitude) && !empty($case->longitude))
                                                <iframe id="BV_Map"
                                                    src="https://maps.google.com/maps?q={{ $case->latitude }},{{ $case->longitude }}&z=15&output=embed"
                                                    width="600" height="450" style="border:0;" allowfullscreen=""
                                                    loading="lazy">
                                                </iframe>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center"><strong>Cross
                                                Verification Info</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Neighbour Check 1</td>
                                        <td style="width: 16.66%;">{{ $case->tcp1_name ?? 'NA' }} </td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Neighbour1 Checked With</td>
                                        <td style="width: 16.66%;">{{ $case->tcp1_checked_with ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">TCP1 Negative Comments</td>
                                        <td style="width: 16.66%;">{{ $case->tcp1_negative_comments ?? 'NA' }} </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Neighbour Check 2</td>
                                        <td style="width: 16.66%;">{{ $case->tcp2_name ?? 'NA' }} </td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Neighbour2 Checked With</td>
                                        <td style="width: 16.66%;">{{ $case->tcp2_checked_with ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">TCP2 Negative Comments</td>
                                        <td colspan="3">{{ $case->tcp2_negative_comments ?? 'NA' }} </td>
                                    </tr>

                                    <tr>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Visited By </td>
                                        <td style="width: 16.66%;">{{ $case->visited_by ?? 'NA' }}</td>
                                        <td style="width: 16.66%; font-weight: 600; color:#0094ff">Verified By </td>
                                        <td style="width: 16.66%;">{{ $case->verified_by ?? 'NA' }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    
                                    
                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center"><strong>Office
                                                CPV COMMENTS</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="ng-binding">{{ $case->app_remarks }}</td>
                                    </tr>
                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center">
                                            <strong>Supervisor Remarks</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">{{ $case->supervisor_remarks ?? 'NA' }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align:center">
                                            <img title=''
                                                style='width:150px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;'
                                                src="{{ asset('images/sign.png') }}" />
                                            <br>
                                            Signature of Agency Supervisor (With agency Seal)
                                        </td>
                                        <td colspan="3" style="text-align:center">
                                            <img title=''
                                                style='width:150px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;'
                                                src="{{ asset('images/sign.png') }}" />
                                            <br>
                                            Audit Check Remarks by Agency With Stamp &amp; Sign
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
            <!-- data table end -->

        </div>
    </div>
<!-- The Image Modal -->
<div id="myimgModal" class="modal imgmodal">

  <!-- The Close Button -->
  <span class="closebtn">&times;</span>

  <!-- Modal Content (The Image) -->
  <img class="modal-content imgmodal-content" id="img01">

</div>
    @include('backend.layouts.partials.scripts')
    <!-- Start datatable js -->
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

    <script>
        function printformFunction() {
            var divToPrint = document.getElementById('outprint'); // Get the table element
            var newWindow = window.open('', '', 'width=800,height=600'); // Open a new window
            newWindow.document.write('<html><head><title>Print Table</title>');
            newWindow.document.write(
                '<style>table, th, td {border: 1px solid black; border-collapse: collapse; padding: 10px;}</style>'
                ); // Optional: Add CSS for the table
            newWindow.document.write('</head><body>');
            newWindow.document.write(divToPrint.outerHTML); // Add the table's HTML to the new window
            newWindow.document.write('</body></html>');
            newWindow.document.close(); // Close the document
            newWindow.print(); // Trigger print
        }
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
$(document).on('keydown', function(event) {
    if (event.key === 'Escape') {
      $("#myimgModal").hide(); // Hide the active modal
      $("#viewFormModel") = null; // Reset the active modal
    }
  });
  // Close the active modal when the Escape key is pressed
        $(document).keydown(function(event) {
            if (event.key === "Escape") { // Check if the pressed key is Escape
                if ($("#myimgModal").is(":visible")) { // If second modal is visible
                    $("#myimgModal").fadeOut();
                } else if ($("#viewFormModel").is(":visible") && !$("#myimgModal").is(":visible")) { // If only first modal is visible
                    $("#viewFormModel").fadeOut();
                }
            }
        });
</script>
