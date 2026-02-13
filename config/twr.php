<?php

return [
    // file upload
    "file_upload" => [
        "mimes_array" => ["jpg", "png", "jpeg", "webp"],
        "mimes" => "jpg,png,jpeg,webp",
        "max_size" => 2048000,
        "max_size_kb" => 2048,
        "dirs" => ["images", "videos", "docs"],
        "storage_capacity" => 1e+9 // ~1GB
    ]

];
