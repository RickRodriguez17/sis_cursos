@extends('layouts.guest')

@section('content')
    <div>
        <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Empieza hoy</p>
        <h1 class="mt-2 text-2xl font-black">Crea tu cuenta</h1>
        <p class="mt-2 text-sm text-slate-500">Guarda tus cursos y aprende a tu ritmo.</p>
        <form class="mt-7 space-y-5" method="post">
            @csrf
            <div><label class="erp-label" for="name">Nombre completo</label><input class="erp-input" id="name" name="name" value="{{ old('name') }}" placeholder="Tu nombre" required>@error('name')<x-input-error :messages="$message" />@enderror</div>
            <div><label class="erp-label" for="email">Correo electrónico</label><input class="erp-input" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="tu@correo.com" required>@error('email')<x-input-error :messages="$message" />@enderror</div>
            <div><label class="erp-label" for="password">Contraseña</label><input class="erp-input" id="password" name="password" type="password" placeholder="Mínimo 8 caracteres" required>@error('password')<x-input-error :messages="$message" />@enderror</div>
            <div><label class="erp-label" for="password_confirmation">Confirmar contraseña</label><input class="erp-input" id="password_confirmation" name="password_confirmation" type="password" placeholder="Repite tu contraseña" required></div>
            <button class="erp-button-primary w-full"><i class="bi bi-person-plus"></i>Crear cuenta</button>
        </form>
        <p class="mt-6 text-center text-sm text-slate-500">¿Ya tienes cuenta? <a class="font-bold text-indigo-600 hover:text-indigo-800" href="{{ route('login') }}">Ingresa</a></p>
    </div>
@endsection
