<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name','size'=>20]));

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

foreach (array_filter((['name','size'=>20]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<svg width="<?php echo e($size); ?>" height="<?php echo e($size); ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
<?php switch($name):
case ('counter'): ?><rect x="3" y="3" width="18" height="14" rx="3"/><path d="M8 21h8M12 17v4M7 8h4M7 12h9"/><?php break; ?>
<?php case ('products'): ?><path d="m12 3 9 5-9 5-9-5 9-5ZM3 8v9l9 5 9-5V8M12 13v9M7.5 5.5l9 5"/><?php break; ?>
<?php case ('customers'): ?><circle cx="9" cy="8" r="3"/><path d="M3 21v-3a6 6 0 0 1 12 0v3M16 5a3 3 0 0 1 0 6M18 15a5 5 0 0 1 3 5"/><?php break; ?>
<?php case ('staff'): ?><rect x="4" y="5" width="16" height="16" rx="3"/><path d="M9 5V3h6v2M8 17h8"/><circle cx="12" cy="11" r="2"/><?php break; ?>
<?php case ('receipt'): ?><path d="M6 3h12v19l-3-2-3 2-3-2-3 2V3ZM9 7h6M9 11h6M9 15h3"/><?php break; ?>
<?php case ('search'): ?><circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 4.5 4.5"/><?php break; ?>
<?php case ('plus'): ?><path d="M12 5v14M5 12h14"/><?php break; ?>
<?php case ('arrow'): ?><path d="M5 12h14m-5-5 5 5-5 5"/><?php break; ?>
<?php case ('back'): ?><path d="M19 12H5m5-5-5 5 5 5"/><?php break; ?>
<?php case ('logout'): ?><path d="M9 4H5v16h4M10 12h11m-4-4 4 4-4 4"/><?php break; ?>
<?php case ('check'): ?><path d="m5 12 4 4L19 6"/><?php break; ?>
<?php case ('print'): ?><path d="M7 7V3h10v4M7 17H3V8h18v9h-4M7 14h10v7H7v-7M17 11h.01"/><?php break; ?>
<?php case ('lock'): ?><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3"/><?php break; ?>
<?php case ('edit'): ?><path d="m15 5 4 4M4 20l4-1L21 6l-4-4L4 15v5ZM13 20h8"/><?php break; ?>
<?php case ('upload'): ?><path d="M12 16V3m-5 5 5-5 5 5M4 16v5h16v-5"/><?php break; ?>
<?php case ('chevron'): ?><path d="m9 5 7 7-7 7"/><?php break; ?>
<?php case ('filter'): ?><path d="M4 6h16M7 12h10M10 18h4"/><?php break; ?>
<?php case ('bag'): ?><path d="M5 7h14l2 14H3L5 7ZM8 8V6a4 4 0 0 1 8 0v2"/><?php break; ?>
<?php case ('mail'): ?><rect x="3" y="5" width="18" height="14" rx="3"/><path d="m3 7 9 6 9-6"/><?php break; ?>
<?php case ('alert'): ?><circle cx="12" cy="12" r="9"/><path d="M12 7v6M12 17h.01"/><?php break; ?>
<?php case ('eye'): ?><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/><?php break; ?>
<?php default: ?><circle cx="12" cy="8" r="4"/><path d="M4 22v-2a8 8 0 0 1 16 0v2"/>
<?php endswitch; ?>
</svg>
<?php /**PATH C:\Users\cedri\OneDrive\Desktop\IT0049\Finals\resources\views/components/icon.blade.php ENDPATH**/ ?>