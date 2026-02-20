<?php

namespace App\Services\CRUD;

use App\Interfaces\ICRUDInterface;
use App\Models\User;
use App\Models\Vehicle;
use App\Types\MResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function App\Helpers\searchFiltered;

class VehicleCRUDService implements ICRUDInterface
{
    public function getNewModelInstance(): array
    {
        return [
            
        ];
    }

    public function create(array $data, ?User $authUser): MResponse
    {
        if (!$authUser || !$authUser->isAdmin()) {
            return MResponse::create(['message' => 'Unauthorized operation!'], 403);
        }

        $validator = Validator::make($data, [
            'name' => ['required', 'string'],
            'type' => ['required', 'string'],
            'media' => ['nullable', 'string'],
            'price_per_hour' => ['required', 'numeric'],
            'availability' => ['required', 'string', Rule::in(Vehicle::Availabilities())],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $model = Vehicle::create($validator->validated());

        return MResponse::create(['message' => 'New vehicle created successfully', 'model' => $model], 201);
    }

    public function read(int|string $id, ?User $authUser): MResponse
    {
        $model = Vehicle::find($id);

        if (!$model) {
            return MResponse::create(['message' => 'Model not found!'], 404);
        }

        return MResponse::create(['message' => 'Model read successfully', 'model' => $model]);
    }

    public function readMany(?string $queryParams, ?User $authUser, int $page = 1, int $perPage = 30): MResponse
    {
        $models = searchFiltered(Vehicle::query(), $queryParams)->paginate(perPage: $perPage, page: $page);
        return MResponse::create(['message' => 'Models filtered successfully', 'models' => $models]);
    }

    public function update(int|string $id, array $data, ?User $authUser): MResponse
    {
        if (!$authUser || !$authUser->isAdmin()) {
            return MResponse::create(['message' => 'Unauthorized operation!'], 403);
        }

        $model = Vehicle::find($id);

        if (!$model) {
            return MResponse::create(['message' => 'Model not found!'], 404);
        }

        $validator = Validator::make($data, [
            'name' => ['sometimes', 'string'],
            'type' => ['sometimes', 'string'],
            'media' => ['nullable', 'string'],
            'price_per_hour' => ['sometimes', 'numeric'],
            'availability' => ['sometimes', 'string', Rule::in(Vehicle::Availabilities())],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $model->update($validator->validated());

        return MResponse::create(['message' => 'Model updated successfully', 'model' => $model]);
    }

    public function delete(int|string $id, ?User $authUser): MResponse
    {
        if (!$authUser || !$authUser->isAdmin()) {
            return MResponse::create(['message' => 'Unauthorized operation!'], 403);
        }

        $ids = is_int($id) ? [$id] : array_map('intval', explode(',', $id));
        $deleted = Vehicle::destroy($ids);

        if ($deleted < 1) {
            return MResponse::create(['message' => 'No models were deleted.'], 400);
        }

        return MResponse::create([
            "message" => "Model(s) deleted successfully!",
            "success" => true,
        ], 204);
    }
}
