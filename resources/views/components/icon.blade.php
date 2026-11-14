@props(['name','size'=>20])
<svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
@switch($name)
@case('counter')<rect x="3" y="3" width="18" height="14" rx="3"/><path d="M8 21h8M12 17v4M7 8h4M7 12h9"/>@break
@case('products')<path d="m12 3 9 5-9 5-9-5 9-5ZM3 8v9l9 5 9-5V8M12 13v9M7.5 5.5l9 5"/>@break
@case('customers')<circle cx="9" cy="8" r="3"/><path d="M3 21v-3a6 6 0 0 1 12 0v3M16 5a3 3 0 0 1 0 6M18 15a5 5 0 0 1 3 5"/>@break
@case('staff')<rect x="4" y="5" width="16" height="16" rx="3"/><path d="M9 5V3h6v2M8 17h8"/><circle cx="12" cy="11" r="2"/>@break
@case('receipt')<path d="M6 3h12v19l-3-2-3 2-3-2-3 2V3ZM9 7h6M9 11h6M9 15h3"/>@break
@case('search')<circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 4.5 4.5"/>@break
@case('plus')<path d="M12 5v14M5 12h14"/>@break
@case('arrow')<path d="M5 12h14m-5-5 5 5-5 5"/>@break
@case('back')<path d="M19 12H5m5-5-5 5 5 5"/>@break
@case('logout')<path d="M9 4H5v16h4M10 12h11m-4-4 4 4-4 4"/>@break
@case('check')<path d="m5 12 4 4L19 6"/>@break
@case('print')<path d="M7 7V3h10v4M7 17H3V8h18v9h-4M7 14h10v7H7v-7M17 11h.01"/>@break
@case('lock')<rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3"/>@break
@case('edit')<path d="m15 5 4 4M4 20l4-1L21 6l-4-4L4 15v5ZM13 20h8"/>@break
@case('upload')<path d="M12 16V3m-5 5 5-5 5 5M4 16v5h16v-5"/>@break
@case('chevron')<path d="m9 5 7 7-7 7"/>@break
@case('filter')<path d="M4 6h16M7 12h10M10 18h4"/>@break
@case('bag')<path d="M5 7h14l2 14H3L5 7ZM8 8V6a4 4 0 0 1 8 0v2"/>@break
@case('mail')<rect x="3" y="5" width="18" height="14" rx="3"/><path d="m3 7 9 6 9-6"/>@break
@case('alert')<circle cx="12" cy="12" r="9"/><path d="M12 7v6M12 17h.01"/>@break
@case('eye')<path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>@break
@default<circle cx="12" cy="8" r="4"/><path d="M4 22v-2a8 8 0 0 1 16 0v2"/>
@endswitch
</svg>
