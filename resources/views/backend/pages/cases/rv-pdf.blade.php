@php
$signPath = $case->getCase->bank_id == 13 ? public_path('images/flexi-sign.jpeg') : public_path('images/sign.png');
$path = $signPath;
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$sign = 'data:image/' . $type . ';base64,' . base64_encode($data);
$logopath = $case->getCase->bank_id == 13 ? public_path('images/sk-logo.png') : public_path('images/logo.jpg');
$logotype = pathinfo($logopath, PATHINFO_EXTENSION);
$logodata = file_get_contents($logopath);
$logo = 'data:image/' . $logotype . ';base64,' . base64_encode($logodata);
@endphp
<style>
    @import url('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');

    @page {
        margin: 8mm 10mm; /* Reduced margins */
        size: A4;
    }

    body {
        margin: 0;
        padding: 0;
        font-size: 12px; /* Reduced base font size */
    }

    .col-xs-12 {
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow-x: hidden; /* Prevent horizontal scroll */
    }

    table {
        width: 100% !important;
        margin: 0 !important;
        border-collapse: collapse;
        table-layout: fixed; /* Fixed table layout */
    }

    td, th {
        padding: 3px !important; /* Reduced padding */
        word-wrap: break-word;
        overflow-wrap: break-word;
        font-size: 11px; /* Smaller font size for table content */
    }

    .head-text {
        font-weight: 600;
        color: #0094ff;
        font-size: 11px; /* Adjusted font size */
    }

    .img-container {
        width: 100%; /* Reduced image size */
        margin-bottom: 3px;
        margin-left: 3px;
    }

    /* Add max-width for text columns */
    td[style*="width: 25%"] {
        max-width: 25%;
        width: 25% !important;
    }
    
    /* Optimize signature images */
    img[title="image"] {
        width: 120px; /* Reduced signature size */
        max-width: 100%;
        height: auto;
    }

    /* Optimize applicant photos */
    .zoomimg {
        max-width: 100%;
        height: auto;
    }
</style>
<div class="col-xs-12" style="width: 100%; max-width: 100%; margin: 0; padding: 5px; border: 2px solid #7c7c7c;">
    <table class="table table-bordered" style="width: 100%;" border="2">
        <tbody>
            <tr>
                <td style="border:none; font-size:22px; color:#000;" align="center">
                    <img style="width: 180px; max-height: 120px;" alt="{{ $case->getCase->bank_id == 13 ? 'SK ENTERPRISES' : 'TIGER 4 INDIA LTD' }}" src="{{ $logo }}">
                </td>
                <td class="address_text" align="center">
                    <h2 style="color: #ff0000; margin-bottom: 0;"><u>
                        <i>{{ $case->getCase->bank_id == 13 ? 'SK ENTERPRISES' : 'TIGER 4 INDIA LTD' }}</i></u></h2>
                        <small>{{ $case->getCase->bank_id == 13 ? 'No 752, Sainik Vihar, Saradhana Road, Kanker Khera, Meerut Uttar Pradesh - 250001' : 'VASANT KUNJ NEW DELHI-110070' }}</small>
                </td>
            </tr>
            <tr>
                <th colspan="4" class="text-center">
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
        </tbody>
    </table>
