# PHP File Sync

[![Tests](https://github.com/Jord-JD/php-file-sync/actions/workflows/tests.yml/badge.svg)](https://github.com/Jord-JD/php-file-sync/actions/workflows/tests.yml)

Synchronise files between multiple local or remote filesystems through Flysystem 1.x.

## Installation

```bash
composer require jord-jd/php-file-sync
```

Create each filesystem with the appropriate Flysystem adapter. The examples below use local adapters, but the synchronisation strategies work with any Flysystem 1.x `Filesystem` instance.

```php
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

$source = new Filesystem(new Local('/path/to/source'));
$destination = new Filesystem(new Local('/path/to/destination'));
```

## One-way synchronisation

One-way synchronisation copies files that are missing from the destination, plus source files whose timestamps are newer. It does not delete destination-only files.

```php
use JordJD\FileSync\FileSync;

(new FileSync())
    ->oneWay()
    ->from($source)
    ->to($destination)
    ->begin();
```

## Multi-directional synchronisation

Multi-directional synchronisation makes the newest version of each file available on every configured filesystem. Files that exist on only one filesystem are copied to the others.

```php
(new FileSync())
    ->multiDirectional()
    ->with($filesystemA)
    ->with($filesystemB)
    ->with($filesystemC)
    ->begin();
```

Add `->withProgressBar()` before `->begin()` on either strategy to show command-line progress.

## Important behavior

- Paths are compared exactly as returned by Flysystem.
- Modification timestamps decide which version is newer.
- Existing files may be overwritten by newer copies.
- Deletions are not propagated.
- Check the boolean result or exceptions produced by your Flysystem adapter when storage failures need application-specific handling.
