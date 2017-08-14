<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder;

class Utility {
    static public function getFileNameExtension($baseName) {
        $baseNameParts = explode('.', $baseName);
        $lastPart = array_pop($baseNameParts);
        if (in_array($lastPart, ['gz', 'bz2', 'zip']) && (1 < count($baseNameParts))) {
            $secondLastPart = array_pop($baseNameParts);
            return $secondLastPart . '.' . $lastPart;
        }
        return $lastPart;
    }

    static public function makeDirForFile($fileName) {
        $dirName = dirname($fileName);
        if (is_dir($dirName)) {
            return is_writable($dirName);
        }
        else {
            return mkdir($dirName, 0755, TRUE);
        }
    }

    static public function getDirWithTrailingSlash($dir) {
        return ('' == $dir) ? '' : ($dir . '/');
    }
}
