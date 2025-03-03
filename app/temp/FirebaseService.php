<?php

namespace App\Services;

use Google\Cloud\Storage\StorageClient;

class FirebaseService
{
    protected $storage;

    public function __construct()
    {
        $this->storage = new StorageClient([
            'keyFilePath' => storage_path('app/firebase_credentials.json'),
        ]);
    }

    public function uploadImage($file, $filename)
    {
        $bucket = $this->storage->bucket(env('FIREBASE_STORAGE_BUCKET'));
        $bucket->upload(fopen($file->getRealPath(), 'r'), [
            'name' => $filename
        ]);
        
        return "https://firebasestorage.googleapis.com/v0/b/" . env('FIREBASE_STORAGE_BUCKET') . "/o/" . urlencode($filename) . "?alt=media";
    }
}
