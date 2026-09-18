<?php

declare(strict_types=1);

/*
 * Runs Pest with --parallel everywhere except Windows, where parallel
 * workers race over the shared Testbench skeleton
 * (bootstrap/cache/services.php) and fail with "Access is denied".
 */

$isWindows = PHP_OS_FAMILY === 'Windows';

$args = array_slice($_SERVER['argv'], 1);

if (! $isWindows && ! in_array('--parallel', $args, true)) {
    $args[] = '--parallel';
}

$command = escapeshellarg(PHP_BINARY).' '.escapeshellarg(__DIR__.'/../vendor/bin/pest');

foreach ($args as $arg) {
    $command .= ' '.escapeshellarg($arg);
}

passthru($command, $exitCode);

exit($exitCode);
