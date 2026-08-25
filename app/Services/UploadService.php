<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    /**
     * Handle file upload.
     *
     * @param UploadedFile $file
     * @param string $path
     * @return string|false
     */
    public function upload(UploadedFile $file, string $path = 'uploads')
    {
        // Gets the configured disk from .env (FILESYSTEM_DISK), defaults to 'public'
        $disk = config('filesystems.default', 'public');
        
        $filename = time() . '_' . $file->getClientOriginalName();
        $filename = str_replace(' ', '_', $filename);

        return $file->storeAs($path, $filename, $disk);
    }

    /**
     * Handle file deletion.
     *
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        $disk = config('filesystems.default', 'public');

        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }

        return false;
    }
}
