<!-- Componente Reutilizable: Badge de Vencimiento -->
@php
    $daysUntilExpiry = \Carbon\Carbon::parse($expiryDate)->diffInDays(\Carbon\Carbon::now());
    $badgeColor = $daysUntilExpiry <= 7 ? 'bg-red-100 text-red-700' : ($daysUntilExpiry <= 15 ? 'bg-orange-100 text-orange-700' : 'bg-yellow-100 text-yellow-700');
@endphp

<span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
    {{ $daysUntilExpiry }}d
</span>
