@props(['paginator'])
<div class="pagination-wrap">
<p>{{ $paginator->total() ? $paginator->firstItem().' - '.$paginator->lastItem().' of '.$paginator->total() : '0' }} {{ $label ?? 'results' }}</p>
@if($paginator->hasPages())
<nav class="pagination" aria-label="Pagination">
@if($paginator->onFirstPage())<span aria-disabled="true">Previous</span>@else<a href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>@endif
@for($page=max(1,$paginator->currentPage()-2);$page<=min($paginator->lastPage(),$paginator->currentPage()+2);$page++)
<a href="{{ $paginator->url($page) }}" @if($page===$paginator->currentPage()) aria-current="page" @endif>{{ $page }}</a>
@endfor
@if($paginator->hasMorePages())<a href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>@else<span aria-disabled="true">Next</span>@endif
</nav>
@endif
</div>
