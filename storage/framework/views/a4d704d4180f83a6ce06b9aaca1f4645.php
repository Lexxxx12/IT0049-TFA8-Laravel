<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['paginator']));

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

foreach (array_filter((['paginator']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div class="pagination-wrap">
<p><?php echo e($paginator->total() ? $paginator->firstItem().' - '.$paginator->lastItem().' of '.$paginator->total() : '0'); ?> <?php echo e($label ?? 'results'); ?></p>
<?php if($paginator->hasPages()): ?>
<nav class="pagination" aria-label="Pagination">
<?php if($paginator->onFirstPage()): ?><span aria-disabled="true">Previous</span><?php else: ?><a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev">Previous</a><?php endif; ?>
<?php for($page=max(1,$paginator->currentPage()-2);$page<=min($paginator->lastPage(),$paginator->currentPage()+2);$page++): ?>
<a href="<?php echo e($paginator->url($page)); ?>" <?php if($page===$paginator->currentPage()): ?> aria-current="page" <?php endif; ?>><?php echo e($page); ?></a>
<?php endfor; ?>
<?php if($paginator->hasMorePages()): ?><a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next">Next</a><?php else: ?><span aria-disabled="true">Next</span><?php endif; ?>
</nav>
<?php endif; ?>
</div>
<?php /**PATH C:\Users\cedri\OneDrive\Desktop\IT0049\Finals\resources\views/components/pagination.blade.php ENDPATH**/ ?>