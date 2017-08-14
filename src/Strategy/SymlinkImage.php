<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder\Strategy;

use Raphaelhuefner\UploadedFilesPlaceholder\Utility as Utility;

abstract class SymlinkImage {
    protected $image = NULL;
    protected $fontFileName = '';

    public function __construct($fontFileName) {
        $this->fontFileName = $fontFileName;
    }

    protected function symlinkImage(array $file, $sourceDirectory, $destinationDirectory, $placeholderExtension) {
        $dims = $this->getDimensions($sourceDirectory, $file);
        $placeholderDestinationFileName = rtrim($destinationDirectory, '/') . '/.placeholders/images/' . $dims['width'] . 'x' .  $dims['height'] . '.' . $placeholderExtension;
        $placeholderRelativeFileName = Utility::getDirWithTrailingSlash($this->makeRelative($file['dirName'])) . '.placeholders/images/' . $dims['width'] . 'x' .  $dims['height'] . '.' . $placeholderExtension;
        $symlinkFileName = rtrim($destinationDirectory, '/') . '/' . Utility::getDirWithTrailingSlash($file['dirName']) . $file['baseName'];

        if (! is_readable($placeholderDestinationFileName)) {
            Utility::makeDirForFile($placeholderDestinationFileName);
            $this->createImage($dims['width'], $dims['height']);
            $this->createBackground($dims['width'], $dims['height']);
            $this->createLabel($dims['width'], $dims['height']);
            // TODO fill with main color of source file
            $this->writeImage($placeholderDestinationFileName);
            imagedestroy($this->image);
        }

        Utility::makeDirForFile($symlinkFileName);

        $result = symlink($placeholderRelativeFileName, $symlinkFileName);
        return $result;
    }

    protected function createImage($width, $height) {
        $this->image = imagecreate($width, $height);
    }

    protected function createBackground($width, $height) {
        $backgroundColor = imagecolorallocate($this->image, 0xCC, 0xCC, 0xCC);
        imagerectangle($this->image, 0, 0, $width, $height, $backgroundColor);
    }

    protected function createLabel($width, $height) {
        if ((30 < $width) && (30 < $height)) {
            $text = $width . 'x' . $height;
            $targetWidth = 0.8 * $width;
            $targetHeight = 0.8 * $height;
            $fontSize = 20;
            $tryCounter = 20;
            do {
                $bbox = imagettfbbox($fontSize, 0, $this->fontFileName, $text);
                $textWidth = $bbox[4] - $bbox[0];
                $textHeight = $bbox[5] - $bbox[1];
                $biggerRatio = max($textWidth / $targetWidth, $textHeight / $targetHeight);
                if (1.0 >= $biggerRatio) {
                    break;
                }
                $fontSize = $fontSize / $biggerRatio;
                $tryCounter--;
            } while (0 < $tryCounter);
            if (0 < $tryCounter) {
                $x = floor(($width - $textWidth) / 2) - $bbox[0];
                $y = floor(($height - $textHeight) / 2) - $bbox[1];
                $foregroundColor = imagecolorallocate($this->image, 0xEE, 0xEE, 0xEE);
                imagettftext($this->image, $fontSize, 0, $x, $y, $foregroundColor, $this->fontFileName, $text);
            }
        }
    }

    protected function getDimensions($sourceDirectory, $file) {
        $sourceFileName = rtrim($sourceDirectory, '/') . '/' . $file['dirName'] . '/'  . $file['baseName'];
        $imageSize = getimagesize($sourceFileName);
        $width = $imageSize[0];
        $height = $imageSize[1];
        return [
            'width' => $width,
            'height' => $height,
        ];
    }

    protected function makeRelative($dirs) {
        $levelCount = ('' == $dirs) ? 0 : count(explode('/', $dirs));
        return implode('/', array_fill(0, $levelCount, '..'));
    }

    abstract protected function writeImage($destinationFileName);
}
