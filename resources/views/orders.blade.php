@extends('layout',['title'=>$customerView?'My orders':'Customer orders'])
@section('subtitle'){{ $customerView?'Track orders you placed for pickup.':'Confirm payment and complete customer pickup orders.' }}@endsection
@section('actions')@if($customerView)<a class="button primary" href="{{ route('customer.shop') }}">Browse products <x-icon name="arrow" :size="17"/></a>@endif@endsection
@section('content')
<section class="table-card">
@if(!$customerView)<nav class="tabs" aria-label="Order status">@foreach(['pending'=>'Pending','completed'=>'Completed','cancelled'=>'Cancelled','all'=>'All orders'] as $key=>$label)<a href="{{ route('orders.index',['status'=>$key]) }}" @class(['is-active'=>$status===$key])>{{ $label }}</a>@endforeach</nav>@endif
@if($orders->count())<div class="table-scroll"><table><thead><tr><th>Order</th>@if(!$customerView)<th>Customer</th>@endif<th>Status</th><th>Total</th><th>Placed</th><th></th></tr></thead><tbody>
@foreach($orders as $order)<tr><td><strong>{{ $order->number() }}</strong><div class="field-help">{{ $order->items_count }} product{{ $order->items_count===1?'':'s' }}</div></td>@if(!$customerView)<td>{{ $order->customer_name }}<div class="field-help">{{ $order->customer_email }}</div></td>@endif<td><span @class(['badge','badge-warning'=>$order->status==='pending','badge-success'=>$order->status==='completed','badge-muted'=>$order->status==='cancelled'])>{{ ucfirst($order->status) }}</span></td><td class="numeric">PHP {{ number_format($order->total_price,2) }}</td><td>{{ $order->created_at->format('M j, Y, g:i a') }}</td><td><a class="button secondary" href="{{ route($customerView?'customer.orders.show':'orders.show',$order->id) }}">View order</a></td></tr>@endforeach
</tbody></table></div>@else<div class="empty-state"><x-icon name="bag" :size="40"/><h2>No {{ !$customerView&&$status!=='all'?$status.' ':'' }}orders</h2><p>{{ $customerView?'Orders you place from the shop will appear here.':'Customer pickup orders will appear here.' }}</p></div>@endif
</section>{{ $orders->links('components.pagination') }}
@endsection
