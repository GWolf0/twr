<?php

namespace App\Interfaces;

use App\Types\DOE;
use Illuminate\Http\UploadedFile;

interface IFileUploadInterface
{
    /**
     * Upload a single file.
     * Returns DOE->data = uploaded file path on success
     */
    public function uploadFile(
        UploadedFile $file,
        string $directory,
        array $acceptedMimes = [],
        int $maxSize = 0,
        ?string $fileName = null
    ): DOE;

    /**
     * Upload multiple files.
     * Returns DOE->data = array of uploaded file paths
     */
    public function uploadFiles(
        array $files, // UploadedFile[]
        string $directory,
        array $acceptedMimes = [],
        int $maxSize = 0
    ): DOE;

    /**
     * Remove a single uploaded file.
     */
    public function removeUploadedFile(string $filePath): DOE;

    /**
     * Remove multiple uploaded files.
     */
    public function removeUploadedFiles(array $filePaths): DOE;

    /**
     * Move a file to a new location.
     * Returns DOE->data = new file path
     */
    public function moveFile(string $from, string $to): DOE;

    /**
     * Move multiple files to a directory.
     * Returns DOE->data = array of new file paths
     */
    public function moveFiles(array $filePaths, string $toDirectory): DOE;
}
