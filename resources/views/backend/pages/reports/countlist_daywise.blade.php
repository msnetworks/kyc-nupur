<div class="col-12">
<a href="#{{ route('export.casescount') }}" id="export-button" class="btn btn-success float-right" style="margin-bottom: 20px;">
    Export to Excel
</a>
</div>
<table id="dataTable" class="table table-responsive table-bordered border-white text-center">
    <thead class="bg-lightblue text-white">
        <tr>
            <th>Date</th>
            <th>Verifier Name</th>
            <th>Case Count</th>
        </tr>
    </thead>
    <tbody>
        @php
            $dataToShow = isset($sortedData) ? $sortedData : (isset($daywiseData) ? $daywiseData : collect());
        @endphp
        
        @if($dataToShow && $dataToShow->count() > 0)
            @foreach ($dataToShow as $dayData)
                @if(isset($dayData['verifiers']) && count($dayData['verifiers']) > 0)
                    @foreach ($dayData['verifiers'] as $verifier)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($dayData['date'])->format('d-m-Y') }}</td>
                            <td>{{ $verifier['verifier'] }}</td>
                            <td>{{ $verifier['count'] }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($dayData['date'])->format('d-m-Y') }}</td>
                        <td>No Data</td>
                        <td>0</td>
                    </tr>
                @endif
            @endforeach
        @else
            <tr>
                <td colspan="3" class="text-center">No data found for the selected date range</td>
            </tr>
        @endif
    </tbody>
</table>
