<?php
declare(strict_types=1);
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
    const PATTERN = '/^\d*[.,]?\d*$/';

    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value) : bool
    {
        // generic pattern match if it's numeric [float/int/double]
        if (!preg_match(self::PATTERN, (string)$value)) {
            return false;
        }

        $this->value = $value;

        return true;
    }


    /**
     * @param mixed $value
     * @return bool
     */
    public function __invoke($value) : bool
    {
        return $this->validate($value);
    }
}