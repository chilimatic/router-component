<?php
declare(strict_types=1);
namespace chilimatic\lib\Route\Validator;

/**
 * Class ValidatorArray
 *
 * Simple validation Class can be extended
 *
 * class is based on the pattern (:num) which will be resolved to this classname
 * therefore new classes can be added and new types of validations defined with such short snipplets
 *
 * @author  j
 * @package chilimatic\lib\Route\Validator
 */
class ValidatorArray extends AbstractValidator
{

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function validate($value) : bool
    {
        if (empty($value)) {
            return false;
        }

        if (stripos($value, $this->delimiter) !== false) {
            $this->value = explode($this->delimiter, $value);

            return true;
        }

        $this->value = array(
            $value
        );

        return true;
    }


    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function __invoke($value) : bool
    {
        return $this->validate($value);
    }
}