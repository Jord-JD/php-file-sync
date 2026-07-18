<?php

namespace JordJD\FileSync\FileListing;

use League\Flysystem\Filesystem;

class TransferAction
{
    private $path;
    private $sourceFilesystem;
    private $destinationFilesystem;

    public function __construct(string $path, Filesystem $sourceFilesystem, Filesystem $destinationFilesystem)
    {
        $this->path = $path;
        $this->sourceFilesystem = $sourceFilesystem;
        $this->destinationFilesystem = $destinationFilesystem;
    }

    public function transfer(): bool
    {
        $stream = $this->sourceFilesystem->readStream($this->path);

        if ($stream === false) {
            return false;
        }

        try {
            return $this->destinationFilesystem->putStream($this->path, $stream);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
    }
}
