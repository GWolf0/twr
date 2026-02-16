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
        'vehicles' => VehicleCRUDService::class,
        'bookings' => BookingCRUDService::class,
        'settings' => SettingCRUDService::class,
        'media' => MediaCRUDService::class,
    ];

    public function __construct(
        private Container $container
    ) {}

    private function resolve(string|null $table): ?ICRUDInterface
    {
        if (!array_key_exists($table, self::MAP)) return null;

        return $this->container->make(self::MAP[$table]);
    }

    private function getFallbackResponse(): MResponse
    {
        return MResponse::create([
            "message" => "Error binding crud handler!"
        ], 500);
    }

    public function getNewModelInstance(string|null $table): Model|null
    {
        $handler = $this->resolve($table);

        if (!$handler) return null;

        return $handler->getNewModelInstance();
    }

    /* ---------------------------------
     * CREATE
     * --------------------------------- */
    public function create(string|null $table, array $data, ?User $authUser): MResponse
    {
        $handler = $this->resolve($table);

        if (!$handler) return $this->getFallbackResponse();

        return $handler->create($data, $authUser);
    }

    /* ---------------------------------
     * READ (single)
     * --------------------------------- */
    public function read(string|null $table, int|string $id, ?User $authUser): MResponse
    {
        $handler = $this->resolve($table);

        if (!$handler) return $this->getFallbackResponse();

        return $handler->read($id, $authUser);
    }

    /* ---------------------------------
     * READ MANY
     * --------------------------------- */
    public function readMany(string|null $table, string $queryParams, ?User $authUser, int $page = 1, int $perPage = 30): MResponse
    {
        $handler = $this->resolve($table);

        if (!$handler) return $this->getFallbackResponse();

        return $handler->readMany($queryParams, $authUser, $page, $perPage);
    }

    /* ---------------------------------
     * UPDATE
     * --------------------------------- */
    public function update(string|null $table, int|string $id, array $data, ?User $authUser): MResponse
    {
        $handler = $this->resolve($table);

        if (!$handler) return $this->getFallbackResponse();

        return $handler->update($id, $data, $authUser);
    }

    /* ---------------------------------
     * DELETE
     * --------------------------------- */
    public function delete(string|null $table, int|string $id, ?User $authUser): MResponse
    {
        $handler = $this->resolve($table);

        if (!$handler) return $this->getFallbackResponse();

        return $handler->delete($id, $authUser);
    }
}
