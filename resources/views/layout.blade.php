<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Aula Viva' }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        @livewireStyles
    </head>
    <body class="bg-slate-50 text-slate-800">
        <nav class="bg-indigo-700 text-white">
            <div class="mx-auto flex max-w-6xl justify-between px-6 py-4">
                <a href="{{ route('home') }}" class="text-xl font-bold">Aula Viva</a>
                <div class="flex items-center gap-4">
                    <a href="{{ route('courses.index') }}">Cursos</a>
                    @auth
                        <a href="{{ route('my.courses') }}">Mis cursos</a>
                        <a href="{{ route('cart') }}">Carrito ({{ count(session('cart', [])) }})</a>
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.index') }}">Administración</a>
                        @endif
                        <form method="post" action="/logout">
                            @csrf
                            <button>Salir</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}">Ingresar</a>
                        <a href="{{ route('register') }}">Crear cuenta</a>
                    @endauth
                </div>
            </div>
        </nav>
        <main class="mx-auto max-w-6xl px-6 py-10">
            @if (session('ok'))
                <div class="mb-6 rounded bg-emerald-100 p-4 text-emerald-800">{{ session('ok') }}</div>
            @endif
            @if (session('info'))
                <div class="mb-6 rounded bg-blue-100 p-4 text-blue-800">{{ session('info') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-6 rounded bg-red-100 p-4 text-red-800">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded bg-red-100 p-4 text-red-800">{{ $errors->first() }}</div>
            @endif
            @yield('content')
        </main>
        @livewireScripts
    </body>
</html>
