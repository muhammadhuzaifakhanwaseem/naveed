<?php $__env->startSection('content2'); ?>
    <div class="dashboard-body-part">

        <div class="mobile-page-header">
            <h5 class="title"><?php echo e(__('Payment Informations')); ?></h5>
            <a href="<?php echo e(route('user.deposit')); ?>" class="back-btn"><i class="bi bi-arrow-left"></i> <?php echo e(__('Back')); ?></a>
        </div>

        <div class="row gy-4">
            <div class="col-xl-6">
                <div class="site-card">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo e(__('Bank Payment Information')); ?></h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-medium"><?php echo e(__('Bank Name')); ?></span>
                                <span><?php echo e($gateway->gateway_parameters->name); ?></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
    <span class="fw-medium"><?php echo e(__('Account Number')); ?></span>
    <div class="d-flex align-items-center">
        <span id="accountNumber" class="me-2"><?php echo e($gateway->gateway_parameters->account_number); ?></span>
        <button class="btn btn-light btn-sm border-0" onclick="copyToClipboard()" data-bs-toggle="tooltip" data-bs-placement="top" title="Copy">
            <i class="bi bi-clipboard"></i>
        </button>
    </div>
</li>

<script>
    function copyToClipboard() {
        var accountNumber = document.getElementById("accountNumber").innerText;
        navigator.clipboard.writeText(accountNumber).then(function() {
            var button = document.querySelector("button[data-bs-toggle='tooltip']");
            button.setAttribute("title", "Copied!");
            var tooltip = new bootstrap.Tooltip(button);
            tooltip.show();
            setTimeout(() => {
                tooltip.hide();
                button.setAttribute("title", "Copy");
            }, 1500);
        }).catch(function(error) {
            console.error("Copy failed", error);
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>



                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-medium">Account Title</span>
                                <span><?php echo e($gateway->gateway_parameters->routing_number); ?></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
    <span class="fw-medium"><?php echo e(__('IBAN Number')); ?></span>
    <div class="d-flex align-items-center">
        <span id="ibanNumber" class="me-2"><?php echo e($gateway->gateway_parameters->branch_name); ?></span>
        <button class="btn btn-light btn-sm border-0" onclick="copyIBAN()" data-bs-toggle="tooltip" data-bs-placement="top" title="Copy">
            <i class="bi bi-clipboard"></i>
        </button>
    </div>
</li>

<script>
    function copyIBAN() {
        var ibanNumber = document.getElementById("ibanNumber").innerText;
        navigator.clipboard.writeText(ibanNumber).then(function() {
            var button = document.querySelector("button[data-bs-toggle='tooltip']");
            button.setAttribute("title", "Copied!");
            var tooltip = new bootstrap.Tooltip(button);
            tooltip.show();
            setTimeout(() => {
                tooltip.hide();
                button.setAttribute("title", "Copy");
            }, 1500);
        }).catch(function(error) {
            console.error("Copy failed", error);
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>


                            <!--<li class="list-group-item d-flex justify-content-between">-->
                            <!--    <span class="fw-medium"><?php echo e(__('Method Currency')); ?></span>-->
                            <!--    <span><?php echo e($gateway->gateway_parameters->gateway_currency); ?></span>-->
                            <!--</li>-->
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-medium">برائے مہربانی یہ کمپنی اکاؤنٹ ہے، آپ اس کو کاپی کرکے اس پر آسانی سے رقم منتقل کر سکتے ہیں۔</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-medium">ادائیگی منتقل کرنے کے بعد، نیچے دیے گئے آپشن پر اسکرین شاٹ بھیجیں۔</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="site-card">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo e(__('Payment Information')); ?></h5>
                    </div>

                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-medium"><?php echo e(__('Gateway Name')); ?>:</span>
                                <span><?php echo e($deposit->gateway->gateway_name); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-medium"><?php echo e(__('Amount')); ?>:</span>
                                <span><?php echo e(number_format($deposit->amount, 2) . ' ' . @$general->site_currency); ?></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-medium"><?php echo e(__('Charge')); ?>:</span>
                                <span><?php echo e(number_format($deposit->charge, 2) . ' ' . @$general->site_currency); ?></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-medium"><?php echo e(__('Conversion Rate')); ?>:</span>
                                <span><?php echo e('1 ' . @$general->site_currency . ' = ' . number_format($deposit->rate, 2)); ?></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-medium"><?php echo e(__('Total Payable Amount')); ?>:</span>
                                <span><?php echo e(number_format($deposit->final_amount, 2) . ' ' . @$general->site_currency); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="site-card">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo e(__('Payment Proof')); ?></h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <?php $__currentLoopData = $gateway->user_proof_param; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proof): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($proof['type'] == 'text'): ?>
                                        <div class="form-group col-md-12">
                                            <label for=""
                                                class="mb-2 mt-2"><?php echo e(__($proof['field_name'])); ?></label>
                                            <input type="text"
                                                name="<?php echo e(strtolower(str_replace(' ', '_', $proof['field_name']))); ?>"
                                                class="form-control"
                                                <?php echo e($proof['validation'] == 'required' ? 'required' : ''); ?>>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($proof['type'] == 'textarea'): ?>
                                        <div class="form-group col-md-12">
                                            <label for=""
                                                class="mb-2 mt-2"><?php echo e(__($proof['field_name'])); ?></label>
                                            <textarea name="<?php echo e(strtolower(str_replace(' ', '_', $proof['field_name']))); ?>" class="form-control"
                                                <?php echo e($proof['validation'] == 'required' ? 'required' : ''); ?>></textarea>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($proof['type'] == 'file'): ?>
                                        <div class="form-group col-md-12">
                                            <label for=""
                                                class="mb-2 mt-2"><?php echo e(__($proof['field_name'])); ?></label>
                                            <input type="file"
                                                name="<?php echo e(strtolower(str_replace(' ', '_', $proof['field_name']))); ?>"
                                                class="form-control"
                                                <?php echo e($proof['validation'] == 'required' ? 'required' : ''); ?>>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group">
                                    <button class="btn main-btn mt-4" type="submit"><span><?php echo e(__('Send Proof For Payment ')); ?></span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(template().'layout.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\naveed\core\resources\views/theme4/user/gateway/bank.blade.php ENDPATH**/ ?>