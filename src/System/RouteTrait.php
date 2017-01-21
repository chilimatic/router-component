<?php
declare(strict_types=1);
namespace chilimatic\lib\Route\System;

use chilimatic\lib\Di\ClosureFactory;
use chilimatic\lib\Interfaces\IFlyWeightTransformer;
use chilimatic\lib\Route\Exception\RouteException;
use chilimatic\lib\Route\Map;
use chilimatic\lib\Route\Map\MapFactory;
use chilimatic\lib\Transformer\String\DynamicFunctionCallName;

/**
 * Class RouteTrait
 *
 * @package chilimatic\lib\Route\System
 */
Trait RouteTrait
{
    /**
     * @var string
     */
    public $defaultModule = 'main';

    /**
     * @var string
     */
    private $defaultClass = 'Index';

    /**
     * @var string
     */
    private $defaultMethod = 'index';

    /**
     * @var string
     */
    private $notFoundMethod = 'notFound';

    /**
     * @var string
     */
    private $actionSuffix = 'Action';

    /**
     * @var string
     */
    private $defaultPath = '/';

    /**
     * @var string
     */
    private $defaultAppNameSpace = '\app\module';

    /**
     * @var string
     */
    private $applicationNameSpace = '';

    /**
     * @var string
     */
    private $defaultControllerPath = 'controller';

    /**
     * @var string
     */
    private $defaultUrlDelimiter = '/';

    /**
     * @var MapFactory
     */
    private $mapFactory;

    /**
     * @var DynamicFunctionCallName
     */
    private $transformer;

    /**
     * gets the default route based on my framework scheme
     *
     * @throws RouteException
     *
     * @returns \chilimatic\lib\Route\Map
     */
    public function getDefaultRoute()
    {

        try {
            return $this->buildRouteMap(
                $this->defaultUrlDelimiter,
                [
                    'object' => implode('\\', $this->generateClassName(
                        $this->getApplicationNameSpace() . $this->defaultAppNameSpace,
                        $this->defaultModule,
                        $this->defaultControllerPath,
                        $this->defaultClass)
                    ),
                    'method' => $this->defaultMethod . $this->actionSuffix
                ],
                $this->defaultUrlDelimiter
            );
        } catch (RouteException $e) {
            throw $e;
        }
    }

    /**
     * loads the config
     */
    public function loadConfig()
    {
        $config = ClosureFactory::getInstance()->get('config');
        $ns = $config->get('application-namespace');
        if ($ns) {
            $this->applicationNameSpace = $ns;
        }
    }


    /**
     * returns the map
     *
     * @param string $path
     * @param array $config
     * @param string $delimiter
     *
     * @throws RouteException
     *
     * @return \chilimatic\lib\Route\Map
     */
    public function buildRouteMap(string $path, $config, $delimiter = Map::DEFAULT_URL_DELIMITER)
    {
        try {
            if (!$this->getMapFactory()) {
                $this->setMapFactory(new MapFactory());
            }

            return $this->mapFactory->make($path, $config, $delimiter);
        } catch (RouteException $e) {
            throw $e;
        }

    }

    /**
     * declarative generator method
     *
     * @param string $namespace
     * @param string $module
     * @param string $class
     * @param string $controllerPath
     *
     * @return string
     */
    private function generateClassName(string $namespace, string $module, string $controllerPath, string $class)
    {
        return [$namespace, $module, $controllerPath, ucfirst($this->transformer->transform($class))];
    }


    /**
     * @param array|null $urlParts
     *
     * @return Map|null
     */
    public function getStandardRoute(array $urlParts = null)
    {
        // if there is the slash in the positon zero remove it
        if (isset($urlParts[0]) && $urlParts[0] === '/') {
            array_shift($urlParts);
        }


        // more than 1 part means class/method/[value or param{/value}]
        if (count($urlParts) >= 1)
        {
            $module    = empty($urlParts[0]) ? $this->defaultModule : $urlParts[0];
            unset($urlParts[0]);
            $className = empty($urlParts[1]) ? $this->defaultClass : $urlParts[1];
            unset($urlParts[1]);
            $class     = implode('\\',
                $this->generateClassName(
                    $this->getApplicationNameSpace(). $this->defaultAppNameSpace,
                    $module,
                    $this->defaultControllerPath,
                    $className
                )
            );
            $urlMethod = (string)empty($urlParts[2]) ? $this->defaultMethod : $urlParts[2];
            unset($urlParts[2]);
            $method    = $this->transformer->transform($urlMethod . $this->actionSuffix);

        } else {
            $className = $this->defaultClass;
            $class     = implode('\\', $this->generateClassName(
                $this->defaultAppNameSpace,
                $this->defaultModule,
                $this->defaultControllerPath,
                $this->defaultClass)
            );

            $urlMethod = (string) $this->notFoundMethod;
            $method    = $this->transformer->transform($urlMethod . $this->actionSuffix);
            $module = $this->defaultModule;
        }

        if (class_exists($class, true)) {
            foreach ((array) get_class_methods($class) as $cmethod) {
                if (strtolower($cmethod) != strtolower($method)) {
                    continue;
                }

                return $this->mapFactory->make(
                    strtolower("/{$module}/{$className}/{$urlMethod}"),
                    [
                        'object'    => $class,
                        'method'    => $this->transformer->transform($method),
                        'namespace' => null,
                        'param'     => $urlParts
                    ],
                    $this->defaultUrlDelimiter
                );
            }
        }

        return null;
    }


    /**
     * @return string
     */
    public function getActionSuffix() : string
    {
        return $this->actionSuffix;
    }

    /**
     * @param string $actionSuffix
     *
     * @return $this
     */
    public function setActionSuffix(string $actionSuffix)
    {
        $this->actionSuffix = $actionSuffix;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultClass() : string
    {
        return $this->defaultClass;
    }

    /**
     * @param string $defaultClass
     */
    public function setDefaultClass(string $defaultClass)
    {
        $this->defaultClass = $defaultClass;
    }

    /**
     * @return string
     */
    public function getDefaultMethod() : string
    {
        return $this->defaultMethod;
    }

    /**
     * @param string $defaultMethod
     */
    public function setDefaultMethod(string $defaultMethod)
    {
        $this->defaultMethod = $defaultMethod;
    }

    /**
     * @return string
     */
    public function getDefaultPath() : string
    {
        return $this->defaultPath;
    }

    /**
     * @param string $defaultPath
     */
    public function setDefaultPath(string $defaultPath)
    {
        $this->defaultPath = $defaultPath;
    }

    /**
     * @return MapFactory|null
     */
    public function getMapFactory()
    {
        return $this->mapFactory;
    }

    /**
     * @param MapFactory $mapFactory
     */
    public function setMapFactory(MapFactory $mapFactory)
    {
        $this->mapFactory = $mapFactory;
    }

    /**
     * @return string
     */
    public function getDefaultNameSpace() : string
    {
        return $this->defaultAppNameSpace;
    }

    /**
     * @param string $defaultNameSpace
     */
    public function setDefaultNameSpace(string $defaultNameSpace)
    {
        $this->defaultNameSpace = $defaultNameSpace;
    }

    /**
     * @return string
     */
    public function getDefaultUrlDelimiter()  : string
    {
        return $this->defaultUrlDelimiter;
    }

    /**
     * @param string $defaultUrlDelimiter
     */
    public function setDefaultUrlDelimiter(string $defaultUrlDelimiter)
    {
        $this->defaultUrlDelimiter = $defaultUrlDelimiter;
    }


    /**
     * @return string
     */
    public function getDefaultModule()  : string
    {
        return $this->defaultModule;
    }

    /**
     * @param string $defaultModule
     *
     * @return $this
     */
    public function setDefaultModule(string $defaultModule)
    {
        $this->defaultModule = $defaultModule;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultControllerPath()  : string
    {
        return $this->defaultControllerPath;
    }

    /**
     * @param string $defaultControllerPath
     *
     * @return $this
     */
    public function setDefaultControllerPath(string $defaultControllerPath)
    {
        $this->defaultControllerPath = $defaultControllerPath;

        return $this;
    }

    /**
     * @return DynamicFunctionCallName
     */
    public function getTransformer()
    {
        return $this->transformer;
    }

    /**
     * @param IFlyWeightTransformer $transformer
     *
     * @return $this
     */
    public function setTransformer(IFlyWeightTransformer $transformer)
    {
        $this->transformer = $transformer;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultAppNameSpace()  : string
    {
        return $this->defaultAppNameSpace;
    }

    /**
     * @param string $defaultAppNameSpace
     *
     * @return $this
     */
    public function setDefaultAppNameSpace(string $defaultAppNameSpace)
    {
        $this->defaultAppNameSpace = $defaultAppNameSpace;

        return $this;
    }

    /**
     * @return string
     */
    public function getApplicationNameSpace() : string
    {
        if (!$this->applicationNameSpace) {
            $this->loadConfig();
        }

        return $this->applicationNameSpace;
    }

    /**
     * @param string $applicationNameSpace
     *
     * @return $this
     */
    public function setApplicationNameSpace(string $applicationNameSpace)
    {
        $this->applicationNameSpace = $applicationNameSpace;

        return $this;
    }

}