<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Aula Viva' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-slate-100 font-sans text-slate-900 antialiased">
        <div class="flex min-h-screen flex-col items-center justify-center px-4 py-10">
            <a href="{{ route('home') }}" class="mb-6 text-center">
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-600 text-2xl text-white shadow-lg"><i class="bi bi-mortarboard-fill"></i></span>
                <span class="mt-3 block text-xl font-bold tracking-tight text-slate-800">Aula Viva</span>
                <span class="text-sm text-slate-500">Aprende. Practica. Avanza.</span>
            </a>
            <div class="erp-card w-full max-w-md px-6 py-7 sm:px-8">
                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </div>
        @if (session('success') || session('ok'))
            <script>window.addEventListener('load', () => window.erpToast(@js(session('success') ?? session('ok')), 'success'), { once: true });</script>
        @endif
        @if (session('error'))
            <script>window.addEventListener('load', () => window.erpAlert({ icon: 'error', title: 'No fue posible completar la operación', text: @js(session('error')) }), { once: true });</script>
        @endif
        @if (session('status') || session('info'))
            <script>window.addEventListener('load', () => window.erpToast(@js(session('status') ?? session('info')), 'info'), { once: true });</script>
        @endif
        @livewireScripts
    </body>
</html>
