<?php

namespace chilimatic\lib\Route\Validator;

/**
 * Class ValidatorNum
 *
 * Simple validation Class can be extended
 *
 * class is based on the pattern (:num) which will be resolved to this classname
 * therefore new classes can be added and new types of validations defined with such short snipplets
 *
 * @author  j
 *
 * @package chilimatic\lib\Route\Validator
 */
class ValidatorNum extends AbstractValidator
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function validate($value)
    {
        // generic pattern match if it's numeric [float/int/double]
        if (!preg_match('/^\d{0,}[.,]?\d*$/', (string)$value)) {
            return false;
        }

        $this->value = $value;

        return true;
    }


    /**
     * (non-PHPdoc)
     *
     * @see \Route\Route_AbstractValidator::validate()
     */
    public function __invoke($value)
    {
        return $this->validate($value);
    }
}