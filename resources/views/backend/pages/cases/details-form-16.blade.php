<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form-16 Verification</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('backend/assets/images/icon/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/bootstrap.min.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            height: 50px;
        }
        .table-container {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 5px;
        }
        .table-container th, .table-container td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .table-container th {
            background-color: #f2f2f2;
        }
        .section-title {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            padding: 5px;
        }
        .remarks {
            font-weight: bold;
            margin-top: 5px;
        }
        .justify {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
    <table class="table table-bordered" style="border: 2px solid #000; width: 100%; table-layout: fixed;">
        <tbody>
            <tr style="border: 2px solid #000;">
                <td style="width: 30%; text-align: center; vertical-align: middle; border: 2px solid #000;">
                    <img alt="TIGER 4 INDIA LTD" src="{{ asset('images/logo.jpg') }}" style="max-width: 100%; height: auto;">
                </td>
                <td style="width: 70%; text-align: center; vertical-align: middle; border: 2px solid #000;">
                    <strong>
                        <h1 style="color: #ff0000; margin: 0;"><u><i>TIGER 4 INDIA LTD</i></u></h1>
                        VASANT KUNJ, NEW DELHI-110070 <br>
                    </strong>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table-container" style="background-color: #efcdb6;">
        <tr>
            <th style="text-align: center; background-color: #efcdb6; padding: 0;"><h3 style="margin: 0;">STATE BANK OF INDIA</h3>
            <h4 style="margin: 0;">Form-16 Verification</h4></th>
        </tr>
    </table>
    <table class="table-container">
        <tr>
            <th colspan="2">Name & Address of the Due Diligence Agency</th>
            <td colspan="2">{{ $case->address }}</td>
        </tr>
        <tr>
            <th>Bank Reference No</th>
            <th>DDA Reference No</th>
            <th>Date of Receipt to File</th>
            <th>Date of Submission of Report</th>
        </tr>
        <tr>
            <td>{{ $case->bank_name }}</td>
            <td>{{ $case->dd_ref_no }}</td>
            <td>{{ $case->assement_date }}</td>
            <td>{{ $case->date_of_visit }}</td>
        </tr>
    </table>
    <table class="table-container">
        <tr>
            <th>Proposal Pertaining to Branch</th>
            <th>Type/Purpose of the Loan</th>
            <th>Zone</th>
        </tr>
        <tr>
            <td>{{ $case->branch }}</td>
            <td>{{ $case->type_of_employer }}</td>
            <td>{{ $case->locality }}</td>
        </tr>
    </table>

    <table class="table-container">
        <tr>
            <th colspan="4" style="background-color: #c5dfb3;">Personal Details (As given in the Loan Application)</th>
        </tr>
        <tr>
            <th></th>
            <th>Applicant</th>
            <th>Co-Applicant</th>
            <th>Guarantor</th>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $case->applicant_name }}</td>
            <td>{{ $case->co_applicant }}</td>
            <td>{{ $case->guarantor }}</td>
        </tr>
        <tr>
            <th>Employer Name</th>
            <td>{{ $case->name_of_employer }}</td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <table class="table-container">
        <tr>
            <th rowspan="3" style="width: 15%; text-align: center; background-color: #bfe5f3">Income & Income Proof</th>
            <th>Salary</th>
            <td>{{ $case->salary }}</td>
        </tr>
        <tr>
            <th>Other Income</th>
            <td>{{ $case->other_income }}</td>
        </tr>
        <tr>
            <th>Total Income</th>
            <td>{{ $case->total_income }}</td>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <table class="table table-bordered" style="border: 2px solid #000; width: 100%; table-layout: fixed;">
        <tbody>
            <tr style="border: 2px solid #000;">
                <td style="width: 30%; text-align: center; vertical-align: middle; border: 2px solid #000;">
                    <img alt="TIGER 4 INDIA LTD" src="{{ asset('images/logo.jpg') }}" style="max-width: 100%; height: auto;">
                </td>
                <td style="width: 70%; text-align: center; vertical-align: middle; border: 2px solid #000;">
                    <strong>
                        <h1 style="color: #ff0000; margin: 0;"><u><i>TIGER 4 INDIA LTD</i></u></h1>
                        VASANT KUNJ, NEW DELHI-110070 <br>
                    </strong>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table-container" style="background-color: #efcdb6;">
        <tr>
            <th style="text-align: center; background-color: #c5dfb3; padding: 0;">
                <h4 style="margin: 0;">Form 16 - Verification Report</h4>
            </th>
        </tr>
    </table>
    <table class="table-container">
        <tr>
            <th>Name and Address of Employer with Form16</th>
            <td>{{ $case->name_of_employer_co }}</td>
        </tr>
        <tr>
            <th>Whether FORM16 is issued by the Employer?</th>
            <td>{{ $case->form16_issued }}</td>
        </tr>
        <tr>
            <th>Whether Tax paid challan and/or 26 AS filed matched</th>
            <td>{{ $case->tax_matched }}</td>
        </tr>
        <tr>
            <th>Verification of Assessment year</th>
            <td>{{ $case->assessment_year }}</td>
        </tr>
        <tr>
            <th>Verification of Employer
                Name, Employer TAN
                number&EmployerPAN
                number
                </th>
            <td>{{ $case->pan_number }}</td>
        </tr>
        <tr>
            <th>Verificationof Total Income
                </th>
            <td>{{ $case->total_income_verification }}</td>
        </tr>
        <tr>
            <th>Verification of Income Tax amount
                </th>
            <td>{{ $case->income_amount }}</td>
        </tr>
        <tr>
            <th>Name of Verifier & remarks</th>
            <td>{{ $case->consolidated_remarks }}</td>
        </tr>
        <tr>
            <th>Name of Supervisor & Remarks</th>
            <td>{{ $case->supervisor_remarks }}</td>
        </tr>
        <tr>
            <th>Remarks in Detail if Negative</th>
            <td>{{ $case->negative_feedback_reason }}</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; background-color: #c5dfb3; padding: 0;"></td>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <div class="remarks">
        <table class="table-container">
            <tr>
                <td>Special information/Observation, if any</td>
            </tr>
            <tr>
                <td>{{ $case->app_remarks }}</td>
            </tr>
            <tr>
                <td>FORM-16NAMEOFAPPLICANT - {{ $case->applicant_name }}, PAN/TAN NUMBER FORM 16 ({{ $case->pan_number }}) - {{ $case->assessment_year }} ASSESSMENT YEAR GIVEN FROM-16 CHECKED</td>
            </tr>
            <tr>
                <td>Justification for Over all Opinion: <b>{{ $case->recommended }}</b></td>
            </tr>
        </table>
    </div>
    </div>
</body>
</html>
