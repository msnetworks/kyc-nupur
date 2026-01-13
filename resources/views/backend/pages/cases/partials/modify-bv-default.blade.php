<style>
    .error {
        color: red;
    }
</style>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <form action="{{ route('admin.case.modifyBVCase', $case->id) }}" method="POST">
        @csrf
        <input type="hidden" name="case_fi_id" value="{{ $case->id }}" />
        <table class="table table-bordered">
            <tbody>
                <tr>
                    @if($case->getCase->bank_id != 12 && $case->getCase->bank_id != 13)
                    <td style="border:none;font-size:22px;color:#0094ff" class="text-center" colspan="2">
                        <img alt="TIGER 4 INDIA LTD" src="{{ asset('images/logo.jpg') }}">
                    </td>
                    @elseif($case->getCase->bank_id == 13)
                    <td style="border:none;font-size:22px;color:#0094ff" class="text-center" colspan="2">
                        <img alt="SK ENTERPRISES" src="{{ asset('images/sk-logo.png') }}">
                    </td>
                    @endif
                    <td class="address_text align-middle text-white" style="background: #3fbaf7;" align="center" colspan="2"><h4> {{ $case->getCase->bank_id == 12 ? 'Synergee Risk Management Pvt. Ltd.' : ($case->getCase->bank_id == 13 ? 'SK ENTERPRISES' : 'TIGER 4 INDIA LTD') }} </h4></td>
                </tr>
                <tr>
                    <td class="w-25">Reference No.</td>
                    <td  class="w-25">
                        <input type="text" name="refrence_number" class="form-control" value="{{ $case->getCase->refrence_number ?? '' }}" readonly>
                    </td>
                    <td  class="w-25">Branch Code</td>
                    <td  class="w-25">
                        <input type="text" name="branch_code" class="form-control" value="{{ isset($case->getCase->getBranch->branch_code) ? optional($case->getCase->getBranch)->branch_code . '('.optional($case->getCase->getBranch)->branch_name . ')' : '' }}" readonly>
                    </td>
                </tr>
                <tr>
                    <td>Customer Name</td>
                    <td class="BVstyle">
                        <input type="text" name="applicant_name" class="form-control" value="{{ $case->getCase->applicant_name ?? '' }}" readonly>
                    </td>
                    <td>Case Creation Login Details</td>
                    <td class="BVstyle">
                        <input type="text" name="created_at" class="form-control" value="{{ $case->getCase->created_at ?? '' }}" readonly>
                    </td>
                </tr>
                <tr>
                    <td>Product Name</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" class="form-control" value="{{ $case->getCase->getProduct->name ?? 'NA' }}" readonly>
                    </td>
                    <td>FI Type</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" class="form-control" value="{{ optional($case->getFiType)->name ?? 'NA' }}" readonly>
                    </td>
                   
                </tr>
                <tr>
                    <td>Loan Amount</td>
                    <td class="BVstyle">
                        <input type="text" name="amount" class="form-control" value="{{ $case->getCase->amount ?? '' }}" readonly>
                    </td>
                    <td>Dealer Code</td>
                    <td class="BVstyle ng-binding"><input type="text" name="dealer_code" class="form-control" value="{{ $case->dealer_code ?? 'NA' }}" /></td>
                </tr>
                <tr>
                    <td>Landline</td>
                    <td class="BVstyle ng-binding"><input type="text" name="landline" class="form-control" value="{{ $case->landline ?? '' }}" /></td>
                    <td>Contact No.</td>
                    <td class="BVstyle ng-binding"><input type="text" name="mobile" class="form-control" value="{{ $case->mobile ?? '' }}" readonly /></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td colspan="3" class="BVstyle">
                        <input type="text" name="address" class="form-control" value="{{ $case->address ?? '' }}" readonly>
                    </td>
                </tr>
                <tr>
                    <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center">
                    <strong>
                        Employment(Salaried)/ Business(Self-Employed) Verification Report<br>
                        (Strictly Private & Confidential)
                    </strong>
                    </td>
                </tr>
                {{-- <tr>
                    <td>Address Confirmed </td>
                    <td>
                    <select name="address_confirmed" class="form-control">
                        <option value="">--Select--</option>
                        <option value="NO" {{ ($case->address_confirmed == 'NO' || $case->address_confirmed == '') ? 'selected' : '' }}>No</option>
                        <option value="Self/Colleague" {{ ($case->address_confirmed == 'Self/Colleague') ? 'selected' : '' }}>Self/Colleague</option>
                        <option value="Receptionist/Guard" {{ ($case->address_confirmed == 'Receptionist/Guard') ? 'selected' : '' }}>Receptionist/Guard</option>
                    </select>
                    </td>
                </tr> --}}
                <tr>
                    <td>Address Confirmed </td>
                    <td colspan="3" class="BVstyle ng-binding"><input type="text" name="address_confirmed" class="form-control" value="{{ $case->address_confirmed ?? '' }}" /> &nbsp; </td>
                </tr>
                <tr>
                    <td>Office/Business Address</td>
                    <td colspan="3" class="BVstyle">
                        <input type="text" name="employer_address" class="form-control" value="{{ $case->employer_address ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <td>Office Status</td>
                    <td colspan="3" class="BVstyle">
                        <input type="text" name="residence_status" class="form-control" value="{{ $case->residence_status ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <td>Type of Proof</td>
                    <td colspan="3" class="BVstyle">
                        <input type="text" name="type_of_proof" class="form-control" value="{{ $case->type_of_proof ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center">
                    <strong>
                        The following information should be obtained if the applicant/colleagues are contacted in the office
                    </strong>
                    </td>
                </tr>
                <tr>
                    <td>Applicant Age(Approx)</td>
                    <td class="BVstyle">
                        <input type="number" name="applicant_age" class="form-control" value="{{ $case->applicant_age ?? '' }}">
                    </td>
                    <td>Person Met	</td>
                    <td class="BVstyle">
                        <input type="text" name="person_met" class="form-control" value="{{ $case->person_met ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <td>Name of Employer/Co</td>
                    <td class="BVstyle">
                        <input type="text" name="name_of_employer_co" class="form-control" value="{{ $case->name_of_employer_co ?? '' }}">
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Mobile Number</td>
                    <td><input type="number" name="employer_mobile" class="form-control" value="{{ $case->employer_mobile ?? '' }}"></td>
                </tr>
                <tr>
                    <td>Co. Board Outside Bldg/Office</td>
                    <td class="BVstyle">
                        <input type="text" name="co_board_outside_bldg_office" class="form-control" value="{{ $case->co_board_outside_bldg_office ?? '' }}">
                    </td>
                    <td>Type of Employer/Co</td>
                    <td class="BVstyle">
                        <input type="text" name="type_of_employer" class="form-control" value="{{ $case->type_of_employer ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <td>Nature of Business</td>
                    <td class="BVstyle">
                        <input type="text" name="nature_of_business" class="form-control" value="{{ $case->nature_of_business ?? '' }}">
                    </td>
                    <td>Applicant Designation</td>
                    <td class="BVstyle">
                        <input type="text" name="designation" class="form-control" value="{{ $case->designation ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <td>Line of Business (for self-employed)</td>
                    <td class="BVstyle">
                        <input type="text" name="line_of_business" class="form-control" value="{{ $case->line_of_business ?? '' }}">
                    </td>
                    <td>Year of Establishment</td>
                    <td class="BVstyle">
                        <input type="text" name="year_of_establishment" class="form-control" value="{{ $case->year_of_establishment ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <td>Level of Business activity(for self-employed)</td>
                    <td class="BVstyle">
                        <input type="text" name="level_of_business_activity" class="form-control" value="{{ $case->level_of_business_activity ?? '' }}">
                    </td>
                    <td>No. of Employees</td>
                    <td class="BVstyle">
                        <input type="number" name="no_of_employees" class="form-control" value="{{ $case->no_of_employees ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <td>Office ambience/look</td>
                    <td class="BVstyle">
                        <input type="text" name="interior_conditions" class="form-control" value="{{ $case->interior_conditions ?? '' }}">
                    </td>
                    <td></td>
                    <td class="BVstyle"></td>
                </tr>
                <tr>
                    <td>Type of Locality</td>
                    <td class="BVstyle">
                        <input type="text" name="type_of_locality" class="form-control" value="{{ $case->type_of_locality ?? '' }}">
                    </td>
                    <td>Area</td>
                    <td class="BVstyle">
                        <input type="text" name="area" class="form-control" value="{{ $case->area ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <td>Nearest Landmark</td>
                    <td class="BVstyle">
                        <input type="text" name="nearest_landmark" class="form-control" value="{{ $case->nearest_landmark ?? '' }}">
                    </td>
                    <td>Ease of Locating</td>
                    <td class="BVstyle">
                        <input type="text" name="ease_of_locating" class="form-control" value="{{ $case->ease_of_locating ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <td>Grade</td>
                    <td class="BVstyle">
                        <input type="text" name="grade" class="form-control" value="{{ $case->grade ?? '' }}">
                    </td>
                    <td></td>
                    <td class="BVstyle"></td>
                </tr>
                <tr>
                    <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center">
                    <strong>If applicant is not giving information, the following information needs to be obtained from the Colleague/Guard/Neighbour</strong></td>
                </tr>
                <tr>
                    <td>Telephono No. Office</td>
                    <td class="BVstyle ng-binding ng-hide">
                        <input type="number" name="telephono_no_office" class="form-control" value="{{ $case->telephono_no_office ?? '' }}">
                    </td>
                    <td>Ext.</td>
                    <td class="BVstyle ng-binding ng-hide">
                        <input type="number" name="ext" class="form-control" value="{{ $case->ext ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <td>Co/Established in(Year)</td>
                    <td class="BVstyle">
                        <input type="number" name="established" class="form-control" value="{{ $case->established ?? '' }}">
                    </td>
                    <td></td>
                    <td class="BVstyle"></td>
                </tr>
                <tr>
                    <td>Nature of Co/Employer</td>
                    <td class="BVstyle">
                        <input type="text" name="nature_of_employer" class="form-control" value="{{ $case->nature_of_employer ?? '' }}">
                    </td>
                    <td></td>
                    <td class="BVstyle"></td>
                </tr>
                <tr>
                <tr ng-hide="BVCase.StatusID==217" class="bg-info text-white">
                    <td colspan="4" class="subheading" style="text-align: center"><strong>Office CPV COMMENTS</strong></td>
                </tr>
                <tr ng-hide="BVCase.StatusID==217" class="">
                    <td colspan="4" class="ng-binding">
                        <textarea type="text" name="app_remarks" maxlength="255" class="form-control">{{ $case->app_remarks ?? '' }}</textarea>
                    </td>
                </tr>
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading" style="text-align: center"><strong>Supervisor Remarks</strong></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <input type="text" name="supervisor_remarks" maxlength="255" class="form-control" value="{{ $case->supervisor_remarks ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center">
                    <strong>AS CLAIMED / CONFIRMED</strong></td>
                </tr>
                <tr>
                    <td>Sub Status </td>
                    <td class="ng-binding"><input type="text" name="negative_feedback_reason" class="form-control" value="{{ $case->negative_feedback_reason ?? '' }}"></td>
                </tr>
                <tr>
                    <td>Visit Conducted </td>
                    <td colspan="2" class="ng-binding">
                        <input type="text" name="visit_conducted" class="form-control" value="{{ $case->visit_conducted ?? '' }}">
                        <!--<select name="status" class="form-control">-->
                        <!--    <option value="2" {{ ($case->status == 2) ? 'selected' : '' }}>Positive</option>-->
                        <!--    <option value="3" {{ ($case->status == 3) ? 'selected' : '' }}>Negative</option>-->
                        <!--</select>-->
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>VisitDate</td>
                    <td class="BVstyle">
                        <input type="date" name="date_of_visit" class="form-control" value="{{ $case->date_of_visit ?? '' }}">
                    </td>
                    <td>VisitTime</td>
                    <td class="BVstyle">
                        <input type="time" name="time_of_visit" class="form-control" value="{{ $case->time_of_visit ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center">
                    <strong>Location</strong></td>
                        
                </tr>
                <tr>
                    <td>Latitude</td>
                    <td class="BVstyle">
                        <input type=number step=0.01 name="latitude" class="form-control" value="{{ $case->latitude ?? '' }}">
                    </td>
                    <td>Longitude</td>
                    <td class="BVstyle">
                        <input type=number step=0.01 name="longitude" class="form-control" value="{{ $case->longitude ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td class="BVstyle">
                        <input type=text name="latlong_address" class="form-control" value="{{ $case->latlong_address ?? '' }}">
                    </td>
                    <td></td>
                    <td class="BVstyle"></td>
                </tr>

                <tr>
                    <tr class="bg-info text-white">
                <td colspan="4" class="subheading" style="text-align: center">
                    <strong>Third Party Check</strong></td>
                </tr>
                <tr>
                    <td>TPC 1 Name</td>
                    <td class="BVstyle">
                        <input type="text" name="tcp1_name" class="form-control" value="{{ $case->tcp1_name ?? '' }}">
                    </td>
                    <td>TPC 1 (Checked with)</td>
                    <td class="BVstyle">
                        <input type="text" name="tcp1_checked_with" class="form-control" value="{{ $case->tcp1_checked_with ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <td>TPC 1 Negative Reason</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" name="tcp1_negative_comments" class="form-control" value="{{ $case->tcp1_negative_comments ?? '' }}">
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>TPC 2 Name</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" name="tcp2_name" class="form-control" value="{{ $case->tcp2_name ?? '' }}">
                    </td>
                    <td>TPC 2 (Checked with)</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" name="tcp2_checked_with" class="form-control" value="{{ $case->tcp2_checked_with ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <td>TPC 2 Negative Reason</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" name="tcp2_negative_comments" class="form-control" value="{{ $case->tcp2_negative_comments ?? '' }}">
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Visited By </td>
                    <td class="BVstyle ng-binding ng-hide">
                        <input type="text" name="visited_by" class="form-control" value="{{ $case->visited_by ?? '' }}">
                    </td>
                    <td>Verified By </td>
                    <td class="BVstyle ng-binding ng-hide">
                        <input type="text" name="verified_by" class="form-control" value="{{ $case->verified_by ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <td colspan="4" align="center"><input type="submit" value="Upate Bv Case" class="btn btn-primary updateBtn btn-sm"></td>
                </tr>
                <tr id="errors">

                </tr>
            </tbody>
        </table>
    </form>


    <br>
</div>