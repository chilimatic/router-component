<?php
declare(strict_types=1);
namespace chilimatic\lib\Route\Validator;

/**
 * Class ValidatorChar
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
class ValidatorChar extends AbstractValidator
{

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function validate($value) : bool
    {
        return preg_match('/^\w{0,}$/', $value) && !preg_match('/^\d{0,}[.,]?\d*$/', $value);
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