<?php

global $rules, $messages;
$rules = [
    'make' => 'required|string',
    'model' => 'required|string',
    'colour' => 'string',
    'capacity' => 'string',
    'network' => 'string',
    'grade' => 'string',
    'condition' => 'string',
];
$messages = [
    'required' => '%attribute% is required.',
    'string' => '%attribute% should be a valid string.',
];

/**
 * Validates the given row data
 * @param $rowData
 * @param $lineNo
 * @param $bailValidation
 * @return bool
 * @throws Exception
 */
function validateRow($rowData, $lineNo, $bailValidation){
    global $rules, $messages;

    foreach ($rowData as $key => $rowDatum) {
        if(empty($key))     continue;
        foreach (explode('|', $rules[$key]) as $rule) {
            $validator = 'validate' . ucfirst($rule);
            if(!$validator($rowDatum)){
                if($bailValidation)
                    throw new RuntimeException("At line #$lineNo, " . str_replace('%attribute%', $key, $messages[$rule]) . "\n");
                else
                    echo "At line #$lineNo, " . str_replace('%attribute%', $key, $messages[$rule]) . "\n";
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
