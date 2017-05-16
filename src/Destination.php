<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder;

class Destination {
    protected $destinationPath = NULL;

    public function __construct($destinationPath) {
        $this->destinationPath = $destinationPath;
    }

    public function create() {
        $mkdirResult = FALSE;
        if (! file_exists($this->destinationPath)) {
            $mkdirResult = mkdir($this->destinationPath, 0755, TRUE);
        }

        if (! $mkdirResult && ! is_dir($this->destinationPath)) {
            Commandline::fail('Could not create destination directory nor confirm that given destination is in fact a directory.');
        }
    }
}
