<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder\Strategy\Image;

use Raphaelhuefner\UploadedFilesPlaceholder\StrategyInterface as StrategyInterface;

class Jpg implements StrategyInterface {
    public function handle(array $file, $sourceDirectory, $destinationDirectory) {
        if ('image/jpeg' == $file['mimeType']) {
            $sourceFileName = rtrim($sourceDirectory, '/') . '/' . $file['dirName'] . '/'  . $file['baseName'];
            $destinationFileName = rtrim($destinationDirectory, '/') . '/' . $file['dirName'] . '/'  . $file['baseName'];

            $destinationDirName = dirname($destinationFileName);
            if (! is_dir($destinationDirName)) {
                mkdir($destinationDirName, 0755, TRUE);
            }

            $imageSize = getimagesize($sourceFileName);
            $width = $imageSize[0];
            $height = $imageSize[1];
            $image = imagecreatetruecolor($width, $height);
            // TODO fill with main color of source file
            $result = imagejpeg($image, $destinationFileName, 0);
            imagedestroy($image);
            return $result;
        }
        return FALSE;
    }
}
