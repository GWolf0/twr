@props([
    'error' => null,
    'key' => null,
])

@php
    $message = null;

    /**
     * Cases:
     * 1) If key exists -> try $errors->first($key)
     * 2) If error is string -> show it directly
     * 3) If error is MessageBag -> try extracting message
     */

    // Case 1: Using Laravel validation bag automatically
    if ($key && isset($errors) && $errors->has($key)) {
        $message = $errors->first($key);
    }

    // Case 2: Custom string passed directly
    elseif (is_string($error)) {
        $message = $error;
    }

    // Case 3: Error array or message bag passed manually
    elseif ($key && is_array($error) && isset($error[$key])) {
        $message = is_array($error[$key]) ? $error[$key][0] : $error[$key];
    }
@endphp

@if ($message)
    <p {{ $attributes->merge([
        'class' => 'mt-1 text-xs text-destructive',
    ]) }}>
        {{ $message }}
    </p>
@endif
