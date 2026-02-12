@props([
    'action' => null,
    'method' => 'POST',
])

@php
    $method = strtoupper($method);
@endphp

<form method="{{ $method === 'GET' ? 'GET' : 'POST' }}" action="{{ $action }}"
    {{ $attributes->merge([
        'class' => 'space-y-6',
    ]) }}>
    @if ($method !== 'GET')
        @csrf
    @endif

    @if (!in_array($method, ['GET', 'POST']))
        @method($method)
    @endif

    {{ $slot }}
</form>
