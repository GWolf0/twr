<?php

namespace App\Services\CRUD;

use App\Interfaces\ICRUDInterface;
use App\Models\Setting;
use App\Models\User;
use App\Types\MResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use function App\Helpers\searchFiltered;

class SettingCRUDService implements ICRUDInterface
{
    public function getNewModelInstance(): Model
    {
        return new Setting();
    }

    public function create(array $data, ?User $authUser): MResponse
    {
        // deny create
        return MResponse::create(['message' => 'Cannot create settings record manually!'], 403);

        // below ignored
        if (!$authUser || !$authUser->isAdmin()) {
            return MResponse::create(['message' => 'Unauthorized operation!'], 403);
        }

        $validator = Validator::make($data, [
            'business_name' => ['required', 'string'],
            'business_description' => ['nullable', 'string'],
            'business_phone_number' => ['required', 'string'],
            'business_addresses' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $model = Setting::create($validator->validated());

        return MResponse::create(['message' => 'New setting created successfully', 'model' => $model], 201);
    }

    public function read(int|string $id, ?User $authUser): MResponse
    {
        // Settings are typically read all at once, but we can implement this for consistency.
        $model = Setting::find($id);

        if (!$model) {
            return MResponse::create(['message' => 'Model not found!'], 404);
        }

        return MResponse::create(['message' => 'Model read successfully', 'model' => $model]);
    }

    public function readMany(string $queryParams, ?User $authUser, int $page = 1, int $perPage = 30): MResponse
    {
        $models = searchFiltered(Setting::query(), $queryParams)->paginate(perPage: $perPage, page: $page);
        return MResponse::create(['message' => 'Models filtered successfully', 'models' => $models]);
    }

    public function update(int|string $id, array $data, ?User $authUser): MResponse
    {
        if (!$authUser || !$authUser->isAdmin()) {
            return MResponse::create(['message' => 'Unauthorized operation!'], 403);
        }

        $model = Setting::find($id);

        if (!$model) {
            return MResponse::create(['message' => 'Model not found!'], 404);
        }

        $validator = Validator::make($data, [
            'business_name' => ['sometimes', 'string'],
            'business_description' => ['nullable', 'string'],
            'business_email' => ['nullable', 'string', "email"],
            'business_phone_number' => ['nullable', 'string'],
            'business_addresses' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $model->update($validator->validated());
        Setting::invalidateInstance(); // do not forget to invalidate instance

        return MResponse::create(['message' => 'Model updated successfully', 'model' => $model]);
    }

    public function delete(int|string $id, ?User $authUser): MResponse
    {
        // deny delete
        return MResponse::create(['message' => 'Cannot delete settings record!'], 403);

        // below ignored
        if (!$authUser || !$authUser->isAdmin()) {
            return MResponse::create(['message' => 'Unauthorized operation!'], 403);
        }

        $ids = is_int($id) ? [$id] : array_map('intval', explode(',', $id));
        $deleted = Setting::destroy($ids);

        if ($deleted < 1) {
            return MResponse::create(['message' => 'No models were deleted.'], 400);
        }

        return MResponse::create([
            "message" => "Model(s) deleted successfully!",
            "success" => true,
        ], 204);
    }
}
