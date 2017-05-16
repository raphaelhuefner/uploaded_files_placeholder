<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder\Commandline;

class Light {
    protected $optionNames = [];
    protected $options = NULL;

    public function __construct(array $optionNames) {
        $this->optionNames = $optionNames;
        $this->scanCommandline();
    }

    public function scanCommandline() {
        global $argv;
        foreach ($this->optionNames as $optionName) {
            $optionValue = NULL;
            foreach ($argv as $argIndex => $argString) {
                $argParts = explode('=', $argString);
                if (('--' . $optionName) == $argParts[0]) {
                    if (isset($argParts[1])) {
                        $optionValue = $argParts[1];
                    }
                    else if (isset($argv[$argIndex + 1])) {
                        $optionValue = $argv[$argIndex + 1];
                    }
                }
            }

            if (is_null($optionValue)) {
              Raphaelhuefner\UploadedFilesPlaceholder\Commandline::fail('--' . $optionName . ' option is missing.');
            }
            $this->options[$optionName] = $optionValue;
        }
    }

    public function getOptions() {
        if (is_null($this->options)) {
            $this->scanCommandline();
        }

        return $this->options;
    }
}
