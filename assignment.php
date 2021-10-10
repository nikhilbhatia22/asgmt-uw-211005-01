<?php
/**
 * CONDITIONS
 * 1. The input file's heading column count and data column count should be same.
 */

require_once('csv_parser.php');
require_once('validations.php');

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
    execute($inputFile, $uniqueCombinationFile, $bailValidation, $printObjects);
} catch (Exception $e) {
    echo "Some exception $e was raised";
}


$script_end_time = microtime(true);
echo "The results were successfully parsed into $uniqueCombinationFile in " . round(($script_end_time - $script_start_time), 2) . ' seconds';

/**
 * Executes the parser.
 * @param $inputFile
 * @param $uniqueCombinationFile
 * @param $bailValidation
 * @param $printObjects
 * @throws Exception
 */
function execute($inputFile, $uniqueCombinationFile, $bailValidation, $printObjects){
    switch (pathinfo($inputFile, PATHINFO_EXTENSION)) {
        case 'json': echo "Parsing for JSON is planned and shall be done soon."; exit; break;
        case 'XML': echo "Parsing for XML is planned and shall be done soon."; exit; break;
        case 'tsv':
        case 'csv':
        default:
            parseCSV($inputFile, $uniqueCombinationFile, $bailValidation, $printObjects);
            break;
    }
}