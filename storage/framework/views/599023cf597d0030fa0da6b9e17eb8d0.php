<?php
    $bankId = optional(optional($case)->getCase)->bank_id;
?>

<?php if($bankId == 12): ?>
    <?php echo $__env->make('backend.pages.cases.partials.modify-bv-axis', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php else: ?>
    <?php echo $__env->make('backend.pages.cases.partials.modify-bv-default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<script src="<?php echo e(asset('backend/assets/js/jquery.validate.min.js')); ?>"></script>

<script>
    $(document).ready(function() {
        $('.updateBtn').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');

            let formData = form.serializeArray();
            let rowId = form.find('input[name="case_fi_id"]').val();
            let actionPath = "<?php echo e(route('admin.case.modifyBVCase','ID')); ?>";
            actionPath = actionPath.replace('ID', rowId);
            $.ajax({
                url: actionPath,
                type: 'POST',
                data: formData,
                success: function(response) {
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    $("#errors").empty();
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, item) {
                            $("#errors").append("<li class='alert alert-danger'>" + item + "</li>");
                        });
                    }
                }
            });
        });
    });
</script><?php /**PATH C:\laragon\www\kyc-live\resources\views/backend/pages/cases/modify-bv.blade.php ENDPATH**/ ?>