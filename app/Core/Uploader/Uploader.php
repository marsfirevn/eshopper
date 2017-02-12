<?php

namespace App\Core\Uploader;

use File;
use Storage;
use Illuminate\Http\UploadedFile;

abstract class Uploader
{
    protected $file;
    protected $settings;

    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
        $this->settings = $this->getUploadSettings();
    }

    abstract protected function getUploadSettings();
    abstract protected function getFolderName();
    abstract protected function updateModelAttribute($directory, $fileName);

    /**
     * Save image
     *
     * @return string|false
     */
    public function make()
    {
        $directory = $this->generateDirectory();
        $fileName = $this->constructFileName();
        File::exists($directory) || File::makeDirectory($directory, 0776, true, true);
        $this->updateModelAttribute($directory, $fileName);

        return $this->file->storeAs($directory, $fileName);
    }

    /**
     * Generate a unique name which the uploaded file will be saved as.
     *
     * @return string
     */
    protected function constructFileName()
    {
        $name = uniqid();
        $extension = $this->file->getClientOriginalExtension();

        return "{$name}.{$extension}";
    }

    /**
     * Get the directory to save new file.
     *
     * @return string
     */
    protected function generateDirectory()
    {
        return $this->settings['base_directory'] . $this->getFolderName();
    }
}
