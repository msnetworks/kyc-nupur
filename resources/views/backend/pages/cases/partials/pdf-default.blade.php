<style>
    @import url('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');

    /* Page settings */
    @page {
        margin: 8mm 10mm;
        size: A4;
    }

    /* Base styles */
    body {
        margin: 0;
        padding: 0;
        font-size: 11px;
        line-height: 1.4;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }

    /* Container styles */
    .col-xs-12 {
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow-x: hidden;
    }

    /* Table styles */
    table {
        width: 100% !important;
        margin: 0 !important;
        border-collapse: collapse;
        table-layout: fixed;
    }

    td, th {
        padding: 3px !important;
        word-wrap: break-word;
        overflow-wrap: break-word;
        font-size: 11px;
        vertical-align: middle !important;
    }

    /* Header styles */
    .head-text {
        font-weight: 600;
        color: #0094ff;
        font-size: 11px;
    }

    .address_text {
        font-size: 11px;
        padding: 2px !important;
    }

    .address_text h2 {
        font-size: 20px;
        margin-bottom: 2px;
    }

    /* Image containers */
    .img-container {
        width: 100%;
        margin: 2px;
        border: 1px solid #ddd;
    }

    /* Background colors */
    .bg-info {
        background-color: #17a2b8 !important;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }

    .text-white {
        color: #fff !important;
    }

    /* Section headers */
    .subheading {
        padding: 4px !important;
        font-size: 12px;
        font-weight: bold;
    }

    /* Column widths */
    td[style*="width: 25%"] {
        max-width: 25%;
        width: 25% !important;
    }

    td[style*="width: 50%"] {
        max-width: 50%;
        width: 50% !important;
    }

    /* Image optimizations */
    img[title="image"] {
        width: 120px;
        max-width: 100%;
        height: auto;
        margin: 5px auto;
        display: block;
    }

    .zoomimg {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
    }

    /* Table borders */
    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered td,
    .table-bordered th {
        border: 1px solid #dee2e6;
    }

    /* Print specific styles */
    @media print {
        .bg-info {
            background-color: #17a2b8 !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }

        .text-white {
            color: #fff !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
    }

    /* Map container */
    .map-container {
        width: 100%;
        height: 300px;
        margin: 10px 0;
    }

    .map-container img {
        max-width: 100%;
        height: auto;
    }
</style>
<div class="col-xs-12" style="width: 100%; max-width: 100%; margin: 0; padding: 5px; border: 2px solid #7c7c7c;">
    <table class="table table-bordered" style="width: 100%" border="2">
        <tbody>
            <tr>
                @if($case->getCase->bank_id != 12 && $case->getCase->bank_id != 13)
                <td style="border:none; font-size:22px; color:#000;" align="center">
                    <img style="width: 180px; max-height: 120px;" alt="TIGER 4 INDIA LTD" src="{{ $logo }}">
                </td>
                @elseif($case->getCase->bank_id == 13)
                <td style="border:none; font-size:22px; color:#000;" align="center">
                    <img style="width: 180px; max-height: 120px;" alt="SK ENTERPRISES" src="{{ $logo }}">
                </td>
                @endif
                <td class="address_text" align="center" calspan="{{ $case->getCase->bank_id == 12 ? 2 : 1 }}">
                    <h2 style="color: #ff0000; margin-bottom: 0;"><u>
                        <i>{{ $case->getCase->bank_id == 12 ? 'Synergee Risk Management Pvt. Ltd.' : ($case->getCase->bank_id == 13 ? 'SK ENTERPRISES' : 'TIGER 4 INDIA LTD') }}</i></u></h2>
                        <small>{{ $case->getCase->bank_id == 13 ? 'No 752, Sainik Vihar, Saradhana Road, Kanker Khera, Meerut Uttar Pradesh - 250001' : 'VASANT KUNJ NEW DELHI-110070' }}</small>
                </td>
            </tr>
            
            <tr>
                <th colspan="2" class="text-center">
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
                </th>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered" style="width: 100%;" border="2">
        <tbody>
            <tr>
                <td style="width: 25%;" class="head-text">Branch Code</td>
                <td style="width: 25%;" >{{ $case->getCase->getBranch->branch_code ?? '' }}</td>
                <td style="width: 25%;" class="head-text">Reference No.</td>
                <td style="width: 25%;" >{{ $case->getCase->refrence_number ?? '' }}</td>
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
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="head-text">Address</td>
                <td>{{ $case->address ?? '' }}</td>
                <td class="head-text">City</td>
                <td>{{ $case->city ?? '' }}</td>
            </tr>
            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center">
                    <strong>Employment(Salaried)/ Business(Self-Employed) Verification Report<br>
                        (Strictly Private &amp; Confidential)</strong>
                </td>
            </tr>
            <tr>
                <td class="head-text">Address Confirmed </td>
                <td>
                    {{ (in_array($case->address_confirmed, ['Self/Colleague', 'Receptionist/Guard']) ? 'Yes' : 'NO') ?? '' }}
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td class="head-text">Office/Business Address</td>
                <td>{{ $case->employer_address ?? '' }} &nbsp; </td>
                <td class="head-text">Office Status</td>
                <td>{{ $case->residence_status ?? '' }}
            </tr>
            <tr>
                <td class="head-text">Type of Proof</td>
                <td>{{ $case->type_of_proof ?? 'NA' }}</td>
                <td class="head-text">Address Confirmed By</td>
                <td>{{ $case->address_confirmed_by ?? 'NA' }} </td>
            </tr>
            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center"><strong>If
                        applicant is not giving information, the following information needs to be
                        obtained from the Colleague/Guard/Neighbour </strong></td>
            </tr>
            <tr>
                <td class="head-text">Name of Employer/Co</td>
                <td>{{ $case->name_of_employer ?? 'NA' }} </td>
                <td class="head-text">Person Met</td>
                <td>{{ $case->person_met ?? 'NA' }} </td>
            </tr>
            <tr>
                <td class="head-text">Address of Employer/Co</td>
                <td colspan="3" >{{ $case->address ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Website of Employer/Co(if available)</td>
                <td>{{ $case->website_of_employer ?? 'NA' }}</td>
                <td class="head-text">e-mail address of Employer/Co(if available)</td>
                <td>{{ $case->email_of_employer ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Mobile Number</td>
                <td class="BVstyle" ng-hide="BVResponse.mobileno">{{ $case->mobile ?? 'NA' }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="head-text">Co. Board Outside Bldg/Office</td>
                <td>{{ $case->co_board_outside_bldg_office ?? 'NA' }}</td>
                <td class="head-text">Type of Employer/Co</td>
                <td>{{ $case->email_of_employer ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Nature of Business</td>
                <td colspan="3" >{{ $case->nature_of_business ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Line of Business (for self-emplyed)</td>
                <td>{{ $case->line_of_business ?? 'NA' }}</td>
                <td class="head-text">Year of Establishment</td>
                <td>{{ $case->year_of_establishment ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Level of Business activity(for self-employed)</td>
                <td>{{ $case->level_of_business_activity ?? 'NA' }}</td>
                <td class="head-text">No. of Employees</td>
                <td>{{ $case->no_of_employees ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">No of Branches/Offices</td>
                <td>{{ $case->no_of_branches ?? 'NA' }} </td>
                <td class="head-text">Office ambience/look</td>
                <td>{{ $case->assets_seen ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Type of Locality </td>
                <td>{{ $case->type_of_locality ?? 'NA' }} </td>
                <td class="head-text">Area</td>
                <td>{{ $case->area ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Nearest Landmark</td>
                <td>{{ $case->nearest_landmark ?? 'NA' }} </td>
                <td class="head-text">Ease of Locating</td>
                <td>{{ $case->email_of_employer ?? '0000' }}</td>
            </tr>
            <tr>
                <td class="head-text">Terms of employment(for employees)</td>
                <td> {{ $case->terms_of_employment ?? 'NA' }}</td>
                <td class="head-text">Grade</td>
                <td>{{ $case->grade ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Years of current employment</td>
                <td>{{ $case->year_of_establishment ?? 'NA' }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center"><strong>If
                        applicant is not giving information, the following information needs to be
                        obtained from the Colleague/Guard/Neighbour </strong></td>
            </tr>
            </tr>
            <tr>
                <td class="head-text">Applicant Age(Approx)</td>
                <td>{{ $case->applicant_age ?? '0' }}</td>
                <td class="head-text">Name of Employer/Co</td>
                <td>{{ $case->name_of_employer_co ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Co/Established in(Year)</td>
                <td>{{ $case->established ?? 'NA' }}</td>
                <td class="head-text">Designation</td>
                <td>{{ $case->designation ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Telephono No. Office</td>
                <td>{{ $case->telephono_no_office ?? 'NA' }}</td>
                <td class="head-text">Ext.</td>
                <td>{{ $case->ext ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Type of Co/Employer</td>
                <td>{{ $case->type_of_employer ?? 'NA' }}</td>
                <td class="head-text">Nature of Co/Employer</td>
                <td>{{ $case->nature_of_employer ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">No of Employees</td>
                <td>{{ $case->no_of_employees ?? 'NA' }}</td>
                <td class="head-text">No. of Branches</td>
                <td>{{ $case->no_of_branches ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Area</td>
                <td>{{ $case->area ?? 'NA' }}</td>
                <td class="head-text">Nearest Landmark</td>
                <td>{{ $case->nearest_landmark ?? 'NA' }}</td>
            </tr>
            <tr>
                <td colspan="4" class="subheading" style="text-align: center">AS CLAIMED /
                    CONFIRMED</td>
            </tr>
            <tr>
                <td colspan="4" class="ng-binding">
                    {{ in_array($case->status, [2, 4]) ? 'Recommended : Address and Details Confirmed' : (in_array($case->status, [3, 5]) ? 'Not Recommended : ' . ($case->negative_feedback_reason ?? (isset($case->getCaseStatus->name) ? $case->getCaseStatus->name : '')) : 'NA') }}
                </td>
            </tr>
            <tr class="">
                <td class="head-text">Visit Conducted </td>
                <td class="ng-binding">
                    {{ $case->visit_conducted ?? (isset($case->getCaseStatus->parent_id) && $case->getCaseStatus->parent_id == 113 ? (isset($case->getCaseStatus->name) ? isset($case->getCaseStatus->name) : '') : (isset($case->getStatus->name) ? $case->getStatus->name : '')) }}
                </td>
            </tr>

            <tr>
                <td class="head-text">VisitDate</td>
                <td>{{ $case->date_of_visit ?? 'NA' }}</td>
                <td class="head-text">VisitTime</td>
                <td>{{ $case->time_of_visit ?? 'NA' }}</td>
            </tr>
            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center">
                    <strong>Location</strong>
                </td>
            </tr>
            <tr>
                <td class="head-text">Latitude</td>
                <td>{{ $case->latitude ?? 'NA' }}</td>
                <td class="head-text">Longitude</td>
                <td>{{ $case->longitude ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="head-text">Address</td>
                <td>{{ $case->latlong_address ?? 'NA' }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center"><strong>Third
                        Party Check</strong></td>
            </tr>
            <tr>
                <td class="head-text">TPC 1 Name</td>
                <td>{{ $case->tcp1_name ?? 'NA' }} </td>
                <td class="head-text">TPC 1 (Checked with)</td>
                <td>{{ $case->tcp1_checked_with ?? 'NA' }}</td>
            </tr>
            @if ($case->tcp1_checked_with != 'Positive')
            <tr>
                <td class="head-text">TPC 1 Negative Reason</td>
                <td>{{ $case->tcp1_negative_comments ?? 'NA' }}</td>
                <td></td>
                <td></td>
            </tr>
            @endif
            <tr>
                <td class="head-text">TPC 2 Name</td>
                <td>{{ $case->tcp2_name ?? 'NA' }} </td>
                <td class="head-text">TPC 2 (Checked with)</td>
                <td>{{ $case->tcp2_checked_with ?? 'NA' }}</td>
            </tr>

            @if ($case->tcp2_checked_with != 'Positive')
            <tr>
                <td class="head-text">TPC 2 Negative Reason</td>
                <td>{{ $case->tcp2_negative_comments ?? 'NA' }}
                </td>
                <td></td>
                <td></td>
            </tr>
            @endif
            <tr>
                {{-- <td class="head-text">Visited By </td>
                <td>{{ $case->visited_by ?? 'NA' }}</td> --}}
                <td class="head-text">Verified By </td>
                <td>{{ $case->verified_by ?? 'NA' }}</td>
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
        </tbody>
    </table>
    <table class="table table-bordered" style="width: 100%;" border="2">
        <tbody>
            <tr>
                <td style="width: 50%; text-align:center">
                    <img title="image"
                        style="width:150px; margin-bottom:5px; margin-left:5px; border:2px solid #b06c1c; border-radius:10px;" src="{{ $sign }}" />
                    <br>
                    Signature of Agency Supervisor (With agency Seal)
                </td>
                <td style="width: 50%; text-align:center">
                    <img title="image"
                        style="width:150px; margin-bottom:5px; margin-left:5px; border:2px solid #b06c1c; border-radius:10px;"
                        src="{{ $sign }}" />
                    <br>
                    Audit Check Remarks by Agency With Stamp &amp; Sign
                </td>
            </tr>
        </tbody>
    </table>
    <br><br><br>
    <table class="table table-bordered" style="width: 100%;" border="2">
        <tbody>
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
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading" style="text-align: center">
                        <img src="https://maps.geoapify.com/v1/staticmap?style=osm-bright&width=600&height=400&center=lonlat:{{ $case->longitude }},{{ $case->latitude }}&zoom=15&marker=lonlat:{{ $case->longitude }},{{ $case->latitude }};color:%23ff0000;size:medium&apiKey=YOUR_GEOAPIFY_KEY" alt="Map">
                    </td>
                </tr>
        </tbody>
    </table>
    <br>
</div>