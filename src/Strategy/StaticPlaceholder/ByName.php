<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder\Strategy\StaticPlaceholder;

use Raphaelhuefner\UploadedFilesPlaceholder\StrategyInterface as StrategyInterface;

class ByName implements StrategyInterface {
    protected $placeholderDirectory = '';
    protected $mapNameToPlaceholder = [];

    public function __construct($placeholderDirectory, array $mapNameToPlaceholder) {
        $this->placeholderDirectory = rtrim($placeholderDirectory, '/');
        $this->mapNameToPlaceholder = $mapNameToPlaceholder;
    }

    public function handle(array $file, $sourceDirectory, $destinationDirectory) {
        if (isset($this->mapNameToPlaceholder[$file['baseName']])) {
            $placeholderBaseName = $this->mapNameToPlaceholder[$file['baseName']];
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
