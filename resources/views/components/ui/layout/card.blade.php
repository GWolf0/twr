@props([])

@php

@endphp

<x-ui.layout.paper>
    {{-- // header --}}
    @isset($header)
    <div class="">
        {{$header}}
    </div>
    @endisset

    {{-- // content --}}
    @isset($content)
    <div>
        {{$content}}
    </div>
    @endisset

    {{-- // footer --}}
    @isset($footer)
    <div>
        {{$footer}}
    </div>
    @endisset
    
</x-ui.layout.paper>