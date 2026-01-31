<?php

namespace App\Http\Controllers;

use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\JsonResponse;

class FileUploadController extends Controller
{
    /**
     * POST /api/file-upload
     */
    public function uploadFile(
        FileUploadService $fileUploadService,
        Request $request,
    ): JsonResponse {
        $m_response = $fileUploadService->uploadFile($request->all(), $request->user());

        return response()->json($m_response->data, $m_response->status);
    }

    /**
     * POST /api/file-upload/multiple
     */
    public function uploadFiles(
        FileUploadService $fileUploadService,
        Request $request,
    ): JsonResponse {
        $m_response = $fileUploadService->uploadFiles($request->all(), $request->user());

        return response()->json($m_response->data, $m_response->status);
    }

    /**
     * DELETE /api/file-upload
     */
    public function deleteFile(
        FileUploadService $fileUploadService,
        Request $request,
    ): JsonResponse {
        $m_response = $fileUploadService->removeUploadedFile($request->all(), $request->user());

        return response()->json($m_response->data, $m_response->status);
    }

    /**
     * DELETE /api/file-upload/multiple
     */
    public function deleteFiles(
        FileUploadService $fileUploadService,
        Request $request,
    ): JsonResponse {
        $m_response = $fileUploadService->removeUploadedFiles($request->all(), $request->user());

        return response()->json($m_response->data, $m_response->status);
    }

    /**
     * POST /api/file-upload/move
     */
    public function moveFile(
        FileUploadService $fileUploadService,
        Request $request,
    ): JsonResponse {
        $m_response = $fileUploadService->moveFile($request->all(), $request->user());

        return response()->json($m_response->data, $m_response->status);
    }

    /**
     * POST /api/file-upload/move/multiple
     */
    public function moveFiles(
        FileUploadService $fileUploadService,
        Request $request,
    ): JsonResponse {
        $m_response = $fileUploadService->moveFiles($request->all(), $request->user());

        return response()->json($m_response->data, $m_response->status);
    }
}
