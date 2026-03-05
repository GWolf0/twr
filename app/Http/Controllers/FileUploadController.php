<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\JsonResponse;

class FileUploadController extends Controller
{
    /**
     * GET /api/version/file-upload
     */
    public function getUploadedFiles(FileUploadService $fus, Request $request): JsonResponse
    {
        $mResponse = $fus->getUploadedFiles($request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * POST /api/version/file-upload
     */
    public function uploadFile(FileUploadService $fileUploadService, Request $request): JsonResponse
    {
        // disable file uploading in demo mode
        if (config("app.demo")) {
            return response()->json(["message" => "Cannot upload files in demo mode"], 403);
        }

        $mResponse = $fileUploadService->uploadFile($request->all(), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * POST /api/version/file-upload-multiple
     */
    public function uploadFiles(FileUploadService $fileUploadService, Request $request): JsonResponse
    {
        // disable file uploading in demo mode
        if (config("app.demo")) {
            return response()->json(["message" => "Cannot upload files in demo mode"], 403);
        }

        $mResponse = $fileUploadService->uploadFiles($request->all(), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * DELETE /api/version/file-upload/{id}
     */
    public function deleteFile(FileUploadService $fileUploadService, Request $request, string $id): JsonResponse
    {
        // disable file deleting in demo mode
        if (config("app.demo")) {
            return response()->json(["message" => "Cannot delete files in demo mode"], 403);
        }

        $mResponse = $fileUploadService->removeUploadedFile(array_merge($request->all(), $request->route()->parameters()), $request->user());

        return response()->json($mResponse->data, $mResponse->status);
    }

    /**
     * DELETE /api/version/file-upload/many/{ids}
     */
    public function deleteFiles(FileUploadService $fileUploadService, Request $request, string $ids): JsonResponse
    {
        // disable file deleting in demo mode
        if (config("app.demo")) {
            return response()->json(["message" => "Cannot delete files in demo mode"], 403);
        }
        
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
