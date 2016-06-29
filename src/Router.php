<?php
namespace chilimatic\lib\Route;

use \chilimatic\lib\Route\Exception\RouteException;
use \chilimatic\lib\Interfaces\IFlyWeightParser;
use \chilimatic\lib\Route\Parser\UrlParser;
use \chilimatic\lib\Route\System\RouteSystemFactory;

/**
 * main routing system that loads & maps and saves
 * contains a list of possible routes that can be called edited
 *
 *
 * possible are
 * -> stdclass
 * -> array numeric
 * -> array assoc
 * -> lambda function
 * -> function call
 * -> static content
 *
 * chilimatic framework routing
 *
 * examples
 * \chilimatic\route\Route::register('/test/(:num)', array('job', 'load'), '/');
 * \chilimatic\route\Route::register('/user/add/(:num)', array('object' => 'user', 'method' => 'add', 'namespace' => '\\user\\', 'param' => array(true, false)));
 * \chilimatic\route\Route::register('/test/(:char)', array('object' => 'user', 'method' => 'add', 'namespace' => '\\user\\', 'param' => array(true, false)));
 * \chilimatic\route\Route::register('/mytest/(:array)[|]',  function($num) { foreach($num as $val) {  echo $val . ': this is a test'; }});
 *
 *
 * @author j
 */
class Router implements IRouter
{

    /**
     * url parts
     *
     * @var array
     */
    protected $urlPart = array();

    /**
     * @var
     */
    private $routeSystem;

    /**
     * @var UrlParser
     */
    private $urlParser;

    /**
     * @var string
     */
    private $path;

    /**
     * @param string
     *
     * @throws RouteException
     * @throws \Exception
     */
    public function __construct($type)
    {
        $this->urlParser   = new UrlParser();
        $this->routeSystem = RouteSystemFactory::make($type, $this->getCurrentUrlPath());
    }

    /**
     * if no path has been specified get a fallback path
     *
     * @return array|string
     */
    private function getCurrentUrlPath()
    {
        if ($this->path) {
            return $this->path;
        }

        /**
         * check if the path is empty otherwise
         * set it with the server variable and if there's no server variable set it as the default delimiter [/] the root
         */
        $this->path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
        $this->urlPart = $this->parsePath($this->path);

        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return array
     */
    private function parsePath($path)
    {
        if (empty($path)) {
            $path = Map::DEFAULT_URL_DELIMITER;
        }

        // get the clean path
        return $this->urlParser->parse($path);
    }


    /**
     * the real routing should happen here
     *
     * @param mixed $path
     *
     * @return null
     *
     * @throws RouteException
     */
    public function getRoute($path = null)
    {
        // check the path
        if (!$path || ($this->path && $path !== $this->path)){
            $urlParts = $this->urlPart;
        } else {
            $urlParts = $this->parsePath($path);
        }
        return $this->routeSystem->getRoute($urlParts);
    }



    /**
     * @return mixed
     */
    public function getUrlParser()
    {
        return $this->urlParser;
    }

    /**
     * @param mixed $urlParser
     *
     * @return $this
     */
    public function setUrlParser(IFlyWeightParser $urlParser)
    {
        $this->urlParser = $urlParser;

        return $this;
    }

    /**
     * @return array
     */
    public function getUrlPart()
    {
        if (!$this->urlPart) {
            $this->urlPart = $this->getCurrentUrlPath();
        }

        return $this->urlPart;
    }

    /**
     * @param array $urlPart
     *
     * @return $this
     */
    public function setUrlPart($urlPart)
    {
        $this->urlPart = $urlPart;

        return $this;
    }
}