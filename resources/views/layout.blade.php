<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Aula Viva · Cursos online' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-slate-100 font-sans text-slate-900 antialiased">
        <nav class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur">
            <div class="mx-auto flex h-20 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-xl text-white shadow-sm"><i class="bi bi-mortarboard-fill"></i></span>
                    <span><strong class="block text-lg tracking-tight text-slate-900">Aula Viva</strong><small class="hidden text-xs text-slate-500 sm:block">Aprende a tu ritmo</small></span>
                </a>
                <div class="hidden items-center gap-6 text-sm font-medium text-slate-600 md:flex">
                    <a class="{{ request()->routeIs('courses.*') ? 'text-indigo-600' : 'hover:text-indigo-600' }}" href="{{ route('courses.index') }}">Explorar cursos</a>
                    @auth
                        <a class="{{ request()->routeIs('my.courses') ? 'text-indigo-600' : 'hover:text-indigo-600' }}" href="{{ route('my.courses') }}">Mis cursos</a>
                        <a class="{{ request()->routeIs('my.orders') ? 'text-indigo-600' : 'hover:text-indigo-600' }}" href="{{ route('my.orders') }}">Mis órdenes</a>
                    @endauth
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    @auth
                        <a href="{{ route('cart') }}" class="relative rounded-xl p-2.5 text-slate-600 transition hover:bg-indigo-50 hover:text-indigo-600" aria-label="Carrito">
                            <i class="bi bi-bag text-xl"></i>
                            @if (count(session('cart', [])))
                                <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-indigo-600 px-1 text-[11px] font-bold text-white">{{ count(session('cart', [])) }}</span>
                            @endif
                        </a>
                        <div class="hidden items-center gap-2 border-l border-slate-200 pl-3 sm:flex">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-indigo-700"><i class="bi bi-person-fill"></i></span>
                            <span class="max-w-28 truncate text-sm font-semibold">{{ auth()->user()->name }}</span>
                        </div>
                        @if (auth()->user()->is_admin)
                            <a class="hidden rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800 lg:inline-flex" href="{{ route('admin.index') }}"><i class="bi bi-speedometer2 mr-2"></i>Admin</a>
                        @endif
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button class="hidden rounded-xl p-2.5 text-slate-500 hover:bg-rose-50 hover:text-rose-600 sm:block" title="Cerrar sesión"><i class="bi bi-box-arrow-right text-lg"></i></button>
                        </form>
                    @else
                        <a class="hidden text-sm font-semibold text-slate-600 hover:text-indigo-600 sm:inline" href="{{ route('login') }}">Ingresar</a>
                        <a class="erp-button-primary" href="{{ route('register') }}">Crear cuenta</a>
                    @endauth
                </div>
            </div>
        </nav>
        <main class="mx-auto min-h-[calc(100vh-10rem)] max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @if (session('success') || session('ok'))
                <script>window.addEventListener('load', () => window.erpToast(@js(session('success') ?? session('ok')), 'success'), { once: true });</script>
            @endif
            @if (session('error'))
                <script>window.addEventListener('load', () => window.erpAlert({ icon: 'error', title: 'No fue posible completar la operación', text: @js(session('error')) }), { once: true });</script>
            @endif
            @if (session('status') || session('info'))
                <script>window.addEventListener('load', () => window.erpToast(@js(session('status') ?? session('info')), 'info'), { once: true });</script>
            @endif
            @if ($errors->any())
                <script>window.addEventListener('load', () => window.erpAlert({ icon: 'error', title: 'Revisa los datos', text: @js($errors->first()) }), { once: true });</script>
            @endif
            @yield('content')
        </main>
        <footer class="border-t border-slate-200 bg-white">
            <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                <span>© {{ now()->year }} Aula Viva</span>
                <span>Aprende habilidades que abren nuevas oportunidades.</span>
            </div>
        </footer>
        @livewireScripts
    </body>
</html>
