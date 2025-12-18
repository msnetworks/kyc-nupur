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
            <?php if(auth()->check()): ?>
                    <?php if(auth()->user()->role != 'Bank'): ?>
            <th style="padding: 5px;">Agent</th>
            <?php endif; ?>
            <?php endif; ?>
            <th style="padding: 5px;">Created By</th>
            <th style="padding: 5px;">Status</th>
            <th style="padding: 5px;">SubStatus</th>
            <th style="padding: 5px 40px 5px 40px;">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $cases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $case): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="<?php echo e($case->getCase?->getCreatedBy?->role == 'Bank' ? 'bg-warning' : 
            (\Carbon\Carbon::now()->greaterThan($case->tat_end) ? 'bg-danger text-white' : '')); ?>">

            <td><input type="checkbox" class="selectRow" value="<?php echo e($case->id); ?>"></td>
            <td><?php echo e(($cases->currentPage() - 1) * $cases->perPage() + $loop->iteration); ?></td>
            <td><?php echo e($case->getCase->refrence_number ?? ''); ?></td>
            <td><?php echo e(isset($case->getCase->getBranch) ? optional($case->getCase->getBranch)->branch_code. "( ". optional($case->getCase->getBranch)->branch_name . ")" : ''); ?></td>
            <td><?php echo e($case->getCase->applicant_name ?? $case->applicant_name); ?></td>
            <td><?php echo e($case->mobile ?? ''); ?></td>
            <td style="width: 15%"><?php echo e($case->address ?? ''); ?>&nbsp;&nbsp;
                <?php echo e(isset($case->pincode) && $case->pincode != 0  ? 'Pincode: '.$case->pincode : ''); ?>&nbsp;&nbsp;
                <?php echo e(isset($case->pan_number) ? 'Pan Card: '.$case->pan_number : ''); ?>&nbsp;&nbsp;
                <?php echo e(isset($case->assessment_year) ? 'Assesment Year: '.$case->assessment_year : ''); ?>&nbsp;&nbsp;
                <?php echo e(isset($case->bank_name) ? 'Verify Bank Name '.$case->bank_name : ''); ?>&nbsp;&nbsp;
                <?php echo e(isset($case->accountnumber) ? 'Account No. '.$case->accountnumber : ''); ?>


            </td>
            <td><?php echo e($case->city ?? ''); ?></td>
            <?php
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
            ?>
            <td><?php echo e($columnValue); ?></td>
            <td><?php echo e(isset($case->tat_start) ? 'Start: '. humanReadableDate($case->tat_start) . ' End : '.humanReadableDate($case->tat_end) :''); ?></td>
            <?php if(auth()->check()): ?>
                    <?php if(auth()->user()->role != 'Bank'): ?>
            <td><?php echo e($case->getUser->name ?? ''); ?></td>
            <?php endif; ?>
            <?php endif; ?>
            <td><?php echo e($case->getCase?->getCreatedBy?->name ?? ''); ?></td>
            <td><?php echo e(get_status($case->status)); ?></td>
            <td><?php echo e($case->getCaseStatus->name ?? ''); ?></td>
            <td>
                <?php if(auth()->check()): ?>
                    <?php if(auth()->user()->role != 'Bank'): ?>
                        <?php if(isset($assign) && !$assign): ?>
                        <a href="<?php echo e(route('admin.case.viewCase', $case->id)); ?>"><img src="<?php echo e(URL::asset('backend/assets/images/icons/user.png')); ?>" title="View"></img></a>
                        <?php endif; ?>

                        <?php if(isset($assign) && $assign): ?>
                        <a href="<?php echo e(route('admin.case.viewCaseAssign', $case->id)); ?>"><img src="<?php echo e(URL::asset('backend/assets/images/icons/user.png')); ?>" title="View"></img></a>
                        <?php endif; ?>
 
                        <a href="<?php echo e(route('admin.case.editCase', $case->id)); ?>"><img src="<?php echo e(URL::asset('backend/assets/images/icons/edit.png')); ?>" title="Edit"></img></a>
                        <a href="javascript:;" data-row="<?php echo e($case->id); ?>" data-target="exampleModal" class="assignSingle"><img src="<?php echo e(URL::asset('backend/assets/images/icons/stock_task-assigned-to.png')); ?>" title="Assign"></img></a>
                        <?php if($case->status != '7'): ?>
                        <a href="javascript:;" data-fitype="<?php echo e($case->fi_type_id); ?>" data-row="<?php echo e($case->id); ?>" data-target="resolveCaseModel" class="resolveCase"><img src="<?php echo e(URL::asset('backend/assets/images/icons/change_status.png')); ?>" title="Resolve"></img></a>
                        <?php endif; ?>
                        <?php if($case->status != '7'): ?>
                            <a href="javascript:;" data-row="<?php echo e($case->id); ?>" data-target="verifiedCaseModel" class="verifiedCase"><img src="<?php echo e(URL::asset('backend/assets/images/icons/checkbox.png')); ?>" title="Verified"></img></a>
                        <?php endif; ?>
                        <a href="javascript:;" data-row="<?php echo e($case->id); ?>" data-target="consolidatedRemarksModel" class="consolidatedRemarks"><img src="<?php echo e(URL::asset('backend/assets/images/icons/page_white_text_width.png')); ?>" title="Consolidated remarks"></img></a>
                        
                        <a href="<?php echo e(route('admin.case.upload.image', $case->id)); ?>"><img src="<?php echo e(URL::asset('backend/assets/images/icons/uploadImage.png')); ?>" title="Upload"></img></a>
                        <a href="<?php echo e(route('admin.case.original.image', $case->id)); ?>"><img style="width: 16px; height: 16px;" src="<?php echo e(URL::asset('backend/assets/images/icons/image_icon.png')); ?>" title="Original Images"></img></a>
                        <?php if($case->status != '7'): ?>
                            <a href="javascript:;" data-row="<?php echo e($case->id); ?>" class="caseClose"><img src="<?php echo e(URL::asset('backend/assets/images/icons/Close.gif')); ?>" title="Case close"></img></a>
                        <?php endif; ?>
                        <?php endif; ?>
                       <?php if(in_array($case->fi_type_id , [7,8, 14])): ?>
                       <a href="<?php echo e(route('admin.case.viewForm',$case->id)); ?>" target="_blank" class="view Form" data-row="<?php echo e($case->id); ?>"><img src="<?php echo e(URL::asset('backend/assets/images/icons/verified_cases.png')); ?>" title="View Form"></img></a>
                       <?php endif; ?>
                    <?php if(auth()->user()->role != 'Bank'): ?>
                        <a href="javascript:;" data-row="<?php echo e($case->id); ?>" class="cloneCase"><img src="<?php echo e(URL::asset('backend/assets/images/icons/add.png')); ?>" title="clone case"></img></a>
                        <?php if(in_array($case->fi_type_id , [7,8, 14])): ?>
                        <a href="javascript:;" data-target="viewFormEditModel" class="viewFormEdit" data-row="<?php echo e($case->id); ?>"><img src="<?php echo e(URL::asset('backend/assets/images/icons/edit.png')); ?>" title="View Form Edit"></img></a>
                        <?php endif; ?>
                        <a href="javascript:;" data-row="<?php echo e($case->id); ?>" class="HoldCase"><img src="<?php echo e(URL::asset('backend/assets/images/icons/HoldCase.png')); ?>" title="Hold case"></img></a>
                    

                        <?php if(auth()->user()->role == 'superadmin'): ?>
                            <a href="javascript:;" data-row="<?php echo e($case->id); ?>" class="CaseDelete"><img src="<?php echo e(URL::asset('backend/assets/images/icons/delete.png')); ?>" title="Case Delete"></img></a>
                            <a href="javascript:;" data-row="<?php echo e($case->getCase->id); ?>" data-bank_id="<?php echo e($case->getCase->bank_id); ?>" data-ref_no="<?php echo e($case->getCase->refrence_number ?? ''); ?>"  data-app_name="<?php echo e($case->getCase->applicant_name ?? $case->applicant_name); ?>" class="productModel" data-target="productModel"><img src="<?php echo e(URL::asset('backend/assets/images/icons/edit.png')); ?>" title="Case Product Update"></img></a>
                            <a href="javascript:;" data-row="<?php echo e($case->id); ?>" data-ref_no="<?php echo e($case->getCase->refrence_number ?? ''); ?>"  data-app_name="<?php echo e($case->getCase->applicant_name ?? $case->applicant_name); ?>" class="fiTypeModel" data-target="fiTypeModel"><img src="<?php echo e(URL::asset('backend/assets/images/icons/edit.png')); ?>" title="Case Fi Type Update"></img></a>
                            <a href="javascript:;" data-row="<?php echo e($case->getCase->id); ?>" data-ref_no="<?php echo e($case->getCase->refrence_number ?? ''); ?>"  data-app_name="<?php echo e($case->getCase->applicant_name ?? $case->applicant_name); ?>" class="branchChangeModel" data-target="branchChangeModel"><img src="<?php echo e(URL::asset('backend/assets/images/icons/user_edit.png')); ?>" title="Change Case Fi Type Update"></img></a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('admin.case.zip.download', $case->id)); ?>"><img src="<?php echo e(URL::asset('backend/assets/images/icons/downloads.png')); ?>" title="Download Zip"></img></a>
                        
                        <?php endif; ?>
                    <?php endif; ?>
                <a href="javascript:;" data-target="caseReinitiatesModel" data-row="<?php echo e($case->id); ?>" class="caseReinitiates"><img src="<?php echo e(URL::asset('backend/assets/images/icons/page_white_text_width.png')); ?>" title="Reinitiates Case"></img></a>
                <?php if(in_array($case->status , [2, 3, 4, 5, 7]) && auth()->user()->role != 'Bank'): ?>
                    <a href="<?php echo e(route('admin.case.export.pdf', $case->id)); ?>"><img src="<?php echo e(URL::asset('backend/assets/images/icons/Pdf.png')); ?>" title="Download PDF"></img></a>
                <?php endif; ?>
                <?php if(in_array($case->status , [4, 5, 7]) && auth()->user()->role == 'Bank'): ?>
                    <a href="<?php echo e(route('admin.case.export.pdf', $case->id)); ?>"><img src="<?php echo e(URL::asset('backend/assets/images/icons/Pdf.png')); ?>" title="Download PDF"></img></a>
                <?php endif; ?>
                <?php if(auth()->user()->role == 'superadmin'): ?>
                    <a href="javascript:;" data-row="<?php echo e($case->getCase->id); ?>" data-ref_no="<?php echo e($case->getCase->refrence_number ?? ''); ?>" data-app_name="<?php echo e($case->getCase->applicant_name ?? $case->applicant_name); ?>" data-geo_limit="<?php echo e($case->getCase->geo_limit ?? ''); ?>" class="geoLimitModel" data-target="geoLimitModel"><img src="<?php echo e(URL::asset('backend/assets/images/icons/edit.png')); ?>" title="Update Geo Limit"></img></a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<br><br>
<div class="pagination-container">
    <?php echo e($cases->links()); ?>

</div><?php /**PATH C:\laragon\www\kyc-live\resources\views/backend/pages/cases/caseTable.blade.php ENDPATH**/ ?>