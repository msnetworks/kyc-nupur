<div class="col-12">
<a href="#{{ route('export.cases') }}" id="export-button" class="btn btn-success float-right" style="margin-bottom: 20px;">
    Export to Excel
</a>
<!-- Excel Export Button -->
{{-- <button id="exportExcelBtn" class="btn btn-success float-right">Export to Excel</button> --}}
</div>
<table id="dataTable" class="table table-responsive table-bordered border-white text-center">
    <thead class="bg-lightblue text-white">
        <tr>
            <th style="width:15%">PROPOSAL NO.</th>
            <th style="width:15%">APPLICANT NAME</th>
            <th style="width:15%">VERIFICATION TYPE</th>
            <th style="width:15%">BRANCH CODE</th>
            <th style="width:15%">ADDRESS</th>
            <th style="width:15%">Mobile Number</th>
            <th style="width:15%">Pan Card</th>
            <th style="width:15%">Assesment Year</th>
            <th style="width:15%">Aadhar Card</th>
            <th style="width:15%">Account No</th>
            <th style="width:15%">Bank Reference No.</th>
            <th style="width:15%">DDA Reference No.</th>
            <th style="width:15%">Date of Receipt to File</th>
            <th style="width:15%">Overall Status</th>
            <th style="width:15%">Status</th>
            <th style="width:20%">Verifier Remark</th>
            <th style="width:15%">SubStatus</th>
            <th style="width:15%">Office CPV Comment</th>
            <th style="width:15%">Uploaded Date</th>
            <th style="width:15%">Uploaded Time</th>
            <th style="width:15%">Date of Visit</th>
            <th style="width:15%">Visit Time</th>
            <th style="width:15%">Agent Name</th>
            <th style="width:15%">Agent Remark</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cases as $case)
        <tr>
            <td>{{ optional($case->getCase)->refrence_number }}</td>
            <td>{{ optional($case->getCase)->applicant_name }}</td>
            <td>{{ optional($case->getFiType)->name }}</td>
            <td>
                {{ optional($case->getBranch)->branch_name ? ' (' . optional($case->getBranch)->branch_name . ')' : '' }}
            </td>
            <td>{{ $case->address }}</td>
            <td>{{ $case->mobile }}</td>
            <td>{{ $case->pan_number ?? ($case->pan_card ?? '-') }}</td>
            <td>{{ $case->assessment_year ?? '-' }}</td>
            <td>{{ $case->aadhar_card ?? ($case->getCase->aadhar_card ?? '-') }}</td>
            <td>{{ $case->accountnumber ?? '-' }}</td>
            <td>{{ $case->bank_name ?? '-' }}</td>
            <td>{{ $case->dd_ref_no ?? '-' }}</td>
            <td>{{ $case->assement_date ?? '-' }}</td>
            <td>{{ $case->overall_status == 2 ? 'Positive' : ($case->overall_status == 3 ? 'Negative' : optional($case->getStatus)->name) }}</td>
            <td>{{ ucfirst(optional($case->getStatus)->name) }}</td>
            <td>{{ $case->supervisor_remarks }}</td>
            <td>{{ ucfirst(optional($case->getCaseStatus)->name) }}</td>
            <td>{{ $case->app_remarks ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($case->created_at)->format('d-m-Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($case->created_at)->format('H:i:s') }}</td>
            <td>{{ $case->date_of_visit }}</td>
            <td>{{ $case->time_of_visit }}</td>
            <td>{{ optional($case->getUser)->name }}</td>
            <td>{{ $case->consolidated_remarks }}</td>
        </tr>
        @endforeach
    </tbody>
</table>