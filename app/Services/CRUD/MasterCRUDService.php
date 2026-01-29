<?php

namespace App\Services\CRUD;

use App\Interfaces\ICRUDInterface;
use Illuminate\Contracts\Container\Container;
use App\Interfaces\IMasterCRUDInterface;
use App\Models\User;
use App\Types\MResponse;
use Illuminate\Database\Eloquent\Model;

class MasterCRUDService implements IMasterCRUDInterface
{
    private const MAP = [
        'users' => UserCRUDService::class,
    ];

    public function __construct(
        private Container $container
    ) {}

    private function resolve(string|null $table): ?ICRUDInterface
    {
        if (!array_key_exists($table, self::MAP)) return null;

        return $this->container->make(self::MAP[$table]);
    }

    private function get_fallback_response(): MResponse
    {
        return MResponse::create([
            "message" => "Error binding crud handler!"
        ], 500);
    }

    public function get_new_model_instance(string|null $table): Model|null {
        $handler = $this->resolve($table);

        if (!$handler) return null;

        return $handler->get_new_model_instance();
    }

    /* ---------------------------------
     * CREATE
     * --------------------------------- */
    public function create(string|null $table, array $data, ?User $auth_user): MResponse
    {
        $handler = $this->resolve($table);

        if (!$handler) return $this->get_fallback_response();

        return $handler->create($data, $auth_user);
    }

    /* ---------------------------------
     * READ (single)
     * --------------------------------- */
    public function read(string|null $table, int|string $id, ?User $auth_user): MResponse
    {
        $handler = $this->resolve($table);

        if (!$handler) return $this->get_fallback_response();

        return $handler->read($id, $auth_user);
    }

    /* ---------------------------------
     * READ MANY
     * --------------------------------- */
    public function readMany(string|null $table, string $queryParams, ?User $auth_user): MResponse
    {
        $handler = $this->resolve($table);

        if (!$handler) return $this->get_fallback_response();

        return $handler->readMany($queryParams, $auth_user);
    }

    /* ---------------------------------
     * UPDATE
     * --------------------------------- */
    public function update(string|null $table, int|string $id, array $data, ?User $auth_user): MResponse
    {
        $handler = $this->resolve($table);

        if (!$handler) return $this->get_fallback_response();

        return $handler->update($id, $data, $auth_user);
    }

    /* ---------------------------------
     * DELETE
     * --------------------------------- */
    public function delete(string|null $table, int|string $id, ?User $auth_user): MResponse
    {
        $handler = $this->resolve($table);

        if (!$handler) return $this->get_fallback_response();

        return $handler->delete($id, $auth_user);
    }
}
