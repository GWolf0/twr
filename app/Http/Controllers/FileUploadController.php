<?php

namespace App\Http\Controllers;

use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\JsonResponse;

class FileUploadController extends Controller
{
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
     * DELETE /api/version/file-upload
     */
    public function deleteFile(FileUploadService $fileUploadService, Request $request): JsonResponse
    {
        $mResponse = $fileUploadService->removeUploadedFile($request->all(), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * DELETE /api/version/file-upload-multiple
     */
    public function deleteFiles(FileUploadService $fileUploadService, Request $request): JsonResponse
    {
        $mResponse = $fileUploadService->removeUploadedFiles($request->all(), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * POST /api/version/file-upload/move
     */
    public function moveFile(FileUploadService $fileUploadService, Request $request): JsonResponse
    {
        $mResponse = $fileUploadService->moveFile($request->all(), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * POST /api/version/file-upload/move-multiple
     */
    public function moveFiles(FileUploadService $fileUploadService, Request $request): JsonResponse
    {
        $mResponse = $fileUploadService->moveFiles($request->all(), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }
}
