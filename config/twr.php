<?php

return [
    // file upload
    "file_upload" => [
        "mimes_array" => ["jpg", "png", "jpeg", "webp"],
        "mimes" => "jpg,png,jpeg,webp",
        "max_size" => 2048000, // ~2MB // per user
        "max_size_kb" => 2048,
        "storage_capacity" => 2e+7, // ~20MB per user
        "max_files" => 20, // max files to upload per user
    ]

];
