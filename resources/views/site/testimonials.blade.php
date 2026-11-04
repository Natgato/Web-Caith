@extends('layouts.site')
@section('title', 'Historias y testimonios')
@section('content')
<section class="page-shell wrap"><h1 class="page-title">Historias compartidas.</h1><p class="lead">Cada relato necesita revisión institucional y consentimiento vigente. Respetamos el nombre público elegido y el derecho a retirarlo.</p><div class="entry-grid">@forelse($stories as $story)<x-entry :href="route('story', $story->slug)" :title="$story->title">{{ $story->public_name }} · {{ $story->testimony_date?->format('d/m/Y') }}</x-entry>@empty<p class="empty-state">Aún no hay testimonios con autorización publicada. No utilizamos relatos inventados para llenar este espacio.</p>@endforelse</div>{{ $stories->links('components.pagination') }}<a class="text-link" href="{{ route('contact', ['tipo' => 'retiro-consentimiento']) }}">Solicitar el retiro de un testimonio <x-arrow /></a></section>
@endsection
