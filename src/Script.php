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
        new Strategy\Image\Gif(),
        new Strategy\Image\Jpg(),
        new Strategy\Image\Png(),
        new Strategy\StaticPlaceholder\ByMime(
            dirname(__DIR__) . '/placeholders/',
            [
                // @see https://brendanzagaeski.appspot.com/0004.html for minimal PDF file.
                'application/pdf' => 'placeholder.pdf',
                'text/rtf' => 'placeholder.rtf',
                'application/msword' => 'placeholder.doc',
                'application/vnd.ms-office' => 'placeholder.xls',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'placeholder.pptx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'placeholder.xlsx',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'placeholder.docx',
            ]
        ),
        new Strategy\StaticPlaceholder\ByName(
            dirname(__DIR__) . '/placeholders/',
            [
                'favicon.ico' => 'placeholder.ico',
            ]
        ),
        new Strategy\Copy\ByMime([
            'text/plain', // TODO exclude .mysql and maybe .csv, deal with shape file parts
            'application/gzip', // TODO exclude .mysql.gz and .sql.gz
            'application/x-gzip', // TODO exclude .mysql.gz and .sql.gz
            'application/x-empty',
            'inode/x-empty',
            'application/xml', // TODO .kml
            'application/zip', // TODO shape files, .kmz
            'application/octet-stream', // TODO deal with shape file parts
            'application/x-dbf', // TODO shape files
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
