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
    public function get_new_model_instance(): Model
    {
        return new Setting();
    }

    public function create(array $data, ?User $auth_user): MResponse
    {
        // deny create
        return MResponse::create(['message' => 'Cannot create settings record manually!'], 403);

        // below ignored
        if (!$auth_user || !$auth_user->is_admin()) {
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

    public function read(int|string $id, ?User $auth_user): MResponse
    {
        // Settings are typically read all at once, but we can implement this for consistency.
        $model = Setting::find($id);

        if (!$model) {
            return MResponse::create(['message' => 'Model not found!'], 404);
        }

        return MResponse::create(['message' => 'Model read successfully', 'model' => $model]);
    }

    public function readMany(string $queryParams, ?User $auth_user): MResponse
    {
        $models = searchFiltered(Setting::query(), $queryParams);
        return MResponse::create(['message' => 'Models filtered successfully', 'models' => $models]);
    }

    public function update(int|string $id, array $data, ?User $auth_user): MResponse
    {
        if (!$auth_user || !$auth_user->is_admin()) {
            return MResponse::create(['message' => 'Unauthorized operation!'], 403);
        }

        $model = Setting::find($id);

        if (!$model) {
            return MResponse::create(['message' => 'Model not found!'], 404);
        }

        $validator = Validator::make($data, [
            'business_name' => ['sometimes', 'string'],
            'business_description' => ['nullable', 'string'],
            'business_phone_number' => ['sometimes', 'string'],
            'business_addresses' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $model->update($validator->validated());
        Setting::invalidate_instance(); // do not forget to invalidate instance

        return MResponse::create(['message' => 'Model updated successfully', 'model' => $model]);
    }

    public function delete(int|string $id, ?User $auth_user): MResponse
    {
        // deny delete
        return MResponse::create(['message' => 'Cannot delete settings record!'], 403);

        // below ignored
        if (!$auth_user || !$auth_user->is_admin()) {
            return MResponse::create(['message' => 'Unauthorized operation!'], 403);
        }

        $ids = is_int($id) ? [$id] : array_map('intval', explode(',', $id));
        $deleted = Setting::destroy($ids);

        if ($deleted < 1) {
            return MResponse::create(['message' => 'No models were deleted.'], 400);
        }

        return MResponse::create(null, 204);
    }
}
