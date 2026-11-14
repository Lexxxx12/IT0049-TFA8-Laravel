
<?php $__env->startSection('subtitle'); ?><?php echo e($customerView?'Track orders you placed for pickup.':'Confirm payment and complete customer pickup orders.'); ?><?php $__env->stopSection(); ?>
<?php $__env->startSection('actions'); ?><?php if($customerView): ?><a class="button primary" href="<?php echo e(route('customer.shop')); ?>">Browse products <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'arrow','size' => 17]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow','size' => 17]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?></a><?php endif; ?>@endsection
<?php $__env->startSection('content'); ?>
<section class="table-card">
<?php if(!$customerView): ?><nav class="tabs" aria-label="Order status"><?php $__currentLoopData = ['pending'=>'Pending','completed'=>'Completed','cancelled'=>'Cancelled','all'=>'All orders']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a href="<?php echo e(route('orders.index',['status'=>$key])); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['is-active'=>$status===$key]); ?>"><?php echo e($label); ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></nav><?php endif; ?>
<?php if($orders->count()): ?><div class="table-scroll"><table><thead><tr><th>Order</th><?php if(!$customerView): ?><th>Customer</th><?php endif; ?><th>Status</th><th>Total</th><th>Placed</th><th></th></tr></thead><tbody>
<?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr><td><strong><?php echo e($order->number()); ?></strong><div class="field-help"><?php echo e($order->items_count); ?> product<?php echo e($order->items_count===1?'':'s'); ?></div></td><?php if(!$customerView): ?><td><?php echo e($order->customer_name); ?><div class="field-help"><?php echo e($order->customer_email); ?></div></td><?php endif; ?><td><span class="<?php echo \Illuminate\Support\Arr::toCssClasses(['badge','badge-warning'=>$order->status==='pending','badge-success'=>$order->status==='completed','badge-muted'=>$order->status==='cancelled']); ?>"><?php echo e(ucfirst($order->status)); ?></span></td><td class="numeric">PHP <?php echo e(number_format($order->total_price,2)); ?></td><td><?php echo e($order->created_at->format('M j, Y, g:i a')); ?></td><td><a class="button secondary" href="<?php echo e(route($customerView?'customer.orders.show':'orders.show',$order->id)); ?>">View order</a></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody></table></div><?php else: ?><div class="empty-state"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'bag','size' => 40]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bag','size' => 40]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?><h2>No <?php echo e(!$customerView&&$status!=='all'?$status.' ':''); ?>orders</h2><p><?php echo e($customerView?'Orders you place from the shop will appear here.':'Customer pickup orders will appear here.'); ?></p></div><?php endif; ?>
</section><?php echo e($orders->links('components.pagination')); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout',['title'=>$customerView?'My orders':'Customer orders'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\cedri\OneDrive\Desktop\IT0049\Finals\resources\views/orders.blade.php ENDPATH**/ ?>