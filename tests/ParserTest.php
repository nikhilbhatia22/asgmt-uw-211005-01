<?php


class ParserTest extends \PHPUnit\Framework\TestCase {

    public function testParser() {
        \App\Parser::execute('tests\test_products_comma_separated.csv', 'tests\unique-combination-results.csv');

        self::assertFileEquals('tests\expected_unique-combination-results.csv', 'tests\unique-combination-results.csv');
    }
}