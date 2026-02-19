<!-- Componente Reutilizable: Tarjeta de Estadística -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide">{{ $title }}</h3>
        <div class="w-12 h-12 {{ $iconBgColor }} rounded-lg flex items-center justify-center">
            <i class="{{ $icon }} {{ $iconColor }} text-lg"></i>
        </div>
    </div>
    <p class="text-3xl font-bold text-slate-900">{{ $value }}</p>
    <p class="text-xs text-slate-500 mt-2">{{ $subtitle }}</p>
</div>
