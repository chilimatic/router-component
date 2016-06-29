<?php
namespace chilimatic\lib\Route\System;

use chilimatic\lib\Datastructure\Graph\Tree\Binary\BinaryTree;
use chilimatic\lib\Route\Exception\RouteException;
use chilimatic\lib\Route\Map;


/**
 * Class NodeRoute
 * @package chilimatic\lib\Route\System
 */
class NodeRoute extends AbstractRoute
{

    /**
     * Main Node
     *
     * @var BinaryTree
     */
    private $binaryTree;


    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->binaryTree = new BinaryTree();
        parent::__construct($path);
    }

    /**
     * @return BinaryTree
     * @throws RouteException
     */
    public function getRoot()
    {
        if ($this->binaryTree->isEmpty()) {
            $this->binaryTree->insert('/', $this->getDefaultRoute());
        }

        return $this->binaryTree->getRoot();
    }

    /**
     * @param null $path
     * @param array|null $urlParts
     *
     * @return mixed|null
     */
    public function getRoute(array $urlParts = null)
    {
        if (($map = $this->binaryTree->findByKey(implode($urlParts)))) {
            return $map;
        }

        if (($map = $this->getStandardRoute($urlParts))) {
            return $map;
        }

        return $this->getRoot()->getData();
    }

    /**
     * register a new custom Route / overwrite an old one
     *
     * @param string $uri
     * @param mixed $callback
     * @param $delimiter
     *
     * @throws RouteException
     * @return void
     */
    public function addRoute($uri, $callback, $delimiter = Map::DEFAULT_URL_DELIMITER)
    {
        try {
            /**
             * if the uri is empty throw an exception
             */
            if (empty($uri)) {
                throw new RouteException(sprintf(_('There is no Route entered %s'), $uri));
            }

            // class for mapping
            $route = new Map($uri, $callback, $delimiter);
            $this->rootNode->appendToBranch($uri, $route, Map::DEFAULT_URL_DELIMITER);

        } catch (RouteException $e) {
            throw $e;
        }

    }
}