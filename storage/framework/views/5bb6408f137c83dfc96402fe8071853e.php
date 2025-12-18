

<?php $__env->startSection('title'); ?>
Create Case Create - Admin Panel
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
                <h4 class="page-title pull-left">Create Case Create</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                    <li><a href="<?php echo e(route('admin.fitypes.index')); ?>">All Create Case</a></li>
                    <li><span>Create Case</span></li>
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
                    <h4 class="header-title">Create Case</h4>
                    <?php echo $__env->make('backend.layouts.partials.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <form action="<?php echo e(route('admin.case.store')); ?>" method="POST">
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
                                <label for="name">Branch Code <span class="text-danger">*</span></label>
                                <select class="custom-select branchDropdown" name="branch_code" id="branchDropdown" required>
                                    <option value="">--Select Option--</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Product <span class="text-danger">*</span></label>
                                <select id="productSelect" name="product_id" class="custom-select" required>
                                    <option value="">--Select Option--</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">FI Type <span class="text-danger">*</span></label>
                                <?php $__currentLoopData = $fitypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $fitype): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-check">
                                    <input class="form-check-input #fytpe_checkbox" type="checkbox" id="Checkbox<?php echo e($key); ?>" onclick="toggleFields('field<?php echo e($fitype['fi_code']); ?>', this)" name="fi_type_id[<?php echo e($key); ?>][id]" value="<?php echo e($fitype['id']); ?>" rel-name="<?php echo e($fitype['fi_code']); ?>">
                                    <label class="form-check-label"  for="fitype<?php echo e($fitype['id']); ?>">
                                        <?php echo e($fitype['name']); ?>

                                    </label>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php echo $fitypesFeild; ?>

                            <div class="form-group col-md-6 col-sm-12">
                                <label for="application_type">Application Type <span class="text-danger">*</span></label>
                                <select class="custom-select application_type" name="application_type" id="application_type" required>
                                    <option value="">--Select Option--</option>
                                    <?php $__currentLoopData = $ApplicationTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ApplicationType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($ApplicationType['id']); ?>"><?php echo e($ApplicationType['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div id="fieldrv" class="fieldSet" style="display:none;">
                            <h4>RV</h4>
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="v_address7" type="text" placeholder="RV Address">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="v_pincode7" type="text" inputmode="numeric" pattern="[0-9]{6}" minlength="6" maxlength="6" placeholder="RV Pincode (6 digits)" pattern="\d{6}">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="mobile7" type="text" inputmode="numeric" pattern="[0-9]{10}" minlength="10" maxlength="10" placeholder="RV Phone Number (10 digits)">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="v_city7" type="text" placeholder="RV City">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="v_landmark7" type="text" placeholder="RV Landmark">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="geo_limit">Geo Limit </label>
                                    <select id="geo_limit7" name="geo_limit7" class="custom-select">
                                        <option value="">--Select Option--</option>
                                        <option value="Local">Local</option>
                                        <option value="Outstation">Outstation</option>
                                    </select>
                                </div>
                            </div>
                          </div>
                        
                          <div id="fieldbv" class="fieldSet" style="display:none;">
                            <h4>BV</h4>
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="v_address8" type="text" placeholder="BV Address">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="v_pincode8" type="text" inputmode="numeric" pattern="[0-9]{6}" minlength="6" maxlength="6" placeholder="BV Pincode (6 digits)" pattern="\d{6}">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="mobile8" type="text" inputmode="numeric" pattern="[0-9]{10}" minlength="10" maxlength="10" placeholder="BV Phone Number (10 digits)">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="v_city8" type="text" placeholder="BV City">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="v_landmark8" type="text" placeholder="BV Landmark">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="geo_limit">Geo Limit </label>
                                    <select id="geo_limit8" name="geo_limit8" class="custom-select">
                                        <option value="">--Select Option--</option>
                                        <option value="Local">Local</option>
                                        <option value="Outstation">Outstation</option>
                                    </select>
                                </div>
                            </div>
                          </div>
                        
                          <div id="fielditr" class="fieldSet" style="display:none;">
                            <h4>ITR</h4>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <input class="form-control" name="pan_number12" type="text" placeholder="ITR Pancard">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <input class="form-control" name="assessment_year12" type="text" placeholder="ITR Assessment Year">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <input class="form-control" name="mobile12" type="number" placeholder="ITR Mobile Number (10 digits)" pattern="\d{10}">
                                    </div>
                                </div>
                          </div>
                        
                          <div id="fieldbanking" class="fieldSet" style="display:none;">
                            <h4>Banking</h4>
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="accountnumber13" type="text" placeholder="Banking Account Number">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="mobile13" type="number" placeholder="Banking Mobile Number (10 digits)" pattern="\d{10}">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="bank_name13" type="text" placeholder="Verify Bank Name">
                                </div>
                            </div>
                          </div>
                        
                          <div id="fieldform_16" class="fieldSet" style="display:none;">
                            <h4>Form 16</h4>
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="pan_number14" type="text" placeholder="Pan Card">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="assessment_year14" type="text" placeholder="Assignment Years">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="mobile14" type="number" placeholder="Applicant's Mobile Number (10 digits)" pattern="\d{10}">
                                </div>
                            </div>
                          </div>
                        
                          <div id="fieldpan_card" class="fieldSet" style="display:none;">
                            <h4>Pancard</h4>
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="pan_number17" type="text" placeholder="Pan Card">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="aadhar_number17" type="number" placeholder="Aadhar Number">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <input class="form-control" name="mobile17" type="number17" placeholder="Mobile Number (10 digits)" pattern="\d{10}">
                                </div>
                            </div>
                          </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" value="<?php echo e(old('amount')); ?>" placeholder=" Enter Amount">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <span class="d-none" id="pan_card_block">
                                    <label for="pan_card">Pan Card <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="pan_card" name="pan_card" value="<?php echo e(old('pan_card')); ?>" placeholder=" Enter Pan Card">
                                </span>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <span class="d-none" id="aadhar_card_block">
                                    <label for="aadhar_card">Aadhar Card <span class="text-danger">*</span></label>
                                    <input type="number" minlength="12" maxlength="12" class="form-control" id="aadhar_card" name="aadhar_card" value="<?php echo e(old('aadhar_card')); ?>" placeholder=" Enter Aadhar Card">
                                </span>
                            </div>
                        </div>
                        <div class="form-row">
                            <!-- TAT Start -->
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="tat_start">TAT Start</label>
                                <input type="datetime-local" class="form-control" id="tat_start" name="tat_start" value="<?php echo e(old('tat_start')); ?>">
                            </div>
                            
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="tat_end">TAT End</label>
                                <input type="datetime-local" class="form-control" id="tat_end" name="tat_end" value="<?php echo e(old('tat_end')); ?>">
                            </div>
                            
                        </div>
                        

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12 name Applicant co_applicant_name d-none">
                                <label for="applicant_name">Applicant Name</label>
                                <input type="text" class="form-control" name="applicant_name" value="<?php echo e(old('applicant_name')); ?>" placeholder=" Enter Applicant Name">
                            </div>
                            <div class="form-group col-md-6 col-sm-12 name co_applicant_name d-none">
                                <label for="co_applicant_name">Co-Applicant Name</label>
                                <input type="text" class="form-control" name="co_applicant_name" value="<?php echo e(old('co_applicant_name')); ?>" placeholder=" Enter Co-Applicant Name">
                            </div>
                            
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Remarks</label>
                                <textarea name="remarks" rows="2" cols="20" id="remarks" class="form-control" placeholder="Remarks"></textarea>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save Case</button>
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
    function toggleFields(sectionId, checkbox) {
    const section = document.getElementById(sectionId);
    
    if (checkbox.checked) {
        section.style.display = "block";
    } else {
        section.style.display = "none";
        // Clear inputs when hiding the fields
        const inputs = section.querySelectorAll("input");
        inputs.forEach(input => {
        input.value = ""; // Clear input value
        input.checked = false; // Uncheck checkboxes if any
        });
    }
    }
