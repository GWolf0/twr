<?php

namespace App\Services\CRUD;

use App\Interfaces\ICRUDInterface;
use App\Misc\Enums\UserRole as EnumsUserRole;
use App\Misc\UserRole;
use App\Models\User;
use App\Types\MResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function App\Helpers\searchFiltered;

class UserCRUDService implements ICRUDInterface
{

    public function getNewModelInstance(): Model
    {
        return new Model([
            "name" => "",
            "email" => "",
            "password" => "",
            "role" => EnumsUserRole::customer->name
        ]);
    }

    /* ---------------------------------
     * CREATE
     * --------------------------------- */
    public function create(array $data, ?User $authUser): MResponse
    {
        if (!$authUser || !$authUser->isAdmin()) {
            return MResponse::create([
                'message' => 'Unauthorized operation!',
            ], 403);
        }

        $validator = Validator::make($data, [
            'name'     => ['required', 'string', 'min:3', 'max:64'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', Rule::in(User::Roles())],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $validated = $validator->validate();

        $validated['password'] = Hash::make($validated['password']);

        $model = User::create($validated);

        return MResponse::create([
            'message' => 'New model created successfully',
            'model'   => $model,
        ], 201);
    }

    /* ---------------------------------
     * READ (single)
     * --------------------------------- */
    public function read(int|string $id, ?User $authUser): MResponse
    {
        if (!$authUser || (!$authUser->isAdmin() && $authUser->id != $id)) {
            return MResponse::create([
                'message' => 'Unauthorized operation!',
            ], 403);
        }

        $model = User::find($id);
        if (!$model) {
            return MResponse::create([
                'message' => 'Model not found!',
            ], 404);
        }

        return MResponse::create([
            'message' => 'Model read successfully',
            'model'   => $model,
        ], 200);
    }

    /* ---------------------------------
     * READ MANY
     * --------------------------------- */
    public function readMany(string $queryParams, ?User $authUser, int $page = 1, int $perPage = 30): MResponse
    {
        if (!$authUser) {
            return MResponse::create([
                'message' => 'Unauthorized operation!',
            ], 403);
        }

        $models = searchFiltered(
            User::query(),
            $queryParams,
            [],
            $authUser->isAdmin()
                ? null
                : fn(Builder $b) => $b->where('id', $authUser->id)
        )->paginate(perPage: $perPage, page: $page);

        return MResponse::create([
            'message' => 'Models filtered successfully',
            'models'  => $models,
        ], 200);
    }

    /* ---------------------------------
     * UPDATE
     * --------------------------------- */
    public function update(int|string $id, array $data, ?User $authUser): MResponse
    {
        if (!$authUser || (!$authUser->isAdmin() && $authUser->id != $id)) {
            return MResponse::create([
                'message' => 'Unauthorized operation!',
            ], 403);
        }

        $model = User::find($id);
        if (!$model) {
            return MResponse::create([
                'message' => 'Model not found!',
            ], 404);
        }

        $rules = [
            'name' => ['sometimes', 'string', 'min:3', 'max:64'],
        ];

        if ($authUser->isAdmin()) {
            $rules['email'] = [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ];
            $rules['password'] = ['sometimes', 'string', 'min:8', 'confirmed'];
            $rules['role'] = ['sometimes', Rule::in(User::Roles())];
        }

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $validated = $validator->validate();

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $model->update($validated);

        return MResponse::create([
            'message' => 'Model updated successfully',
            'model'   => $model,
        ], 200);
    }

    /* ---------------------------------
     * DELETE
     * --------------------------------- */
    public function delete(int|string $id, ?User $authUser): MResponse
    {
        if (!$authUser || !$authUser->isAdmin()) {
            return MResponse::create([
                'message' => 'Unauthorized operation!',
            ], 403);
        }

        $ids = is_int($id) ? [$id] : array_map('intval', explode(',', $id));

        // Prevent admin from deleting themselves
        if (in_array($authUser->id, $ids)) {
            return MResponse::create([
                'message' => 'Cannot delete your own account.',
            ], 400);
        }

        $deleted = User::destroy($ids);

        if ($deleted < 1) {
            return MResponse::create([
                'message' => 'No models were deleted.',
            ], 400);
        }

        return MResponse::create([
            "message" => "Model(s) deleted successfully!",
            "success" => true,
        ], 204);
    }
}
