<?php $__env->startSection('content2'); ?>
    <div class="dashboard-body-part">

        <div class="mobile-page-header">
            <h5 class="title"><?php echo e(__('Perfomance Chart')); ?></h5>
            <a href="<?php echo e(route('user.dashboard')); ?>" class="back-btn"><i class="bi bi-arrow-left"></i> <?php echo e(__('Back')); ?></a>
        </div>

        <div class="site-card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="mb-sm-0 mb-2"><?php echo e(__('Wholw Downline Details')); ?></h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table site-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Total Active Users')); ?></th>
                                <th><?php echo e(__('Total Inactive Users')); ?></th>
                                <th><?php echo e(__('Total Deposit')); ?></th>
                                <th><?php echo e(__('Total Withdraw')); ?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td data-caption="<?php echo e(__('Total Active Users')); ?>"><?php echo e($activeUsers); ?></td>
                                <td data-caption="<?php echo e(__('Total Inactive Users')); ?>"><?php echo e($inactiveUsers); ?>

                                </td>
                                <td data-caption="<?php echo e(__('Total Deposit')); ?>"><?php echo e(number_format($downlineDeposit)); ?></td>
                                <td data-caption="<?php echo e(__('Total Withdraw')); ?>"><?php echo e(number_format($downlineWithdraw)); ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(template() . 'layout.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\naveed\core\resources\views/theme4/user/perfomance.blade.php ENDPATH**/ ?>