</script>
<script>
    $(document).ready(function() {
        var baseUrl = "<?php echo e(url('/')); ?>";
        $('#selectBank').on('change', function(e) {
            var bankId = $(this).val();
            var customGetPath = "<?php echo e(route('admin.case.item','ID')); ?>";
            customGetPath = customGetPath.replace('ID', bankId);
            $.ajax({
                url: customGetPath,
                type: 'GET',
                success: function(response) {
                    var select = $('#productSelect');
                    select.empty(); // Clear any existing options
                    select.append('<option value="">--Select Option--</option>'); // Add default option

                    $.each(response, function(key, products) {
                        $.each(products, function(index, product) {
                            console.log(product);
                            var option = $('<option></option>')
                                .attr('value', product.product_id)
                                .text(product.name + ' (' + product.product_code + ')');
                            select.append(option);
                        });
                    });
                },
                error: function() {
                    alert('Request failed');
                }
            });
        });
        $('#application_type').on('change', function(e) {
            var selectedApplicationType = $(this).find("option:selected").text();
            console.log(selectedApplicationType);
            $('.name').addClass('d-none');
            if (selectedApplicationType == 'Co-Applicant') {
                $(".co_applicant_name").removeClass('d-none');
            } else {
                $('.' + selectedApplicationType).removeClass('d-none');
            }
        });


        $(document).on('click', '.fytpe_checkbox', function() {
            var isChecked = $(this).prop('checked');
            var relName = $(this).attr('rel-name');
            if (isChecked) {
                console.log();
                $('.' + relName + "_section").removeClass('d-none');
            } else {
                $('.' + relName + "_section").addClass('d-none');
            }
            
            let itr = $('input[value="12"]').prop('checked');
            let pan = $('input[value="17"]').prop('checked');
            if (itr === true || pan === true) {
                $('#pan_card').attr('required', true); 
                $('#pan_card_block').removeClass('d-none');
            } else {
                $('#pan_card').attr('required', false); 
                $('#pan_card_block').addClass('d-none');
            }
            if (pan === true) {
                $('#aadhar_card').attr('required', true); 
                $('#aadhar_card_block').removeClass('d-none');
            } else {
                $('#aadhar_card').attr('required', false); 
                $('#aadhar_card_block').addClass('d-none');
            }
        });

    });
</script>
<script>
    $(document).ready(function () {
    $('#selectBank').change(function () {
        var bankId = $(this).val(); // Get selected bank ID
        var branchDropdown = $('#branchDropdown'); // Reference to branch dropdown

        if (bankId) {
            // Clear existing options and add a loading message
            branchDropdown.html('<option value="">Loading...</option>');

            // Make AJAX call to fetch branches
            $.ajax({
                url: '<?php echo e(route('get.branches', ':bankId')); ?>'.replace(':bankId', bankId),
                type: 'GET',
                success: function (data) {
                    console.log(data);
                    // Clear and populate branch dropdown
                    branchDropdown.empty().append('<option value="">--Select Option--</option>');
                    $.each(data, function (key, branch) {
                        branchDropdown.append('<option value="' + branch.id + '">' + branch.branch_code + '</option>');
                    });
                },
                error: function () {
                    alert('Unable to fetch branch codes. Please try again.');
                }
            });
        } else {
            // Reset branch dropdown if no bank selected
            branchDropdown.html('<option value="">--Select Option--</option>');
        }
    });
});

</script>
<script>

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kyc-live\resources\views/backend/pages/cases/create.blade.php ENDPATH**/ ?>