<?php

namespace App\Helpers;

function convertCurrency($amount, $from, $to)
{
    $rates = [
        'USD' => 1,
        'JPY' => 158, // example rate
    ];

    return $amount * ($rates[$to] / $rates[$from]);
}

function getCurrency()
{
    return app()->getLocale() === 'ja' ? 'JPY' : 'USD';
}

function formatCurrency($amount, $currency)
{
    if ($currency === 'JPY') {
        return '¥' . number_format(round($amount));
    }

    return '$' . number_format($amount, 2);
}

function currency($usdAmount)
{
    $currency = getCurrency();
    $amount = convertCurrency($usdAmount, "USD", $currency);
    return formatCurrency($amount, $currency);
}
