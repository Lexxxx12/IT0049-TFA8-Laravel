<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['src' => null, 'name' => 'Profile']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['src' => null, 'name' => 'Profile']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<span <?php echo e($attributes->merge(['class' => 'avatar'])); ?>>
<?php if($src): ?>
<img src="<?php echo e($src); ?>" alt="<?php echo e($name); ?> profile picture" loading="lazy">
<?php else: ?>
<svg viewBox="0 0 100 100" role="img" aria-label="<?php echo e($name); ?>: no profile picture" focusable="false"><circle cx="50" cy="50" r="50" fill="#e4e6eb"/><circle cx="50" cy="37" r="19" fill="#a8adb5"/><path d="M12 100v-8c0-19 17-31 38-31s38 12 38 31v8Z" fill="#a8adb5"/></svg>
<?php endif; ?>
</span>
<?php /**PATH C:\Users\cedri\OneDrive\Desktop\IT0049\Finals\resources\views/components/avatar.blade.php ENDPATH**/ ?>