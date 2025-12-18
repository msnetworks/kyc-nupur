

<?php $__env->startSection('title'); ?>
404 - Page Not Found
<?php $__env->stopSection(); ?>

<?php $__env->startSection('error-content'); ?>
    <h2>404</h2>
    <p>Sorry ! Page Not Found !</p>
    <a href="<?php echo e(route('admin.dashboard')); ?>">Back to Dashboard</a>
    <a href="<?php echo e(route('admin.login')); ?>">Login Again !</a>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('errors.errors_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kyc-live\resources\views/errors/404.blade.php ENDPATH**/ ?>