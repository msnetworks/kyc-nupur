

<?php $__env->startSection('title'); ?>
banks - Admin Panel
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<!-- Start datatable css -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
<?php $__env->stopSection(); ?>


<?php $__env->startSection('admin-content'); ?>

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Banks</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                    <li><span>All Banks</span></li>
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
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title float-left">Banks List</h4>
                    <?php if(auth()->check()): ?>
                        <?php if(auth()->user()->can('bank.create')): ?>
                            <p class="float-right mb-2">
                                <a class="btn btn-primary text-white" href="<?php echo e(route('admin.banks.create')); ?>">Create New banks</a>
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                    <div class="data-tables">
                        <?php echo $__env->make('backend.layouts.partials.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <table id="dataTable" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="5%">Sl</th>
                                    <th width="10%">Name</th>
                                    <th width="10%">Code</th>
                                    
                                            <th width="15%">Action</th>
                                        
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-left"><?php echo e($loop->index+1); ?></td>
                                    <td class="text-left"><?php echo e($bank->name); ?></td>
                                    <td class="text-left"><?php echo e($bank->branch_code); ?></td>
                                    
                                    <td>
                                        
                                                <a class="btn btn-success text-white" href="<?php echo e(route('admin.banks.edit', $bank->id)); ?>">Edit</a>

                                                
                                            
                                        <?php if(auth()->check()): ?>
                                            <?php if(auth()->user()->can('bank.delete')): ?>
                                                
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('scripts'); ?>
<!-- Start datatable js -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

<script>
    /*================================
        datatable active
        ==================================*/
    if ($('#dataTable').length) {
        $('#dataTable').DataTable({
            responsive: true
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kyc-live\resources\views/backend/pages/banks/index.blade.php ENDPATH**/ ?>