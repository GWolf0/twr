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

    public function getNewModelInstance(): Model
    {
        return new Media();
    }

    public function create(array $data, ?User $authUser): MResponse
    {
        $uploadResult = $this->fileUploadService->uploadFile($data, $authUser);

        if (!$uploadResult->success()) {
            return $uploadResult;
        }

        $file = $uploadResult["file"];

        $media = Media::create([
            'type' => $file->getMimeType(),
            'url' => $uploadResult->data,
            'size' => $file->getSize(),
            'user_id' => $authUser->id,
        ]);

        return MResponse::create(['message' => 'File uploaded successfully', 'model' => $media], 201);
    }

    public function read(int|string $id, ?User $authUser): MResponse
    {
        // if (!$authUser) {
        //     return MResponse::create(['message' => 'Unauthorized operation!'], 403);
        // }

        $model = Media::find($id);

        if (!$model) {
            return MResponse::create(['message' => 'Model not found!'], 404);
        }

        // Optional: Add ownership check
        // if (!$authUser->is_admin() && $authUser->id !== $model->user_id) {
        //     return MResponse::create(['message' => 'Unauthorized operation!'], 403);
        // }

        return MResponse::create(['message' => 'Model read successfully', 'model' => $model]);
    }

    public function readMany(string $queryParams, ?User $authUser, int $page = 1, int $perPage = 30): MResponse
    {
        // if (!$authUser) {
        //     return MResponse::create(['message' => 'Unauthorized operation!'], 403);
        // }

        $query = Media::query();

        // Optional: Add ownership filtering for non-admins
        // if (!$authUser->is_admin()) {
        //     $query->where('user_id', $authUser->id);
        // }

        $models = searchFiltered($query, $queryParams)->paginate(perPage: $perPage, page: $page);

        return MResponse::create(['message' => 'Models filtered successfully', 'models' => $models]);
    }

    public function update(int|string $id, array $data, ?User $authUser): MResponse
    {
        return MResponse::create(['message' => 'Update operation is not supported for Media.'], 405);
    }

    public function delete(int|string $id, ?User $authUser): MResponse
    {
        if (!$authUser || !$authUser->isAdmin()) {
            return MResponse::create(['message' => 'Unauthorized operation!'], 403);
        }

        $ids = is_int($id) ? [$id] : array_map('intval', explode(',', $id));

        $models = Media::findMany($ids);

        foreach ($models as $model) {
            $mResponse = $this->fileUploadService->removeUploadedFile(["path" => $model["path"]], $authUser);
            if (!$mResponse->success()) {
                return $mResponse;
            }

            $model->delete();
        }

        return MResponse::create([
            "message" => "Model(s) deleted successfully!",
            "success" => true,
        ], 204);
    }
}
