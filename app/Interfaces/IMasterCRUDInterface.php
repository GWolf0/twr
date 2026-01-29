<?php

namespace App\Interfaces;

use App\Models\User;
use App\Types\MResponse;
use Illuminate\Database\Eloquent\Model;

// interface defining crud ops
interface IMasterCRUDInterface
{

    public function get_new_model_instance(string|null $table): Model|null;

    public function create(string|null $table, array $data, ?User $auth_user): MResponse;
    public function read(string|null $table, int|string $id, ?User $auth_user): MResponse;
    public function readMany(string|null $table, string $queryParams, ?User $auth_user): MResponse;
    public function update(string|null $table, int|string $id, array $data, ?User $auth_user): MResponse;
    public function delete(string|null $table, int|string $id, ?User $auth_user): MResponse;
}
