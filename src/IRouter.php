<?php
declare(strict_types=1);
namespace chilimatic\lib\Route;

use chilimatic\lib\Route\Exception\RouteException;

/**
 *
 * @author j
 * Date: 4/25/15
 * Time: 8:46 PM
 *
 * File: IRouter.php
 */
interface IRouter
{

    /**
     * @var string
     */
    const DEFAULT_ROUTING_TYPE = 'Node';

    /**
     * routing error code
     *
     * @var int
     */
    const ROUTING_ERROR = 20;

    /**
     * @param string
     *
     * @throws RouteException
     * @throws \Exception
     */
    public function __construct(string $type);


}