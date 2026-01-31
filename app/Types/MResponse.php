<?php

namespace App\Types;

class MResponse
{

    public function __construct(public mixed $data, public int $status) {}

    public static function create(mixed $data = [], int $status = 200): MResponse
    {
        return new MResponse($data, $status);
    }

    public function success(): bool
    {
        return $this->status < 400;
    }
    public function failed(): bool
    {
        return $this->status >= 400;
    }
}
