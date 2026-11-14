<?php
 $isCustomer=request()->is('customer/*');
 $authPage=$authPage??false;
 $staff=auth('web')->user();
 $member=auth('customer')->user();
 $navigation=$isCustomer?[
 ['url'=>route('customer.shop'),'label'=>'Shop','icon'=>'products','active'=>request()->routeIs('customer.shop')],
 ['url'=>route('customer.cart'),'label'=>'Cart','icon'=>'bag','active'=>request()->routeIs('customer.cart')],
 ['url'=>route('customer.orders.index'),'label'=>'My orders','icon'=>'receipt','active'=>request()->routeIs('customer.orders.*')],
 ['url'=>route('customer.purchases'),'label'=>'My purchases','icon'=>'bag','active'=>request()->routeIs('customer.purchases','customer.receipts.*')],
 ['url'=>route('customer.account'),'label'=>'My account','icon'=>'user','active'=>request()->routeIs('customer.account')]
 ]:[
 ['url'=>route('orders.index'),'label'=>'Customer orders','icon'=>'bag','active'=>request()->routeIs('orders.*')],
 ['url'=>route('sales.create'),'label'=>'Sales counter','icon'=>'counter','active'=>request()->is('sales/create')],
 ['url'=>route('records.index', ['kind'=>'products']),'label'=>'Products','icon'=>'products','active'=>request()->is('products*')],
 ['url'=>route('records.index', ['kind'=>'customers']),'label'=>'Customers','icon'=>'customers','active'=>request()->is('customers*')],
 ['url'=>route('records.index', ['kind'=>'staff']),'label'=>'Staff','icon'=>'staff','active'=>request()->is('staff*')],
 ['url'=>route('sales.index'),'label'=>'Sales history','icon'=>'receipt','active'=>request()->is('sales')||request()->is('receipts/*')]
 ];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#f4f6f5"><title><?php echo e($title ?? 'La pasión'); ?> | La pasión</title>
<link rel="icon" href="/brand.svg" type="image/svg+xml"><link rel="preload" href="/fonts/instrument-regular.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="/pos.css?v=6">
</head>
<body class="<?php echo e($authPage?'auth-page':'workspace'); ?> <?php echo e($isCustomer?'customer-workspace':''); ?>">
<a class="skip-link" href="#main">Skip to content</a>
<?php if(!$authPage): ?>
<aside class="sidebar">
<a class="brand" href="<?php echo e($isCustomer?route('customer.shop'):route('sales.create')); ?>"><img class="brand-logo" src="/brand/specaura.svg" alt="" width="48" height="34"><span class="wordmark">La pasión<span class="brand-caption"><?php echo e($isCustomer?'Customer account':'Point of sale'); ?></span></span></a>
<div class="nav-section-label"><?php echo e($isCustomer?'Account':'Workspace'); ?></div>
<nav class="desktop-nav" aria-label="Main navigation">
<?php $__currentLoopData = $navigation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<a href="<?php echo e($item['url']); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['nav-link','is-active'=>$item['active']]); ?>" <?php if($item['active']): ?> aria-current="page" <?php endif; ?>><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => $item['icon']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?><span><?php echo e($item['label']); ?></span><?php if($item['active']): ?><span class="nav-active-dot"></span><?php endif; ?></a>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</nav>
<div class="sidebar-bottom"><div class="sidebar-account"><?php if (isset($component)) { $__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.avatar','data' => ['class' => 'avatar-dark','src' => !$isCustomer && $staff?->avatar ? Storage::disk('public')->url($staff->avatar) : null,'name' => $isCustomer ? ($member?->customer?->full_name ?? 'Customer') : ($staff?->full_name ?? 'Staff')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'avatar-dark','src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(!$isCustomer && $staff?->avatar ? Storage::disk('public')->url($staff->avatar) : null),'name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isCustomer ? ($member?->customer?->full_name ?? 'Customer') : ($staff?->full_name ?? 'Staff'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b)): ?>
<?php $attributes = $__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b; ?>
<?php unset($__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b)): ?>
<?php $component = $__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b; ?>
<?php unset($__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b); ?>
<?php endif; ?><div><strong><?php echo e($isCustomer?($member?->customer?->full_name??'Customer'):($staff?->full_name??'Staff')); ?></strong><small><?php echo e($isCustomer?'Customer account':'Staff account'); ?></small></div><form method="post" action="<?php echo e($isCustomer?route('customer.logout'):route('logout')); ?>"><?php echo csrf_field(); ?><button class="icon-button logout-button" aria-label="<?php echo e($isCustomer?'Customer log out':'Log out'); ?>" title="Log out"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'logout']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'logout']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?></button></form></div></div>
</aside>
<div class="app-shell">
<header class="topbar"><div class="breadcrumb"><span>Point of sale</span><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'chevron','size' => 12]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron','size' => 12]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?><strong><?php echo e($isCustomer?'Customer space':'Workspace'); ?></strong></div><a class="mobile-brand" href="<?php echo e($isCustomer?route('customer.shop'):route('sales.create')); ?>">La pasión<span>.</span></a><div class="topbar-end"><time datetime="<?php echo e(now()->toDateString()); ?>"><?php echo e(now()->format('l, d F Y')); ?></time><?php if (isset($component)) { $__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.avatar','data' => ['class' => 'avatar-small','src' => !$isCustomer && $staff?->avatar ? Storage::disk('public')->url($staff->avatar) : null,'name' => $isCustomer ? ($member?->customer?->full_name ?? 'Customer') : ($staff?->full_name ?? 'Staff')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'avatar-small','src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(!$isCustomer && $staff?->avatar ? Storage::disk('public')->url($staff->avatar) : null),'name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isCustomer ? ($member?->customer?->full_name ?? 'Customer') : ($staff?->full_name ?? 'Staff'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b)): ?>
<?php $attributes = $__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b; ?>
<?php unset($__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b)): ?>
<?php $component = $__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b; ?>
<?php unset($__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b); ?>
<?php endif; ?><form class="mobile-logout" method="post" action="<?php echo e($isCustomer?route('customer.logout'):route('logout')); ?>"><?php echo csrf_field(); ?><button class="icon-button" aria-label="<?php echo e($isCustomer?'Customer log out':'Log out'); ?>"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'logout']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'logout']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?></button></form></div></header>
<main id="main" class="main-content">
<div class="page-heading"><div><h1><?php echo e($title ?? 'La pasión'); ?></h1><?php if (! empty(trim($__env->yieldContent('subtitle')))): ?><p><?php echo $__env->yieldContent('subtitle'); ?></p><?php endif; ?></div><div class="page-heading-actions"><?php echo $__env->yieldContent('actions'); ?></div></div>
<?php else: ?>
<main id="main" class="auth-main">
<section class="auth-visual">
<a href="/" class="auth-brand"><img src="/brand/specaura.svg" alt="" width="50" height="32"><span>La pasión</span></a>
<div class="auth-visual-body">
<div class="logo-stage" data-glass-surface>
<img class="logo-static" src="/brand/specaura.svg" alt="Specaura logo" width="400" height="235">
<div id="liquid-logo" class="liquid-logo" aria-hidden="true" data-image="/brand/specaura-metal-mask.svg"></div>
<button type="button" id="logo-motion" class="logo-motion" aria-label="Pause logo animation" hidden><span aria-hidden="true">II</span></button>
</div>

