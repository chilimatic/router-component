<?php
declare(strict_types=1);
namespace chilimatic\lib\Route\Validator;

use chilimatic\lib\Interfaces\IFlyWeightValidator;

/**
 * Class AbstractValidator
 *
 * @package chilimatic\lib\Route\Validator
 */
abstract class AbstractValidator implements IFlyWeightValidator
{


    /**
     * current value inserted through the url
     *
     * @var string
     */
    public $value = null;


    /**
     * for more complex calles / lamdafunctions with array parameters through
     * the url a delimiter is needed
     *
     * @var string
     */
    public $delimiter = null;


    /**
     * @param mixed $value
     *
     * @return mixed
     */
    abstract public function validate($value) : bool;


    /**
     * @param mixed $value
     *
     * @return mixed
     */
    abstract public function __invoke($value) : bool;
}