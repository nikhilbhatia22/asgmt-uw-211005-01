<?php

require ('vendor/autoload.php');

$script_start_time = microtime(true);

//Input Collection
$inputs = getopt(null,['file:', 'unique-combination::', 'dont-bail', 'print-objects']);
$inputFile = $inputs['file'];
$uniqueCombinationFile = $inputs['unique-combination'] ?? 'unique-combination-results.csv';
$bailValidation = isset($inputs['dont-bail']) ? false : true;
$printObjects = isset($inputs['print-objects']);


//Few Argument Validations
if(empty($inputFile))  throw new InvalidArgumentException("The option --file is required to execute this file.");
if(!file_exists($inputFile))   throw new InvalidArgumentException("The specified file \"" .$inputFile. "\" is not found.");


//Main Script Execution.
try {
    App\Parser::execute($inputFile, $uniqueCombinationFile, $bailValidation, $printObjects);
} catch (Exception $e) {
    echo "Some exception $e was raised";
}


$script_end_time = microtime(true);
echo "The results were successfully parsed into $uniqueCombinationFile in "
    . round(($script_end_time - $script_start_time), 2) . ' seconds with peak memory usage of '
    . \App\Memory::formatBytes(memory_get_peak_usage());
