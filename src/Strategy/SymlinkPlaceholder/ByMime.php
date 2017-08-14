<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder\Strategy\SymlinkPlaceholder;

use Raphaelhuefner\UploadedFilesPlaceholder\StrategyInterface as StrategyInterface;
use Raphaelhuefner\UploadedFilesPlaceholder\Strategy\SymlinkPlaceholder as SymlinkPlaceholder;

class ByMime extends SymlinkPlaceholder implements StrategyInterface {
    protected $mapMimeToPlaceholder = [];

    public function __construct($placeholderSourceDirectory, array $mapMimeToPlaceholder) {
        $this->placeholderSourceDirectory = rtrim($placeholderSourceDirectory, '/');
        $this->mapMimeToPlaceholder = $mapMimeToPlaceholder;
    }

    public function handle(array $file, $sourceDirectory, $destinationDirectory) {
        if (isset($this->mapMimeToPlaceholder[$file['mimeType']])) {
            $placeholderBaseName = $this->mapMimeToPlaceholder[$file['mimeType']];
            return $this->symlinkPlaceholder($placeholderBaseName, $destinationDirectory, $file);
        }
        return FALSE;
    }
}