</div>
<div class="col-xs-12" style="width: 100%; max-width: 100%; margin: 0; border: 2px solid #7c7c7c;">
    <table class="table table-bordered" style="width: 100%;" border="2">
        <tbody>
            <tr>
                <td style="width: 25%!important;" class="head-text">Branch Code</td>
                <td style="width: 25%!important; ">{{ $case->getCase->getBranch->branch_code ?? '' }}</td>
                <td style="width: 25%!important;" class="head-text">Reference No.</td>
                <td style="width: 25%!important;">{{ $case->getCase->refrence_number ?? '' }}</td>
            </tr>
            <tr>
                <td class="head-text">Customer Name</td>
                <td>{{ $case->getCase->applicant_name ?? '' }}</td>
                <td class="head-text">Fi Type</td>
                <td>{{ $case->getFiType->name }}</td>
            </tr>
            <tr>
                <td class="head-text">Case Creation Login Details</td>
                <td>{{ $case->getCase->created_at ?? 'NA' }}</td>
                <td class="head-text">Bank</td>
                <td>{{ $case->getCase->getBank->name ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Product Name</td>
                <td>{{ $case->getCase->getProduct->name ?? 'NA' }}</td>
                <td class="head-text">Loan Amount</td>
                <td>{{ $case->getCase->amount ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Contact No.</td>
                <td>{{ $case->mobile ?? '' }}</td>
                <td class="head-text">Loan Amount</td>
                <td >{{ $case->getCase->amount ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Dealer Code</td>
                <td >{{ $case->dealer_code ?? 'NA' }}</td>
                <td class="head-text">Landline</td>
                <td >{{ $case->landline ?? '' }}</td>
            </tr>
            <tr>
                <td class="head-text">Address</td>
                <td >{{ $case->address ?? '' }}</td>
                <td class="head-text">City</td>
                <td>{{ $case->city ?? '' }}</td>
            </tr>
            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center"><strong>Residence
                        Verification Format</strong>
                </td>
            </tr>
            <tr>
                <td class="head-text">Address Confirmed </td>
                <td >{{ $case->address_confirmed ?? '' }} &nbsp; </td>
                <td class="head-text">Address Confirmed By</td>
                <td class="BVstyle ng-binding ng-hide">{{ $case->address_confirmed_by ?? '' }}</td>
            </tr>
            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center"><strong>The
                        following information should be obtained if the applicant/colleagues are
                        contacted in the office </strong></td>
            </tr>
            <tr>
                <td class="head-text">Applicant Name</td>
                <td >
                    {{ $case->applicant_name ?? $case->getCase->applicant_name }}</td>
                <td class="head-text">Permanent Address/Phone</td>
                <td >{{ $case->permanent_address ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Person Met</td>
                <td >{{ $case->person_met ?? 'NA' }} </td>
                <td class="head-text">Relationship</td>
                <td >{{ $case->relationship ?? 'NA' }} </td>
            </tr>
            <tr>
                <td class="head-text">No of Residents in the House</td>
                <td >{{ $case->no_of_residents_in_house ?? 'NA' }}</td>
                <td class="head-text">Years at current Residence</td>
                <td >{{ $case->years_at_current_residence ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">No of Earning Family Members</td>
                <td class="BVstyle ng-binding ng-hide">
                    {{ $case->no_of_earning_family_members ?? 'NA' }}</td>
                <td class="head-text">Residence Status</td>
                <td class="BVstyle ng-binding ng-hide">{{ $case->residence_status ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Approx Rent</td>
                <td >{{ $case->approx_rent ?? 'NA' }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center"><strong>Verifier's Observations</strong></td>
            </tr>
            <tr>
                <td class="head-text">Location </td>
                <td >{{ $case->location ?? 'NA' }} </td>
                <td class="head-text">Locality</td>
                <td >{{ $case->locality ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Accomodation Type</td>
                <td >{{ $case->accommodation_type ?? 'NA' }} </td>
                <td class="head-text">Interior Conditions</td>
                <td >{{ $case->interior_conditions ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Assets Seen</td>
                <td class="BVstyle ng-binding ng-hide"> {{ $case->assets_seen ?? 'NA' }}</td>
                <td class="head-text">Nearest Landmark</td>
                <td >{{ $case->nearest_landmark ?? 'NA' }} </td>
            </tr>
            <tr>
                <td class="head-text">Area</td>
                <td class="BVstyle ng-binding ng-hide">{{ $case->area ?? 'NA' }}</td>
                <td class="head-text">Standard of Living</td>
                <td >{{ $case->standard_of_living ?? 'NA' }}</td>
            </tr>

            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center"><strong>If the
                        house is locked,the following information needs to be obtained from the
                        Neighbour/Third Party.</strong></td>
            </tr>
            <tr>
                <td class="head-text">Applicant Name</td>
                <td >{{ $case->applicant_name ?? 'NA' }}</td>
                <td class="head-text">Person Met</td>
                <td >{{ $case->person_met ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Relationship</td>
                <td >{{ $case->locked_relationship ?? 'NA' }}</td>
                <td class="head-text">Applicant Age(Approx)</td>
                <td style="font-weight: 600; ">{{ $case->applicant_age ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">No. of Residents in House</td>
                <td style="font-weight: 600;">
                    {{ $case->residence_status_others ?? 'NA' }}</td>
                <td class="head-text">Years Lived at this Residence</td>
                <td style="font-weight: 600;">
                    {{ $case->years_at_current_residence ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Occupation</td>
                <td >{{ $case->occupation ?? '0000' }}</td>
                <td class="head-text">Years Lived at this Residence other</td>
                <td >{{ $case->years_at_current_residence_others ?? 'NA' }}</td>
            </tr>
            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center"><strong>If the
                        address is not confirmed then the following information needs to be
                        filled.</strong></td>
            </tr>
            <tr>
                <td >{{ $case->untraceable == 'true' ? 'Untraceable' : ''}}</td>
                <td class="head-text">Reason</td>
                <td >{{ $case->reason_of_untraceable }}</td>
                <td class="head-text">Result of Calling</td>
            </tr>
            <tr>
                <td >
                    '{{ $case->reason_of_calling }}'</td>
                <td ></td>
                <td class="head-text">
                    <b>{{ $case->untraceable == 'false' ? 'Mismatch in Residence Address' : ''}}</b>
                </td>
                <td class="head-text">Is Applicant Known to the person</td>
            </tr>
            <tr>
                <td >{{ $case->is_applicant_know_to_person }} </td>
                <td></td>
                <td class="head-text">To Whom Does Address Belong ?</td>
                <td >{{ $case->to_whom_does_address_belong }}</td>
            </tr>
            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center"><strong>The
                        following is based on Verifier Observations</strong></td>
            </tr>
            <tr>
                <td class="head-text">Verifier's Name</td>
                <td >{{ optional($case->getUserVerifiersName)->name ?? 'NA' }}</td>
                <td class="head-text">Verification Conducted at</td>
                <td >{{ $case->verification_conducted_at ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Proof attached</td>
                <td >{{ $case->proof_attached ?? 'NA' }}</td>
                <td class="head-text">Type of Proof</td>
                <td >{{ $case->type_of_proof ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Date of Visit</td>
                <td >{{ $case->date_of_visit ?? 'NA' }}</td>
                <td class="head-text">Time of Visit</td>
                <td >{{ $case->time_of_visit ?? 'NA' }}</td>
            </tr>

            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center">
                    <strong>Updations</strong>
                </td>
            </tr>
            <tr>
                <td >Address</td>
                <td colspan="3"></td>
            </tr>
            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center"><strong>NEGATIVE
                        FEATURES</strong></td>
            </tr>
            <tr ng-hide="BVCase.VerifiedType==219 || BVCase.SubStatusId!=536">
                <td colspan="4" class="ng-binding">
                    {{ in_array($case->status, [2, 4]) ? 'Recommended : Address and Details Confirmed' : (in_array($case->status, [3, 5]) ? 'Not Recommended : ' . ($case->negative_feedback_reason ?? (isset($case->getCaseStatus->name) ? $case->getCaseStatus->name : '')) : 'NA') }}
                </td>
            </tr>
            <tr>
                <td class="head-text">Visit Conducted </td>
                <td >
                    {{ $case->visit_conducted ?? (isset($case->getCaseStatus->parent_id) && $case->getCaseStatus->parent_id == 113 ? (isset($case->getCaseStatus->name) ? isset($case->getCaseStatus->name) : '') : (isset($case->getStatus->name) ? $case->getStatus->name : '')) }}
                </td>
                <td class="head-text">Reason </td>
                <td >{{ $case->negative_feedback_reason ?? 'NA' }}</td>
            </tr>
            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center">
                    <strong>Location</strong>
                </td>
            </tr>
            <tr>
                <td class="head-text">Latitude</td>
                <td >{{ $case->latitude ?? 'NA' }}</td>
                <td class="head-text">Longitude</td>
                <td >{{ $case->longitude ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Address</td>
                <td >{{ $case->latlong_address ?? 'NA' }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center"><strong>Cross
                        Verification Info</strong></td>
            </tr>
            <tr>
                <td class="head-text">Neighbour Check 1</td>
                <td >{{ $case->tcp1_name ?? 'NA' }} </td>
                <td class="head-text">Neighbour1 Checked With</td>
                <td >{{ $case->tcp1_checked_with ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">TCP1 Negative Comments</td>
                <td >{{ $case->tcp1_negative_comments ?? 'NA' }} </td>
                <td class="head-text">Neighbour Check 2</td>
                <td >{{ $case->tcp2_name ?? 'NA' }} </td>
            </tr>
            <tr>
                <td class="head-text">Neighbour2 Checked With</td>
                <td >{{ $case->tcp2_checked_with ?? 'NA' }}</td>
                <td class="head-text">TCP2 Negative Comments</td>
                <td colspan="3">{{ $case->tcp2_negative_comments ?? 'NA' }} </td>
            </tr>

            <tr>
                {{-- <td class="head-text">Visited By </td>
                <td >{{ $case->visited_by ?? 'NA' }}</td> --}}
                <td class="head-text">Verified By </td>
                <td >{{ $case->verified_by ?? 'NA' }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center"><strong>Office
                        CPV COMMENTS</strong></td>
            </tr>
            <tr>
                <td colspan="4" class="ng-binding">{{ $case->app_remarks }}</td>
            </tr>
            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center">
                    <strong>Supervisor Remarks</strong>
                </td>
            </tr>

            <tr>
                <td colspan="4">{{ $case->supervisor_remarks ?? 'NA' }}</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center">
                    <img title="image"
                        style="width:150px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;" src="{{ $sign }}" />
                    <br>
                    Signature of Agency Supervisor (With agency Seal)
                </td>
                <td colspan="2" style="text-align:center">
                    <img title="image"
                        style="width:150px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;"
                        src="{{ $sign }}" />
                    <br>
                    Audit Check Remarks by Agency With Stamp &amp; Sign
                </td>
            </tr>
            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center">
                    <strong>Applicant Photos</strong>
                </td>
            </tr>
            @php
            $images = []; // Create an array to hold the image data
            for ($i = 0; $i < 9; $i++) {
                $imageProperty='image_' . $i;
                $img=$case->$imageProperty ?? null; // Avoid undefined property errors
                if (!empty($img)) {
                $path = public_path($img);
                if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION); // Get the file extension
                $data = file_get_contents($path); // Get the file content
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data); // Encode to base64
                $images[] = $base64; // Add base64 image to array
                }
                }
                }
                @endphp

                @for ($i = 0; $i < count($images); $i +=2)
                    <tr>
                    @for ($j = 0; $j < 2; $j++)
                        @if (isset($images[$i + $j]))
                            <td colspan="2" style="width: 50%; text-align: center;">
                                {{-- <div class="col-md-4"> --}}
                                    <img class="zoomimg img-container" alt="img" data-img="{{ $images[$i + $j] }}" src="{{ $images[$i + $j] }}" />
                                {{-- </div> --}}
                            </td>
                        @endif
                        @endfor
                    </tr>
                @endfor
        </tbody>
    </table>
    <br>
</div>