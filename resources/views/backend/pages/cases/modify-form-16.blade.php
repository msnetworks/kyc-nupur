<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form-16</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
        }
        .form-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            display: inline-block;
            padding: 10px 15px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Form-16</h2>
    <form action="{{ route('admin.case.modifyForm16Case', $case->id) }}" method="POST">
        @csrf
        <input type="hidden" name="case_fi_id" value="{{ $case->id }}" />
        <!-- Agency Details -->
        <div class="row">
        <div class="form-group col-md-4">
            <label for="agency_name">Agency Name & Address</label>
            <input type="text" id="agency_name" name="agency_name" value="{{ $case->address }}" placeholder="Enter agency name and address" required>
        </div>

        <!-- Bank Details -->
        <div class="form-group col-md-4">
            <label for="bank_reference">Bank Reference No</label>
            <input type="text" id="bank_reference" name="bank_reference" value="{{ $case->bank_name }}" placeholder="Enter bank reference number">
        </div>
        <div class="form-group col-md-4">
            <label for="dda_reference">DDA Reference No</label>
            <input type="text" id="dda_reference" name="dda_reference" value="{{ $case->dd_ref_no }}" placeholder="Enter DDA reference number">
        </div>
        <div class="form-group col-md-4">
            <label for="date_received">Date of Receipt of File</label>
            <input type="date" id="date_received" value="{{ $case->assement_date }}" name="date_received" required>
        </div>
        <div class="form-group col-md-4">
            <label for="date_submitted">Date of Submission of Report</label>
            <input type="date" id="date_submitted" value="{{ $case->date_of_visit }}"  name="date_submitted" required>
        </div>

        <!-- Loan Details -->
        <div class="form-group col-md-4">
            <label for="loan_branch">Proposal Pertaining to Branch</label>
            <input type="text" id="loan_branch" name="loan_branch" value="{{ $case->branch }}"  placeholder="Enter branch name">
        </div>
        <div class="form-group col-md-4">
            <label for="loan_type">Type/Purpose of Loan</label>
            <input type="text" id="loan_type" name="loan_type" value="{{ $case->type_of_employer }}"  placeholder="Enter type or purpose of loan" required>
        </div>
        <div class="form-group col-md-4">
            <label for="zone">Zone</label>
            <input type="text" id="zone" name="zone"  value="{{ $case->locality }}"  placeholder="Enter zone">
        </div>

        <!-- Applicant Details -->
        <div class="form-group col-md-4">
            <label for="applicant_name">Applicant Name</label>
            <input type="text" id="applicant_name" name="applicant_name" value="{{ $case->applicant_name }}"  placeholder="Enter applicant name" required>
        </div>
        <div class="form-group col-md-4">
            <label for="name_of_employer">Employer Name</label>
            <input type="text" id="name_of_employer" name="name_of_employer"  value="{{ $case->name_of_employer }}"  placeholder="Enter employer name">
        </div>

        <!-- Income Details -->
        <div class="form-group col-md-4">
            <label for="salary">Salary</label>
            <input type="text" id="salary" name="salary" value="{{ $case->salary }}"  placeholder="Enter salary">
        </div>
        <div class="form-group col-md-4">
            <label for="other_income">Other Income</label>
            <input type="text" id="other_income" name="other_income" value="{{ $case->other_income }}"  placeholder="Enter other income">
        </div>
        <div class="form-group col-md-4">
            <label for="total_income">Total Income</label>
            <input type="text" id="total_income" name="total_income" value="{{ $case->total_income }}"  placeholder="Enter total income">
        </div>
        <div class="form-group col-md-4">
            <label for="co_applicant">Co-Applicant</label>
            <input type="text" id="co_applicant" name="co_applicant" value="{{ $case->co_applicant }}"  placeholder="Enter Co-Applicant">
        </div>
        <div class="form-group col-md-4">
            <label for="form16_employer">Guarantor</label>
            <input type="text" id="guarantor" name="guarantor" value="{{ $case->guarantor }}"  placeholder="Enter Guarantor">
        </div>
        
        <!-- Verification Details -->
        <div class="form-group col-md-4">
            <label for="name_of_employer_co">Name and Address of Employer with Form16</label>
            <textarea type="text" id="name_of_employer_co" name="name_of_employer_co" placeholder="Enter employer name from Form-16">{{ $case->name_of_employer_co }}</textarea>
        </div>
        <div class="form-group col-md-4">
            <label>Whether FORM16 is issued by the Employer?</label><br>
            <div style="display: flex; align-items: center; gap: 10px;">
                <label for="form16_yes">Yes</label>
                <input style="width: auto!important;" type="radio" id="form16_yes" name="form16_issued" value="yes" {{ $case->form16_issued == 'yes' ? 'checked' : '' }} required>
                &nbsp; &nbsp; &nbsp;
                <label for="form16_no">No</label>
                <input style="width: auto!important;" type="radio" id="form16_no" name="form16_issued"  {{ $case->form16_issued == 'no' ? 'checked' : '' }}  value="no">
            </div>
        </div>
        
        <div class="form-group col-md-4">
            <label>Whether Tax Paid Challan and/or 26 AS filed matched?</label><br>
            <div style="display: flex; align-items: center; gap: 10px;">
                <label for="tax_matched_yes">Yes</label>
                <input style="width: auto!important;" type="radio" id="tax_matched_yes" name="tax_matched" {{ $case->tax_matched == 'yes' ? 'checked' : '' }}  value="yes" required>
                &nbsp; &nbsp; &nbsp;
                <label for="tax_matched_no">No</label>
                <input style="width: auto!important;" type="radio" id="tax_matched_no" name="tax_matched" {{ $case->tax_matched == 'no' ? 'checked' : '' }}  value="no">
            </div>
        </div>
        
        <div class="form-group col-md-4">
            <label for="assessment_year">Verification of Assessment year</label>
            <input type="text" id="assessment_year" name="assessment_year" value="{{ $case->assessment_year }}"  placeholder="Enter assessment year">
        </div>
        <div class="form-group col-md-4">
            <label for="pan_number">Verification of Employer Name & TAN/PAN number</label>
            <textarea id="pan_number" name="pan_number" placeholder="Enter PAN number">{{ $case->pan_number }}</textarea>
        </div>
        <div class="form-group col-md-4">
            <label for="total_income_verification">Verification of Total Income</label>
            <textarea type="text" id="total_income_verification" name="total_income_verification" class="form-control" placeholder="Enter verified total income" required>{{ $case->total_income_verification }}</textarea>
        </div>
        <div class="form-group col-md-4">
            <label for="income_amount">Verification of Income Tax amount</label>
            <textarea type="text" id="income_amount" name="income_amount"  class="form-control" placeholder="Enter Income Amount" required>{{ $case->income_amount }}</textarea>
        </div>
        <div class="form-group col-md-4">
            <label for="consolidated_remarks">Name of Verifier & remarks </label>
            <textarea id="consolidated_remarks" name="consolidated_remarks" rows="3"  placeholder="Enter Verifier Remarks">{{ $case->consolidated_remarks ?? '' }}</textarea>
        </div>
        <div class="form-group col-md-4">
            <label for="supervisor_remarks">Name of Supervisor & Remarks</label>
            <textarea id="supervisor_remarks" name="supervisor_remarks" rows="3" placeholder="Enter Verifier Remarks">{{ $case->supervisor_remarks ?? '' }}</textarea>
        </div>
        <div class="form-group col-md-4">
            <label for="negative_feedback_reason">Remarks in Detail if Negative</label>
            <textarea id="negative_feedback_reason" name="negative_feedback_reason" rows="3" placeholder="Enter Verifier Remarks">{{ $case->negative_feedback_reason ?? '' }}</textarea>
        </div>
        <div class="form-group col-md-4">
            <label for="remarks">Remarks</label>
            <textarea id="remarks" name="remarks" rows="3" placeholder="Enter remarks">{{ $case->remarks ?? '' }} </textarea>
        </div>
        <div class="form-group col-md-4">
            <label for="app_remarks">Special information/Observation,if any</label>
            <textarea type="text" id="app_remarks" name="app_remarks" class="form-control" placeholder="Enter Special information.observation">{{ $case->app_remarks }}</textarea>
        </div>
        <div class="form-group col-md-4">
            <label>Justification for Overall Opinion</label><br>
            <div style="display: flex; align-items: center; gap: 10px;">
                <label for="opinion_yes">Satisfactory</label>
                <input style="width: auto!important;" type="radio" id="opinion_yes" name="recommended" {{ $case->recommended == 'Satisfactory' ? 'checked' : '' }}  value="Satisfactory" required>
                &nbsp; &nbsp; &nbsp;
                <label for="opinion_no">Not Satisfactory</label>
                <input style="width: auto!important;" type="radio" id="opinion_no" name="recommended" {{ $case->recommended == 'Not Satisfactory' ? 'checked' : '' }} value="Not Satisfactory">
            </div>
        </div>

    </div>
        <button type="submit" class="updateBtn">Generate Form-16 Verification</button>
    </form>
</div>

</body>
</html>
<script>
    $(document).ready(function() {

        $('.updateBtn').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');

            let formData = form.serializeArray();
            let rowId = form.find('input[name="case_fi_id"]').val();
            let actionPath = "{{ route('admin.case.modifyForm16Case','ID')}}";
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