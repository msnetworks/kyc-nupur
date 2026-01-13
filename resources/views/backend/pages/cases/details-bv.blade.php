<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'View Detail - BV')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('backend.layouts.partials.styles')
    @yield('styles')
</head>
@section('title')
    View Detail - BV
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

                                        @if($case->getCase->bank_id != 12 && $case->getCase->bank_id != 13)
                                        <td style="width: 50%; border:none;font-size:22px;color:#0094ff; text-align:center" colspan="3">
                                            <img alt="{{ $case->getCase->bank_id == 12 ? 'Synergee Risk Management Pvt. Ltd.' : ($case->getCase->bank_id == 13 ? 'SK ENTERPRISES' : 'TIGER 4 INDIA LTD') }}" src="{{ $case->getCase->bank_id == 13 ? asset('images/sk-logo.png') : asset('images/logo.jpg') }}" style="max-height: 120px;">
                                        </td>
                                        @elseif($case->getCase->bank_id == 13)
                                        <td style="width: 50%; border:none;font-size:22px;color:#0094ff; text-align:center" colspan="3">
                                            <img alt="SK" src="{{ asset('images/sk-logo.png') }}" style="max-height: 120px;">
                                        </td>
                                        @endif
                                        <td class="address_text align-middle text-white" style="width: 50%;background: #3fbaf7; text-align:center" colspan="{{ $case->getCase->bank_id == 12 ? 6 : 3 }}">
                                            <h4>{{ $case->getCase->bank_id == 12 ? 'Synergee Risk Management Pvt. Ltd.' : ($case->getCase->bank_id == 13 ? 'SK ENTERPRISES' : 'TIGER 4 INDIA LTD') }}</h4>
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
                                        <td style="font-weight: 600; color:#0094ff">Product Name</td>
                                        <td>{{ $case->getCase->getProduct->name ?? 'NA' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">Loan Amount</td>
                                        <td>{{ $case->getCase->amount ?? 'NA' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">Dealer Code</td>
                                        <td>{{ $case->dealer_code ?? 'NA' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">Landline</td>
                                        <td>{{ $case->landline ?? '' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">Contact No.</td>
                                        <td>{{ $case->mobile ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">Address</td>
                                        <td>{{ $case->address ?? '' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">{{ $case->pincode != 0 ? 'Pincode' :'' }}</td>
                                        <td>{{ $case->pincode != 0 ? $case->pincode :'' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">City</td>
                                        <td>{{ $case->city ?? '' }}</td>
                                    </tr>
                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center">
                                            <strong>Employment(Salaried)/ Business(Self-Employed) Verification Report<br>
                                                (Strictly Private &amp; Confidential)</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">Address Confirmed </td>
                                        <td>
                                            {{ $case->address_confirmed ?? '' }}
                                            {{-- {{ in_array($case->address_confirmed, ['Self/Colleague', 'Receptionist/Guard']) ? 'Yes' : 'NO' }} --}}
                                            {{-- {{ in_array($case->address_confirmed, ['Self/Colleague', 'Receptionist/Guard']) ? 'Yes' : 'NO' }} --}}
                                            &nbsp; </td>
                                        <td style="font-weight: 600; color:#0094ff">Office/Business Address</td>
                                        <td>{{ $case->employer_address ?? '' }} &nbsp; </td>
                                        <td style="font-weight: 600; color:#0094ff">Office Status</td>
                                        <td>{{ $case->residence_status ?? '' }}
                                            &nbsp; </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">Type of Proof</td>
                                        <td>{{ $case->type_of_proof ?? 'NA' }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center"><strong>The
                                                following information should be obtained if the applicant/colleagues are
                                                contacted in the office</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">Name of Employer/Co</td>
                                        <td>{{ $case->name_of_employer_co ?? 'NA' }} </td>
                                        <td style="font-weight: 600; color:#0094ff">Person Met</td>
                                        <td>{{ $case->person_met ?? 'NA' }} </td>
                                        <td style="font-weight: 600; color:#0094ff">Address of Employer/Co</td>
                                        <td>{{ $case->employer_address ?? 'NA' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">Mobile Number</td>
                                        <td class="BVstyle" ng-hide="BVResponse.mobileno">{{ $case->employer_mobile ?? 'NA' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">Co. Board Outside Bldg/Office</td>
                                        <td class="BVstyle ng-binding ng-hide">{{ $case->co_board_outside_bldg_office ?? 'NA' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">Type of Employer/Co</td>
                                        <td>{{ $case->type_of_employer ?? 'NA' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">Nature of Business</td>
                                        <td>{{ $case->nature_of_business ?? 'NA' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">Applicant Designation</td>
                                        <td>{{ $case->designation ?? 'NA' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">Line of Business (for self-emplyed)</td>
                                        <td>{{ $case->line_of_business ?? 'NA' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">Year of Establishment</td>
                                        <td>{{ $case->year_of_establishment ?? 'NA' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">Level of Business activity(for self-employed)</td>
                                        <td>{{ $case->level_of_business_activity ?? 'NA' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">No. of Employees</td>
                                        <td>{{ $case->no_of_employees ?? 'NA' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">Office ambience/look</td>
                                        <td>{{ $case->interior_conditions ?? 'NA' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">Type of Locality </td>
                                        <td>{{ $case->type_of_locality ?? 'NA' }} </td>
                                        <td style="font-weight: 600; color:#0094ff">Area</td>
                                        <td>{{ $case->area ?? 'NA' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">Nearest Landmark</td>
                                        <td>{{ $case->nearest_landmark ?? 'NA' }} </td>
                                        <td style="font-weight: 600; color:#0094ff">Ease of Locating</td>
                                        <td>{{ $case->ease_of_locating ?? '0000' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">Employee Designation</td>
                                        <td class="BVstyle ng-binding ng-hide">{{ $case->grade ?? 'NA' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">Years of current employment</td>
                                        <td class="BVstyle ng-binding ng-hide">{{ $case->year_of_establishment ?? 'NA' }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center"><strong>If
                                                applicant is not giving information, the following information needs to be
                                                obtained from the Colleague/Guard/Neighbour </strong></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">Applicant Age(Approx)</td>
                                        <td class="BVstyle ng-binding ng-hide">{{ $case->applicant_age ?? '0' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">Name of Employer/Co</td>
                                        <td class="BVstyle ng-binding ng-hide">{{ $case->address_confirmed == 'Receptionist/Guard' ? $case->name_of_employer_co : 'NA' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">Co/Established in(Year)</td>
                                        <td class="BVstyle ng-binding ng-hide">{{ $case->established ?? 'NA' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">Designation</td>
                                        <td class="BVstyle ng-binding ng-hide">
                                            {{ $case->address_confirmed == 'Receptionist/Guard' ? $case->designation : 'NA' }}
                                        </td>
                                        <td style="font-weight: 600; color:#0094ff">Telephono No. Office</td>
                                        <td class="BVstyle ng-binding ng-hide">{{ $case->telephono_no_office ?? 'NA' }}
                                        </td>
                                        <td style="font-weight: 600; color:#0094ff">Ext.</td>
                                        <td class="BVstyle ng-binding ng-hide">{{ $case->ext ?? 'NA' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">Type of Co/Employer</td>
                                        <td class="BVstyle ng-binding ng-hide">
                                            {{ $case->address_confirmed == 'Receptionist/Guard' ? $case->type_of_employer : 'NA' }}
                                        </td>
                                        <td style="font-weight: 600; color:#0094ff">Nature of Co/Employer</td>
                                        <td class="BVstyle ng-binding ng-hide">{{ $case->nature_of_employer ?? 'NA' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">No of Employees</td>
                                        <td class="BVstyle ng-binding ng-hide">
                                            {{ $case->address_confirmed == 'Receptionist/Guard' ? $case->no_of_employees : 'NA' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">Area</td>
                                        <td class="BVstyle ng-binding ng-hide">
                                            {{ $case->address_confirmed == 'Receptionist/Guard' ? $case->area : 'NA' }}
                                        </td>
                                        <td style="font-weight: 600; color:#0094ff">Nearest Landmark</td>
                                        <td class="BVstyle ng-binding ng-hide">
                                            {{ $case->address_confirmed == 'Receptionist/Guard' ? $case->nearest_landmark : 'NA' }}
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr ng-hide="BVCase.VerifiedType==219 || BVCase.SubStatusId!=536"=218" class="">
                                        <td colspan="6" class="subheading" style="text-align: center">AS CLAIMED /
                                            CONFIRMED</td>
                                    </tr>
                                    <tr ng-hide="BVCase.VerifiedType==219 || BVCase.SubStatusId!=536"=218" class="">
                                        <td colspan="6" class="ng-binding">
                                            {{ in_array($case->status, [2, 4]) ? 'Recommended : Address and Details Confirmed' : (in_array($case->status, [3, 5]) ? 'Not Recommended : ' . ($case->negative_feedback_reason ?? (isset($case->getCaseStatus->name) ? $case->getCaseStatus->name : '')) : 'NA') }}
                                        </td>
                                    </tr>

                                    <tr ng-hide="BVCase.StatusID==220" class="">
                                        <td>Visit Conducted </td>
                                        <td class="ng-binding">
                                            {{ $case->visit_conducted ?? (isset($case->getCaseStatus->parent_id) && $case->getCaseStatus->parent_id == 113 ? (isset($case->getCaseStatus->name) ? isset($case->getCaseStatus->name) : '') : (isset($case->getStatus->name) ? $case->getStatus->name : '')) }}
                                        </td>
                                    </tr>
                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center">
                                            <strong>Applicant Photos</strong>
                                        </td>
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
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">VisitDate</td>
                                        <td>{{ $case->date_of_visit ?? 'NA' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">VisitTime</td>
                                        <td>{{ $case->time_of_visit ?? 'NA' }}</td>
                                    </tr>
                                    <tr class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center">
                                            <strong>Location</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">Latitude</td>
                                        <td>{{ $case->latitude ?? 'NA' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">Longitude</td>
                                        <td>{{ $case->longitude ?? 'NA' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">Address</td>
                                        <td>{{ $case->latlong_address ?? 'NA' }}</td>
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
                                        <td colspan="6" class="subheading" style="text-align: center"><strong>Third
                                                Party Check</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">TPC 1 Name</td>
                                        <td>{{ $case->tcp1_name ?? 'NA' }} </td>
                                        <td style="font-weight: 600; color:#0094ff">TPC 1 (Checked with)</td>
                                        <td>{{ $case->tcp1_checked_with ?? 'NA' }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @if ($case->tcp1_checked_with != 'Positive')
                                        <tr>
                                            <td style="font-weight: 600; color:#0094ff">TPC 1 Negative Reason</td>
                                            <td>{{ $case->tcp1_negative_comments ?? 'NA' }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">TPC 2 Name</td>
                                        <td>{{ $case->tcp2_name ?? 'NA' }} </td>
                                        <td style="font-weight: 600; color:#0094ff">TPC 2 (Checked with)</td>
                                        <td>{{ $case->tcp2_checked_with ?? 'NA' }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @if ($case->tcp2_checked_with != 'Positive')
                                        <tr>
                                            <td style="font-weight: 600; color:#0094ff">TPC 2 Negative Reason</td>
                                            <td>{{ $case->tcp2_negative_comments ?? 'NA' }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td style="font-weight: 600; color:#0094ff">Visited By </td>
                                        <td class="BVstyle ng-binding ng-hide">{{ $case->visited_by ?? 'NA' }}</td>
                                        <td style="font-weight: 600; color:#0094ff">Verified By </td>
                                        <td class="BVstyle ng-binding ng-hide">{{ $case->verified_by ?? 'NA' }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr ng-hide="BVCase.StatusID==217" class="bg-info text-white">
                                        <td colspan="6" class="subheading" style="text-align: center"><strong>Office
                                                CPV COMMENTS</strong></td>
                                    </tr>
                                    <tr ng-hide="BVCase.StatusID==217" class="">
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
                                            <img title='image'
                                                style='width:150px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;'
                                                src="{{ $case->getCase->bank_id == 12 ? asset('images/synergeerisk-sign.jpeg') : ($case->getCase->bank_id == 13 ? asset('images/flexi-sign.jpeg') : asset('images/sign.png')) }}" />
                                            <br>
                                            Signature of Agency Supervisor (With agency Seal)
                                        </td>
                                        <td colspan="3" style="text-align:center">
                                            <img title='image'
                                                style='width:150px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;'
                                                src="{{ $case->getCase->bank_id == 12 ? asset('images/synergeerisk-sign.jpeg') : ($case->getCase->bank_id == 13 ? asset('images/flexi-sign.jpeg') : asset('images/sign.png')) }}" />
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
