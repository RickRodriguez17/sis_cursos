@props(['active' => false, 'href'])

<a href="{{ $href }}" @class([
    'block rounded-xl px-3 py-2.5 text-sm transition',
    'bg-indigo-600 text-white shadow-sm' => $active,
    'text-slate-300 hover:bg-slate-800 hover:text-white' => ! $active,
])>
    {{ $slot }}
</a>
