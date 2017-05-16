<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder;

class Script {
    protected $commandline = NULL;
    protected $destination = NULL;
    protected $inputreader = NULL;
    protected $replacer = NULL;

    public function __construct() {
        $this->commandline = new Commandline();
        $this->destination = new Destination($this->commandline->getOption('destination-directory'));
        $this->inputreader = new Inputreader($this->commandline->getOption('source-directory'), $this->commandline->getOption('field-separator'));
        $this->replacer = new Replacer($this->commandline->getOption('source-directory'), $this->commandline->getOption('destination-directory'));
    }

    protected function getStrategies() {
      return [
        new Strategy\Image\Png(),
        new Strategy\Image\Jpg(),
        new Strategy\StaticPlaceholder\ByMime(
            dirname(__DIR__) . '/placeholders/',
            [
                // @see https://brendanzagaeski.appspot.com/0004.html for minimal PDF file.
                'application/pdf' => 'placeholder.pdf',
                // 'application/x-gzip' => 'placeholder.gz',
            ]
        ),
        new Strategy\StaticPlaceholder\ByName(
            dirname(__DIR__) . '/placeholders/',
            [
                'favicon.ico' => 'placeholder.ico',
            ]
        ),
        new Strategy\Copy\ByMime([
            'application/x-gzip',
            'application/x-empty',
        ]),
        new Strategy\Copy\ByName([
            '.htaccess',
        ]),
      ];
    }

    public function run() {
        $this->destination->create();
        $this->inputreader->read();
        $this->replacer->replace($this->inputreader->getFiles(), $this->getStrategies());
        $stats = $this->replacer->getStatistics();
        var_dump($stats);
        // var_dump($this->inputreader->getFiles());
        // var_dump($this->inputreader->getMimeTypes());
    }
}