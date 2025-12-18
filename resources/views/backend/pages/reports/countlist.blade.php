
<div class="col-12">
<a href="#{{ route('export.casescount') }}" id="export-button" class="btn btn-success float-right" style="margin-bottom: 20px;">
    Export to Excel
</a>
<!-- Excel Export Button -->
{{-- <button id="exportExcelBtn" class="btn btn-success float-right">Export to Excel</button> --}}
</div>
<table id="dataTable" class="table table-responsive table-bordered border-white text-center">
    <thead class="bg-lightblue text-white">
        <tr>
            <th class="w-50">Verifier Name</th>
            <th class="w-50">Case Count</th>
            {{-- <th class="w-50">Positive Verified</th>
            <th class="w-50">Negative Verified</th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($userCasesCount as $case)
            <tr>
                <td>{{ $case['user'] }}</td>
                <td>{{ $case['total_cases'] }}</td>
                {{-- <td>{{ $case['positive_verified'] }}</td>
                <td>{{ $case['negative_verified'] }}</td> --}}
            </tr>
        @endforeach
    </tbody>
</table>