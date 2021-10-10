<?php


class ParserResultsTest extends \PHPUnit\Framework\TestCase {

    /**
     * Tests results of CSV file's parsing.
     * @throws Exception
     */
    public function testCsvParsing() {
        \App\Parser::execute('tests\test_products_comma_separated.csv', 'tests\unique-combination-results-csv.csv');

        self::assertEquals($this->getCsvAsArr('tests\expected_unique-combination-results-csv.csv'),
            $this->getCsvAsArr('tests\unique-combination-results-csv.csv'));
    }

    /**
     * Tests results of TSV file's parsing.
     * @throws Exception
     */
    public function testTsvParsing() {
        \App\Parser::execute('tests\test_products_tab_separated.tsv', 'tests\unique-combination-results-tsv.csv');

        self::assertEquals($this->getCsvAsArr('tests\expected_unique-combination-results-tsv.csv'),
            $this->getCsvAsArr('tests\unique-combination-results-tsv.csv'));
    }

    /**
     * Tests results of JSON file's parsing.
     * @throws Exception
     */
    public function testJsonParsingReturnsComingSoonMessage() {
        $response = \App\Parser::execute('tests\test_products_json.json');

        self::assertEquals('Parsing for JSON is planned and shall be done soon.', $response);
        self::assertFileDoesNotExist('tests\unique-combination-results-json.csv');
    }

    /**
     * Tests results of XML file's parsing.
     * @throws Exception
     */
    public function testXmlParsingReturnsComingSoonMessage() {
        $response = \App\Parser::execute('tests\test_products_xml.xml');

        self::assertEquals('Parsing for XML is planned and shall be done soon.', $response);
        self::assertFileDoesNotExist('tests\unique-combination-results-xml.csv');
    }

    /**
     * Tests results of Other-format file's parsing.
     * @throws Exception
     */
    public function testOtherFormatParsingReturnsIncorrectFileFormatMessage() {
        $response = \App\Parser::execute('tests\test_products_txt.txt');

        self::assertEquals('File format is incorrect.', $response);
        self::assertFileDoesNotExist('tests\unique-combination-results-xml.csv');
    }

    /**
     * Returns a CSV in 2D Array.
     * @param $filePath
     * @param $delimiter
     * @return array
     */
    public function getCsvAsArr($filePath, $delimiter = ",") {
        $csvArr = [];
        if (($handle = fopen($filePath, 'r')) !== FALSE) {
            while (($data = fgetcsv($handle, 1024 * 1024, $delimiter)) !== FALSE) {
                $csvArr[] = $data;
            }
            fclose($handle);
        }
        return $csvArr;
    }
}