<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder;

class Replacer {
    protected $destinationPath = NULL;
    protected $files = [];
    protected $strategies = [];
    protected $statistics = [];

    public function __construct($sourceDirectory, $destinationDirectory) {
        $this->sourceDirectory = $sourceDirectory;
        $this->destinationDirectory = $destinationDirectory;
    }

    public function replace(array $files, array $strategies) {
        $this->files = $files;
        $this->strategies = $strategies;
        foreach ($this->files as $filePath => $file) {
            $isHandled = FALSE;
            foreach ($this->strategies as $strategy) {
                $result = $strategy->handle($file, $this->sourceDirectory, $this->destinationDirectory);
                if ($result) {
                    $isHandled = TRUE;
                    break;
                }
            }
            if (! $isHandled) {
                $fallback = new Strategy\Copy\Fallback();
                $fallback->handle($file, $this->sourceDirectory, $this->destinationDirectory);
                $this->statistics['fallback'][$filePath] = $file;
                $this->statistics['fallbackByMimeType'][$file['mimeType']][$file['extension']] = isset($this->statistics['fallbackByMimeType'][$file['mimeType']][$file['extension']]) ? $this->statistics['fallbackByMimeType'][$file['mimeType']][$file['extension']] + 1 : 1;
            }
        }
    }

    public function getStatistics() {
        return $this->statistics;
    }
}
