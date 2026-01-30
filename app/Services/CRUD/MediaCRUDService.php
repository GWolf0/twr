<?php

namespace App\Services\CRUD;

use App\Interfaces\ICRUDInterface;
use App\Models\Media;
use App\Models\User;
use App\Services\FileUploadService;
use App\Types\MResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use function App\Helpers\searchFiltered;

class MediaCRUDService implements ICRUDInterface
{
    protected FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function get_new_model_instance(): Model
    {
        return new Media();
    }

    public function create(array $data, ?User $auth_user): MResponse
    {
        if (!$auth_user || !$auth_user->is_admin()) {
            return MResponse::create(['message' => 'Unauthorized operation!'], 403);
        }

        $validator = Validator::make($data, [
            'file' => ['required', 'file'],
            'directory' => ['sometimes', 'string'],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $validated = $validator->validated();

        $file = $validated['file'];
        $directory = $validated['directory'] ?? 'uploads';

        $uploadResult = $this->fileUploadService->uploadFile($file, $directory);

        if (!$uploadResult->is_success()) {
            return MResponse::create(['message' => $uploadResult->error], 400);
        }

        $media = Media::create([
            'type' => $file->getMimeType(),
            'url' => $uploadResult->data,
            'size' => $file->getSize(),
            'user_id' => $auth_user->id,
        ]);

        return MResponse::create(['message' => 'File uploaded successfully', 'model' => $media], 201);
    }

    public function read(int|string $id, ?User $auth_user): MResponse
    {
        // if (!$auth_user) {
        //     return MResponse::create(['message' => 'Unauthorized operation!'], 403);
        // }

        $model = Media::find($id);

        if (!$model) {
            return MResponse::create(['message' => 'Model not found!'], 404);
        }

        // Optional: Add ownership check
        // if (!$auth_user->is_admin() && $auth_user->id !== $model->user_id) {
        //     return MResponse::create(['message' => 'Unauthorized operation!'], 403);
        // }

        return MResponse::create(['message' => 'Model read successfully', 'model' => $model]);
    }

    public function readMany(string $queryParams, ?User $auth_user): MResponse
    {
        // if (!$auth_user) {
        //     return MResponse::create(['message' => 'Unauthorized operation!'], 403);
        // }

        $query = Media::query();

        // Optional: Add ownership filtering for non-admins
        // if (!$auth_user->is_admin()) {
        //     $query->where('user_id', $auth_user->id);
        // }

        $models = searchFiltered($query, $queryParams);

        return MResponse::create(['message' => 'Models filtered successfully', 'models' => $models]);
    }

    public function update(int|string $id, array $data, ?User $auth_user): MResponse
    {
        return MResponse::create(['message' => 'Update operation is not supported for Media.'], 405);
    }

    public function delete(int|string $id, ?User $auth_user): MResponse
    {
        if (!$auth_user || !$auth_user->is_admin()) {
            return MResponse::create(['message' => 'Unauthorized operation!'], 403);
        }

        $ids = is_int($id) ? [$id] : array_map('intval', explode(',', $id));

        $models = Media::findMany($ids);

        foreach ($models as $model) {
            // Optional: Add ownership check
            // if (!$auth_user->is_admin() && $auth_user->id !== $model->user_id) {
            //     continue; // Skip unauthorized deletions
            // }
            $this->fileUploadService->removeUploadedFile($model->url);
            $model->delete();
        }

        return MResponse::create(null, 204);
    }
}
