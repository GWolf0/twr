<?php

namespace App\Interfaces;

use App\Models\User;
use App\Types\DOE;
use App\Types\MResponse;
use Illuminate\Http\UploadedFile;

interface IFileUploadInterface
{
    /**
     * Upload a single file.
     * Returns DOE->data = uploaded file path on success
     */
    public function uploadFile(array $data, ?User $auth_user): MResponse;

    /**
     * Upload multiple files.
     * Returns DOE->data = array of uploaded file paths
     */
    public function uploadFiles(array $data, ?User $auth_user): MResponse;

    /**
     * Remove a single uploaded file.
     */
    public function removeUploadedFile(array $data, ?User $auth_user): MResponse;

    /**
     * Remove multiple uploaded files.
     */
    public function removeUploadedFiles(array $data, ?User $auth_user): MResponse;

    /**
     * Move a file to a new location.
     * Returns DOE->data = new file path
     */
    public function moveFile(array $data, ?User $auth_user): MResponse;

    /**
     * Move multiple files to a directory.
     * Returns DOE->data = array of new file paths
     */
    public function moveFiles(array $data, ?User $auth_user): MResponse;
}
