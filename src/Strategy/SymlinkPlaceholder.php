<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder\Strategy;

use Raphaelhuefner\UploadedFilesPlaceholder\Utility as Utility;

class SymlinkPlaceholder {
    protected $placeholderSourceDirectory = '';

    protected function symlinkPlaceholder($placeholderBaseName, $destinationDirectory, $file) {
        $placeholderSourceFileName = $this->placeholderSourceDirectory . '/' . $placeholderBaseName;
        $placeholderDestinationFileName = rtrim($destinationDirectory, '/') . '/.placeholders/' . $placeholderBaseName;
        $placeholderRelativeFileName = Utility::getDirWithTrailingSlash($this->makeRelative($file['dirName'])) . '.placeholders/' . $placeholderBaseName;
        $symlinkFileName = rtrim($destinationDirectory, '/') . '/' . Utility::getDirWithTrailingSlash($file['dirName']) . $file['baseName'];

        if (! is_readable($placeholderDestinationFileName)) {
            Utility::makeDirForFile($placeholderDestinationFileName);
            copy($placeholderSourceFileName, $placeholderDestinationFileName);
        }

        Utility::makeDirForFile($symlinkFileName);

        $result = symlink($placeholderRelativeFileName, $symlinkFileName);
        return $result;
    }

    protected function makeRelative($dirs) {
        $levelCount = count(explode('/', $dirs));
        return implode('/', array_fill(0, $levelCount, '..'));
    }
}
