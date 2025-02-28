<?php $__env->startSection('content2'); ?>
    <div class="dashboard-body-part">

        <div class="mobile-page-header">
            <h5 class="title"><?php echo e(__('Bind Your Account')); ?></h5>
            <a href="<?php echo e(route('user.dashboard')); ?>" class="back-btn">
                <i class="bi bi-arrow-left"></i> <?php echo e(__('Back')); ?>

            </a>
        </div>

        <div class="row gy-4">
            <div class="col-xxl-8 col-lg-6">
                <div class="site-card">
                    <form action="<?php echo e(url('account/bind')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="card-header">
                            <h5 class="mb-0"><?php echo e(__('Bind Your Account for Withdrawals')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="withdraw_method_id"><?php echo e(__('Withdraw Method')); ?></label>
                                <select name="withdraw_method_id" id="withdraw_method_id" class="select">
                                    <option value=""><?php echo e(__('Select Method')); ?></option>
                                    <?php $__currentLoopData = $withdrawMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($method->id); ?>"
                                            data-url="<?php echo e(route('user.withdraw.fetch', $method->id)); ?>">
                                            <?php echo e($method->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="row appendData"></div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-xxl-4 col-lg-6 withdraw-ins">
                <div class="site-card">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo e(__('Method Instructions')); ?></h5>
                    </div>
                    <div class="card-body">
                        <p class="instruction">برائے مہربانی اپنا اکاؤنٹ بائنڈ کرتے وقت اپنا اکاؤنٹ نمبر اور اکاؤنٹ ہولڈر کا نام صحیح طریقے سے درج کریں۔ اگر غلطی سے کوئی غلط معلومات درج ہو جائیں تو برائے مہربانی کسٹمر سروس سے رابطہ کرکے اپنا اکاؤنٹ بائنڈ تبدیل کروائیں۔ شکریہ۔</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>

<script>
    $(function() {
        'use strict'

        $('select[name=withdraw_method_id]').on('change', function() {
            if ($(this).val() == '') {
                $('.appendData').addClass('d-none');
                $('.instruction').text('');
                return;
            }
            $('.appendData').removeClass('d-none');
            getData($('select[name=withdraw_method_id] option:selected').data('url'))
        })

        function getData(url) {
            $.ajax({
                url: url,
                method: "GET",
                success: function(response) {
                    $('.instruction').html(response.withdraw_instruction);
                    let html = `
                        <div class="col-md-12 mb-3 mt-3">
                            <label for="email"><?php echo e(__('Account Number')); ?> <span class="text-danger">*</span></label>
                            <input type="text" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="account_information"><?php echo e(__('Account Title')); ?></label>
                            <textarea class="form-control" name="account_information" row="5" required></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="note"><?php echo e(__('Additional Note')); ?></label>
                            <textarea class="form-control" name="note" row="5"></textarea>
                        </div>
                        <div class="col-md-12">
                            <button class="btn main-btn w-100" type="submit"><?php echo e(__('Bind Account')); ?></button>
                        </div>
                    `;

                    $('.appendData').html(html);
                }
            })
        }
    });
</script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make(template() . 'layout.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\naveed\core\resources\views/theme4/user/account_bind.blade.php ENDPATH**/ ?>