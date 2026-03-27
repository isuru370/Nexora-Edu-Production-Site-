<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class UploadBackupToGoogle extends Command
{
    protected $signature = 'backup:upload-google';
    protected $description = 'Upload latest backup to Google Drive';

    public function handle()
    {
        // backup files ganna
        $files = Storage::disk('local')->allFiles();

        $zipFiles = collect($files)->filter(function ($file) {
            return strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'zip';
        });

        if ($zipFiles->isEmpty()) {
            $this->error('No backup zip files found!');
            return 1;
        }

        $latestFile = $zipFiles->sortDesc()->first();

        $this->info('Uploading: ' . $latestFile);

        $filePath = storage_path('app/' . $latestFile);

        // Google Client setup
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $client->refreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));

        $service = new Google_Service_Drive($client);

        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' => basename($latestFile),
            'parents' => [env('GOOGLE_DRIVE_FOLDER_ID')],
        ]);

        $content = file_get_contents($filePath);

        $file = $service->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => 'application/zip',
            'uploadType' => 'multipart',
        ]);

        $this->info('Upload success ✅');
        return 0;
    }
}