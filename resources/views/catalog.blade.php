@extends('layout',['title'=>'Shop'])
@section('subtitle')Browse products and place an order for pickup.@endsection
@section('actions')<a class="button primary" href="{{ route('customer.cart') }}"><x-icon name="bag" :size="17"/> Cart ({{ array_sum($cart) }})</a>@endsection
@section('content')
<form method="get" class="shop-filters">
<div class="search-field"><x-icon name="search" :size="18"/><input name="q" value="{{ $filters['q']??'' }}" maxlength="100" placeholder="Search products" aria-label="Search products"></div>
<label class="stock-filter"><input type="checkbox" name="available" value="1" @checked(request()->boolean('available'))> In stock only</label>
<button class="button secondary">Search</button>@if(request()->filled('q')||request()->boolean('available'))<a href="{{ route('customer.shop') }}">Clear filters</a>@endif
</form>
<div class="shop-grid">
@forelse($products as $product)
<article class="panel shop-product"><div class="shop-product-image">@if($product->image)<img src="{{ Storage::disk('public')->url($product->image) }}" alt="{{ $product->name }}" loading="lazy">@else<x-icon name="products" :size="48"/>@endif</div>
<div class="shop-product-body"><h2>{{ $product->name }}</h2><strong class="shop-price">PHP {{ number_format($product->price,2) }}</strong><p class="field-help">{{ $product->stock_quantity>0?number_format($product->stock_quantity).' available':'Out of stock' }}</p>
@if(isset($cart[$product->id]))<a class="button secondary full-width" href="{{ route('customer.cart') }}">In cart: {{ $cart[$product->id] }} &middot; Edit cart</a>
@else<form method="post" action="{{ route('customer.cart.update',$product) }}" data-submit>@csrf @method('put')<input type="hidden" name="quantity" value="1"><button class="button primary full-width" @disabled($product->stock_quantity===0)>Add to cart <x-icon name="plus" :size="17"/></button></form>@endif
</div></article>
@empty<div class="panel empty-state"><x-icon name="products" :size="40"/><h2>No products found</h2><p>{{ request()->filled('q')?'Try another product name.':'Products will appear here when staff adds them.' }}</p></div>@endforelse
</div>
{{ $products->links('components.pagination') }}
@endsection
