<table class="table table-responsive table-bordered table-striped text-center" id="exampleTable">
    <thead class="bg-light text-capitalize">
        <tr>
            <th style="padding: 5px;"><input type="checkbox" id="selectAll"></th>
            <th style="padding: 5px;">App Id</th>
            <th style="padding: 5px;">Internal Code</th>
            <th style="padding: 5px;">Branch Code</th>
            <th style="padding: 5px;">Name</th>
            <th style="padding: 5px;">Mobile Number</th>
            <th style="padding: 5px;">Address</th>
            <th style="padding: 5px;">City</th>
            <th style="padding: 5px;">FIType</th>
            <th style="padding: 5px;">Tat Start & End</th>
            @if(auth()->check())
                    @if(auth()->user()->role != 'Bank')
            <th style="padding: 5px;">Agent</th>
            @endif
            @endif
            <th style="padding: 5px;">Created By</th>
            <th style="padding: 5px;">Status</th>
            <th style="padding: 5px;">SubStatus</th>
            <th style="padding: 5px 40px 5px 40px;">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cases as $case)
        <tr class="{{ $case->getCase?->getCreatedBy?->role == 'Bank' ? 'bg-warning' : 
            (\Carbon\Carbon::now()->greaterThan($case->tat_end) ? 'bg-danger text-white' : '') }}">

            <td><input type="checkbox" class="selectRow" value="{{ $case->id }}"></td>
            <td>{{ ($cases->currentPage() - 1) * $cases->perPage() + $loop->iteration }}</td>
            <td>{{ $case->getCase->refrence_number ?? '' }}</td>
            <td>{{ isset($case->getCase->getBranch) ? optional($case->getCase->getBranch)->branch_code. "( ". optional($case->getCase->getBranch)->branch_name . ")" : '' }}</td>
            <td>{{ $case->getCase->applicant_name ?? $case->applicant_name }}</td>
            <td>{{ $case->mobile ?? '' }}</td>
            <td style="width: 15%">{{ $case->address ?? '' }}&nbsp;&nbsp;
                {{ isset($case->pincode) && $case->pincode != 0  ? 'Pincode: '.$case->pincode : '' }}&nbsp;&nbsp;
                {{ isset($case->pan_number) ? 'Pan Card: '.$case->pan_number : '' }}&nbsp;&nbsp;
                {{ isset($case->assessment_year) ? 'Assesment Year: '.$case->assessment_year : '' }}&nbsp;&nbsp;
                {{ isset($case->bank_name) ? 'Verify Bank Name '.$case->bank_name : '' }}&nbsp;&nbsp;
                {{ isset($case->accountnumber) ? 'Account No. '.$case->accountnumber : '' }}

            </td>
            <td>{{ $case->city ?? '' }}</td>
            @php
            $fiType = $case->getFiType->name ?? null;
            $bank = $case->getCase->getBank->name ?? null;
            $product = $case->getCase->getProduct->name ?? null;

            $columnValue = null;

            if($bank){
            $columnValue = $bank;
            }

            if($product){
            if($columnValue){
            $columnValue .= ' ';
            }
            $columnValue .= $product;
            }

            if($fiType){
            if($columnValue){
            $columnValue .= ' ';
            }
            $columnValue .= $fiType;
            }
            @endphp
            <td>{{ $columnValue }}</td>
            <td>{{ isset($case->tat_start) ? 'Start: '. humanReadableDate($case->tat_start) . ' End : '.humanReadableDate($case->tat_end) :'' }}</td>
            @if(auth()->check())
                    @if(auth()->user()->role != 'Bank')
            <td>{{ $case->getUser->name ?? '' }}</td>
            @endif
            @endif
            <td>{{  $case->getCase?->getCreatedBy?->name ?? '' }}</td>
            <td>{{ get_status($case->status) }}</td>
            <td>{{ $case->getCaseStatus->name ?? '' }}</td>
            <td>
                @if(auth()->check())
                    @if(auth()->user()->role != 'Bank')
                        @if(isset($assign) && !$assign)
                        <a href="{{ route('admin.case.viewCase', $case->id) }}"><img src="{{URL::asset('backend/assets/images/icons/user.png')}}" title="View"></img></a>
                        @endif

                        @if(isset($assign) && $assign)
                        <a href="{{ route('admin.case.viewCaseAssign', $case->id) }}"><img src="{{URL::asset('backend/assets/images/icons/user.png')}}" title="View"></img></a>
                        @endif
 
                        <a href="{{ route('admin.case.editCase', $case->id) }}"><img src="{{URL::asset('backend/assets/images/icons/edit.png')}}" title="Edit"></img></a>
                        <a href="javascript:;" data-row="{{ $case->id }}" data-target="exampleModal" class="assignSingle"><img src="{{URL::asset('backend/assets/images/icons/stock_task-assigned-to.png')}}" title="Assign"></img></a>
                        @if($case->status != '7')
                        <a href="javascript:;" data-fitype="{{ $case->fi_type_id }}" data-row="{{ $case->id }}" data-target="resolveCaseModel" class="resolveCase"><img src="{{URL::asset('backend/assets/images/icons/change_status.png')}}" title="Resolve"></img></a>
                        @endif
                        @if($case->status != '7')
                            <a href="javascript:;" data-row="{{ $case->id }}" data-target="verifiedCaseModel" class="verifiedCase"><img src="{{URL::asset('backend/assets/images/icons/checkbox.png')}}" title="Verified"></img></a>
                        @endif
                        <a href="javascript:;" data-row="{{ $case->id }}" data-target="consolidatedRemarksModel" class="consolidatedRemarks"><img src="{{URL::asset('backend/assets/images/icons/page_white_text_width.png')}}" title="Consolidated remarks"></img></a>
                        {{-- <a href="javascript:;" data-row="{{ $case->id }}" data-target="caseHistoryModel" class="caseHistory"><img src="{{URL::asset('backend/assets/images/icons/history1.png')}}" title="Case History"></img></a> --}}
                        <a href="{{ route('admin.case.upload.image', $case->id) }}"><img src="{{URL::asset('backend/assets/images/icons/uploadImage.png')}}" title="Upload"></img></a>
                        <a href="{{ route('admin.case.original.image', $case->id) }}"><img style="width: 16px; height: 16px;" src="{{URL::asset('backend/assets/images/icons/image_icon.png')}}" title="Original Images"></img></a>
                        @if($case->status != '7')
                            <a href="javascript:;" data-row="{{ $case->id }}" class="caseClose"><img src="{{URL::asset('backend/assets/images/icons/Close.gif')}}" title="Case close"></img></a>
                        @endif
                        @endif
                       @if(in_array($case->fi_type_id , [7,8, 14]) && auth()->user()->role != 'Bank' && $case->getCase->bank_id != 12)
                        <a href="{{ route('admin.case.viewForm',$case->id)}}" target="_blank" class="viewForm" data-row="{{ $case->id }}"><img src="{{URL::asset('backend/assets/images/icons/verified_cases.png')}}" title="View Form"></img></a>
                       @endif
                    @if(auth()->user()->role != 'Bank')
                        <a href="javascript:;" data-row="{{ $case->id }}" class="cloneCase"><img src="{{URL::asset('backend/assets/images/icons/add.png')}}" title="clone case"></img></a>
                        @if(in_array($case->fi_type_id , [7,8, 14]))
                        <a href="javascript:;" data-target="viewFormEditModel" class="viewFormEdit" data-row="{{ $case->id }}"><img src="{{URL::asset('backend/assets/images/icons/edit.png')}}" title="View Form Edit"></img></a>
                        @endif
                        <a href="javascript:;" data-row="{{ $case->id }}" class="HoldCase"><img src="{{URL::asset('backend/assets/images/icons/HoldCase.png')}}" title="Hold case"></img></a>
                    

                        @if(auth()->user()->role == 'superadmin')
                            <a href="javascript:;" data-row="{{ $case->id }}" class="CaseDelete"><img src="{{URL::asset('backend/assets/images/icons/delete.png')}}" title="Case Delete"></img></a>
                            <a href="javascript:;" data-row="{{ $case->getCase->id }}" data-bank_id="{{ $case->getCase->bank_id }}" data-ref_no="{{ $case->getCase->refrence_number ?? '' }}"  data-app_name="{{ $case->getCase->applicant_name ?? $case->applicant_name }}" class="productModel" data-target="productModel"><img src="{{URL::asset('backend/assets/images/icons/edit.png')}}" title="Case Product Update"></img></a>
                            <a href="javascript:;" data-row="{{ $case->id }}" data-ref_no="{{ $case->getCase->refrence_number ?? '' }}"  data-app_name="{{ $case->getCase->applicant_name ?? $case->applicant_name }}" class="fiTypeModel" data-target="fiTypeModel"><img src="{{URL::asset('backend/assets/images/icons/edit.png')}}" title="Case Fi Type Update"></img></a>
                            <a href="javascript:;" data-row="{{ $case->getCase->id }}" data-ref_no="{{ $case->getCase->refrence_number ?? '' }}"  data-app_name="{{ $case->getCase->applicant_name ?? $case->applicant_name }}" class="branchChangeModel" data-target="branchChangeModel"><img src="{{URL::asset('backend/assets/images/icons/user_edit.png')}}" title="Change Case Fi Type Update"></img></a>
                        @endif
                        <a href="{{ route('admin.case.zip.download', $case->id) }}"><img src="{{URL::asset('backend/assets/images/icons/downloads.png')}}" title="Download Zip"></img></a>
                        {{-- <a href="{{ route('admin.case.dedup-case', $case->id) }}" target="__blank"><img src="{{URL::asset('backend/assets/images/icons/view.png')}}" title="show orignal case"></img></a> --}}
                        @endif
                    @endif
                <a href="javascript:;" data-target="caseReinitiatesModel" data-row="{{ $case->id }}" class="caseReinitiates"><img src="{{URL::asset('backend/assets/images/icons/page_white_text_width.png')}}" title="Reinitiates Case"></img></a>
                @if(in_array($case->status , [2, 3, 4, 5, 7]) && auth()->user()->role != 'Bank')
                    <a href="{{ route('admin.case.export.pdf', $case->id) }}"><img src="{{URL::asset('backend/assets/images/icons/Pdf.png')}}" title="Download PDF"></img></a>
                @endif
                @if(in_array($case->status , [4, 5, 7]) && auth()->user()->role == 'Bank')
                    <a href="{{ route('admin.case.export.pdf', $case->id) }}"><img src="{{URL::asset('backend/assets/images/icons/Pdf.png')}}" title="Download PDF"></img></a>
                @endif
                @if(auth()->user()->role == 'superadmin')
                    <a href="javascript:;" data-row="{{ $case->getCase->id }}" data-ref_no="{{ $case->getCase->refrence_number ?? '' }}" data-app_name="{{ $case->getCase->applicant_name ?? $case->applicant_name }}" data-geo_limit="{{ $case->getCase->geo_limit ?? '' }}" class="geoLimitModel" data-target="geoLimitModel"><img src="{{URL::asset('backend/assets/images/icons/edit.png')}}" title="Update Geo Limit"></img></a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<br><br>
<div class="pagination-container">
    {{ $cases->links() }}
</div>