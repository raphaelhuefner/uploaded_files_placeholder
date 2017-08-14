<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder\Strategy\SymlinkImage;

use Raphaelhuefner\UploadedFilesPlaceholder\StrategyInterface as StrategyInterface;
use Raphaelhuefner\UploadedFilesPlaceholder\Strategy\SymlinkImage as SymlinkImage;

class Png extends SymlinkImage implements StrategyInterface {
    public function handle(array $file, $sourceDirectory, $destinationDirectory) {
        if ('image/png' == $file['mimeType']) {
            return $this->symlinkImage($file, $sourceDirectory, $destinationDirectory, 'png');
        }
        return FALSE;
    }

    protected function writeImage($destinationFileName) {
        return imagepng($this->image, $destinationFileName, 9, PNG_ALL_FILTERS);
    }
}
