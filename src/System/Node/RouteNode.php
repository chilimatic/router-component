<?php
declare(strict_types=1);
namespace chilimatic\lib\Route\System\Node;

use chilimatic\lib\Datastructure\Graph\INode;
use chilimatic\lib\Datastructure\Graph\TreeNode;
use chilimatic\lib\Route\Exception\RouteException;
use chilimatic\lib\Route\Map;

/**
 * Class RouteNode
 * @package chilimatic\lib\Route\System\Node
 */
class RouteNode extends TreeNode
{

    /**
     * RouteNode constructor.
     * @param INode|null $parentNode
     * @param string $key
     * @param $data
     * @param string $comment
     */
    public function __construct(INode $parentNode = null,string $key, $data, string $comment = '')
    {
        if (!$data instanceof Map) {
            throw new RouteException('Data parameter needs to be of Type Map');
        }

        // set the parent node
        $this->parentNode = $parentNode;
        // set the current path identifier
        $this->key = $key;
        // set the current value of the node

        if (empty($this->parentNode->$key)) {
            $this->id = (string)$key;
        } else {
            $this->id = "{$this->parentNode->$key}.{$key}";
        }


        // add the map
        $this->value = $data;

        /**
         * remove double slashes
         */
        $this->id = str_replace('//', '/', $this->id);

        // optional comment
        $this->comment = $comment;

        /**
         * add a Route_NodeList for the children
         */
        $this->initChildren();
    }

    public function getMap() : Map
    {
        return $this->getData();
    }

    /**
     * gets a comment
     *
     * @return string
     */
    public function getComment() : string
    {
        return $this->comment;
    }

    /**
     * sets a comment
     *
     * @param $comment
     *
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }
}