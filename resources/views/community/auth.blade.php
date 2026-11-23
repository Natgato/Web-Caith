@extends('layouts.site')
@section('title', $register ? 'Crear cuenta' : 'Entrar al espacio de usuario')
@section('content')
<div class="wrap community-auth">
    <div class="workspace-panel">
        <a class="workspace-text-link" href="{{ route('opinions') }}">Opiniones de la comunidad <x-arrow /></a>
        <h1>{{ $register ? 'Crea tu cuenta' : 'Tu espacio en Casa CAITH' }}</h1>
        <p>Escribe una opinión o un testimonio y elige el nombre con el que quieres firmar.</p>
        <x-notice />
        <form method="post" action="{{ route($register ? 'community.register.store' : 'community.login.store') }}">
            @csrf
            @if($register)<x-field name="name" label="Nombre de tu cuenta (privado)" autocomplete="name" required />@endif
            <x-field name="email" label="Correo electrónico" type="email" autocomplete="email" required />
            <x-field name="password" label="{{ $register ? 'Contraseña (mínimo 10 caracteres)' : 'Contraseña' }}" type="password" :autocomplete="$register ? 'new-password' : 'current-password'" required />
            @if($register)
                <x-field name="password_confirmation" label="Repite la contraseña" type="password" autocomplete="new-password" required />
                <label class="check-row"><input type="checkbox" name="privacy" value="1" @checked(old('privacy')) required><span>He leído el <a href="{{ route('privacy') }}">aviso de privacidad</a> y acepto el uso de mis datos para la cuenta y sus publicaciones.</span></label>
            @endif
            <button class="button" type="submit" data-submit>{{ $register ? 'Crear cuenta' : 'Entrar' }}</button>
        </form>
        <p class="workspace-auth-switch"><a href="{{ route($register ? 'community.login' : 'community.register') }}">{{ $register ? 'Ya tengo una cuenta' : 'Crear una cuenta' }}</a></p>
    </div>
</div>
@endsection
