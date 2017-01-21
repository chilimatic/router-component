<?php
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
     * maybe some comments for the nodes
     *
     * @var string
     */
    private $comment = '';

    /**
     * @param Inode|null $parentNode
     * @param $key
     * @param \chilimatic\lib\Route\Map $map
     * @param string $comment
     */
    public function __construct(INode $parentNode = null, $key, $data,$comment = '')
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