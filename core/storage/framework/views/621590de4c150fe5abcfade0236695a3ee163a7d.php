<?php $__env->startSection('content'); ?>
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1><?php echo e(__($pageTitle)); ?></h1>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <!-- Search Form -->
                        <div class="card-header">
                            <form method="GET" action="<?php echo e(route('admin.user.list')); ?>">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search by Email, Username, or Phone" value="<?php echo e(request('search')); ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Full Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Country</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td><?php echo e($loop->iteration); ?></td>
                                                <td><?php echo e($user->fullname); ?></td>
                                                <td><?php echo e($user->phone); ?></td>
                                                <td><?php echo e($user->email); ?></td>
                                                <td><?php echo e($user->address->country ?? 'N/A'); ?></td>
                                                <td>
                                                    <?php if($user->status): ?>
                                                        <span class='badge badge-success'>Active</span>
                                                    <?php else: ?>
                                                        <span class='badge badge-danger'>Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(route('admin.user.details', $user)); ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fa fa-eye mr-2"></i>Details
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td class="text-center" colspan="7">No Data Found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer">
                            <?php echo e($users->links()); ?>

                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\naveed\core\resources\views/backend/users/index.blade.php ENDPATH**/ ?>