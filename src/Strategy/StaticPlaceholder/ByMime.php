<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder\Strategy\StaticPlaceholder;

use Raphaelhuefner\UploadedFilesPlaceholder\StrategyInterface as StrategyInterface;

class ByMime implements StrategyInterface {
    protected $placeholderDirectory = '';
    protected $mapMimeToPlaceholder = [];

    public function __construct($placeholderDirectory, array $mapMimeToPlaceholder) {
        $this->placeholderDirectory = rtrim($placeholderDirectory, '/');
        $this->mapMimeToPlaceholder = $mapMimeToPlaceholder;
    }

    public function handle(array $file, $sourceDirectory, $destinationDirectory) {
        if (isset($this->mapMimeToPlaceholder[$file['mimeType']])) {
            $placeholderBaseName = $this->mapMimeToPlaceholder[$file['mimeType']];
            $placeholderFileName = $this->placeholderDirectory . '/' . $placeholderBaseName;
            $destinationFileName = rtrim($destinationDirectory, '/') . '/' . $file['dirName'] . '/'  . $file['baseName'];

            $destinationDirName = dirname($destinationFileName);
            if (! is_dir($destinationDirName)) {
                mkdir($destinationDirName, 0755, TRUE);
            }

            $result = copy($placeholderFileName, $destinationFileName);
            return $result;
        }
        return FALSE;
    }
}
