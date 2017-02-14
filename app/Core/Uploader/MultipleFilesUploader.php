<?php

namespace App\Core\Uploader;

use File;
use Storage;
use Illuminate\Http\UploadedFile;

abstract class MultipleFilesUploader
{
    protected $files;
    protected $settings;

    public function __construct($files)
    {
        $this->files = $files;
        $this->settings = $this->getUploadSettings();
    }

    abstract protected function getUploadSettings();
    abstract protected function getFolderName();
    abstract protected function updateModelAttribute($directory, $fileName, $originName);

    /**
     * Save file
     *
     * @return string|false
     */
    public function make()
    {
        if (!is_array($this->files)) {
            return false;
        }
        
        $directory = $this->generateDirectory();
        File::exists($directory) || File::makeDirectory($directory, 0776, true, true);
        foreach ($this->files as $file) {
            $fileName = $this->constructFileName($file);
            $originName = $file->getClientOriginalName();
            $this->updateModelAttribute($directory, $fileName, $originName);
            $file->storeAs($directory, $fileName);
        }
    }
    

    /**
     * Generate a unique name which the uploaded file will be saved as.
     *
     * @return string
     */
    protected function constructFileName(UploadedFile $file)
    {
        $name = uniqid();
        $extension = mb_strtolower($file->getClientOriginalExtension(), 'UTF-8');

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
