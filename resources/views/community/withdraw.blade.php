@extends('layouts.site')
@section('title', 'Retirar publicación')
@section('content')
<div class="wrap community-auth">
    <div class="workspace-panel">
        <a class="workspace-text-link" href="{{ route('community.index', ['tipo' => $type]) }}">← Mis publicaciones</a>
        <h1>Retirar publicación</h1>
        <p><strong>{{ $entry->title }}</strong></p>
        <p>El texto y cualquier foto asociada dejarán de estar disponibles de inmediato. Para volver a compartirlo, tendrás que enviarlo a revisión con una nueva autorización.</p>
        <x-notice />
        <form method="post" action="{{ route('community.withdraw', [$type, $entry->id]) }}">
            @csrf
            <label class="check-row"><input type="checkbox" name="confirm" value="1" required><span>Confirmo que quiero retirar esta publicación.</span></label>
            <div class="workspace-actions">
                <button class="button danger" type="submit" data-submit>Confirmar retiro</button>
                <a class="workspace-text-link" href="{{ route('community.index', ['tipo' => $type]) }}">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
