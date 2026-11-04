@props(['href', 'title'])
<a class="editorial-entry" href="{{ $href }}"><h3>{{ $title }} <x-arrow /></h3><p>{{ $slot }}</p></a>
