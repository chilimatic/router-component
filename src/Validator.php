<?php
declare(strict_types=1);
namespace chilimatic\lib\Route;

use chilimatic\lib\Config\Config;
use chilimatic\lib\Interfaces\IFlyWeightValidator;
use chilimatic\lib\Route\Exception\RouteException;

/**
 * Class Validator
 *
 * @package chilimatic\lib\Route
 */
class Validator
{
    /**
     * @var string
     */
    const VALIDATOR_PREFIX = 'Validator';

    /**
     * validator gets the short other validators
     *
     * @var IFlyWeightValidator
     */
    private $validator;


    /**
     * pattern for url type matching default (:<shortcurt>)
     *
     * @var string
     */
    private $_current_pattern = '/[(]\:(.*)[)](?:[\[]([\!|+-_:%]?)[\]])?/';


    /**
     * url part to be validated
     *
     * @var string
     */
    private $urlPart = '';


    /**
     * constructor
     *
     * @param string $urlPart
     */
    public function __construct($urlPart)
    {

        if (empty($urlPart)) {
            return;
        }

        $this->urlPart = $urlPart;

        if (($p = Config::get('url_validator_pattern')) != '') {
            $this->_current_pattern = $p;
        }

        $this->init();
    }


    /**
     * validates pattern based on the settings
     *
     * @param string $string
     *
     * @return bool
     */
    private function isValidPattern(string $string)
    {
        return preg_match($this->_current_pattern, $string);
    }


    /**
     * magic getter
     *
     * @param string $property
     *
     * @return mixed
     */
    public function __get(string $property)
    {
        if (!property_exists($this, $property)) {
            return false;
        }

        return $property;
    }


    /**
     * extract pattern
     *
     * @return array
     */
    private function getMatches() : array
    {

        if (empty($this->urlPart) || !$this->validator->validate($this->urlPart)) {
            return [];
        }

        if (!preg_match($this->_current_pattern, $this->urlPart, $matches)) {
            return [];
        }

        return $matches;
    }


    /**
     * init method
     *
     * @param null $urlPart
     *
     * @return $this
     * @throws \chilimatic\lib\Route\Exception\RouteException
     */
    public function init($urlPart = null)
    {

        try {
            if (!empty($urlPart) && $this->validator->validate($urlPart)) {
                $this->urlPart = $urlPart;
            }

            if (empty($this->urlPart) || !$this->validator->validate($this->urlPart)) {
                throw new RouteException('url part empty or not a valid pattern : ' . $this->urlPart);
            }

            $array = $this->getMatches();

            $validator = (string)(get_class($this) . '\\') . self::VALIDATOR_PREFIX . ucfirst($array[1]);

            if (!class_exists($validator)) {
                throw new RouteException('Class does not exist : ' . $validator);
            }
        } catch (RouteException $e) {
            throw $e;
        }

        $this->validator = new $validator();
        if (isset($array[2])) {
            $this->validator->delimiter = $array[2];
        }

        return $this;
    }
}