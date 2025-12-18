<style>
    .error {
        color: red;
    }
</style>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 caseModelPop">
    <form action="{{ route('admin.case.modifyRVCase', $case->id) }}" method="POST">
        @csrf
        <input type="hidden" name="case_fi_id" value="{{ $case->id }}" />
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td style="border:none;font-size:22px;color:#0094ff; width: 50%;" align="center" colspan="2">
                        <img alt="State Bank of India" src="{{ asset('images/logo.jpg') }}">
                    </td>
                    <td class="address_text align-middle text-white" style="background: #3fbaf7; width: 50%;" align="center" colspan="2"><h4> TIGER 4 INDIA LTD </h4></td>
                </tr>

                <tr>
                    <td>Reference No.</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" name="refrence_number" class="form-control" value="{{ $case->getCase->refrence_number ?? '' }}" readonly />
                    </td>
                    
                    <td>Branch Code</td>
                    <td class="BVstyle">
                        <input type="text" name="branch_code" class="form-control" value="{{ isset($case->getCase->getBranch->branch_code) ? optional($case->getCase->getBranch)->branch_code . '('.optional($case->getCase->getBranch)->branch_name . ')' : '' }}" readonly>
                    </td>
                </tr>
                <tr>
                    <td>Customer Name</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" name="applicant_name" class="form-control" value="{{ $case->getCase->applicant_name ?? '' }}" readonly />
                    </td>
                    <td>Case Creation Login Details</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" name="created_at" class="form-control" value="{{ $case->getCase->created_at ?? '' }}" readonly />
                    </td>
                </tr>
                <tr>
                    <td>Product Name</td>
                    <td class="BVstyle ng-binding">{{ $case->getCase->getProduct->name ?? 'NA' }}</td>
                    <td>Loan Amount</td>
                    <td class="BVstyle ng-binding"><input type="text" name="amount" class="form-control" value="{{ $case->getCase->amount ?? 'NA' }}" readonly /></td>
                </tr>
                <tr>
                    <td>Dealer Code</td>
                    <td class="BVstyle ng-binding"><input type="text" name="dealer_code" class="form-control" value="{{ $case->dealer_code ?? 'NA' }}" /></td>
                    <td>FI Type</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" class="form-control" value="{{ optional($case->getFiType)->name ?? 'NA' }}" readonly>
                    </td>
                </tr>
                <tr>
                    <td>Landline</td>
                    <td class="BVstyle ng-binding"><input type="text" name="landline" class="form-control" value="{{ $case->landline ?? '' }}" /></td>
                    <td>Contact No.</td>
                    <td class="BVstyle ng-binding"><input type="text" name="mobile" class="form-control" value="{{ $case->mobile ?? '' }}" readonly /></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td colspan="3" class="BVstyle ng-binding"><input type="text" name="address" class="form-control" value="{{ $case->address ?? '' }}" readonly /></td>
                </tr>
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading" style="text-align: center">
                        <strong>Residence Verification Format</strong>
                    </td>
                </tr>
                <tr>
                    <td>Address Confirmed </td>
                    <td colspan="3" class="BVstyle ng-binding"><input type="text" name="address_confirmed" class="form-control" value="{{ $case->address_confirmed ?? '' }}" /> &nbsp; </td>
                </tr>
                <tr>
                    <td>Address Confirmed By</td>
                    <td colspan="3" class="BVstyle ng-binding"><input type="text" name="address_confirmed_by" class="form-control" value="{{ $case->address_confirmed_by ?? '' }}" /> &nbsp; </td>
                </tr>
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading" style="text-align: center">
                        <strong>
                            The following information should be obtained if the applicant/colleagues are contacted in the office
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td>Applicant Name</td>
                    <td class="BVstyle ng-binding"><input type="text" name="applicant_name" class="form-control" value="{{ $case->getCase->applicant_name ?? '' }}" /></td>
                    <td></td>
                    <td class="BVstyle ng-binding"></td>
                </tr>
                <tr>
                    <td>Person Met</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" name="person_met" class="form-control" value="{{ $case->person_met ?? '' }}" />
                    </td>
                    <td>Relationship</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" name="relationship" class="form-control" value="{{ $case->relationship ?? '' }}" />
                    </td>
                </tr>
                <tr>
                    <td>No of Residents in the House</td>
                    <td class="BVstyle ng-binding">
                        <input type="number" name="no_of_residents_in_house" class="form-control" value="{{ $case->no_of_residents_in_house ?? '' }}" />

                    </td>
                    <td>Years at current Residence</td>
                    <td class="BVstyle ng-binding">
                        <input type="number" name="years_at_current_residence" class="form-control" value="{{ $case->years_at_current_residence ?? '' }}" maxlength="4" minlength="4" />
                    </td>
                </tr>
                <tr>
                    <td>No of Earning Family Members</td>
                    <td class="BVstyle ng-binding ng-hide">
                        <input type="number" name="no_of_earning_family_members" class="form-control" value="{{ $case->no_of_earning_family_members ?? '' }}" />
                    </td>
                    <td>Residence Status</td>
                    <td class="BVstyle ng-binding ng-hide">
                        <input type="text" name="residence_status" class="form-control" value="{{ $case->residence_status ?? '' }}" />

                    </td>
                </tr>
                <tr>
                    <td>Approx Rent</td>
                    <td class="BVstyle ng-binding">
                        <input type="number" step="0.01" name="approx_rent" class="form-control" value="{{ $case->approx_rent ?? '' }}" />

                    </td>
                    <td></td>
                    <td class="BVstyle ng-binding"></td>
                </tr>
                <tr>
                    <td>Permanent Address/Phone</td>
                    <td colspan="3" class="BVstyle ng-binding">
                        <input type="text" name="permanent_address" class="form-control" value="{{ $case->permanent_address ?? '' }}" />

                    </td>
                </tr>
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading" style="text-align: center">
                        <strong>Verifier's Observations</strong>
                    </td>
                </tr>
                <tr>
                    <td>Location </td>
                    <td class="BVstyle ng-binding">
                        <input type="text" name="location" class="form-control" value="{{ $case->location ?? '' }}" />

                    </td>
                    <td>Locality</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" name="locality" class="form-control" value="{{ $case->locality ?? '' }}" />

                    </td>
                </tr>
                <tr>
                    <td>Accomodation Type</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" name="accommodation_type" class="form-control" value="{{ $case->accommodation_type ?? '' }}" />

                    </td>
                    <td>Interior Conditions</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" name="interior_conditions" class="form-control" value="{{ $case->interior_conditions ?? '' }}" />

                    </td>
                </tr>
                <tr>
                    <td>Assets Seen</td>
                    <td colspan="3" class="BVstyle ng-binding ng-hide">
                        <input type="text" name="assets_seen" class="form-control" value="{{ $case->assets_seen ?? '' }}" />

                    </td>

                </tr>
                <tr>
                    <td>Area</td>
                    <td class="BVstyle ng-binding ng-hide">
                        <input type="text" name="area" class="form-control" value="{{ $case->area ?? '' }}" />

                    </td>
                    <td>Standard of Living</td>
                    <td>
                        <input type="text" name="standard_of_living" class="form-control" value="{{ $case->standard_of_living ?? '' }}" />

                    </td>
                </tr>

                <tr>
                    <td>Nearest Landmark</td>
                    <td colspan="3" class="BVstyle ng-binding">
                        <input type="text" name="nearest_landmark" class="form-control" value="{{ $case->nearest_landmark ?? '' }}" />

                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="subheading" style="text-align: center">If the house is locked,the following information needs to be obtained from the Neighbour/Third Party.</td>
                </tr>
                <tr>
                    <td>Applicant Name</td>
                    <td class="BVstyle ng-binding ng-hide">
                        <input type="text" name="applicant_name1" class="form-control" value="{{ $case->applicant_name ?? '' }}" />

                    </td>
                    <!-- <td>Person Met</td>
                    <td class="BVstyle ng-binding ng-hide">
                        <input type="text" name="person_met" class="form-control" value="{{ $case->person_met ?? '' }}" />
                    </td> -->
                </tr>
                <tr>
                    <td>Relationship</td>
                    <td class="BVstyle ng-binding ng-hide">
                        <input type="text" name="locked_relationship" class="form-control" value="{{ $case->locked_relationship ?? '' }}" />

                    </td>
                    <td>Applicant Age(Approx)</td>
                    <td class="BVstyle ng-binding ng-hide">
                        <input type="text" name="applicant_age" class="form-control" value="{{ $case->applicant_age ?? '' }}" />

                    </td>
                </tr>
                <tr>
                    <td>No. of Residents in House</td>
                    <td class="BVstyle ng-binding ng-hide">
                        <input type="text" name="residence_status_others" class="form-control" value="{{ $case->residence_status_others ?? '' }}" />

                    </td>
                    <td>Years Lived at this Residence</td>
                    <td class="BVstyle ng-binding ng-hide">
                        <input type="text" name="years_at_current_residence_others" class="form-control" value="{{ $case->years_at_current_residence_others ?? '' }}" />

                    </td>
                </tr>
                <tr>
                    <td>Occupation</td>
                    <td class="BVstyle ng-binding ng-hide">
                        <input type="text" name="occupation" class="form-control" value="{{ $case->occupation ?? '' }}" />

                    </td>
                </tr>
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading" style="text-align: center">
                        <strong>If the address is not confirmed then the following information needs to be filled.
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td>Untraceable</td>
                    <td class="BVstyle ng-binding ng-hide">
                        <input type="text" name="untraceable" class="form-control" value="{{ $case->untraceable ?? '' }}" />
                    </td>
                    <td>Reason</td>
                    <td colspan="2" class="BVstyle ng-binding ng-hide"><input type="text" name="reason_of_untraceable" class="form-control" value="{{ $case->reason_of_untraceable ?? '' }}" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>Result of Calling</td>
                    <td colspan="2" class="BVstyle ng-binding ng-hide"><input type="text" name="reason_of_calling" class="form-control" value="{{ $case->reason_of_calling ?? '' }}" /></td>
                </tr>
                <tr>
                    <td><b>Mismatch in Residence Address</b></td>
                    <td>Is Applicant Known to the person</td>
                    <td colspan="2" class="BVstyle ng-binding ng-hide"><input type="text" name="is_applicant_know_to_person" class="form-control" value="{{ $case->is_applicant_know_to_person ?? '' }}" /></td>
                </tr>

                <tr>
                    <td></td>
                    <td>To Whom Does Address Belong ?</td>
                    <td colspan="2" class="BVstyle ng-binding ng-hide"><input type="text" name="to_whom_does_address_belong" class="form-control" value="{{ $case->to_whom_does_address_belong ?? '' }}" /></td>
                </tr>
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading" style="text-align: center">
                        <strong>The following is based on Verifier Observations
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td>Verifier's Name</td>
                    <td colspan="3">
                        <input type="text" name="verifiers_name" class="form-control" value="{{ $case->verifiers_name ?? '' }}" />

                    </td>

                </tr>

                <tr>
                    <td>Verification Conducted at</td>
                    <td colspan="3">
                        <input type="text" name="verification_conducted_at" class="form-control" value="{{ $case->verification_conducted_at ?? '' }}" />

                    </td>
                </tr>
                <tr>
                    <td>Proof attached</td>
                    <td colspan="3">
                        <input type="text" name="proof_attached" class="form-control" value="{{ $case->proof_attached ?? '' }}" />
                    </td>
                </tr>
                <tr>
                    <td>Type of Proof</td>
                    <td colspan="3">
                        <input type="text" name="type_of_proof" class="form-control" value="{{ $case->type_of_proof ?? '' }}" />

                    </td>
                </tr>
                <tr>
                    <td>Date of Visit</td>
                    <td>
                        <input type="date" name="date_of_visit" class="form-control" value="{{ $case->date_of_visit ?? '' }}" />

                    </td>
                    <td>Time of Visit</td>
                    <td>
                        <input type="time" name="time_of_visit" class="form-control" value="{{ $case->time_of_visit ?? '' }}" />

                    </td>
                </tr>

                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading" style="text-align: center">
                        <strong>Updations</strong></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td colspan="3">
                        <input type="text" name="address" class="form-control" value="{{ $case->address ?? '' }}" />
                    </td>
                </tr>
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading" style="text-align: center">
                        <strong>Office CPV COMMENTS</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <textarea type="text" name="app_remarks" class="form-control" placeholder="Type Here Office CPV COMMENTS...">{{$case->app_remarks}}</textarea>
                    </td>
                </tr>
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading" style="text-align: center">
                        <strong>Supervisor Remarks</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <input type="text" name="supervisor_remarks" class="form-control" value="{{ $case->supervisor_remarks }}"  placeholder="Type Here Supervisor Remarks..."/>

                    </td>
                </tr>
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading" style="text-align: center">
                        <strong>NEGATIVE FEATURES</strong>
                    </td>
                </tr>
                <tr class="ng-hide">
                    <td>Sub Status </td>
                    <td class="ng-binding"><input type="text" name="negative_feedback_reason" class="form-control" value="{{ $case->negative_feedback_reason ?? '' }}"></td>
                </tr>
                <tr>
                    <td>Visit Conducted </td>
                    <td class="ng-binding">
                        <input type="text" name="visit_conducted" class="form-control" value="{{ $case->visit_conducted ?? '' }}">
                        <!--<select name="status" class="form-control">-->
                        <!--    <option value="2" {{ ($case->status == 2) ? 'selected' : '' }}>Positive</option>-->
                        <!--    <option value="3" {{ ($case->status == 3) ? 'selected' : '' }}>Negative</option>-->
                        <!--</select>-->
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading" style="text-align: center">
                        <strong>Location
                        </strong>
                    </td>
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
                    <td>Address</td>
                    <td class="BVstyle">
                        <input type=text name="latlong_address" class="form-control" value="{{ $case->latlong_address ?? '' }}">
                    </td>
                    <td></td>
                    <td class="BVstyle"></td>
                </tr>
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading" style="text-align: center">
                        <strong>Cross Verification Info
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td>Neighbour Check 1</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" name="tcp1_name" class="form-control" value="{{ $case->tcp1_name ?? '' }}" />

                    </td>
                    <td>Neighbour1 Checked With</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" name="tcp1_checked_with" class="form-control" value="{{ $case->tcp1_checked_with ?? '' }}" />

                    </td>
                </tr>
                <tr>
                    <td>TCP1 Negative Comments</td>
                    <td colspan="3">
                        <input type="text" name="tcp1_negative_comments" class="form-control" value="{{ $case->tcp1_negative_comments ?? '' }}" />

                    </td>
                </tr>
                <tr>
                    <td>Neighbour Check 2</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" name="tcp2_name" class="form-control" value="{{ $case->tcp2_name ?? '' }}" />

                    </td>
                    <td>Neighbour2 Checked With</td>
                    <td class="BVstyle ng-binding">
                        <input type="text" name="tcp2_checked_with" class="form-control" value="{{ $case->tcp2_checked_with ?? '' }}" />

                    </td>

                </tr>
                <tr>
                    <td>TCP2 Negative Comments</td>
                    <td colspan="3">
                        <input type="text" name="tcp2_negative_comments" class="form-control" value="{{ $case->tcp2_negative_comments ?? '' }}" />
                    </td>
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
                    <!-- <td colspan="2" style="text-align:center">
                         <img src="http://verification.mobileforce.in/Data/CaseLogos/" height="100" width="200"> 
                        {{ $case->signature_of_agency_supervisor ?? 'NA' }}
                        <br>
                        Signature of Agency Supervisor (With agency Seal)
                    </td>
                    <td colspan="2" style="text-align:center">
                     <img src="http://verification.mobileforce.in/Data/CaseLogos/" height="100" width="200">
                    {{ $case->audit_check_remarks_by_agency_with_stamp ?? 'NA' }}
                    <br>
                    Audit Check Remarks by Agency With Stamp &amp; Sign
                    </td> -->
                </tr>
                <tr>
                    <td colspan="4" align="center"><input type="submit" value="Upate RV Case" class="btn btn-primary updateBtn btn-sm"></td>
                </tr>
                <tr id="errors">

                </tr>
            </tbody>
        </table>
    </form>


    <br>
</div>
<script src="{{ asset('backend/assets/js/jquery.validate.min.js') }}"></script>

<script>
    $(document).ready(function() {

        $('.updateBtn').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');

            let formData = form.serializeArray();
            let rowId = form.find('input[name="case_fi_id"]').val();
            let actionPath = "{{ route('admin.case.modifyRVCase','ID')}}";
            actionPath = actionPath.replace('ID', rowId);
            $.ajax({
                url: actionPath,
                type: 'POST',
                data: formData,
                success: function(response) {
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    $.each(xhr.responseJSON.errors, function(key, item) {
                        $("#errors").append("<li class='alert alert-danger'>" + item + "</li>")
                    });
                }
            });
        });


    });
</script>