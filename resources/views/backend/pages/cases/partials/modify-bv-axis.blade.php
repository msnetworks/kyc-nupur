<style>
    .axis-report h4 {
        margin: 0;
        font-weight: 600;
        color: #9d1d3f;
    }

    .axis-report .subheading {
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 axis-report">
    <form action="{{ route('admin.case.modifyBVCase', $case->id) }}" method="POST">
        @csrf
        <input type="hidden" name="case_fi_id" value="{{ $case->id }}" />
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td colspan="4" class="text-center">
                        <h4>Axis Bank Field Verification Report</h4>
                        <small>Field Credit Unit (FCU)</small>
                    </td>
                </tr>
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading text-center"><strong>Report Header Information</strong></td>
                </tr>
                <tr>
                    <td>FCU Agency Name</td>
                    <td><input type="text" name="fcu_agency_name" class="form-control" value="{{ $case->fcu_agency_name ?? '' }}"></td>
                    <td>RAC Name</td>
                    <td><input type="text" name="rac_name" class="form-control" value="{{ $case->rac_name ?? '' }}"></td>
                </tr>
                <tr>
                    <td>FCU Location</td>
                    <td><input type="text" name="fcu_location" class="form-control" value="{{ $case->fcu_location ?? '' }}"></td>
                    <td>File Number</td>
                    <td><input type="text" name="file_number" class="form-control" value="{{ $case->file_number ?? $case->getCase->refrence_number ?? '' }}"></td>
                </tr>
                <tr>
                    <td>Product</td>
                    <td><input type="text" class="form-control" value="{{ $case->getCase->getProduct->name ?? '' }}" readonly></td>
                    <td>DSA Code</td>
                    <td><input type="text" name="dsa_code" class="form-control" value="{{ $case->dsa_code ?? '' }}"></td>
                </tr>
                <tr>
                    <td>DME</td>
                    <td><input type="text" name="dme" class="form-control" value="{{ $case->dme ?? '' }}"></td>
                    <td>Sales Manager Code</td>
                    <td><input type="text" name="sales_manager_code" class="form-control" value="{{ $case->sales_manager_code ?? '' }}"></td>
                </tr>
                <tr>
                    <td>Loan Amount</td>
                    <td><input type="text" name="amount" class="form-control" value="{{ $case->getCase->amount ?? '' }}" readonly></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading text-center"><strong>Customer Information</strong></td>
                </tr>
                <tr>
                    <td>Customer Name</td>
                    <td><input type="text" name="applicant_name" class="form-control" value="{{ $case->getCase->applicant_name ?? '' }}" readonly></td>
                    <td>Residence Address</td>
                    <td><textarea name="residence_address" class="form-control" rows="2">{{ $case->residence_address ?? $case->address ?? '' }}</textarea></td>
                </tr>
                <tr>
                    <td>Contact Number</td>
                    <td><input type="text" name="contact_number" class="form-control" value="{{ $case->contact_number ?? $case->mobile ?? '' }}"></td>
                    <td>Office Name</td>
                    <td><input type="text" name="office_name" class="form-control" value="{{ $case->office_name ?? '' }}"></td>
                </tr>
                <tr>
                    <td>Employment Status</td>
                    <td>
                        <select name="employment_status" class="form-control">
                            <option value="">--Select--</option>
                            <option value="Proprietor" {{ ($case->employment_status ?? '') === 'Proprietor' ? 'selected' : '' }}>Proprietor</option>
                            <option value="Partner" {{ ($case->employment_status ?? '') === 'Partner' ? 'selected' : '' }}>Partner</option>
                            <option value="Director" {{ ($case->employment_status ?? '') === 'Director' ? 'selected' : '' }}>Director</option>
                        </select>
                    </td>
                    <td>Office Address</td>
                    <td><textarea name="office_address" class="form-control" rows="2">{{ $case->office_address ?? $case->employer_address ?? '' }}</textarea></td>
                </tr>
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading text-center"><strong>Pickup Information</strong></td>
                </tr>
                <tr>
                    <td>Pickup For</td>
                    <td>
                        <select name="pickup_for" class="form-control">
                            <option value="">--Select--</option>
                            <option value="Document" {{ ($case->pickup_for ?? '') === 'Document' ? 'selected' : '' }}>Document</option>
                            <option value="Profile" {{ ($case->pickup_for ?? '') === 'Profile' ? 'selected' : '' }}>Profile</option>
                        </select>
                    </td>
                    <td>Pickup Reason (Trigger)</td>
                    <td><input type="text" name="pickup_reason" class="form-control" value="{{ $case->pickup_reason ?? '' }}"></td>
                </tr>
                <tr>
                    <td>Pickup On</td>
                    <td><input type="date" name="pickup_on" class="form-control" value="{{ $case->pickup_on ?? '' }}"></td>
                    <td>Report Submitted</td>
                    <td><input type="date" name="report_submitted_on" class="form-control" value="{{ $case->report_submitted_on ?? '' }}"></td>
                </tr>
                <tr>
                    <td>Sampler's Name</td>
                    <td><input type="text" name="sampler_name" class="form-control" value="{{ $case->sampler_name ?? '' }}"></td>
                    <td>Verifier's Name</td>
                    <td><input type="text" name="verifier_name" class="form-control" value="{{ $case->verifier_name ?? '' }}"></td>
                </tr>
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading text-center"><strong>Field Verifier's Observations</strong></td>
                </tr>
                <tr>
                    <td>Fraudulent Documents</td>
                    <td colspan="3">
                        <div class="row">
                            <div class="col-md-4"><input type="text" name="fraudulent_document_1" class="form-control" placeholder="Document 1" value="{{ $case->fraudulent_document_1 ?? '' }}"></div>
                            <div class="col-md-4"><input type="text" name="fraudulent_document_2" class="form-control" placeholder="Document 2" value="{{ $case->fraudulent_document_2 ?? '' }}"></div>
                            <div class="col-md-4"><input type="text" name="fraudulent_document_3" class="form-control" placeholder="Document 3" value="{{ $case->fraudulent_document_3 ?? '' }}"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Fraudulent Profile</td>
                    <td colspan="3">
                        <div class="row">
                            <div class="col-md-4"><input type="text" name="fraudulent_profile_1" class="form-control" placeholder="Observation 1" value="{{ $case->fraudulent_profile_1 ?? '' }}"></div>
                            <div class="col-md-4"><input type="text" name="fraudulent_profile_2" class="form-control" placeholder="Observation 2" value="{{ $case->fraudulent_profile_2 ?? '' }}"></div>
                            <div class="col-md-4"><input type="text" name="fraudulent_profile_3" class="form-control" placeholder="Observation 3" value="{{ $case->fraudulent_profile_3 ?? '' }}"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Other Observations</td>
                    <td colspan="3">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="d-block">Setup Company</label>
                                <select name="observation_setup_company" class="form-control">
                                    <option value="">--Select--</option>
                                    <option value="Yes" {{ ($case->observation_setup_company ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ ($case->observation_setup_company ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="d-block">Negative Market Info</label>
                                <select name="observation_negative_market" class="form-control">
                                    <option value="">--Select--</option>
                                    <option value="Yes" {{ ($case->observation_negative_market ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ ($case->observation_negative_market ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="d-block">Anti-Social / PEP</label>
                                <select name="observation_anti_social" class="form-control">
                                    <option value="">--Select--</option>
                                    <option value="Yes" {{ ($case->observation_anti_social ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ ($case->observation_anti_social ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading text-center"><strong>Agency Remarks</strong></td>
                </tr>
                <tr>
                    <td>Residence Profile</td>
                    <td colspan="3"><textarea name="residence_profile" class="form-control" rows="3">{{ $case->residence_profile ?? '' }}</textarea></td>
                </tr>
                <tr>
                    <td>Verification Status</td>
                    <td colspan="3">
                        <select name="verification_status" class="form-control">
                            <option value="">--Select--</option>
                            <option value="Positive" {{ ($case->verification_status ?? '') === 'Positive' ? 'selected' : '' }}>Positive</option>
                            <option value="Negative" {{ ($case->verification_status ?? '') === 'Negative' ? 'selected' : '' }}>Negative</option>
                        </select>
                    </td>
                </tr>
                <tr class="bg-info text-white">
                    <td colspan="4" class="subheading text-center"><strong>Authentication</strong></td>
                </tr>
                <tr>
                    <td>Authorized Signatory</td>
                    <td colspan="3"><input type="text" name="authorized_signatory" class="form-control" value="{{ $case->authorized_signatory ?? '' }}" placeholder="Signature of Authorized Signatory with Agency Seal"></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-center"><input type="submit" value="Update BV Case" class="btn btn-primary updateBtn btn-sm"></td>
                </tr>
                <tr id="errors"></tr>
            </tbody>
        </table>
    </form>
    <br>
</div>