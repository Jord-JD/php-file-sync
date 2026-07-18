<?php

namespace JordJD\FileSync\Tests;

use JordJD\FileSync\FileSync;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PHPUnit\Framework\TestCase;

final class MultiDirectionalFileSyncTest extends TestCase
{
    public function setupDirectory($name)
    {
        $path = __DIR__.'/Data/'.$name.'/';

        if (is_dir($path)) {

            $files = glob($path.'*.txt');

            foreach($files as $file) {
                unlink($file);
            }
        } else {
            mkdir($path, 0777, true);
        }

        for ($i=0; $i < 3; $i++) {
            $filename = $name.'-'.$i.'.txt';
            $content = 'Test content '.$name.' '.$i;
            file_put_contents($path.$filename, $content);
            touch($path.$filename, time() - $i);
        }

        $adapter = new Local($path);
        return new Filesystem($adapter);
    }

    public function testMultiDirectionalFileSync()
    {
        $directoryA = $this->setupDirectory('a');
        $directoryB = $this->setupDirectory('b');
        $directoryC = $this->setupDirectory('c');

        (new FileSync())
            ->multiDirectional()
            ->with($directoryA)
            ->with($directoryB)
            ->with($directoryC)
            ->begin();

        $filesA = glob(__DIR__.'/Data/a/*.txt');
        $filesB = glob(__DIR__.'/Data/b/*.txt');
        $filesC = glob(__DIR__.'/Data/c/*.txt');

        $this->assertSameSize($filesA, $filesB);
        $this->assertSameSize($filesA, $filesC);

        foreach($filesA as $fileA) {
            $fileB = __DIR__.'/Data/b/'.basename($fileA);
            $fileC = __DIR__.'/Data/c/'.basename($fileA);

            $this->assertFileExists($fileB);
            $this->assertFileExists($fileC);

            $this->assertFileEquals($fileA, $fileB);
            $this->assertFileEquals($fileA, $fileC);
        }
    }
}
