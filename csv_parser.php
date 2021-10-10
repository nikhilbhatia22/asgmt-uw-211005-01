<?php

global $mappingAndSequence_ofHeadings;
$mappingAndSequence_ofHeadings = [
    'brand_name' => 'make',
    'model_name' => 'model',
    'colour_name' => 'colour',
    'gb_spec_name' => 'capacity',
    'network_name' => 'network',
    'grade_name' => 'grade',
    'condition_name' => 'condition',
];

/**
 * Parses any CSV based file.
 * @param $inputFile
 * @param $uniqueCombinationFile
 * @param $bailValidation
 * @param $printObjects
 * @throws Exception
 */
function parseCSV($inputFile, $uniqueCombinationFile, $bailValidation, $printObjects){
    $fp = fopen($inputFile, "r+");      //Opening the input file.

    $indexedData = [];
    $uniqueArr = [];
    $uniqueArrIndex = 0;
    $lineNo = 0;
    $totalColumnsInAFile = 0;
    $headingsString = '';
    $headingsAsArr = [];

    while (($line = stream_get_line($fp, 1024 * 1024, "\n")) !== false) {
        $lineNo++;      //Incrementing Line No Count
        $parsedCsvRow = str_getcsv($line, getDelimiter($inputFile));
        if($lineNo == 1){
            $headingsString = $line;
            $headingsAsArr = $parsedCsvRow;
            $totalColumnsInAFile = count($parsedCsvRow);
            continue;
        }

        if($line == $headingsString)    continue;       //If a same heading line comes across, then skipping.

        $associativeRowData = array_combine($headingsAsArr, $parsedCsvRow);     //Initial - with CSV Headings
        $associativeRowData = mapObjectProperties($associativeRowData);     //Mapped with properties.

        if(!validateRow($associativeRowData, $lineNo, $bailValidation))      continue;   //Skipping if the validation of any row fails.

        if($printObjects)   print_r($associativeRowData);       //Prints validated product objects, if valid arg flag is provided.

        if(! isset($indexedData[$line])){
            $indexedData[$line] = $uniqueArrIndex;
            $uniqueArr[$uniqueArrIndex] = sequentializeValues($associativeRowData);
            $uniqueArr[$uniqueArrIndex][$totalColumnsInAFile+1] = 0;
            $uniqueArrIndex++;
        }
        $uniqueArr[$indexedData[$line]][$totalColumnsInAFile+1]++;
    }

    fclose($fp);    //Closing the input file.

    //Writing the final results.
    $fp_unique_comb = fopen($uniqueCombinationFile, 'w');
    fwrite($fp_unique_comb, 'make,model,colour,capacity,network,grade,condition,count' . PHP_EOL);
    foreach ($uniqueArr as $fields) {
        fputcsv($fp_unique_comb, $fields);
    }
    fclose($fp_unique_comb);
}


/**
 * Returns delimiter based on file path.
 * @param $file
 * @return string
 */
function getDelimiter($file){
    switch (pathinfo($file, PATHINFO_EXTENSION)) {
        case 'tsv': return "\t";
        case 'csv':
        default:    return ',';
    }
}

/**
 * Maps parsed records with required object properties.
 * @param $associativeParsedRecord
 * @return array
 */
function mapObjectProperties($associativeParsedRecord){
    global $mappingAndSequence_ofHeadings;
    $mapped = [];
    foreach ($associativeParsedRecord as $key => $value)
    {
        $mapped[$mappingAndSequence_ofHeadings[$key]] = $value;
    }
    return $mapped;
}

/**
 * Puts the values in required sequence for final unique combination file.
 * @param $product
 * @return array
 */
function sequentializeValues($product){
    global $mappingAndSequence_ofHeadings;
    $sequentialized = [];
    foreach ($mappingAndSequence_ofHeadings as $mappingAndSequence_ofHeading) {
        $sequentialized[] = $product[$mappingAndSequence_ofHeading];
    }
    return $sequentialized;
}