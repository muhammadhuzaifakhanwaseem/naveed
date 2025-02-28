<?php $__env->startSection('content2'); ?>
    <div class="dashboard-body-part">

        <div class="mobile-page-header">
            <h5 class="title"><?php echo e(__('Perfomance Chart')); ?></h5>
            <a href="<?php echo e(route('user.dashboard')); ?>" class="back-btn"><i class="bi bi-arrow-left"></i> <?php echo e(__('Back')); ?></a>
        </div>

        <div class="site-card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="mb-sm-0 mb-2"><?php echo e(__('Level 1 Detalis')); ?></h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table site-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Total Users')); ?></th>
                                <th><?php echo e(__('Total Invested Members')); ?></th>
                                <th><?php echo e(__('Total Invest Amount')); ?></th>
                                <th><?php echo e(__('Total Active Plans')); ?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td data-caption="<?php echo e(__('Total Users')); ?>"><?php echo e($levelOneUsersCount); ?></td>
                                <td data-caption="<?php echo e(__('Total Invested Members')); ?>"><?php echo e($totalInvestedMembersLevel1); ?>

                                </td>
                                <td data-caption="<?php echo e(__('Total Invest Amount')); ?>"><?php echo e(number_format($totalInvestedAmountLevel1)); ?></td>
                                <td data-caption="<?php echo e(__('Total Active Plans')); ?>"><?php echo e($totalPlansLevel1); ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>  
            </div>
        </div>
        <div class="site-card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="mb-sm-0 mb-2"><?php echo e(__('Level 2 Detalis')); ?></h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table site-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Total Users')); ?></th>
                                <th><?php echo e(__('Total Invested Members')); ?></th>
                                <th><?php echo e(__('Total Invest Amount')); ?></th>
                                <th><?php echo e(__('Total Active Plans')); ?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td data-caption="<?php echo e(__('Total Users')); ?>"><?php echo e($levelTwoUsersCount); ?></td>
                                <td data-caption="<?php echo e(__('Total Invested Members')); ?>"><?php echo e($totalInvestedMembersLevel2); ?>

                                </td>
                                <td data-caption="<?php echo e(__('Total Invest Amount')); ?>"><?php echo e(number_format($totalInvestedAmountLevel2)); ?></td>
                                <td data-caption="<?php echo e(__('Total Active Plans')); ?>"><?php echo e($totalPlansLevel2); ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="site-card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="mb-sm-0 mb-2"><?php echo e(__('Level 3 Detalis')); ?></h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table site-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Total Users')); ?></th>
                                <th><?php echo e(__('Total Invested Members')); ?></th>
                                <th><?php echo e(__('Total Invest Amount')); ?></th>
                                <th><?php echo e(__('Total Active Plans')); ?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td data-caption="<?php echo e(__('Total Users')); ?>"><?php echo e($levelThreeUsersCount); ?></td>
                                <td data-caption="<?php echo e(__('Total Invested Members')); ?>"><?php echo e($totalInvestedMembersLevel3); ?>

                                </td>
                                <td data-caption="<?php echo e(__('Total Invest Amount')); ?>"><?php echo e(number_format($totalInvestedAmountLevel3)); ?></td>
                                <td data-caption="<?php echo e(__('Total Active Plans')); ?>"><?php echo e($totalPlansLevel3); ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(template() . 'layout.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\naveed\core\resources\views/theme4/user/perfomance.blade.php ENDPATH**/ ?>