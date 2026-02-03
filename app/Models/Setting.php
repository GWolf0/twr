<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'business_name',
        'business_description',
        'business_phone_number',
        'business_addresses',
    ];

    public static function instance(): self
    {
        return cache()->rememberForever(
            'settings',
            fn() => self::firstOrCreate(
                ['id' => 1],
                [
                    'business_name' => 'Business Name',
                    'business_description' => 'Description (optional)',
                    'business_phone_number' => 'Phone number',
                    'business_addresses' => 'Address 1, Address 2',
                ]
            )
        );
    }

    public static function invalidateInstance()
    {
        cache()->forget('settings');
    }
}
