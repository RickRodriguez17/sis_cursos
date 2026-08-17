<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Administración · Aula Viva' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-slate-100 font-sans text-slate-900 antialiased">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen">
            <div x-cloak x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/60 lg:hidden" @click="sidebarOpen = false"></div>
            <aside class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col bg-slate-900 text-white shadow-xl transition-transform duration-300 lg:translate-x-0" :class="{ 'translate-x-0': sidebarOpen }">
                <div class="flex h-20 shrink-0 items-center justify-between border-b border-slate-800 px-6">
                    <a href="{{ route('admin.index') }}" class="flex items-center gap-3">
                        <span class="erp-icon bg-indigo-600 text-white"><i class="bi bi-mortarboard-fill"></i></span>
                        <span><strong class="block tracking-tight">Aula Viva</strong><small class="text-xs text-slate-400">Panel administrativo</small></span>
                    </a>
                    <button type="button" class="rounded-lg p-2 text-slate-400 hover:bg-slate-800 lg:hidden" @click="sidebarOpen = false"><i class="bi bi-x-lg"></i></button>
                </div>
                <nav class="flex-1 space-y-2 overflow-y-auto px-3 py-5">
                    <a href="{{ route('admin.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm {{ request()->routeIs('admin.index') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"><i class="bi bi-grid-1x2-fill w-5 text-center"></i>Panel</a>
                    <x-sidebar-group title="Catálogo" icon="bi-collection-play" :active="request()->routeIs('admin.courses.*')">
                        <x-sidebar-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.*')"><i class="bi bi-journal-richtext mr-2"></i>Cursos y videos</x-sidebar-link>
                    </x-sidebar-group>
                    <x-sidebar-group title="Operaciones" icon="bi-receipt" :active="false">
                        <x-sidebar-link :href="route('admin.index').'#ordenes'"><i class="bi bi-receipt mr-2"></i>Órdenes y pagos</x-sidebar-link>
                        <x-sidebar-link :href="route('admin.index').'#inscripciones'"><i class="bi bi-people mr-2"></i>Inscripciones</x-sidebar-link>
                    </x-sidebar-group>
                </nav>
                <div class="border-t border-slate-800 p-4">
                    <a href="{{ route('courses.index') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-white"><i class="bi bi-box-arrow-up-right"></i>Ver tienda</a>
                    <form class="mt-2" method="post" action="{{ route('logout') }}">@csrf<button class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-sm text-rose-300 hover:bg-rose-950/40"><i class="bi bi-box-arrow-left"></i>Cerrar sesión</button></form>
                </div>
            </aside>
            <div class="min-w-0 lg:pl-72">
                <header class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-slate-200 bg-white/95 px-4 shadow-sm backdrop-blur sm:px-6">
                    <div class="flex items-center gap-3">
                        <button type="button" class="rounded-xl p-2 text-slate-500 hover:bg-slate-100 lg:hidden" @click="sidebarOpen = true"><i class="bi bi-list text-2xl"></i></button>
                        <span class="hidden text-sm text-slate-500 sm:inline"><i class="bi bi-shield-check mr-1 text-indigo-600"></i>Gestión de Aula Viva</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="hidden text-right sm:block"><strong class="block text-sm">{{ auth()->user()->name }}</strong><small class="text-xs text-slate-500">{{ auth()->user()->email }}</small></span>
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 text-indigo-700"><i class="bi bi-person-fill"></i></span>
                    </div>
                </header>
                <main class="mx-auto max-w-7xl px-4 py-7 sm:px-6 lg:px-8">
                    @yield('content')
                </main>
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
