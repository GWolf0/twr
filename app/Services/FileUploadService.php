<?php

namespace App\Services;

use App\Interfaces\IFileUploadInterface;
use App\Models\User;
use App\Types\MResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class FileUploadService implements IFileUploadInterface
{
    public function uploadFile(
        array $data,
        ?User $auth_user,
    ): MResponse {
        if (!$auth_user || !$auth_user->is_admin()) {
            return MResponse::create(["message" => "Unauthorized"], 403);
        }

        $validator = Validator::make($data, [
            "file" => ["required", "file", "mimes:" . config("twr.file_upload_mimes"), "max:" . config("twr.file_upload_max_size_kb")],
            "directory" => ["nullable", Rule::in(config("twr.file_upload_dirs"))],
            "file_name" => ["nullable", "string", "min:3", "max:64"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $validated = $validator->validate();
        $file = $validated["file"];
        $directory = $validated["directory"] ?? "images";

        $name = isset($validated["file_name"])
            ? $validated["file_name"] . '.' . $file->getClientOriginalExtension()
            : Str::uuid() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs($directory, $name);

        if (!$path) {
            return MResponse::create(["message" => "File upload failed"], 500);
        }

        return MResponse::create([
            "path" => $path,
        ], 201);
    }

    public function uploadFiles(
        array $data,
        ?User $auth_user,
    ): MResponse {
        if (!$auth_user || !$auth_user->is_admin()) {
            return MResponse::create(["message" => "Unauthorized"], 403);
        }

        $validator = Validator::make($data, [
            "files" => ["required", "array", "min:1"],
            "files.*" => ["file"],
            "directory" => ["nullable", Rule::in(config("twr.file_upload_dirs"))],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $validated = $validator->validate();
        $directory = $validated["directory"] ?? "images";

        $paths = [];

        foreach ($validated["files"] as $file) {
            $result = $this->uploadFile([
                "file" => $file,
                "directory" => $directory,
            ], $auth_user);

            if (!$result->success()) {
                return $result;
            }

            $paths[] = $result->data["path"];
        }

        return MResponse::create(["paths" => $paths], 201);
    }

    public function removeUploadedFile(
        array $data,
        ?User $auth_user,
    ): MResponse {
        if (!$auth_user || !$auth_user->is_admin()) {
            return MResponse::create(["message" => "Unauthorized"], 403);
        }

        $validator = Validator::make($data, [
            "path" => ["required", "string"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $path = $validator->validate()["path"];

        if (!Storage::exists($path)) {
            return MResponse::create(["message" => "File does not exist"], 404);
        }

        if (!Storage::delete($path)) {
            return MResponse::create(["message" => "Failed to delete file"], 500);
        }

        return MResponse::create(true);
    }

    public function removeUploadedFiles(
        array $data,
        ?User $auth_user,
    ): MResponse {
        if (!$auth_user || !$auth_user->is_admin()) {
            return MResponse::create(["message" => "Unauthorized"], 403);
        }

        $validator = Validator::make($data, [
            "paths" => ["required", "array", "min:1"],
            "paths.*" => ["string"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        foreach ($validator->validate()["paths"] as $path) {
            $result = $this->removeUploadedFile(["path" => $path], $auth_user);
            if (!$result->success()) {
                return $result;
            }
        }

        return MResponse::create(true);
    }

    public function moveFile(
        array $data,
        ?User $auth_user,
    ): MResponse {
        if (!$auth_user || !$auth_user->is_admin()) {
            return MResponse::create(["message" => "Unauthorized"], 403);
        }

        $validator = Validator::make($data, [
            "from" => ["required", "string"],
            "to" => ["required", "string"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $validated = $validator->validate();

        if (!Storage::exists($validated["from"])) {
            return MResponse::create(["message" => "Source file does not exist"], 404);
        }

        if (!Storage::move($validated["from"], $validated["to"])) {
            return MResponse::create(["message" => "Failed to move file"], 500);
        }

        return MResponse::create(["path" => $validated["to"]]);
    }

    public function moveFiles(
        array $data,
        ?User $auth_user,
    ): MResponse {
        if (!$auth_user || !$auth_user->is_admin()) {
            return MResponse::create(["message" => "Unauthorized"], 403);
        }

        $validator = Validator::make($data, [
            "paths" => ["required", "array", "min:1"],
            "paths.*" => ["string"],
            "to_directory" => ["required", "string"],
        ]);

        if ($validator->fails()) {
            return MResponse::create($validator->errors(), 422);
        }

        $validated = $validator->validate();
        $newPaths = [];

        foreach ($validated["paths"] as $path) {
            $newPath = rtrim($validated["to_directory"], '/') . '/' . basename($path);

            $result = $this->moveFile([
                "from" => $path,
                "to" => $newPath,
            ], $auth_user);

            if (!$result->success()) {
                return $result;
            }

            $newPaths[] = $newPath;
        }

        return MResponse::create(["paths" => $newPaths]);
    }
}
