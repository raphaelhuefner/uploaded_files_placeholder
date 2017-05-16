<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder;

interface StrategyInterface {
    public function handle(array $file, $sourceDirectory, $destinationDirectory);
}
