<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder;

class SimpleAutoloader {
    static public function loader($className) {
        if (static::beginsWith($className, __NAMESPACE__ . '\\')) {
            $localClassName = static::removePrefix($className, __NAMESPACE__ . '\\');
            $filename = __DIR__ . '/' . str_replace('\\', '/', $localClassName) . ".php";
            if (file_exists($filename)) {
                include_once($filename);
                if (class_exists($className)) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    static protected function beginsWith($string, $prefix) {
        return (0 === strpos($string, $prefix));
    }

    static protected function removePrefix($string, $prefix) {
        $prefixLength = strlen($prefix);
        return substr($string, $prefixLength);
    }
}
