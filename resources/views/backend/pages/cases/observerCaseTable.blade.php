<table class="table table-responsive table-bordered table-striped text-center mb-0">
    <thead class="bg-light text-capitalize">
        <tr>
            <th style="width: 40px;"><input type="checkbox" id="selectAllObserver" title="Select all"></th>
            <th>App id</th>
            <th>Internal code</th>
            <th>Branch code</th>
            <th>Name</th>
            <th>Address</th>
            <th>City</th>
            <th>Fi type</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($cases as $case)
        <tr>
            <td><input type="checkbox" class="observerCaseCheckbox" value="{{ $case->id }}"></td>
            <td>{{ $case->case_id ?? '' }}</td>
            <td>{{ $case->getCase->refrence_number ?? '' }}</td>
            <td>{{ optional($case->getCase->getBranch)->branch_code ?? '' }}</td>
            <td>{{ $case->getCase->applicant_name ?? '' }}</td>
            <td>{{ $case->address ?? '' }}</td>
            <td>{{ $case->city ?? '' }}</td>
            <td>{{ $case->getFiType->name ?? '' }}</td>
            <td>{{ get_status($case->status) }}</td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="actionDropdown{{ $case->id }}" data-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="actionDropdown{{ $case->id }}">
                        <li><a class="dropdown-item" style="border-bottom: 1px solid #e3e6f0; padding: 12px 16px;" href="{{ route('admin.case.export.pdf', $case->id) }}" target="_blank" rel="noopener">Download PDF</a></li>
                        <li><a class="dropdown-item observerCpvRemarks" style="border-bottom: 1px solid #e3e6f0; padding: 12px 16px;" href="javascript:;" data-row="{{ $case->id }}">CPV Comment</a></li>
                        <li><a class="dropdown-item" style="padding: 12px 16px;" href="{{ route('admin.case.upload.image', $case->id) }}">Upload Image</a></li>
                    </ul>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="11" class="text-center">No cases found for the selected    criteria.</td>
        </tr>
        @endforelse
    </tbody>
</table>
<div class="pagination-container mt-3">
    {{ $cases->withQueryString()->links() }}
</div>
