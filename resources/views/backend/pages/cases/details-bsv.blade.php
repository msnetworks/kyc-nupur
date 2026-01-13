<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <span align="right" height="30px;">
        <a href="javascript:void(0)" onclick="printformFunction()">Click here to print</a>
    </span>
    <table class="table table-bordered" id="outprint">
        <tbody>
            <tr>
                <td style="border:none;font-size:22px;color:#0094ff" align="center" colspan="1">
                    <img alt="State Bank of India" src="{{ asset('images/sbi.jpg') }}">
                </td>
                <td class="address_text" align="center" colspan="3"> CORE DOC2INFO SERVICES</td>
            </tr>

            <tr>
                <td>Reference No.</td>
                <td class="BVstyle ng-binding">{{ $case->getCase->refrence_number ?? '' }}</td>
                <td>Customer Name</td>
                <td class="BVstyle ng-binding">{{ $case->getCase->applicant_name ?? '' }}</td>
            </tr>
            <tr>
                <td>Case Creation Login Details</td>
                <td colspan="3" class="BVstyle ng-binding">{{ $case->getCase->created_at ?? 'NA' }}</td>
            </tr>
            <tr>
                <td>Product Name</td>
                <td class="BVstyle ng-binding">{{ $case->getCase->getProduct->name ?? 'NA' }}</td>
                <td>Loan Amount</td>
                <td class="BVstyle ng-binding">{{ $case->getCase->amount ?? 'NA' }}</td>
            </tr>
            <tr>
                <td>Contact No.</td>
                <td colspan="3" class="BVstyle ng-binding">{{ $case->mobile ?? '' }}</td>
            </tr>
            <tr>
                <td>Address</td>
                <td colspan="3" class="BVstyle ng-binding">{{ $case->address ?? '' }}</td>
            </tr>
            <tr>
                <td colspan="4" class="subheading" style="text-align: center">
                    BSV Verification Format
                </td>
            </tr>
            <tr>
                <td>Address Confirmed </td>
                <td colspan="3" class="BVstyle ng-binding">{{ $case->address_confirmed ?? 'NO' }} &nbsp; </td>
            </tr>
            <tr>
                <td>Address Confirmed By</td>
                <td colspan="3" class="BVstyle ng-binding ng-hide">{{ $case->address_confirmed_by ?? 'NA' }} </td>
            </tr>
            <tr>
                <td colspan="4" class="subheading" style="text-align: center">Supervisor Remarks</td>
            </tr>
            <tr>
                <td colspan="4">{{ $case->remarks ?? 'NA' }}</td>
            </tr>
            <tr>
                <td colspan="4" class="subheading" style="text-align: center">AS CLAIMED / CONFIRMED</td>
            </tr>
            <tr>
                <td colspan="4">{{ 'Recommended - ADDRESS CONFIRMED' }}</td>
            </tr>
            <tr>
                <td colspan="2">Visit Conducted</td>
                <td colspan="2" class="ng-binding">{{ ($case->status == 2) ? 'positive' : (($case->status == 3) ? 'negative' : 'NA') }}</td>
            </tr>
            <tr>
                <td>Visited Date</td>
                <td class="BVstyle ng-binding">{{ $case->date_of_visit ?? 'NA' }}</td>
                <td>Visited Time</td>
                <td class="BVstyle ng-binding">{{ $case->time_of_visit ?? 'NA' }}</td>
            </tr>
            <tr>
                <td>Latitude</td>
                <td class="BVstyle ng-binding">{{ $case->latitude ?? 'NA' }}</td>
                <td>Longitude</td>
                <td class="BVstyle ng-binding">{{ $case->longitude ?? 'NA' }}</td>
            </tr>
            <tr>
                <td colspan="4">
                    @if(!empty($case->latitude) && !empty($case->longitude))
                    <iframe id="BV_Map" src="https://maps.google.com/maps?q={{ $case->latitude }},{{ $case->longitude }}&z=15&output=embed" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy">
                    </iframe>

                    @endif
                </td>
            </tr>
            <tr>
                <td>Visited By </td>
                <td class="BVstyle ng-binding ng-hide">{{ $case->visited_by ?? 'NA' }}</td>
                <td>Verified By </td>
                <td class="BVstyle ng-binding ng-hide">{{ $case->verified_by ?? 'NA' }}</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center">
                    @if(!empty($case->signature_of_agency_supervisor))

                    <img title='' style='width:100px;height:100px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;' src="{{ asset($case->signature_of_agency_supervisor) }}" />

                    @endif
                    <br>
                    Signature of Agency Supervisor (With agency Seal)
                </td>
                <td colspan="2" style="text-align:center">
                    <!-- <img src="http://verification.mobileforce.in/Data/CaseLogos/" height="100" width="200"> -->
                    {{ $case->email_of_employer ?? '0000' }}
                    <br>
                    Audit Check Remarks by Agency With Stamp &amp; Sign
                </td>
            </tr>
        </tbody>
    </table>
    <br>
</div>