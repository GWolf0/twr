<?php

namespace App\Services;

use App\Interfaces\IFileUploadInterface;
use App\Types\DOE;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService implements IFileUploadInterface
{
    public function uploadFile(
        UploadedFile $file,
        string $directory,
        array $acceptedMimes = [],
        int $maxSize = 0,
        ?string $fileName = null
    ): DOE {
        // mime validation
        if (!empty($acceptedMimes) && !in_array($file->getMimeType(), $acceptedMimes, true)) {
            return DOE::create(null, 'Invalid file type');
        }

        // size validation (bytes)
        if ($maxSize > 0 && $file->getSize() > $maxSize) {
            return DOE::create(null, 'File size exceeds limit');
        }

        $name = $fileName
            ? $fileName . '.' . $file->getClientOriginalExtension()
            : Str::uuid() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs($directory, $name);

        if (!$path) {
            return DOE::create(null, 'File upload failed');
        }

        return DOE::create($path);
    }

    public function uploadFiles(
        array $files,
        string $directory,
        array $acceptedMimes = [],
        int $maxSize = 0
    ): DOE {
        $paths = [];

        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) {
                return DOE::create(null, 'Invalid file input');
            }

            $result = $this->uploadFile($file, $directory, $acceptedMimes, $maxSize);

            if (!$result->is_success()) {
                return $result;
            }

            $paths[] = $result->data;
        }

        return DOE::create($paths);
    }

    public function removeUploadedFile(string $filePath): DOE
    {
        if (!Storage::exists($filePath)) {
            return DOE::create(null, 'File does not exist');
        }

        return Storage::delete($filePath)
            ? DOE::create(true)
            : DOE::create(null, 'Failed to delete file');
    }

    public function removeUploadedFiles(array $filePaths): DOE
    {
        foreach ($filePaths as $path) {
            $result = $this->removeUploadedFile($path);
            if (!$result->is_success()) {
                return $result;
            }
        }

        return DOE::create(true);
    }

    public function moveFile(string $from, string $to): DOE
    {
        if (!Storage::exists($from)) {
            return DOE::create(null, 'Source file does not exist');
        }

        if (!Storage::move($from, $to)) {
            return DOE::create(null, 'Failed to move file');
        }

        return DOE::create($to);
    }

    public function moveFiles(array $filePaths, string $toDirectory): DOE
    {
        $newPaths = [];

        foreach ($filePaths as $path) {
            $fileName = basename($path);
            $newPath  = rtrim($toDirectory, '/') . '/' . $fileName;

            $result = $this->moveFile($path, $newPath);

            if (!$result->is_success()) {
                return $result;
            }

            $newPaths[] = $newPath;
        }

        return DOE::create($newPaths);
    }
}
