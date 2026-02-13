<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\JsonResponse;

class FileUploadController extends Controller
{
    /**
     * GET /api/version/file-upload
     */
    public function getUploadedFiles(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(["message" => "Unauthorized"], 401);
        }

        $filesQuery = $user->media();

        $usedBytes = (clone $filesQuery)->sum('size');

        $files = $filesQuery->latest()->get(); // switch to paginated when /managers/fileUploadManager.js updates for that

        return response()->json([
            "files" => $files,
            "usage" => [
                "used" => $usedBytes,
                "total" => config('twr.file_upload.storage_capacity'),
            ]
        ]);
    }

    /**
     * POST /api/version/file-upload
     */
    public function uploadFile(FileUploadService $fileUploadService, Request $request): JsonResponse
    {
        $mResponse = $fileUploadService->uploadFile($request->all(), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * POST /api/version/file-upload-multiple
     */
    public function uploadFiles(FileUploadService $fileUploadService, Request $request): JsonResponse
    {
        $mResponse = $fileUploadService->uploadFiles($request->all(), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * DELETE /api/version/file-upload/{id}
     */
    public function deleteFile(FileUploadService $fileUploadService, Request $request, string $id): JsonResponse
    {
        $mResponse = $fileUploadService->removeUploadedFile(array_merge($request->all(), $request->route()->parameters()), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * DELETE /api/version/file-upload/many/{ids}
     */
    public function deleteFiles(FileUploadService $fileUploadService, Request $request, string $ids): JsonResponse
    {
        $mResponse = $fileUploadService->removeUploadedFiles(array_merge($request->all(), $request->route()->parameters()), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * PUT /api/version/file-upload/{id}
     */
    public function moveFile(FileUploadService $fileUploadService, Request $request, string $id): JsonResponse
    {
        $mResponse = $fileUploadService->moveFile(array_merge($request->all(), $request->route()->parameters()), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * PUT /api/version/file-upload/many/{ids}
     */
    public function moveFiles(FileUploadService $fileUploadService, Request $request, string $ids): JsonResponse
    {
        $mResponse = $fileUploadService->moveFiles(array_merge($request->all(), $request->route()->parameters()), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }
}
