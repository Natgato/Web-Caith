<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Acceso administrativo — Casa CAITH</title><link rel="stylesheet" href="{{ asset('css/fonts.css') }}"><link rel="stylesheet" href="{{ asset('css/styles.css') }}"><link rel="stylesheet" href="{{ asset('css/platform.css') }}"><script src="{{ asset('js/forms.js') }}" defer></script></head>
<body><main class="login-shell">
<a class="wordmark" href="{{ route('home') }}"><span class="wordmark-name">Casa CAITH</span></a>
<h1>Acceso administrativo.</h1>
@if($demoEnabled)
<p>Explora el panel y muestra el avance del proyecto con esta cuenta de demostración.</p>
<div class="demo-access" aria-label="Credenciales de demostración"><h2>Cuenta de demostración</h2><dl><dt>Correo</dt><dd>{{ config('caith.demo_email') }}</dd><dt>Contraseña</dt><dd>{{ config('caith.demo_password') }}</dd></dl><p>No necesitas crear una cuenta. Usa datos ficticios durante la presentación.</p></div>
@else
<p>Solo para personas autorizadas. No existe registro público de cuentas.</p>
@endif
<x-notice/>
<form action="{{ route('admin.login.store') }}" method="post">@csrf
<x-field name="email" label="Correo electrónico" type="email" autocomplete="username" :value="$demoEnabled ? config('caith.demo_email') : ''" :required="true"/>
<x-field name="password" label="Contraseña" type="password" autocomplete="current-password" :required="true"/>
<button class="button" type="submit" data-submit>Iniciar sesión</button></form>
<p class="source-note">{{ $demoEnabled ? 'Acceso local para presentar el proyecto Casa CAITH.' : 'La primera cuenta se crea mediante el comando de administración descrito en el README.' }}</p>
</main></body></html>
