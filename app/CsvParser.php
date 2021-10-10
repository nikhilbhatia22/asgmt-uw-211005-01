<?php


namespace App;


class CsvParser {

    protected static $mappingAndSequence_ofHeadings = [
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
     * @throws \Exception
     */
    public static function execute($inputFile, $uniqueCombinationFile, $bailValidation, $printObjects){
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
            $parsedCsvRow = str_getcsv($line, self::getDelimiter($inputFile));
            if($lineNo == 1){
                $headingsString = $line;
                $headingsAsArr = $parsedCsvRow;
                $totalColumnsInAFile = count($parsedCsvRow);
                continue;
            }

            if($line == $headingsString)    continue;       //If a same heading line comes across, then skipping.

            $associativeRowData = array_combine($headingsAsArr, $parsedCsvRow);     //Initial - with CSV Headings
            $associativeRowData = self::mapObjectProperties($associativeRowData);     //Mapped with properties.

            if(!Validations::validateRow($associativeRowData, $lineNo, $bailValidation))      continue;   //Skipping if the validation of any row fails.

            if($printObjects)   print_r($associativeRowData);       //Prints validated product objects, if valid arg flag is provided.

            if(! isset($indexedData[$line])){
                $indexedData[$line] = $uniqueArrIndex;
                $uniqueArr[$uniqueArrIndex] = self::sequentializeValues($associativeRowData);
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
    public static function getDelimiter($file){
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
    public static function mapObjectProperties($associativeParsedRecord){
        $mapped = [];
        foreach ($associativeParsedRecord as $key => $value)
        {
            $mapped[self::$mappingAndSequence_ofHeadings[$key]] = $value;
        }
        return $mapped;
    }

    /**
     * Puts the values in required sequence for final unique combination file.
     * @param $product
     * @return array
     */
    public static function sequentializeValues($product){
        $sequentialized = [];
        foreach (self::$mappingAndSequence_ofHeadings as $mappingAndSequence_ofHeading) {
            $sequentialized[] = $product[$mappingAndSequence_ofHeading];
        }
        return $sequentialized;
    }
}