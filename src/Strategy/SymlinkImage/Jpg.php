<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder\Strategy\SymlinkImage;

use Raphaelhuefner\UploadedFilesPlaceholder\StrategyInterface as StrategyInterface;
use Raphaelhuefner\UploadedFilesPlaceholder\Strategy\SymlinkImage as SymlinkImage;

class Jpg extends SymlinkImage implements StrategyInterface {
    public function handle(array $file, $sourceDirectory, $destinationDirectory) {
        if ('image/jpeg' == $file['mimeType']) {
            return $this->symlinkImage($file, $sourceDirectory, $destinationDirectory, 'jpg');
        }
        return FALSE;
    }

    protected function writeImage($destinationFileName) {
        imagepalettetotruecolor($this->image);
        return imagejpeg($this->image, $destinationFileName, 20);
    }
}
