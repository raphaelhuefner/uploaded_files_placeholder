<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder\Strategy\SymlinkPlaceholder;

use Raphaelhuefner\UploadedFilesPlaceholder\StrategyInterface as StrategyInterface;
use Raphaelhuefner\UploadedFilesPlaceholder\Strategy\SymlinkPlaceholder as SymlinkPlaceholder;

class ByName extends SymlinkPlaceholder implements StrategyInterface {
    protected $mapNameToPlaceholder = [];

    public function __construct($placeholderSourceDirectory, array $mapNameToPlaceholder) {
        $this->placeholderSourceDirectory = rtrim($placeholderSourceDirectory, '/');
        $this->mapNameToPlaceholder = $mapNameToPlaceholder;
    }

    public function handle(array $file, $sourceDirectory, $destinationDirectory) {
        if (isset($this->mapNameToPlaceholder[$file['baseName']])) {
            $placeholderBaseName = $this->mapNameToPlaceholder[$file['baseName']];
            return $this->symlinkPlaceholder($placeholderBaseName, $destinationDirectory, $file);
        }
        return FALSE;
    }
}
