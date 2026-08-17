<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div class="relative flex-1">
        <input {{ $attributes->merge(['class' => 'erp-input pl-10']) }} placeholder="Buscar...">
        <i class="bi bi-search pointer-events-none absolute left-3 top-3 text-slate-400"></i>
    </div>
    {{ $slot }}
</div>
