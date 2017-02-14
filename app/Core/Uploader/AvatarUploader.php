<?php

namespace App\Core\Uploader;

use App\Core\Uploader\Uploader as BaseUploader;
use App\Entities\BaseUser;
use Illuminate\Http\UploadedFile;
use Storage;

class AvatarUploader extends BaseUploader
{
    protected $file;
    protected $user;

    /**
     * AvatarUploader constructor.
     * @param BaseUser $user
     * @param UploadedFile $file
     */
    public function __construct(BaseUser $user, UploadedFile $file)
    {
        parent::__construct($file);
        $this->user = $user;
    }

    protected function getUploadSettings()
    {
        return config('file-upload.avatar');
    }

    protected function getFolderName()
    {
        return 'avatars';
    }

    protected function updateModelAttribute($directory, $fileName)
    {
        $oldAvatar = $this->user->getOriginal('avatar');
        if ($oldAvatar && Storage::exists($oldAvatar)) {
            Storage::delete($oldAvatar);
        }

        $path = $directory . '/' . $fileName;
        $relativePath = clear_pattern(public_path(), $path);
        $this->user->avatar = $relativePath;

        return $this->user->save();
    }
}
