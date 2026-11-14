
<?php $__env->startSection('subtitle'); ?>Placed <?php echo e($order->created_at->format('F j, Y \a\t g:i a')); ?><?php $__env->stopSection(); ?>
<?php $__env->startSection('actions'); ?><a class="button secondary" href="<?php echo e(route($customerView?'customer.orders.index':'orders.index')); ?>"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
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
<?php endif; ?> All orders</a><?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="shopping-layout"><section class="panel"><div class="panel-body"><div class="order-detail-heading"><h2>Order details</h2><span class="<?php echo \Illuminate\Support\Arr::toCssClasses(['badge','badge-warning'=>$order->status==='pending','badge-success'=>$order->status==='completed','badge-muted'=>$order->status==='cancelled']); ?>"><?php echo e(ucfirst($order->status)); ?></span></div>
<?php if(!$customerView): ?><div class="order-contact"><strong><?php echo e($order->customer_name); ?></strong><span><?php echo e($order->customer_email); ?></span><span><?php echo e($order->customer_phone??'No phone number provided'); ?></span></div><?php endif; ?>
<?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="order-item"><div><strong><?php echo e($item->product_name); ?></strong><p class="field-help"><?php echo e($item->quantity); ?> &times; PHP <?php echo e(number_format($item->unit_price,2)); ?></p><?php if($item->sale_id): ?><a href="<?php echo e(route($customerView?'customer.receipts.show':'receipts.show',$item->sale_id)); ?>">View receipt <?php echo e($item->sale->receiptNumber()); ?></a><?php endif; ?></div><strong>PHP <?php echo e(number_format($item->total_price,2)); ?></strong></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div></section>
<aside class="panel order-summary"><h2>Payment &amp; pickup</h2><div class="summary-row summary-total"><span>Total</span><strong>PHP <?php echo e(number_format($order->total_price,2)); ?></strong></div>
<?php if($order->status==='pending'): ?><p class="field-help"><?php echo e($customerView?'Pay at pickup. Staff will confirm payment and complete your order. Stock is not reserved until completion.':'Collect the full amount before confirming. Completing this order records the sales under your staff account and reduces stock.'); ?></p>
<?php if($customerView): ?><form method="post" action="<?php echo e(route('customer.orders.cancel',$order->id)); ?>" data-confirm="Cancel this order?"><?php echo csrf_field(); ?><button class="button secondary full-width">Cancel order</button></form>
<?php else: ?><form method="post" action="<?php echo e(route('orders.complete',$order)); ?>" data-submit data-confirm="Confirm payment received and complete this order?"><?php echo csrf_field(); ?><button type="submit" class="button primary full-width">Confirm payment &amp; complete</button></form><form method="post" action="<?php echo e(route('orders.cancel',$order)); ?>" data-confirm="Cancel this customer order?"><?php echo csrf_field(); ?><button class="button secondary full-width">Cancel order</button></form><?php endif; ?>
<?php elseif($order->status==='completed'): ?><p class="field-help">Payment confirmed. Completed <?php echo e($order->completed_at->format('M j, Y, g:i a')); ?>. Receipts are available for each product.</p><?php else: ?><p class="field-help">This order was cancelled. No payment or stock changes were recorded.</p><?php endif; ?>
</aside></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout',['title'=>$order->number()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\cedri\OneDrive\Desktop\IT0049\Finals\resources\views/order.blade.php ENDPATH**/ ?>