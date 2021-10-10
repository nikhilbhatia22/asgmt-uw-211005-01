<?php


namespace App;


class Parser {

    /**
     * Executes the parser.
     * @param $inputFile
     * @param $uniqueCombinationFile
     * @param $bailValidation
     * @param $printObjects
     * @throws \Exception
     */
    public static function execute($inputFile, $uniqueCombinationFile, $bailValidation, $printObjects){
        switch (pathinfo($inputFile, PATHINFO_EXTENSION)) {
            case 'json': echo "Parsing for JSON is planned and shall be done soon."; exit; break;
            case 'XML': echo "Parsing for XML is planned and shall be done soon."; exit; break;
            case 'tsv':
            case 'csv':
            default:
                CsvParser::execute($inputFile, $uniqueCombinationFile, $bailValidation, $printObjects);
                break;
        }
    }

}