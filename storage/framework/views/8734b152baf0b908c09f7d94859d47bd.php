

<?php $__env->startSection('title'); ?>
Dashboard Page - Admin Panel
<?php $__env->stopSection(); ?>


<?php $__env->startSection('admin-content'); ?>

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Dashboard</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="index.html">Home</a></li>
                    <li><span>Dashboard</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            <?php echo $__env->make('backend.layouts.partials.logout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">



    <div class="row mt-4">
        <div class="col-lg-10 col-md-10">
            <?php if(Auth::guard('admin')->user()->role !== 'Bank'): ?>
            <form method="" action="" id="filterForm">
                <div class="row">
                    
                    <div class="form-group col-md-3">
                        <label for="agent">Agent</label>
                        <select name="agent" id="agent" class="form-control">
                            <option selected="selected" value="">--Select--</option>
                            <?php if($agentLists): ?>
                            <?php $__currentLoopData = $agentLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($id); ?>"><?php echo e($user); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="fromDate">From Date</label>
                        <input type="date" class="form-control" id="fromDate" name="fromDate">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="toDate">To Date</label>
                        <input type="date" class="form-control" id="toDate" name="toDate">
                    </div>
                    <div class="form-group col-md-3">
                        <input type="submit" class="form-control btn btn-sm btn-primary mt-4" id="submit" name="submit" value="Filter">
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-2 col-md-2">
            <p class="mt-3"><strong>Unassigned Cases</strong>: <a href="<?php echo e(route('admin.case.caseStatus', ['status' => '0'])); ?>"><?php echo e($total_Unassigned); ?> </a> </p>
            <p class="mt-3"><strong>Dedup Cases</strong>: <a href="<?php echo e(route('admin.case.caseStatus', ['status' => '8'])); ?>"><?php echo e($total_dedup); ?> </a> </p>
        </div>
        <?php endif; ?>
    </div>
    <div class="row mt-4">
        <div class="col-md-12 col-lg-12" id="recordtable">
            <table id="dataTable" class="table table-responsive table-bordered">
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
                        <?php if(Auth::guard('admin')->user()->role == 'Bank'): ?>
                        
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if(Auth::guard('admin')->user()->role !== 'Bank'): ?>
                    <tr style="color: #0000FF ! Important;background-color: #00F000;">
                        <td>Total</td>

                        <td><a href="<?php echo e($totalSum['total'] != 0 ? route('admin.case.caseStatus', ['status' => 'aaa','user_id' => 0]) : 'javascript'); ?>"><?php echo e($totalSum['total'] ?? 0); ?></a></td>
                        <td><a href="<?php echo e($totalSum['inprogressTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 1,'user_id' => 0]) : 'javascript:;'); ?>"><?php echo e($totalSum['inprogressTotal'] ?? 0); ?></a></td>
                        <td><a href="<?php echo e($totalSum['positive_resolvedTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 2,'user_id' => 0]) : 'javascript:;'); ?>"><?php echo e($totalSum['positive_resolvedTotal'] ?? 0); ?> </a></td>
                        <td><a href="<?php echo e($totalSum['negative_resolvedTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 3,'user_id' => 0]) : 'javascript:;'); ?>"><?php echo e($totalSum['negative_resolvedTotal'] ?? 0); ?> </a></td>
                        <td><a href="<?php echo e($totalSum['positive_verifiedTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 4,'user_id' => 0]) : 'javascript:;'); ?>"><?php echo e($totalSum['positive_verifiedTotal'] ?? 0); ?> </a></td>
                        <td><a href="<?php echo e($totalSum['negative_verifiedTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 5,'user_id' => 0]) : 'javascript:;'); ?>"><?php echo e($totalSum['negative_verifiedTotal'] ?? 0); ?> </a></td>
                        <td><a href="<?php echo e($totalSum['holdTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 6,'user_id' => 0]) : 'javascript:;'); ?>"><?php echo e($totalSum['holdTotal'] ?? 0); ?> </a></td>
                        <td><a href="<?php echo e($totalSum['closeTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 7,'user_id' => 0]) : 'javascript:;'); ?>"><?php echo e($totalSum['closeTotal'] ?? 0); ?> </a></td>
                        
                    </tr>

                    <?php if($userCount): ?>
                    <?php $__currentLoopData = $userCount; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userWise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><a href="<?php echo e($userWise['agentName'] != '' ?  route('admin.case.caseStatus', ['status' => 'aaa', 'user_id' => $userWise['agentid']]) : 'javascript:;'); ?>"><?php echo e($userWise['agentName'] ?? ''); ?></a></td>
                        <td><a href="<?php echo e($userWise['total'] != 0 ?  route('admin.case.caseStatus', ['status' => 'aaa', 'user_id' => $userWise['agentid']]) : 'javascript:;'); ?>"><?php echo e($userWise['total'] ?? 0); ?></a></td>
                        <td><a href="<?php echo e($userWise['inprogress'] != 0 ?  route('admin.case.caseStatus', ['status' => 1, 'user_id' => $userWise['agentid']]) : 'javascript:;'); ?>"><?php echo e($userWise['inprogress'] ?? 0); ?></a></td>
                        <td><a href="<?php echo e($userWise['positive_resolved'] != 0 ?  route('admin.case.caseStatus', ['status' => 2, 'user_id' => $userWise['agentid']]) : 'javascript:;'); ?>"><?php echo e($userWise['positive_resolved'] ?? 0); ?> </a></td>
                        <td><a href="<?php echo e($userWise['negative_resolved'] != 0 ?  route('admin.case.caseStatus', ['status' => 3, 'user_id' => $userWise['agentid']]) : 'javascript:;'); ?>"><?php echo e($userWise['negative_resolved'] ?? 0); ?></a></td>
                        <td><a href="<?php echo e($userWise['positive_verified'] != 0 ?  route('admin.case.caseStatus', ['status' => 4, 'user_id' => $userWise['agentid']]) : 'javascript:;'); ?>"><?php echo e($userWise['positive_verified'] ?? 0); ?></a></td>
                        <td><a href="<?php echo e($userWise['negative_verified'] != 0 ?  route('admin.case.caseStatus', ['status' => 5, 'user_id' => $userWise['agentid']]) : 'javascript:;'); ?>"><?php echo e($userWise['negative_verified'] ?? 0); ?> </a></td>
                        <td><a href="<?php echo e($userWise['hold'] != 0 ?  route('admin.case.caseStatus', ['status' => 6, 'user_id' => $userWise['agentid']]) : 'javascript:;'); ?>"><?php echo e($userWise['hold'] ?? 0); ?> </a></td>
                        <td><a href="<?php echo e($userWise['close'] != 0 ?  route('admin.case.caseStatus', ['status' => 7, 'user_id' => $userWise['agentid']]) : 'javascript:;'); ?>"><?php echo e($userWise['close'] ?? 0); ?></a></td>
                        
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    
                    <?php endif; ?>
                    <?php if(Auth::guard('admin')->user()->role == 'Bank'): ?>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userWise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <tr>
                            <td><a href="<?php echo e($userWise->agent_name != '' ?  route('admin.case.caseStatus', ['status' => 'aaa', 'user_id' => $userWise->user_id]) : 'javascript:;'); ?>"><?php echo e($userWise->agent_name != null  ? $userWise->agent_name : 'Unassigned'); ?></a></td>
                            <td><a href="<?php echo e($userWise->total_cases != 0 ?  route('admin.case.caseStatus', ['status' => 'aaa', 'user_id' => $userWise->user_id]) : 'javascript:;'); ?>"><?php echo e($userWise->total_cases ?? 0); ?></a></td>
                            <td><a href="<?php echo e($userWise->inprogress != 0 ?  route('admin.case.caseStatus', ['status' => 1, 'user_id' => $userWise->user_id]) : 'javascript:;'); ?>"><?php echo e($userWise->inprogress ?? 0); ?></a></td>
                            <td><a href="<?php echo e($userWise->positive_resolve != 0 ?  route('admin.case.caseStatus', ['status' => 2, 'user_id' => $userWise->user_id]) : 'javascript:;'); ?>"><?php echo e($userWise->positive_resolve ?? 0); ?> </a></td>
                            <td><a href="<?php echo e($userWise->negative_resolve != 0 ?  route('admin.case.caseStatus', ['status' => 3, 'user_id' => $userWise->user_id]) : 'javascript:;'); ?>"><?php echo e($userWise->negative_resolve ?? 0); ?></a></td>
                            <td><a href="<?php echo e($userWise->positive_verified != 0 ?  route('admin.case.caseStatus', ['status' => 4, 'user_id' => $userWise->user_id]) : 'javascript:;'); ?>"><?php echo e($userWise->positive_verified ?? 0); ?></a></td>
                            <td><a href="<?php echo e($userWise->negative_verified != 0 ?  route('admin.case.caseStatus', ['status' => 5, 'user_id' => $userWise->user_id]) : 'javascript:;'); ?>"><?php echo e($userWise->negative_verified ?? 0); ?> </a></td>
                            <td><a href="<?php echo e($userWise->hold != 0 ?  route('admin.case.caseStatus', ['status' => 6, 'user_id' => $userWise->user_id]) : 'javascript:;'); ?>"><?php echo e($userWise->hold ?? 0); ?> </a></td>
                            <td><a href="<?php echo e($userWise->closed != 0 ?  route('admin.case.caseStatus', ['status' => 7, 'user_id' => $userWise->user_id]) : 'javascript:;'); ?>"><?php echo e($userWise->closed ?? 0); ?></a></td>
                            
                            
                        </tr>
                        
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#filterForm').on('submit', function(event) {
                event.preventDefault(); 
    
                // Validation to ensure at least one filter is selected
                if ($('#agent').val() === '' && $('#fromDate').val() === '' && $('#toDate').val() === '') {
                    alert('Please select a date range or agent to filter.');
                    return false;
                }
    
                // Prepare form data
                var formData = {
                    _token: "<?php echo e(csrf_token()); ?>", // Ensure CSRF token is included
                    agent: $('#agent').val(),
                    fromDate: $('#fromDate').val(),
                    toDate: $('#toDate').val(),
                };
    
                // Send AJAX request
                $.ajax({
                    url: "<?php echo e(route('admin.filter')); ?>", // Route to handle the filter logic
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        // Update the table with the filtered results
                        $('#recordtable').html(response);
                    },
                    error: function(xhr, status, error) {
                        // Handle errors gracefully
                        console.error("Error:", xhr.responseText || status);
                        alert("An error occurred while fetching the records. Please try again.");
                    }
                });
            });
        });
    </script>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kyc-live\resources\views/backend/pages/dashboard/index.blade.php ENDPATH**/ ?>