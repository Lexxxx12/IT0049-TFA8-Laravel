
<?php $__env->startSection('subtitle'); ?>Review your items before placing an order.<?php $__env->stopSection(); ?>
<?php $__env->startSection('actions'); ?><a class="button secondary" href="<?php echo e(route('customer.shop')); ?>"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
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
<?php endif; ?> Continue shopping</a><?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php if(!$rows): ?><section class="panel empty-state"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'bag','size' => 42]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bag','size' => 42]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?><h2>Your cart is empty</h2><p>Add products from the shop to place an order.</p><a class="button primary" href="<?php echo e(route('customer.shop')); ?>">Browse products</a></section>
<?php else: ?>
<div class="shopping-layout"><section class="panel cart-items">
<?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="cart-item"><div class="cart-item-image"><?php if($row['product']?->image): ?><img src="<?php echo e(Storage::disk('public')->url($row['product']->image)); ?>" alt="" loading="lazy"><?php else: ?><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'products','size' => 30]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'products','size' => 30]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?><?php endif; ?></div>
<div class="cart-item-details"><h2><?php echo e($row['product']?->name??'Unavailable product'); ?></h2><p>PHP <?php echo e(number_format($row['product']?->price??0,2)); ?> each</p>
<?php if(!$row['available']): ?><p class="field-error">Unavailable quantity. Reduce it or remove this item.</p><?php else: ?><p class="field-help"><?php echo e($row['product']->stock_quantity); ?> available</p><?php endif; ?>
<?php if($row['product']): ?>
<form method="post" action="<?php echo e(route('customer.cart.update',$row['id'])); ?>" class="cart-quantity" data-submit><?php echo csrf_field(); ?> <?php echo method_field('put'); ?><label class="sr-only" for="quantity-<?php echo e($row['id']); ?>">Quantity for <?php echo e($row['product']->name); ?></label><input id="quantity-<?php echo e($row['id']); ?>" type="number" name="quantity" min="0" max="2147483647" value="<?php echo e($row['quantity']); ?>" required><button class="button secondary" type="submit">Update</button><button class="text-danger" name="quantity" value="0" type="submit" formnovalidate>Remove</button></form>
<?php endif; ?>
</div><strong class="cart-line-total">PHP <?php echo e(number_format($row['total'],2)); ?></strong></div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</section>
<aside class="panel order-summary"><h2>Order summary</h2><div class="summary-row"><span>Items</span><strong><?php echo e(array_sum(array_column($rows,'quantity'))); ?></strong></div><div class="summary-row summary-total"><span>Total</span><strong>PHP <?php echo e(number_format($total,2)); ?></strong></div><p class="field-help">Payment is due at pickup. Staff confirms payment before completing your order. Stock is not reserved until completion.</p>
<form method="post" action="<?php echo e(route('customer.orders.store')); ?>" data-submit><?php echo csrf_field(); ?><input type="hidden" name="submission_token" value="<?php echo e($token); ?>"><button class="button primary full-width" <?php if(in_array(false,array_column($rows,'available'),true)): echo 'disabled'; endif; ?>>Place order <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
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
<?php endif; ?></button></form></aside></div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout',['title'=>'Shopping cart'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\cedri\OneDrive\Desktop\IT0049\Finals\resources\views/cart.blade.php ENDPATH**/ ?>