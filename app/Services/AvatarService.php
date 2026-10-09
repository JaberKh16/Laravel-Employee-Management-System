<?php

namespace App\Services;

use App\Models\Profile;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Http\UploadedFile;

class AvatarService
{
    public function __construct(private FilesystemFactory $filesystem)
    {
    }

    /**
     * Replace a profile's avatar. Deletes the old file if present.
     *
     * @return string  the new stored path (relative to disk root)
     */
    public function replace(Profile $profile, UploadedFile $file): string
    {
        $disk = $this->filesystem->disk('public');

        // Delete old
        if ($profile->avatar && $disk->exists($profile->avatar)) {
            $disk->delete($profile->avatar);
        }

        // Store new — same logic as before, no Storage facade
        $path = $file->store('avatars/' . $profile->user_id, 'public');

        return $path;
    }

    public function delete(?string $path): void
    {
        if (!$path)
            return;
        $disk = $this->filesystem->disk('public');
        if ($disk->exists($path)) {
            $disk->delete($path);
        }
    }

    public function url(?string $path): ?string
    {
        if (!$path)
            return null;
        return $this->filesystem->disk('public')->url($path);
    }
}