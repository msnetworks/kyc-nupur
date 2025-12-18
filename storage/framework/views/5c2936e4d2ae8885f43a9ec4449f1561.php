

<?php $__env->startSection('title'); ?>
Bank Edit - Admin Panel
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
                <h4 class="page-title pull-left">Product Create</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                    <li><a href="<?php echo e(route('admin.banks.index')); ?>">All banks</a></li>
                    <li><span>Edit Bank - <?php echo e($bank->name); ?></span></li>
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
                    <h4 class="header-title">Edit Product - <?php echo e($bank->name); ?></h4>
                    <?php echo $__env->make('backend.layouts.partials.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <form action="<?php echo e(route('admin.banks.update', $bank->id)); ?>" method="POST">
                        <?php echo method_field('PUT'); ?>
                        <?php echo csrf_field(); ?>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Bank Name</label>
                                <input type="text" id="name" name="name" class="form-control" value="<?php echo e(old('name', $bank->name)); ?>" required>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Branch Code</label>
                                <input type="text" id="branch_code" name="branch_code" class="form-control" value="<?php echo e(old('branch_code', $bank->branch_code)); ?>" required>
                            </div>
                        </div>
                        <label for="name">Products</label>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <input type="checkbox" name="bank_products[]" id="product_<?php echo e($product->id); ?>" value="<?php echo e($product->id); ?>" <?php echo e(in_array($product->id, $bank->products->pluck('id')->toArray()) ? 'checked' : ''); ?>>
                            <label for="product_<?php echo e($product->id); ?>"><?php echo e($product->name); ?></label>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save Product</button>
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
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kyc-live\resources\views/backend/pages/banks/edit.blade.php ENDPATH**/ ?>