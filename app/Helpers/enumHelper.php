<?php

namespace App\Helpers;

/**
 * returns enums options as 
 * [
 *  "key" => "value", ...
 * ]
 */
function enumOptions(string $enumClass, bool $capitalize = false): array
{
    return collect($enumClass::cases())->mapWithKeys(function ($case) use ($capitalize) {

        $value = $case instanceof \BackedEnum ? $case->value : $case->name;

        $label = str_replace('_', ' ', $value);

        if ($capitalize) {
            $label = ucfirst($label);
        }

        return [$label => $value];
    })->toArray();
}
