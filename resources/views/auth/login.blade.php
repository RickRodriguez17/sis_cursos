@extends('layouts.guest')

@section('content')
    <div>
        <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">
            Bienvenido de nuevo
        </p>
        <h1 class="mt-2 text-2xl font-black">Ingresar a tu cuenta</h1>
        <p class="mt-2 text-sm text-slate-500">
            Continúa tu ruta de aprendizaje.
        </p>
        <form class="mt-7 space-y-5" method="post">
            @csrf
            <div>
                <label class="erp-label" for="email">Correo electrónico</label>
                <input
                    class="erp-input"
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    placeholder="tu@correo.com"
                    required
                >
                @error('email')
                    <x-input-error :messages="$message" />
                @enderror
            </div>
            <div>
                <label class="erp-label" for="password">Contraseña</label>
                <input
                    class="erp-input"
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Tu contraseña"
                    required
                >
                @error('password')
                    <x-input-error :messages="$message" />
                @enderror
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input class="erp-checkbox" type="checkbox" name="remember">
                Recordarme
            </label>
            <button class="erp-button-primary w-full">
                <i class="bi bi-box-arrow-in-right"></i>
                Ingresar
            </button>
        </form>
        <p class="mt-6 text-center text-sm text-slate-500">
            ¿No tienes cuenta?
            <a class="font-bold text-indigo-600 hover:text-indigo-800" href="{{ route('register') }}">
                Regístrate
            </a>
        </p>
    </div>
@endsection
