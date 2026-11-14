@extends('layout',['title'=>'Shopping cart'])
@section('subtitle')Review your items before placing an order.@endsection
@section('actions')<a class="button secondary" href="{{ route('customer.shop') }}"><x-icon name="back" :size="17"/> Continue shopping</a>@endsection
@section('content')
@if(!$rows)<section class="panel empty-state"><x-icon name="bag" :size="42"/><h2>Your cart is empty</h2><p>Add products from the shop to place an order.</p><a class="button primary" href="{{ route('customer.shop') }}">Browse products</a></section>
@else
<div class="shopping-layout"><section class="panel cart-items">
@foreach($rows as $row)
<div class="cart-item"><div class="cart-item-image">@if($row['product']?->image)<img src="{{ Storage::disk('public')->url($row['product']->image) }}" alt="" loading="lazy">@else<x-icon name="products" :size="30"/>@endif</div>
<div class="cart-item-details"><h2>{{ $row['product']?->name??'Unavailable product' }}</h2><p>PHP {{ number_format($row['product']?->price??0,2) }} each</p>
@if(!$row['available'])<p class="field-error">Unavailable quantity. Reduce it or remove this item.</p>@else<p class="field-help">{{ $row['product']->stock_quantity }} available</p>@endif
@if($row['product'])
<form method="post" action="{{ route('customer.cart.update',$row['id']) }}" class="cart-quantity" data-submit>@csrf @method('put')<label class="sr-only" for="quantity-{{ $row['id'] }}">Quantity for {{ $row['product']->name }}</label><input id="quantity-{{ $row['id'] }}" type="number" inputmode="numeric" name="quantity" min="0" max="2147483647" value="{{ $row['quantity'] }}" required><button class="button secondary" type="submit">Update</button><button class="text-danger" name="quantity" value="0" type="submit" formnovalidate>Remove</button></form>
@endif
</div><strong class="cart-line-total">PHP {{ number_format($row['total'],2) }}</strong></div>
@endforeach
</section>
<aside class="panel order-summary"><h2>Order summary</h2><div class="summary-row"><span>Items</span><strong>{{ array_sum(array_column($rows,'quantity')) }}</strong></div><div class="summary-row summary-total"><span>Total</span><strong>PHP {{ number_format($total,2) }}</strong></div><p class="field-help">Payment is due at pickup. Staff confirms payment before completing your order. Stock is not reserved until completion.</p>
<form method="post" action="{{ route('customer.orders.store') }}" data-submit>@csrf<input type="hidden" name="submission_token" value="{{ $token }}"><button class="button primary full-width" @disabled(in_array(false,array_column($rows,'available'),true))>Place order <x-icon name="arrow" :size="17"/></button></form></aside></div>
@endif
@endsection
