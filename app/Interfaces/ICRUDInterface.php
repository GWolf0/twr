<?php

namespace App\Interfaces;

use App\Models\User;
use App\Types\MResponse;
use Illuminate\Database\Eloquent\Model;

// interface defining crud ops
interface ICRUDInterface
{

    public function getNewModelInstance(): array;

    public function create(array $data, ?User $authUser): MResponse;
    public function read(int|string $id, ?User $authUser): MResponse;
    public function readMany(?string $queryParams, ?User $authUser, int $page = 1, int $perPage = 30): MResponse;
    public function update(int|string $id, array $data, ?User $authUser): MResponse;
    public function delete(int|string $id, ?User $authUser): MResponse;
}
