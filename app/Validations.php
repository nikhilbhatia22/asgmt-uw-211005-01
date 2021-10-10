<?php


namespace App;


class Validations {

    protected static $rules = [
        'make' => 'required|string',
        'model' => 'required|string',
        'colour' => 'string',
        'capacity' => 'string',
        'network' => 'string',
        'grade' => 'string',
        'condition' => 'string',
    ];

    protected static $messages = [
        'required' => '%attribute% is required.',
        'string' => '%attribute% should be a valid string.',
    ];

    /**
     * Validates the given row data
     * @param $rowData
     * @param $lineNo
     * @param $bailValidation
     * @return bool
     * @throws \Exception
     */
    public static function validateRow($rowData, $lineNo, $bailValidation){
        foreach ($rowData as $key => $rowDatum) {
            if(empty($key))     continue;
            foreach (explode('|', self::$rules[$key]) as $rule) {
                $validator = 'validate' . ucfirst($rule);
                if(!self::$validator($rowDatum)){
                    if($bailValidation)
                        throw new \RuntimeException("At line #$lineNo, " . str_replace('%attribute%', $key, self::$messages[$rule]) . "\n");
                    else
                        echo "At line #$lineNo, " . str_replace('%attribute%', $key, self::$messages[$rule]) . "\n";
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
    public static function validateRequired($value){
        if (is_null($value))    return false;
        elseif (is_string($value) && trim($value) === '')   return false;
        elseif ((is_array($value) || $value instanceof \Countable) && count($value) < 1)     return false;
        return true;
    }

    /**
     * Validates if the value is string.
     * @param $value
     * @return bool
     */
    public static function validateString($value)
    {
        return is_string($value);
    }

}