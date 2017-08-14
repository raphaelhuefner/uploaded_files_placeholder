<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder\Strategy\SymlinkImage;

use Raphaelhuefner\UploadedFilesPlaceholder\StrategyInterface as StrategyInterface;
use Raphaelhuefner\UploadedFilesPlaceholder\Strategy\SymlinkImage as SymlinkImage;

class Gif extends SymlinkImage implements StrategyInterface {
    public function handle(array $file, $sourceDirectory, $destinationDirectory) {
        if ('image/gif' == $file['mimeType']) {
            return $this->symlinkImage($file, $sourceDirectory, $destinationDirectory, 'gif');
        }
        return FALSE;
    }

    protected function writeImage($destinationFileName) {
        return imagegif($this->image, $destinationFileName);
    }
}
