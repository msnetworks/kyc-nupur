

<?php $__env->startSection('title'); ?>
Branch Create - Admin Panel
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .form-check-label {
        text-transform: capitalize;
    }
</style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('admin-content'); ?>

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Branch Create</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                    <li><a href="<?php echo e(route('admin.banks.index')); ?>">All Branches</a></li>
                    <li><span>Create Bank</span></li>
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
                    <h4 class="header-title">Create New Branch</h4>
                    <?php echo $__env->make('backend.layouts.partials.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <form action="<?php echo e(route('admin.branchcodes.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Bank <span class="text-danger">*</span></label>
                                <select class="custom-select selectBank" name="bank_id" id="selectBank" required>
                                    <option value="">--Select Option--</option>
                                    <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($bank['id']); ?>"><?php echo e($bank['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Branch Code</label>
                                <input type="text" class="form-control" id="branch_code" name="branch_code" placeholder="Enter Bank Code">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Branch Name</label>
                                <input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="Enter Bank Code">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Area</label>
                                <input type="text" class="form-control" id="area" name="area" placeholder="Enter Bank Code">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kyc-live\resources\views/backend/pages/branchcode/create.blade.php ENDPATH**/ ?>