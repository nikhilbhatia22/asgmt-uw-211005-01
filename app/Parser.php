<?php


namespace App;


class Parser {

    /**
     * Executes the parser.
     * @param $inputFile
     * @param $uniqueCombinationFile
     * @param $bailValidation
     * @param $printObjects
     * @return string
     * @throws \Exception
     */
    public static function execute($inputFile, $uniqueCombinationFile = 'unique-combination-results.csv',
                                   $bailValidation = true, $printObjects = false){
        switch (pathinfo($inputFile, PATHINFO_EXTENSION)) {
            case 'json': return "Parsing for JSON is planned and shall be done soon."; break;
            case 'XML': return "Parsing for XML is planned and shall be done soon."; break;
            case 'tsv':
            case 'csv':
            default:
                return CsvParser::execute($inputFile, $uniqueCombinationFile, $bailValidation, $printObjects);
                break;
        }
    }

}