<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder;

class Inputreader {
    protected $sourceDirectoryRegEx = '';
    protected $fieldSeparator = '@@@';
    protected $mimeTypes = [];
    protected $files = [];

    public function __construct($sourceDirectory, $fieldSeparator) {
        $sourceDirectory = rtrim($sourceDirectory, '/') . '/';
        $sourceDirectory = realpath($sourceDirectory);
        $this->sourceDirectoryRegEx = '{^' . preg_quote($sourceDirectory) . '}';

        $this->fieldSeparator = $fieldSeparator;
    }

    public function read() {
        while ($line = fgets(STDIN)) {
            $line = trim($line);
            list($fileName, $mime) = explode($this->fieldSeparator, $line);
            $fileName = trim($fileName);
            $fileName = realpath($fileName);
            $fileName = $this->trimSourceDirectoryPrefix($fileName);
            $mime = trim($mime);
            list($mimeType, $encoding) = explode('; charset=', $mime);
            $dirName = dirname($fileName);
            if ('.' == $dirName) {
              $dirName = '';
            }
            $baseName = basename($fileName);
            $extension = Utility::getFileNameExtension($baseName);
            $this->files[$fileName] = [
                'mimeType' => $mimeType,
                'encoding' => $encoding,
                'dirName' => $dirName,
                'baseName' => $baseName,
                'extension' => $extension,
            ];
            $this->mimeTypes[$mimeType][$extension] = isset($this->mimeTypes[$mimeType][$extension]) ? ($this->mimeTypes[$mimeType][$extension] + 1) : 1;
        }
    }

    protected function trimSourceDirectoryPrefix($fileName) {
        $fileName = preg_replace($this->sourceDirectoryRegEx, '', $fileName);
        return ltrim($fileName, '/');
    }

    public function getFiles() {
        return $this->files;
    }

    public function getMimeTypes() {
        return $this->mimeTypes;
    }
}
