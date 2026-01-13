<table id="dataTable" class="table table-bordered">
    <thead>
        <tr>
            <th valign="middle">Agent</th>
            <th valign="middle">Total</th>
            <th valign="middle">Inprogress</th>
            <th valign="middle">Positive Resolved</th>
            <th valign="middle">Negative Resolved</th>
            <th valign="middle">Positive Verified</th>
            <th valign="middle">Negetive Verified</th>
            <th valign="middle">Hold</th>
            <th valign="middle">Close</th>
            {{-- <th valign="middle">First Time</th>
            <th valign="middle">Last Time</th>
            <th valign="middle">#Visit Count</th>
            <th valign="middle">Location</th> --}}
        </tr>
    </thead>
    <tbody>
        {{-- <tr style="color: #0000FF ! Important;background-color: #00F000;">
            <td>Total</td>

            <td><a href="{{ route('admin.case.caseStatus', ['status' => 'aaa']) }}">{{ $totalSum['total'] ?? 0 }}</a></td>
            <td><a href="{{ route('admin.case.caseStatus', ['status' => 1]) }}">{{ $totalSum['inprogressTotal'] ?? 0 }}</a></td>
            <td><a href="{{ route('admin.case.caseStatus', ['status' => 2]) }}">{{ $totalSum['positive_resolvedTotal'] ?? 0 }} </a></td>
            <td><a href="{{ route('admin.case.caseStatus', ['status' => 3]) }}">{{ $totalSum['negative_resolvedTotal'] ?? 0 }} </a></td>
            <td><a href="{{ route('admin.case.caseStatus', ['status' => 4]) }}">{{ $totalSum['positive_verifiedTotal'] ?? 0 }} </a></td>
            <td><a href="{{ route('admin.case.caseStatus', ['status' => 5]) }}">{{ $totalSum['negative_verifiedTotal'] ?? 0 }} </a></td>
            <td><a href="{{ route('admin.case.caseStatus', ['status' => 6]) }}">{{ $totalSum['holdTotal'] ?? 0 }} </a></td>
            <td><a href="{{ route('admin.case.caseStatus', ['status' => 1]) }}">{{ 0 }} </a></td>
        </tr> --}}
        
        <tr style="color: #0000FF ! Important;background-color: #00F000;">
            <td>Total</td>

            <td><a href="{{ $totalSum['total'] != 0 ? route('admin.case.caseStatus', ['status' => 'aaa','user_id' => 0]) : 'javascript' }}">{{ $totalSum['total'] ?? 0 }}</a></td>
            <td><a href="{{ $totalSum['inprogressTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 1,'user_id' => 0]) : 'javascript:;' }}">{{ $totalSum['inprogressTotal'] ?? 0 }}</a></td>
            <td><a href="{{ $totalSum['positive_resolvedTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 2,'user_id' => 0]) : 'javascript:;' }}">{{ $totalSum['positive_resolvedTotal'] ?? 0 }} </a></td>
            <td><a href="{{ $totalSum['negative_resolvedTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 3,'user_id' => 0]) : 'javascript:;' }}">{{ $totalSum['negative_resolvedTotal'] ?? 0 }} </a></td>
            <td><a href="{{ $totalSum['positive_verifiedTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 4,'user_id' => 0]) : 'javascript:;' }}">{{ $totalSum['positive_verifiedTotal'] ?? 0 }} </a></td>
            <td><a href="{{ $totalSum['negative_verifiedTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 5,'user_id' => 0]) : 'javascript:;' }}">{{ $totalSum['negative_verifiedTotal'] ?? 0 }} </a></td>
            <td><a href="{{ $totalSum['holdTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 6,'user_id' => 0]) : 'javascript:;' }}">{{ $totalSum['holdTotal'] ?? 0 }} </a></td>
            <td><a href="{{ $totalSum['closeTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 7,'user_id' => 0]) : 'javascript:;' }}">{{ $totalSum['closeTotal'] ?? 0 }} </a></td>           
        </tr>
       
        @if($userCount)
        @foreach ($userCount as $userWise)
        <tr>
            <td><a href="{{ route('admin.case.caseStatus', ['status' => 'aaa', 'user_id' => $userWise['agentid']])  }}">{{ $userWise['agentName'] ?? '' }}</a></td>
            <td><a href="{{ $userWise['total'] != 0 ? route('admin.case.caseStatus', ['status' => 'aaa', 'user_id' => $userWise['agentid']]) : 'javascript:;'  }}">{{ $userWise['total'] ?? 0 }}</a></td>
            <td><a href="{{ $userWise['inprogress'] != 0 ? route('admin.case.caseStatus', ['status' => 1, 'user_id' => $userWise['agentid']]) : 'javascript:;'  }}">{{ $userWise['inprogress'] ?? 0 }}</a></td>
            <td><a href="{{ $userWise['positive_resolved'] != 0 ? route('admin.case.caseStatus', ['status' => 2, 'user_id' => $userWise['agentid']]) : 'javascript:;'  }}">{{ $userWise['positive_resolved'] ?? 0 }} </a></td>
            <td><a href="{{ $userWise['negative_resolved'] != 0 ? route('admin.case.caseStatus', ['status' => 3, 'user_id' => $userWise['agentid']]) : 'javascript:;'  }}">{{ $userWise['negative_resolved'] ?? 0 }}</a></td>
            <td><a href="{{ $userWise['positive_verified'] != 0 ? route('admin.case.caseStatus', ['status' => 4, 'user_id' => $userWise['agentid']]) : 'javascript:;'  }}">{{ $userWise['positive_verified'] ?? 0 }}</a></td>
            <td><a href="{{ $userWise['negative_verified'] != 0 ? route('admin.case.caseStatus', ['status' => 5, 'user_id' => $userWise['agentid']]) : 'javascript:;'  }}">{{ $userWise['negative_verified'] ?? 0 }} </a></td>
            <td><a href="{{ $userWise['hold'] != 0 ? route('admin.case.caseStatus', ['status' => 6, 'user_id' => $userWise['agentid']]) : 'javascript:;'  }}">{{ $userWise['hold'] ?? 0 }} </a></td>
            <td><a href="{{ $userWise['close'] != 0 ? route('admin.case.caseStatus', ['status' => 6, 'user_id' => $userWise['agentid']]) : 'javascript:;'  }}">{{ $userWise['close'] ?? 0 }} </a></td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>