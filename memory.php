<?php

/**
 * Prints Memory usages.
 */
function printMemoryUsage(){
    echo "\n Current Peak Memory usage is: " . formatBytes(memory_get_peak_usage()) . " and Current Memory usage is " . formatBytes(memory_get_usage()) . "\n";
}

/**
 * Converts bytes in proper representation.
 * @param $bytes
 * @param int $precision
 * @return string
 */
function formatBytes($bytes, $precision = 2) {
    $units = array("B", "KB", "MB", "GB", "TB");

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . " " . $units[$pow];
}