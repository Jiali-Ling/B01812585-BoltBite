<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploadService
{
    public function uploadAndResize(
        UploadedFile $file,
        string $directory = 'menu-items',
        int $maxWidth = 800,
        int $maxHeight = 800,
        int $quality = 85
    ): string {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $directory . '/' . $filename;

        $manager = new ImageManager(new Driver());
        
        $image = $manager->read($file);

        $image->scale(width: $maxWidth, height: $maxHeight);

        $encoded = $image->toJpeg(quality: $quality);

        Storage::disk('public')->put($path, $encoded);

        return $path;
    }

    public function delete(?string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    public function validate(UploadedFile $file, int $maxSizeMB = 2): array
    {
        $errors = [];

        $maxBytes = $maxSizeMB * 1024 * 1024;
        if ($file->getSize() > $maxBytes) {
            $errors[] = "Image must be less than {$maxSizeMB}MB";
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            $errors[] = "Image must be a JPEG, PNG, GIF, or WebP file";
        }

        $imageInfo = getimagesize($file->getPathname());
        if ($imageInfo) {
            [$width, $height] = $imageInfo;
            if ($width < 200 || $height < 200) {
                $errors[] = "Image must be at least 200x200 pixels";
            }
        }

        return $errors;
    }
}
