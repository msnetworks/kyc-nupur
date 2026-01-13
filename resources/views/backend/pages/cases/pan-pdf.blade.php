@php
$path = public_path('images/sign.png');
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$sign = 'data:image/' . $type . ';base64,' . base64_encode($data);

$logopath = public_path('images/logo.jpg');
$logotype = pathinfo($logopath, PATHINFO_EXTENSION);
$logodata = file_get_contents($logopath);
$logo = 'data:image/' . $logotype . ';base64,' . base64_encode($logodata);
@endphp

<table class="table table-bordered" style="border: 2px solid #000; width: 100%; table-layout: fixed;">
    <tbody>
        <!-- Header Row -->
        <tr style="border: 2px solid #000;">
            <td style="width: 30%; text-align: center; vertical-align: middle; border: 2px solid #000;">
                <img alt="TIGER 4 INDIA LTD" src="{{ $logo }}" style="max-width: 100%; height: auto;">
            </td>
            <td style="width: 70%; text-align: center; vertical-align: middle; border: 2px solid #000;">
                <strong>
                    <h1 style="color: #ff0000; margin: 0;"><u><i>TIGER 4 INDIA LTD</i></u></h1>
                    VASANT KUNJ, NEW DELHI-110070 <br>
                </strong>
            </td>
        </tr>

        <!-- Case Details -->
        <tr style="border: 2px solid #000;">
            <td style="border: 2px solid #000; text-align: center;" colspan="2"><strong>{{ optional($case->getCase->getBank)->name ?? '' }} <br> {{ optional($case->getFiType)->name }}</strong></td>
        </tr>
        <tr style="border: 2px solid #000;">
            <td style="border: 2px solid #000;">Sr No.</td>
            <td style="border: 2px solid #000;">{{ $case->getCase->refrence_number ?? 'N/A' }}</td>
        </tr>
        <tr style="border: 2px solid #000;">
            <td style="border: 2px solid #000;">Branch</td>
            <td style="border: 2px solid #000;">
                {{ optional($case->getCase->getBranch)->branch_name . ' (' . optional($case->getCase->getBranch)->branch_code . ')' ?? 'N/A' }}
            </td>
        </tr>
        <tr style="border: 2px solid #000;">
            <td style="border: 2px solid #000;">Product Name</td>
            <td style="border: 2px solid #000;">{{ $case->getCase->getProduct->name ?? 'N/A' }}</td>
        </tr>
        <tr style="border: 2px solid #000;">
            <td style="border: 2px solid #000;">Applicant Name</td>
            <td style="border: 2px solid #000;">{{ $case->getCase->applicant_name ?? 'N/A' }}</td>
        </tr>
        <tr style="border: 2px solid #000;">
            <td style="border: 2px solid #000;">Mobile</td>
            <td style="border: 2px solid #000;">{{ $case->mobile ?? 'N/A' }}</td>
        </tr>
       
        
        @if($case->fi_type_id == 17)
            <tr style="border: 2px solid #000;">
                <td style="border: 2px solid #000;">Pan Card</td>
                <td style="border: 2px solid #000;">{{ $case->pan_number ?? ($case->getCase->pan_card ?? 'N/A') }}</td>
            </tr>
            <tr style="border: 2px solid #000;">
                <td style="border: 2px solid #000;">Aadhar Card</td>
                <td style="border: 2px solid #000;">{{ $case->aadhar_card ?? ($case->getCase->aadhar_card ?? 'N/A') }}</td>
            </tr>
        @endif
        @if($case->fi_type_id == 13)
            <tr style="border: 2px solid #000;">
                <td style="border: 2px solid #000;">Account No</td>
                <td style="border: 2px solid #000;">{{ $case->accountnumber ?? 'N/A' }}</td>
            </tr>
            <tr style="border: 2px solid #000;">
                <td style="border: 2px solid #000;">Verify Bank Name</td>
                <td style="border: 2px solid #000;">{{ $case->bank_name ?? 'N/A' }}</td>
            </tr>
        @endif
        <tr style="border: 2px solid #000;">
            <td style="border: 2px solid #000;">Date Reported</td>
            <td style="border: 2px solid #000;">{{ $case->getCase->created_at ?? 'N/A' }}</td>
        </tr>
        <tr style="border: 2px solid #000;">
            <td style="border: 2px solid #000;">Overall Status</td>
            <td style="border: 2px solid #000;">{{ $case->overall_status == 2 ? 'Positive' : ($case->overall_status == 3 ? 'Negative' : '') }}</td>
            {{-- <td style="border: 2px solid #000;">{{ ($case->status == 4 ? 'Positive' : 'Negative') ?? 'N/A' }}</td> --}}
        </tr>
        <!-- Remarks Section -->
        {{-- @if(isset($case->fi_type_id))
        @if($case->fi_type_id == 17 || $case->fi_type_id == 13) --}}
        <tr style="border: 2px solid #000;">
            <td style="width: 30%; border: 2px solid #000; text-align: left;">Remarks</td>
            <td style="width: 70%; border: 2px solid #000; text-align: left;">
                {{ !empty($case->consolidated_remarks) ? $case->consolidated_remarks : 'No remarks available for this case.' }}
            </td>
        </tr>
        {{-- @elseif($case->fi_type_id == 13)
            <tr style="border: 0px !important;">
                <td colspan="2" style="border: 0px; !important"></td>
            </tr>
            <tr style="border: 2px solid #000">
                <th style="width: 20%; border: 2px solid #000; text-align: left;">Date</th>
                <th style="width: 80%; border: 2px solid #000; text-align: left;">Remarks</th>
            </tr>
            @if(!empty($case->itr_form_remarks))
                @php
                    $itrFormRemarks = json_decode($case->itr_form_remarks, true);
                @endphp
                @if(is_array($itrFormRemarks) && count($itrFormRemarks) > 0)
                    @foreach($itrFormRemarks as $remark)
                        <tr style="border: 2px solid #000">
                            <td>{{ $remark['financial_year'] ?? 'N/A' }}</td>
                            <td>{{ $remark['fi_remark'] ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2">No remarks found.</td>
                    </tr>
                @endif
            @else
                <tr>
                    <td colspan="2">No remarks available for this case.</td>
                </tr>
            @endif
        @endif --}}
        {{-- @endif --}}

        <tr style="border: 0!important;">
            <td colspan="2" style="border: 0px!important">
                <br>
            </td>
        </tr>
        <!-- Signature Section -->
        <tr style="border: 0px;">
            <td colspan="2" style="text-align: left; border: 0px;">
                <img src="{{ $sign }}" style="width: 150px; margin-top: 15px;">
            </td>
        </tr>
    </tbody>
</table>
