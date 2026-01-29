<?php

namespace App\Types;

// data or error
class DOE
{

    public function __construct(public mixed $data, public ?string $error) {}

    public static function create(mixed $data = null, ?string $error = null): DOE
    {
        return new DOE($data, $error);
    }

    public function is_success(): bool
    {
        return !$this->error;
    }
}
