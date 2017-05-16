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
}