</div>

</section>
<section class="auth-form-area">
<div class="auth-topline"><a class="auth-mobile-brand" href="/"><img src="/brand/specaura.svg" alt="" width="36" height="24"><span>La pasión</span></a><a class="auth-access-switch" href="<?php echo e($isCustomer?route('login'):route('customer.login')); ?>"><?php echo e($isCustomer?'Staff login':'Customer login'); ?></a></div>
<div class="auth-form-container">

<?php endif; ?>
<?php if(session('status')): ?><div class="notice notice-success" role="status"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'check']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?><span><?php echo e(session('status')); ?></span><?php if(session('receipt_id')): ?><a href="<?php echo e(route('receipts.show',session('receipt_id'))); ?>">View receipt</a><?php endif; ?></div><?php endif; ?>
<?php if($errors->any()): ?><div class="notice notice-error" role="alert"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?><div><strong>Check the following details</strong><ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div></div><?php endif; ?>
<?php echo $__env->yieldContent('content'); ?>
<?php if($authPage): ?></div></section></main>
<?php else: ?>
</main></div>
<nav data-glass-surface class="mobile-nav" aria-label="Mobile navigation"><?php $__currentLoopData = $navigation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a href="<?php echo e($item['url']); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['is-active'=>$item['active']]); ?>" <?php if($item['active']): ?> aria-current="page" <?php endif; ?>><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => $item['icon']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?><span><?php echo e(match($item['label']) { 'Customer orders','My orders'=>'Orders', 'Sales counter'=>'Counter', 'Sales history'=>'Sales', 'My purchases'=>'Purchases', 'My account'=>'Account', default=>$item['label'] }); ?></span></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></nav>
<?php endif; ?>
<dialog id="confirmation" class="confirm-dialog" aria-labelledby="confirmation-title" aria-describedby="confirmation-message"><form method="dialog"><div class="confirm-symbol"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert','size' => 24]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert','size' => 24]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?></div><h2 id="confirmation-title">Confirm this action</h2><p id="confirmation-message"></p><div class="button-row"><button value="cancel" class="button secondary">Cancel</button><button value="confirm" class="button danger-solid" id="confirm-action">Confirm</button></div></form></dialog>
<script src="/pos.js?v=5" defer></script>
<?php echo app('Illuminate\Foundation\Vite')('resources/js/app.js'); ?>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body></html>
<?php /**PATH C:\Users\cedri\OneDrive\Desktop\IT0049\Finals\resources\views/layout.blade.php ENDPATH**/ ?>