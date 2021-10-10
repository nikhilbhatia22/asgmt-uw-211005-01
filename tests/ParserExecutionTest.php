<?php


class ParserExecutionTest extends \PHPUnit\Framework\TestCase {

    /**
     * Test for parser bailing when validation is failed.
     * @throws Exception
     */
    public function testParserBailsOnFailedValidation() {
        $this->expectException(RuntimeException::class);
        \App\Parser::execute('tests\test_products_comma_separated_invalid_data.csv', 'tests\unique-combination-results-csv.csv');
    }
}