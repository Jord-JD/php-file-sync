<?php

namespace JordJD\FileSync;

use JordJD\FileSync\FileSyncStrategies\MultiDirectional;
use JordJD\FileSync\FileSyncStrategies\OneWay;

class FileSync
{
    public function oneWay()
    {
        return new OneWay();
    }

    public function multiDirectional()
    {
        return new MultiDirectional();
    }
}