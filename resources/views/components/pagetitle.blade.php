@props(['title', 'icon' => 'bi-grid-1x2', 'section' => 'Inicio', 'subtitle' => null])

<div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
    <div class="flex items-center gap-3">
        <span class="erp-icon"><i class="bi {{ $icon }}"></i></span>
        <div>
            <nav class="mb-1 text-xs text-slate-400" aria-label="Breadcrumb">
                <a href="{{ auth()->check() && auth()->user()->is_admin ? route('admin.index') : route('home') }}" class="hover:text-indigo-600">Inicio</a>
                <span class="mx-1">/</span>
                <span>{{ $section }}</span>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $title }}</h1>
            @if ($subtitle)
                <p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    @if (isset($actions) && trim((string) $actions))
        <div>{{ $actions }}</div>
    @endif
</div>
