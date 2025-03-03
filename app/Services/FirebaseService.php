<?php

namespace App\Services;

use Google\Cloud\Storage\StorageClient;

class FirebaseService
{
    protected $storage;

    public function __construct()
    {
        $firebaseCredentials = [
            'type' => 'service_account',
            'project_id' => env('FIREBASE_PROJECT_ID'),
            'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID'),
            'private_key' => str_replace('\n', "\n", env('FIREBASE_PRIVATE_KEY')),  // Substitui \n corretamente
            'client_email' => env('FIREBASE_CLIENT_EMAIL'),
            'client_id' => env('FIREBASE_CLIENT_ID'),
            'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
            'token_uri' => 'https://oauth2.googleapis.com/token',
            'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
            'client_x509_cert_url' => env('FIREBASE_CLIENT_X509_CERT_URL'),
        ];

        $this->storage = new StorageClient([
            'keyFile' => $firebaseCredentials, // Passa as credenciais diretamente aqui
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
