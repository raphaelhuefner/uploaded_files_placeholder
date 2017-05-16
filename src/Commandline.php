<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder;

use Commando\Command as Command;

class Commandline {
    protected $options = NULL;

    public function __construct() {
        $this->scanCommandline();
    }

    public function scanCommandline() {
        $this->options = new Command();

        $this->options->option('field-separator')
            ->aka('f')
            ->describedAs('Field separator of the output of the "file" command which we will read via STDIN.')
            ->default('@@@');

        $this->options->option('source-directory')
            ->aka('s')
            ->describedAs('Source directory')
            ->must(function($sourceDirectory) {
                return is_dir($sourceDirectory);
            });

        $this->options->option('destination-directory')
            ->aka('d')
            ->describedAs('Destination directory')
            ->default('uploaded-files-placeholder')
            ->must(function($destinationDirectory) {
                return is_dir($destinationDirectory) || is_dir(dirname($destinationDirectory));
            });
    }

    public function getOption($optionName) {
        if (is_null($this->options)) {
            $this->scanCommandline();
        }

        if (! isset($this->options[$optionName])) {
            throw new Exception('Could not find command line option ' . $optionName);
        }

        return $this->options[$optionName];
    }

    static public function fail($message) {
        fprintf(STDERR, 'ERROR: ' . $message . PHP_EOL);
        exit(1);
    }
}
