<?php

namespace JordJD\FileSync\Interfaces;

interface FileSyncStrategyInterface
{
    public function begin(): void;
}