<?php $__env->startSection('content2'); ?>
    <div class="dashboard-body-part">

        <div class="mobile-page-header">
            <h5 class="title"><?php echo e(__('Withdraw')); ?></h5>
            <a href="<?php echo e(url('user/dashboard')); ?>" class="back-btn"><i class="bi bi-arrow-left"></i> <?php echo e(__('Back')); ?></a>
        </div>

        <div class="row gy-4">
            <div class="col-xxl-8 col-lg-6">
                <div class="site-card">
                    <form action="<?php echo e(url('withdraw')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="card-header">
                            <h5 class="mb-0">
                                <?php echo e(__('Current Balance: ')); ?>

                                <span
                                    class="color-change"><?php echo e(number_format(auth()->user()->balance, 2) . ' ' . $general->site_currency); ?></span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if(!$accountBind): ?>
                                <div class="alert alert-danger">
                                    <?php echo e(__('You need to bind an account before withdrawing.')); ?>

                                    <a href="<?php echo e(url('account/bind')); ?>"
                                        class="btn btn-sm btn-primary"><?php echo e(__('Bind Now')); ?></a>
                                </div>
                            <?php else: ?>
                            <div class="form-group">
                                <label for=""><?php echo e(__('Withdraw Amount')); ?></label>
                                <input type="text" name="amount" class="form-control amount" required>
                                <p class="text-muted small">
                                    Min Amount: <strong><?php echo e(number_format($accountBind->withdrawMethod->min_amount, 0)); ?></strong> /
                                    Max Amount: <strong><?php echo e(number_format($accountBind->withdrawMethod->max_amount, 0)); ?></strong>
                                </p>
                            </div>


                                <div class="form-group">
                                    <label for=""><?php echo e(__('Withdraw Method')); ?></label>
                                    <input type="text" class="form-control text-black"
                                        value="<?php echo e($accountBind->withdrawMethod->name); ?>" disabled>
                                </div>

                                <div class="form-group">
                                    <label><?php echo e(__('Withdraw Charge')); ?></label>
                                    <input type="text" class="form-control charge text-black"
                                        value="<?php echo e(number_format($accountBind->withdrawMethod->charge, 2)); ?>" required
                                        disabled>
                                </div>

                                <div class="form-group">
                                    <label for=""><?php echo e(__('Final Withdraw Amount')); ?></label>
                                    <input type="text" name="final_amo" class="form-control final_amo" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo e(__('Account Number')); ?></label>
                                    <input type="text" class="form-control" readonly
                                        value="<?php echo e($accountBind->email); ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo e(__('Account Information')); ?></label>
                                    <textarea class="form-control" readonly>
                                        <?php echo e($accountBind->account_information); ?>

                                    </textarea>
                                </div>

                                <button class="btn main-btn w-100 mt-3" type="submit"><?php echo e(__('Withdraw Now')); ?></button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        $(document).ready(function() {
            'use strict';

            $('.amount').on('keyup', function() {
                let withdrawChargeType = "<?php echo e($accountBind->withdrawMethod->charge_type); ?>";
                let charge = parseFloat("<?php echo e($accountBind->withdrawMethod->charge); ?>");
                let amount = parseFloat($(this).val());

                if (isNaN(amount) || amount <= 0) {
                    $('.final_amo').val(0);
                    return;
                }

                let totalAmount = (withdrawChargeType === "percent") ?
                    amount + (charge * amount / 100) :
                    amount + charge;

                $('.final_amo').val(totalAmount.toFixed(2));
            });

            // Prevent withdrawal outside allowed hours
            $('form').on('submit', function(e) {
                let now = new Date();
                let currentHour = now.getHours();

                if (currentHour < 12 || currentHour >= 20) {
                    e.preventDefault();
                    alert("Withdrawals are only available between 12 PM and 8 PM.");
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make(template() . 'layout.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\naveed\core\resources\views/theme4/user/withdraw/index.blade.php ENDPATH**/ ?>