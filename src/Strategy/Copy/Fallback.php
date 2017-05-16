<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder\Strategy\Copy;

use Raphaelhuefner\UploadedFilesPlaceholder\StrategyInterface as StrategyInterface;

class Fallback implements StrategyInterface {
    public function handle(array $file, $sourceDirectory, $destinationDirectory) {
        $sourceFileName = rtrim($sourceDirectory, '/') . '/' . $file['dirName'] . '/'  . $file['baseName'];
        $destinationFileName = rtrim($destinationDirectory, '/') . '/' . $file['dirName'] . '/'  . $file['baseName'];

        $destinationDirName = dirname($destinationFileName);
        if (! is_dir($destinationDirName)) {
            mkdir($destinationDirName, 0755, TRUE);
        }

        $result = copy($sourceFileName, $destinationFileName);
        return $result;
    }
}
