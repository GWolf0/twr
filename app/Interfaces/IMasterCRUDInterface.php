<?php

namespace App\Interfaces;

use App\Models\User;
use App\Types\MResponse;
use Illuminate\Database\Eloquent\Model;

// interface defining crud ops
interface IMasterCRUDInterface
{

    public function getNewModelInstance(string|null $table): Model|null;

    public function create(string|null $table, array $data, ?User $authUser): MResponse;
    public function read(string|null $table, int|string $id, ?User $authUser): MResponse;
    public function readMany(string|null $table, string $queryParams, ?User $authUser, int $page = 1, int $perPage = 30): MResponse;
    public function update(string|null $table, int|string $id, array $data, ?User $authUser): MResponse;
    public function delete(string|null $table, int|string $id, ?User $authUser): MResponse;
}
