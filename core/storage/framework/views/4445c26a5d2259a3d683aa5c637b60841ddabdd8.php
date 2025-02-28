<?php $__env->startSection('content2'); ?>
    <div class="dashboard-body-part">
        <div class="mobile-page-header">
            <h5 class="title"><?php echo e(__('Password')); ?></h5>
            <a href="<?php echo e(route('user.dashboard')); ?>" class="back-btn"><i class="bi bi-arrow-left"></i> <?php echo e(__('Back')); ?></a>
        </div>

        <div class="row justify-content-center">
            <div class="col-xxl-6 xol-xl-8">
                <div class="site-card">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo e(__('Change Password')); ?></h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('user.update.password')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label for="exampleInputEmail1"
                                    class="mt-2 mb-2"><?php echo e(__('Old Password')); ?></label>
                                <input type="password" class="form-control" name="oldpassword"
                                    placeholder="<?php echo e(__('Enter Old Password')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1"
                                    class="mt-2 mb-2"><?php echo e(__('New Password')); ?></label>
                                <input type="password" class="form-control" name="password"
                                    placeholder="<?php echo e(__('Enter New Password')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1"
                                    class="mt-2 mb-2"><?php echo e(__('Confirm Password')); ?></label>
                                <input type="password" class="form-control" name="password_confirmation"
                                    placeholder="<?php echo e(__('Confirm Password')); ?>">
                            </div>
                            <div class="row mt-4">
                                <div class="col-xs-12 d-grid gap-2">
                                    <button class="btn main-btn w-100" type="submit"><span><?php echo e(__('Update')); ?></span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(template().'layout.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u145197596/domains/opecfuture.com/public_html/core/resources/views/theme4/user/auth/changepassword.blade.php ENDPATH**/ ?>