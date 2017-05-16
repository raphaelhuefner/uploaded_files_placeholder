<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder\Strategy\Image;

use Raphaelhuefner\UploadedFilesPlaceholder\StrategyInterface as StrategyInterface;

class Png implements StrategyInterface {
    public function handle(array $file, $sourceDirectory, $destinationDirectory) {
        if ('image/png' == $file['mimeType']) {
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
            $result = imagepng($image, $destinationFileName, 9, PNG_ALL_FILTERS);
            imagedestroy($image);
            return $result;
        }
        return FALSE;
    }
}
