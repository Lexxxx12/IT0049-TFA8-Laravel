@extends('layout',['title'=>$order->number()])
@section('subtitle')Placed {{ $order->created_at->format('F j, Y \a\t g:i a') }}@endsection
@section('actions')<a class="button secondary" href="{{ route($customerView?'customer.orders.index':'orders.index') }}"><x-icon name="back" :size="17"/> All orders</a>@endsection
@section('content')
<div class="shopping-layout"><section class="panel"><div class="panel-body"><div class="order-detail-heading"><h2>Order details</h2><span @class(['badge','badge-warning'=>$order->status==='pending','badge-success'=>$order->status==='completed','badge-muted'=>$order->status==='cancelled'])>{{ ucfirst($order->status) }}</span></div>
@if(!$customerView)<div class="order-contact"><strong>{{ $order->customer_name }}</strong><span>{{ $order->customer_email }}</span><span>{{ $order->customer_phone??'No phone number provided' }}</span></div>@endif
@foreach($order->items as $item)<div class="order-item"><div><strong>{{ $item->product_name }}</strong><p class="field-help">{{ $item->quantity }} &times; PHP {{ number_format($item->unit_price,2) }}</p>@if($item->sale_id)<a href="{{ route($customerView?'customer.receipts.show':'receipts.show',$item->sale_id) }}">View receipt {{ $item->sale->receiptNumber() }}</a>@endif</div><strong>PHP {{ number_format($item->total_price,2) }}</strong></div>@endforeach
</div></section>
<aside class="panel order-summary"><h2>Payment &amp; pickup</h2><div class="summary-row summary-total"><span>Total</span><strong>PHP {{ number_format($order->total_price,2) }}</strong></div>
@if($order->status==='pending')<p class="field-help">{{ $customerView?'Pay at pickup. Staff will confirm payment and complete your order. Stock is not reserved until completion.':'Collect the full amount before confirming. Completing this order records the sales under your staff account and reduces stock.' }}</p>
@if($customerView)<form method="post" action="{{ route('customer.orders.cancel',$order->id) }}" data-confirm="Cancel this order?">@csrf<button class="button secondary full-width">Cancel order</button></form>
@else<form method="post" action="{{ route('orders.complete',$order) }}" data-submit data-confirm="Confirm payment received and complete this order?">@csrf<button type="submit" class="button primary full-width">Confirm payment &amp; complete</button></form><form method="post" action="{{ route('orders.cancel',$order) }}" data-confirm="Cancel this customer order?">@csrf<button class="button secondary full-width">Cancel order</button></form>@endif
@elseif($order->status==='completed')<p class="field-help">Payment confirmed. Completed {{ $order->completed_at->format('M j, Y, g:i a') }}. Receipts are available for each product.</p>@else<p class="field-help">This order was cancelled. No payment or stock changes were recorded.</p>@endif
</aside></div>
@endsection
