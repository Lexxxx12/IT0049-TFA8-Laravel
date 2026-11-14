
<?php $__env->startSection('subtitle'); ?>View or print the sale details.<?php $__env->stopSection(); ?>
<?php $__env->startSection('actions'); ?><a class="button secondary" href="<?php echo e($customerView?route('customer.purchases'):'/sales'); ?>"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'back','size' => 17]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'back','size' => 17]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?> Back to <?php echo e($customerView?'purchases':'sales'); ?></a><button type="button" class="button primary" data-print><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'print','size' => 17]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'print','size' => 17]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?> Print receipt</button><?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="receipt-page"><article class="receipt-paper"><div class="receipt-brand-row"><div class="receipt-wordmark">La pasión</div><img class="receipt-logo" src="/brand/specaura.svg" alt="Specaura logo" width="80" height="48"></div><div class="receipt-heading"><div><h2>Sales receipt</h2><p><?php echo e($sale->receiptNumber()); ?></p></div><span class="badge badge-success"><span></span>Recorded</span></div><div class="receipt-meta"><div><span>Customer</span><strong><?php echo e($sale->recordedCustomer()); ?></strong></div><div><span>Date &amp; time</span><strong><?php echo e($sale->created_at->format('d F Y')); ?></strong><small><?php echo e($sale->created_at->format('g:i A')); ?></small></div><div><span>Recorded by</span><strong><?php echo e($sale->recordedStaff()); ?></strong></div></div><div class="table-scroll"><table class="receipt-table"><thead><tr><th>Item</th><th class="align-right">Unit price</th><th class="align-right">Qty</th><th class="align-right">Amount</th></tr></thead><tbody><tr><td><?php echo e($sale->recordedProduct()); ?></td><td class="numeric align-right"><?php echo e(number_format($sale->recordedUnitPrice(),2)); ?></td><td class="numeric align-right"><?php echo e($sale->quantity); ?></td><td class="numeric align-right"><?php echo e(number_format($sale->total_price,2)); ?></td></tr></tbody></table></div><div class="receipt-total"><span>Total amount</span><strong class="numeric"><?php echo e(number_format($sale->total_price,2)); ?></strong></div></article><p class="receipt-print-note"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'print','size' => 16]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'print','size' => 16]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?> Print this receipt or save it as a PDF using your browser.</p></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout',['title'=>'Receipt '.$sale->receiptNumber()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\cedri\OneDrive\Desktop\IT0049\Finals\resources\views/receipt.blade.php ENDPATH**/ ?>