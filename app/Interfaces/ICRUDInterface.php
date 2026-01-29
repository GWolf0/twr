<?php

namespace App\Interfaces;

use App\Models\User;
use App\Types\MResponse;
use Illuminate\Database\Eloquent\Model;

// interface defining crud ops
interface ICRUDInterface
{

    public function get_new_model_instance(): Model;

    public function create(array $data, ?User $auth_user): MResponse;
    public function read(int|string $id, ?User $auth_user): MResponse;
    public function readMany(string $queryParams, ?User $auth_user): MResponse;
    public function update(int|string $id, array $data, ?User $auth_user): MResponse;
    public function delete(int|string $id, ?User $auth_user): MResponse;
}
