


<?php $__env->startSection('content'); ?>

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1><?php echo e(__($pageTitle)); ?></h1>
            </div>


            <div class="row">
                <div class="col-md-12">

                    
                    
                    <div class="card">
                        <div class="card-header">
                            <form action="" method="GET">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control"  name="dates">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary"
                                            type="submit"><?php echo e(__('Filter Transaction')); ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>

                                            <th><?php echo e(__('Trx')); ?></th>
                                            <th><?php echo e(__('User')); ?></th>
                                          
                                            <th><?php echo e(__('Gateway')); ?></th>
                                            <th><?php echo e(__('Amount')); ?></th>
                                            <th><?php echo e(__('Currency')); ?></th>
                                            <th><?php echo e(__('Charge')); ?></th>
                                            <th><?php echo e(__('Details')); ?></th>
                                            <th><?php echo e(__('Payment Date')); ?></th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                                            <tr>
                                                <td><?php echo e($transaction->trx); ?></td>
                                                <td><?php echo e(@$transaction->user->fname .' '.@$transaction->user->lname); ?></td>
                                                <td><?php echo e(@$transaction->gateway->gateway_name ?? 'Account Transfer'); ?></td>
                                                <td><?php echo e($transaction->amount); ?></td>
                                                <td><?php echo e($transaction->currency); ?></td>
                                                <td><?php echo e($transaction->charge . ' ' . $transaction->currency); ?></td>
                                                <td><?php echo e($transaction->details); ?></td>
                                                <td><?php echo e($transaction->created_at->format('Y-m-d')); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>


                                            <tr>

                                                <td class="text-center" colspan="100%">
                                                    <?php echo e(__('No users Found')); ?>

                                                </td>

                                            </tr>



                                        <?php endif; ?>



                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <?php if($transactions->hasPages()): ?>
                            <div class="card-footer">

                                <?php echo e($transactions->links('backend.partial.paginate')); ?>


                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('style'); ?>

<style>

.card .card-header .form-control{
    border-radius: 0;
}

.card .card-header .btn:not(.note-btn){
    border-radius: 0;
}

</style>
    
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>

    <script>
        $(function() {
            'use strict'

            $('input[name="dates"]').daterangepicker();

        })
    </script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u145197596/domains/opecfuture.com/public_html/core/resources/views/backend/transaction.blade.php ENDPATH**/ ?>