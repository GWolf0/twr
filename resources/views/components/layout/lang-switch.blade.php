@php
    $currentLocale = app()->getLocale();

    $options = [
        'English' => 'en',
        '日本語' => 'ja',
    ];
@endphp

<form method="GET" action="{{ route('common.action.lang_switch') }}">
    <x-ui.select name="locale" :options="$options" :initialValue="$currentLocale" onchange="this.form.submit()" {{ $attributes }} />
</form>
