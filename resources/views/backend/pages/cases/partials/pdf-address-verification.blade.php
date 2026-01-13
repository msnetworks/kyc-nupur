<style>
    @import url('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');

    @page {
        margin: 8mm 10mm;
        size: A4;
    }

    body {
        margin: 0;
        padding: 0;
        font-size: 11px;
        line-height: 1.4;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }

    .col-xs-12 {
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow-x: hidden;
    }

    table {
        width: 100% !important;
        margin: 0 !important;
        border-collapse: collapse;
        table-layout: fixed;
    }

    td,
    th {
        padding: 4px !important;
        word-wrap: break-word;
        overflow-wrap: break-word;
        font-size: 11px;
        vertical-align: middle !important;
    }

    .axis-head {
        background-color: #9d1d3f;
        color: #fff;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }

    .subheading {
        padding: 5px !important;
        font-size: 12px;
        font-weight: bold;
        background-color: #f1f1f1;
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered td,
    .table-bordered th {
        border: 1px solid #dee2e6;
    }

    .axis-title {
        font-size: 18px;
        font-weight: 600;
        color: #9d1d3f;
        margin: 0;
    }

    .axis-meta {
        font-size: 11px;
        color: #555;
    }

    .zoomimg {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
        border: 1px solid #b06c1c;
        border-radius: 6px;
    }

    .map-container img {
        max-width: 100%;
        height: auto;
    }
</style>

<div class="col-xs-12" style="width: 100%; max-width: 100%; margin: 0; padding: 5px; border: 2px solid #9d1d3f;">
    <table class="table table-bordered" border="2">
        <thead>
            <tr>
                <td style="border:none; font-size:22px; color:#000;" align="center" colspan="2">
                    <img style="width: 180px; max-height: 120px;" alt="Synergee Risk Management Pvt. Ltd." src="{{ $logo }}">
                </td>
                <td class="address_text" align="center" colspan="2">
                    <h5 style="color: #ff0000; margin-bottom: 0;"><u>
                        Synergee Risk Management Pvt. Ltd.</u></h5>
                        <small>G-75, Jagjeet Nagar, East Delhi, Delhi, India, 110053</small>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr class="subheading">
                <td colspan="4" class="text-center">Address Verification</td>
            </tr>
            <tr>
                <td>FCU Agency Name</td>
                <td>{{ $case->fcu_agency_name ?? 'NA' }}</td>
                <td>RAC Name</td>
                <td>{{ $case->rac_name ?? 'NA' }}</td>
            </tr>
            <tr>
                <td>FCU Location</td>
                <td>{{ $case->fcu_location ?? 'NA' }}</td>
                <td>File Number</td>
                <td>{{ $case->file_number ?? $case->getCase->refrence_number ?? 'NA' }}</td>
            </tr>
            <tr>
                <td>Product</td>
                <td>{{ $case->getCase->getProduct->name ?? 'NA' }}</td>
                <td>DSA Code</td>
                <td>{{ $case->dsa_code ?? 'NA' }}</td>
            </tr>
            <tr>
                <td>DME</td>
                <td>{{ $case->dme ?? 'NA' }}</td>
                <td>Sales Manager Code</td>
                <td>{{ $case->sales_manager_code ?? 'NA' }}</td>
            </tr>
            <tr>
                <td>Loan Amount</td>
                <td>{{ $case->getCase->amount ?? 'NA' }}</td>
                <td>Bank</td>
                <td>{{ $case->getCase->getBank->name ?? 'Axis Bank' }}</td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered" border="2">
        <tbody>
            <tr class="subheading">
                <td colspan="4" class="text-center">Customer Information</td>
            </tr>
            <tr>
                <td>Customer Name</td>
                <td>{{ $case->getCase->applicant_name ?? 'NA' }}</td>
                <td>Residence Address</td>
                <td>{{ $case->residence_address ?? $case->address ?? 'NA' }}</td>
            </tr>
            <tr>
                <td>Contact Number</td>
                <td>{{ $case->contact_number ?? $case->mobile ?? 'NA' }}</td>
                <td>Office Name</td>
                <td>{{ $case->office_name ?? 'NA' }}</td>
            </tr>
            <tr>
                <td>Employment Status</td>
                <td>{{ $case->employment_status ?? 'NA' }}</td>
                <td>Office Address</td>
                <td>{{ $case->office_address ?? $case->employer_address ?? 'NA' }}</td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered" border="2">
        <tbody>
            <tr class="subheading">
                <td colspan="4" class="text-center">Pickup Information</td>
            </tr>
            <tr>
                <td>Pickup For</td>
                <td>{{ $case->pickup_for ?? 'NA' }}</td>
                <td>Pickup Reason (Trigger)</td>
                <td>{{ $case->pickup_reason ?? 'NA' }}</td>
            </tr>
            <tr>
                <td>Pickup On</td>
                <td>{{ $case->pickup_on ?? 'NA' }}</td>
                <td>Report Submitted</td>
                <td>{{ $case->report_submitted_on ?? 'NA' }}</td>
            </tr>
            <tr>
                <td>Sampler's Name</td>
                <td>{{ $case->sampler_name ?? 'NA' }}</td>
                <td>Verifier's Name</td>
                <td>{{ $case->verifier_name ?? 'NA' }}</td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered" border="2">
        <tbody>
            <tr class="subheading">
                <td colspan="4" class="text-center">Field Verifier's Observations</td>
            </tr>
            <tr>
                <td style="width: 25%;">Fraudulent Documents</td>
                <td colspan="3">
                    <ul style="padding-left: 18px; margin: 0;">
                        <li>{{ $case->fraudulent_document_1 ?? 'NA' }}</li>
                        <li>{{ $case->fraudulent_document_2 ?? 'NA' }}</li>
                        <li>{{ $case->fraudulent_document_3 ?? 'NA' }}</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>Fraudulent Profile</td>
                <td colspan="3">
                    <ul style="padding-left: 18px; margin: 0;">
                        <li>{{ $case->fraudulent_profile_1 ?? 'NA' }}</li>
                        <li>{{ $case->fraudulent_profile_2 ?? 'NA' }}</li>
                        <li>{{ $case->fraudulent_profile_3 ?? 'NA' }}</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>Setup Company</td>
                <td>{{ $case->observation_setup_company ?? 'NA' }}</td>
                <td>Negative Market Info</td>
                <td>{{ $case->observation_negative_market ?? 'NA' }}</td>
            </tr>
            <tr>
                <td>Anti-Social / PEP</td>
                <td>{{ $case->observation_anti_social ?? 'NA' }}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered" border="2">
        <tbody>
            <tr class="subheading">
                <td colspan="4" class="text-center">Agency Remarks</td>
            </tr>
            <tr>
                <td style="width: 25%;">Residence Profile</td>
                <td colspan="3">{{ $case->residence_profile ?? 'NA' }}</td>
            </tr>
            <tr>
                <td>Verification Status</td>
                <td colspan="3">{{ $case->verification_status ?? 'NA' }}</td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered" border="2">
        <tbody>
            <tr class="subheading">
                <td colspan="4" class="text-center">Authentication</td>
            </tr>
            <tr>
                <td style="width: 50%; text-align:center">
                    <img title="image"
                        style="width:150px; margin-bottom:5px; margin-left:5px; border:2px solid #b06c1c; border-radius:10px;"
                        src="{{ $sign }}" />
                    <br>
                    Signature of Authorized Signatory (With Agency Seal)
                </td>
                <td style="width: 50%; text-align:center">
                    {{-- <div style="min-height: 40px; font-weight: 600;">{{ $case->authorized_signatory ?? 'NA' }}</div> --}}
                    <img title="image"
                        style="width:150px; margin-bottom:5px; margin-left:5px; border:2px solid #b06c1c; border-radius:10px;"
                        src="{{ $sign }}" />
                        <br>

                    Authorized Signatory Name
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered" border="2">
        <tbody>
            <tr class="subheading">
                <td colspan="4" class="text-center">Location Snapshot</td>
            </tr>
            <tr>
                <td>Latitude</td>
                <td>{{ $case->latitude ?? 'NA' }}</td>
                <td>Longitude</td>
                <td>{{ $case->longitude ?? 'NA' }}</td>
            </tr>
            <tr>
                <td>Address</td>
                <td colspan="3">{{ $case->latlong_address ?? 'NA' }}</td>
            </tr>
            <tr>
                <td colspan="4" class="map-container" style="text-align: center;">
                    <img src="https://maps.geoapify.com/v1/staticmap?style=osm-bright&width=600&height=400&center=lonlat:{{ $case->longitude }},{{ $case->latitude }}&zoom=15&marker=lonlat:{{ $case->longitude }},{{ $case->latitude }};color:%239d1d3f;size:medium&apiKey=YOUR_GEOAPIFY_KEY" alt="Map">
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered" border="2">
        <tbody>
            <tr class="subheading">
                <td colspan="4" class="text-center">Applicant Photos</td>
            </tr>
            @php
                $images = [];
                for ($i = 0; $i < 9; $i++) {
                    $imageProperty = 'image_' . $i;
                    $img = $case->$imageProperty ?? null;
                    if (!empty($img)) {
                        $path = public_path($img);
                        if (file_exists($path)) {
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            $images[] = $base64;
                        }
                    }
                }
            @endphp

            @for ($i = 0; $i < count($images); $i += 2)
                <tr>
                @for ($j = 0; $j < 2; $j++)
                    @if (isset($images[$i + $j]))
                        <td colspan="2" style="width: 50%; text-align: center;">
                            <img class="zoomimg" alt="img" data-img="{{ $images[$i + $j] }}" src="{{ $images[$i + $j] }}" />
                        </td>
                    @endif
                @endfor
                </tr>
            @endfor
        </tbody>
    </table>
</div>