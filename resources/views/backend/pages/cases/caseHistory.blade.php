<table cellspacing="0" rules="all" id="cph1_gvCaseHistory" style="border-width:1px;border-style:solid;width:99%;border-collapse:collapse;">
    <tbody>
        <tr>
            <th scope="col">Status</th>
            <th scope="col">Sub Status</th>
            <th scope="col">Assigned User</th>
            <th scope="col">Remark</th>
            <th scope="col">Created Date</th>
            <th scope="col">Case Comment</th>

        </tr>
        @foreach($caseHistories as $caseHistory)
        <tr>
            <td>{{ $caseHistory->status }}</td>
            <td>{{ $caseHistory->sub_status }}</td>
            <td>{{ $caseHistory->assign_to }}</td>
            <td>{{ $caseHistory->remark }}</td>
            <td>{{ $caseHistory->created_at }}</td>
            <td>{{ $caseHistory->description }}</td>
        </tr>
        @endforeach

    </tbody>
</table>