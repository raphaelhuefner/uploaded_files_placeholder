<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder\Strategy\SymlinkImage;

use Raphaelhuefner\UploadedFilesPlaceholder\StrategyInterface as StrategyInterface;
use Raphaelhuefner\UploadedFilesPlaceholder\Strategy\SymlinkImage as SymlinkImage;

// @see http://www.php.net/manual/en/function.imagepalettetotruecolor.php
// Backwards compatiblity
if (!function_exists('imagepalettetotruecolor')) {
    function imagepalettetotruecolor(&$src) {
        if(imageistruecolor($src)) {
            return(true);
        }

        $dst = imagecreatetruecolor(imagesx($src), imagesy($src));

        imagecopy($dst, $src, 0, 0, 0, 0, imagesx($src), imagesy($src));
        imagedestroy($src);

        $src = $dst;

        return TRUE;
    }
}

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
