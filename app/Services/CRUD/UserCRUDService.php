<?php

namespace App\Services\CRUD;

use App\Interfaces\ICRUDInterface;
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

    public function get_new_model_instance(): Model
    {
        return new Model([
            "name" => "",
            "email" => "",
            "password" => "",
            "role" => UserRole::customer->name
        ]);
    }

    /* ---------------------------------
     * CREATE
     * --------------------------------- */
    public function create(array $data, ?User $auth_user): MResponse
    {
        if (!$auth_user || !$auth_user->is_admin()) {
            return MResponse::create([
                'message' => 'Unauthorized operation!',
            ], 403);
        }

        $validator = Validator::make($data, [
            'name'     => ['required', 'string', 'min:3', 'max:64'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', Rule::in(UserRoleArray)],
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
    public function read(int|string $id, ?User $auth_user): MResponse
    {
        if (!$auth_user || (!$auth_user->is_admin() && $auth_user->id != $id)) {
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
    public function readMany(string $queryParams, ?User $auth_user): MResponse
    {
        if (!$auth_user) {
            return MResponse::create([
                'message' => 'Unauthorized operation!',
            ], 403);
        }

        $models = searchFiltered(
            User::query(),
            $queryParams,
            [],
            $auth_user->is_admin()
                ? null
                : fn(Builder $b) => $b->where('id', $auth_user->id)
        );

        return MResponse::create([
            'message' => 'Models filtered successfully',
            'models'  => $models,
        ], 200);
    }

    /* ---------------------------------
     * UPDATE
     * --------------------------------- */
    public function update(int|string $id, array $data, ?User $auth_user): MResponse
    {
        if (!$auth_user || (!$auth_user->is_admin() && $auth_user->id != $id)) {
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

        if ($auth_user->is_admin()) {
            $rules['email'] = [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ];
            $rules['password'] = ['sometimes', 'string', 'min:8', 'confirmed'];
            $rules['role'] = ['sometimes', Rule::in(UserRoleArray)];
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
    public function delete(int|string $id, ?User $auth_user): MResponse
    {
        if (!$auth_user || !$auth_user->is_admin()) {
            return MResponse::create([
                'message' => 'Unauthorized operation!',
            ], 403);
        }

        $ids = is_int($id) ? [$id] : array_map('intval', explode(',', $id));

        // Prevent admin from deleting themselves
        if (in_array($auth_user->id, $ids)) {
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

        return MResponse::create(null, 204);
    }
}
