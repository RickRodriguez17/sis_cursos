@extends('layout')

@section('content')
    <div class="mx-auto max-w-md rounded-2xl bg-white p-8 shadow">
        <h1 class="text-3xl font-black">Crear cuenta</h1>
        <form class="mt-6 space-y-4" method="post">
            @csrf
            <input class="w-full rounded border p-3" name="name" placeholder="Nombre" required>
            <input class="w-full rounded border p-3" name="email" type="email" placeholder="Correo" required>
            <input class="w-full rounded border p-3" name="password" type="password" placeholder="Contraseña (mínimo 8)" required>
            <input class="w-full rounded border p-3" name="password_confirmation" type="password" placeholder="Repite la contraseña" required>
            <button class="w-full rounded bg-indigo-700 p-3 font-bold text-white">Crear cuenta</button>
        </form>
    </div>
@endsection
