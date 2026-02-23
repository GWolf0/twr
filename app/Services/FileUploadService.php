<?php

namespace App\Services;

use App\Interfaces\IFileUploadInterface;
use App\Models\Media;
use App\Models\User;
use App\Types\MResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileUploadService implements IFileUploadInterface
{
    /* -------------------------------------------------------------
        DISK VALIDATIONS
    ------------------------------------------------------------- */
    public function getDiskValidations(): array
    {
        return [
            "images" => ["required", "file", "image", "max:2048"],
            "default" => [
                "required",
                "file",
                "mimes:" . config("twr.file_upload.mimes"),
                "max:" . config("twr.file_upload.max_size_kb"),
            ],
        ];
    }

    /* -------------------------------------------------------------
        SINGLE FILE UPLOAD
    ------------------------------------------------------------- */
    public function uploadFile(array $data, ?User $authUser): MResponse
    {
        if (!$authUser || !$authUser->isAdmin()) {
            return MResponse::create(["message" => "Unauthorized"], 403);
        }

        $disk = $data["disk"] ?? "images";
        $validations = $this->getDiskValidations();
        $fileRules = $validations[$disk] ?? $validations["default"];

        $validator = Validator::make($data, [
            "file" => $fileRules,
            "directory" => ["nullable", "regex:/^[a-zA-Z0-9\/_-]+$/"],
            "file_name" => ["nullable", "string", "min:3", "max:64"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $validated = $validator->validate();

        $directory = $validated["directory"] ?? "";
        $file = $validated["file"];

        // Avoid double extension bug
        $name = isset($validated["file_name"])
            ? $validated["file_name"] . '.' . $file->extension()
            : $file->hashName();

        $path = $file->storeAs($directory, $name, $disk);

        if (!$path) {
            return MResponse::create(["message" => "File upload failed"], 500);
        }

        $url = Storage::disk($disk)->url($path);

        $media = Media::create([
            "type" => $file->getMimeType(),
            "url" => $url,
            "size" => $file->getSize(),
            "user_id" => $authUser->id,
        ]);

        return MResponse::create([
            "message" => "File upload success!",
            "path" => $path,
            "url" => $url,
            "media_id" => $media->id,
        ], 201);
    }

    /* -------------------------------------------------------------
        MULTIPLE FILES UPLOAD
    ------------------------------------------------------------- */
    public function uploadFiles(array $data, ?User $authUser): MResponse
    {
        if (!$authUser || !$authUser->isAdmin()) {
            return MResponse::create(["message" => "Unauthorized"], 403);
        }

        $disk = $data["disk"] ?? "images";
        $validations = $this->getDiskValidations();
        $fileRules = $validations[$disk] ?? $validations["default"];

        $validator = Validator::make($data, [
            "files" => ["required", "array", "min:1"],
            "files.*" => $fileRules,
            "directory" => ["nullable", "regex:/^[a-zA-Z0-9\/_-]+$/"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $validated = $validator->validate();
        $directory = $validated["directory"] ?? "";

        $results = [];

        foreach ($validated["files"] as $file) {
            $result = $this->uploadFile([
                "file" => $file,
                "disk" => $disk,
                "directory" => $directory
            ], $authUser);

            if (!$result->success()) {
                return $result;
            }

            $results[] = $result->data;
        }

        return MResponse::create([
            "message" => "File(s) upload success",
            "files" => $results
        ], 201);
    }

    /* -------------------------------------------------------------
        REMOVE SINGLE FILE
    ------------------------------------------------------------- */
    public function removeUploadedFile(array $data, ?User $authUser): MResponse
    {
        if (!$authUser || !$authUser->isAdmin()) {
            return MResponse::create(["message" => "Unauthorized"], 403);
        }

        $validator = Validator::make($data, [
            "media_id" => ["nullable", "numeric"],
            "path" => ["nullable", "string"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $validated = $validator->validate();

        $media = null;

        if (!empty($validated["media_id"])) {
            $media = Media::find($validated["media_id"]);
        }

        if (!$media && !empty($validated["path"])) {
            $media = Media::where("url", "LIKE", "%" . basename($validated["path"]))->first();
        }

        if (!$media) {
            return MResponse::create(["message" => "Media not found"], 404);
        }

        $relativePath = $this->extractStoragePathFromUrl($media->url);

        if (Storage::disk("images")->exists($relativePath)) {
            Storage::disk("images")->delete($relativePath);
        }

        $media->delete();

        return MResponse::create([
            "message" => "File removed successfully!",
            "success" => true
        ]);
    }

    /* -------------------------------------------------------------
        REMOVE MULTIPLE FILES
    ------------------------------------------------------------- */
    public function removeUploadedFiles(array $data, ?User $authUser): MResponse
    {
        if (!$authUser || !$authUser->isAdmin()) {
            return MResponse::create(["message" => "Unauthorized"], 403);
        }

        $validator = Validator::make($data, [
            "media_ids" => ["required", "array", "min:1"],
            "media_ids.*" => ["numeric"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        foreach ($validator->validate()["media_ids"] as $id) {
            $result = $this->removeUploadedFile(["media_id" => $id], $authUser);

            if (!$result->success()) {
                return $result;
            }
        }

        return MResponse::create([
            "message" => "File(s) removed successfully!",
            "success" => true
        ]);
    }

    /* -------------------------------------------------------------
        MOVE SINGLE FILE
    ------------------------------------------------------------- */
    public function moveFile(array $data, ?User $authUser): MResponse
    {
        if (!$authUser || !$authUser->isAdmin()) {
            return MResponse::create(["message" => "Unauthorized"], 403);
        }

        $validator = Validator::make($data, [
            "media_id" => ["required", "numeric"],
            "to_directory" => ["required", "regex:/^[a-zA-Z0-9\/_-]+$/"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $validated = $validator->validate();

        $media = Media::find($validated["media_id"]);
        if (!$media) {
            return MResponse::create(["message" => "Media not found"], 404);
        }

        $oldPath = $this->extractStoragePathFromUrl($media->url);
        $newPath = rtrim($validated["to_directory"], '/') . '/' . basename($oldPath);

        if (!Storage::disk("images")->exists($oldPath)) {
            return MResponse::create(["message" => "Source file does not exist"], 404);
        }

        Storage::disk("images")->makeDirectory($validated["to_directory"]);

        if (!Storage::disk("images")->move($oldPath, $newPath)) {
            return MResponse::create(["message" => "Failed to move file"], 500);
        }

        $media->update([
            "url" => Storage::disk("images")->url($newPath),
        ]);

        return MResponse::create([
            "message" => "File moved successfully!",
            "new_url" => $media->url
        ]);
    }

    /* -------------------------------------------------------------
        MOVE MULTIPLE FILES
    ------------------------------------------------------------- */
    public function moveFiles(array $data, ?User $authUser): MResponse
    {
        if (!$authUser || !$authUser->isAdmin()) {
            return MResponse::create(["message" => "Unauthorized"], 403);
        }

        $validator = Validator::make($data, [
            "media_ids" => ["required", "array", "min:1"],
            "media_ids.*" => ["numeric"],
            "to_directory" => ["required", "regex:/^[a-zA-Z0-9\/_-]+$/"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        foreach ($validator->validate()["media_ids"] as $id) {
            $result = $this->moveFile([
                "media_id" => $id,
                "to_directory" => $data["to_directory"]
            ], $authUser);

            if (!$result->success()) {
                return $result;
            }
        }

        return MResponse::create([
            "message" => "File(s) moved successfully!",
            "success" => true
        ]);
    }

    /* -------------------------------------------------------------
        HELPER: Extract relative storage path from URL
    ------------------------------------------------------------- */
    private function extractStoragePathFromUrl(string $url): string
    {
        $parsed = parse_url($url, PHP_URL_PATH);
        return Str::after($parsed, '/storage/images/');
    }
}
