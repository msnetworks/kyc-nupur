<div id="uploadCardSummaryValues"
    data-total="{{ $totalSum['total'] ?? 0 }}"
    data-inprogress="{{ $totalSum['inprogressTotal'] ?? 0 }}"
    data-closed="{{ $totalSum['closedTotal'] ?? 0 }}"
    data-positive-verified="{{ $totalSum['positiveVerifiedTotal'] ?? 0 }}"
    data-negative-verified="{{ $totalSum['negativeVerifiedTotal'] ?? 0 }}"
    style="display: none;"></div>

<table id="uploadCardAgentTable" class="table table-bordered text-center" style="width: 100%;">
    <thead class="bg-light text-capitalize">
        <tr>
            <th>Agent</th>
            <th>Total Cases</th>
            <th>Inprogress</th>
            <th>Closed</th>
            <th>Positive Verified</th>
            <th>Negative Verified</th>
        </tr>
    </thead>
    <tbody>
        <tr style="color: #0000FF !important;background-color: #00F000;">
            <td>Total</td>
            <td><a href="{{ ($totalSum['total'] ?? 0) > 0 ? route('admin.upload-cards.index') : 'javascript:;' }}">{{ $totalSum['total'] ?? 0 }}</a></td>
            <td><a href="{{ ($totalSum['inprogressTotal'] ?? 0) > 0 ? route('admin.upload-cards.index', ['status' => 1]) : 'javascript:;' }}">{{ $totalSum['inprogressTotal'] ?? 0 }}</a></td>
            <td><a href="{{ ($totalSum['closedTotal'] ?? 0) > 0 ? route('admin.upload-cards.index', ['status' => 7]) : 'javascript:;' }}">{{ $totalSum['closedTotal'] ?? 0 }}</a></td>
            <td><a href="{{ ($totalSum['positiveVerifiedTotal'] ?? 0) > 0 ? route('admin.upload-cards.index', ['status' => 4]) : 'javascript:;' }}">{{ $totalSum['positiveVerifiedTotal'] ?? 0 }}</a></td>
            <td><a href="{{ ($totalSum['negativeVerifiedTotal'] ?? 0) > 0 ? route('admin.upload-cards.index', ['status' => 5]) : 'javascript:;' }}">{{ $totalSum['negativeVerifiedTotal'] ?? 0 }}</a></td>
        </tr>

        @forelse ($agentWiseCards as $agentWise)
        @php $agentFilter = $agentWise['agentid'] ?: 'unassigned'; @endphp
        <tr>
            <td>{{ $agentWise['agentName'] ?? 'Unknown' }}</td>
            <td><a href="{{ ($agentWise['total'] ?? 0) > 0 ? route('admin.upload-cards.index', ['agent_id' => $agentFilter]) : 'javascript:;' }}">{{ $agentWise['total'] ?? 0 }}</a></td>
            <td><a href="{{ ($agentWise['inprogress'] ?? 0) > 0 ? route('admin.upload-cards.index', ['agent_id' => $agentFilter, 'status' => 1]) : 'javascript:;' }}">{{ $agentWise['inprogress'] ?? 0 }}</a></td>
            <td><a href="{{ ($agentWise['closed'] ?? 0) > 0 ? route('admin.upload-cards.index', ['agent_id' => $agentFilter, 'status' => 7]) : 'javascript:;' }}">{{ $agentWise['closed'] ?? 0 }}</a></td>
            <td><a href="{{ ($agentWise['positive_verified'] ?? 0) > 0 ? route('admin.upload-cards.index', ['agent_id' => $agentFilter, 'status' => 4]) : 'javascript:;' }}">{{ $agentWise['positive_verified'] ?? 0 }}</a></td>
            <td><a href="{{ ($agentWise['negative_verified'] ?? 0) > 0 ? route('admin.upload-cards.index', ['agent_id' => $agentFilter, 'status' => 5]) : 'javascript:;' }}">{{ $agentWise['negative_verified'] ?? 0 }}</a></td>
        </tr>
        @empty
        <tr>
            <td>No upload card records found.</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
        </tr>
        @endforelse
    </tbody>
</table>
