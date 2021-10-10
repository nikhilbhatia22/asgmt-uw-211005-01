<?php
/**
 * CONDITIONS
 * 1. The input file's heading column count and data column count should be same.
 */

$inputs = getopt(null,['file:', 'unique-combination::']);

$inputFile = $inputs['file'];
$uniqueCombinationFile = $inputs['unique-combination'] ?? 'unique-combination-results.csv';

if(empty($inputFile))  throw new InvalidArgumentException("The option --file is required to execute this file.");
if(!file_exists($inputFile))   throw new InvalidArgumentException("The specified file \"" .$inputFile. "\" is not found.");

$fp = fopen($inputFile, "r+");

$indexedData = [];
$uniqueArr = [];
$uniqueArrIndex = 0;
$lineNo = 0;
$totalColumnsInAFile = 0;
$headingsString = '';
$headingsAsArr = [];

while (($line = stream_get_line($fp, 1024 * 1024, "\n")) !== false) {
    $lineNo++;      //Incrementing Line
    $parsedCsvRow = str_getcsv($line);
    if($lineNo == 1){
        $headingsString = $line;
        $headingsAsArr = $parsedCsvRow;
        $totalColumnsInAFile = count($parsedCsvRow);
        continue;
    }

    if($line == $headingsString)    continue;       //If a same heading line comes across, then skipping.

    $associativeRowData = array_combine($headingsAsArr, $parsedCsvRow);

    if(!validateRow($associativeRowData, $lineNo))      continue;

    if(! isset($indexedData[$line])){
        $indexedData[$line] = $uniqueArrIndex;
        $uniqueArr[$uniqueArrIndex] = [
            $associativeRowData['brand_name'],
            $associativeRowData['model_name'],
            $associativeRowData['colour_name'],
            $associativeRowData['gb_spec_name'],
            $associativeRowData['network_name'],
            $associativeRowData['grade_name'],
            $associativeRowData['condition_name'],
        ];
        $uniqueArr[$uniqueArrIndex][$totalColumnsInAFile+1] = 0;
        $uniqueArrIndex++;
    }
    $uniqueArr[$indexedData[$line]][$totalColumnsInAFile+1]++;
}
fclose($fp);


// Open a file in write mode ('w')
$fp_unique_comb = fopen($uniqueCombinationFile, 'w');


//Inserting heading
fwrite($fp_unique_comb, 'make,model,colour,capacity,network,grade,condition,count' . PHP_EOL);

// Loop through file pointer and a line
foreach ($uniqueArr as $fields) {
    fputcsv($fp_unique_comb, $fields);
}

fclose($fp_unique_comb);


/**
 * Validates the given row data
 * @param $rowData
 * @param $lineNo
 * @return bool
 */
function validateRow($rowData, $lineNo){
    $rules = [
        'brand_name' => 'required|string',
        'model_name' => 'required|string',
        'colour_name' => 'string',
        'gb_spec_name' => 'string',
        'network_name' => 'string',
        'grade_name' => 'string',
        'condition_name' => 'string',
    ];
    $messages = [
        'required' => '%attribute% is required.',
        'string' => '%attribute% should be a valid string.',
    ];

    foreach ($rowData as $key => $rowDatum) {
        if(empty($key))     continue;
        foreach (explode('|', $rules[$key]) as $rule) {
            $validator = 'validate' . ucfirst($rule);
            if(!$validator($rowDatum)){
                throw new Exception("At line #$lineNo, " . str_replace('%attribute%', $key, $messages[$rule]) . "\n");
//                echo "At line #$lineNo, " . str_replace('%attribute%', $key, $messages[$rule]) . "\n";
                return false;
            }
        }
    }

    return true;
}

/**
 * Validates if the value is not empty.
 * @param $value
 * @return bool
 */
function validateRequired($value){
    if (is_null($value))    return false;
    elseif (is_string($value) && trim($value) === '')   return false;
    elseif ((is_array($value) || $value instanceof Countable) && count($value) < 1)     return false;
    return true;
}

/**
 * Validates if the value is string.
 * @param $value
 * @return bool
 */
function validateString($value)
{
    return is_string($value);
}
