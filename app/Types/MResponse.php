<?php

namespace App\Types;

class MResponse
{

    public function __construct(public mixed $data, public int $status) {}

    public static function create(mixed $data = [], int $status = 200): MResponse
    {
        return new MResponse($data, $status);
    }
}
