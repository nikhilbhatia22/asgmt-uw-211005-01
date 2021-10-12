# CSV Parser Assignment
This is an assignment whereby a CSV parser has been created that generates a unique combination count for a given assignment-question placed in the directory with the same name.

## File Definitions
| File\Classes   |      Remarks      |
|----------|-------------|
| assignment.php |  This is the main file, from where the parser can be executed. |
| App\Parser.php | This is the class, to initialize the parser execution across multiple formats. |
| App\CsvParser.php | This is the class, to execute the CSV based parsers with different delimiters. |
| App\Validations.php | This is class which contains various validations for the input file. |
| App\Memory.php | This is just a helper class which has some helper memory related functions. |

## How to(s)
To execute this project, one needs to install composer first by `composer install` command. 

Once the composer is installed, the script in the simplest form can be executed with following command,

`php assignment.php --file path\to\the\file`

*Example, Following command shall generate a unique-combination-results.csv file with required output.*

`php assignment.php --file sample-input-files\products_comma_separated.csv` 

However, the script has a **number of customizations**, which are as follows:

1. The output file name and path **can be customized** by passing an option `--unique-combination` to the command. Example,

    `php assignment.php --file sample-input-files\products_comma_separated.csv --unique-combination=final-results.csv`
2. By default, the script **bails with an exception**, if any row in the input file fails the validation. However, this **can be prevented** and parsing can be continued without failed-rows by passing an option `--dont-bail`. Example,

    `php assignment.php --file sample-input-files\products_comma_separated.csv --dont-bail`
3. By default, the script **doesn't output the parsed product object** with given properties to be mapped. However, the parsed objects **can be printed** by passing an option `--print-objects`. Example,

    `php assignment.php --file sample-input-files\products_comma_separated.csv --print-objects`
4. Since, the headings in the input file can be changed in the future, it is required the same should be updated in the mapping provided in a class `App\CsvParser.php` with variable `$mappingAndSequence_ofHeadings`, so that the headings can be easily mapped with required properties of the product.
5. The **validation rules are customizable** by modifying the array `$rules` in a class `App\Validations.php`.
6. The **validation messages are customizable** by modifying the array `$messages` in a class `App\Validations.php`.
7. The tests can be run by `vendor\bin\phpunit --testdox`.
