@props(['file' => null, 'src' => null, 'eager' => false])
<img {{ $attributes->class('inside-art') }} src="{{ $src ?? asset('images/'.$file) }}" alt="" @if($eager) fetchpriority="high" @else loading="lazy" @endif decoding="async">
