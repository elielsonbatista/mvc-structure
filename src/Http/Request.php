<?php

namespace Src\Http;

class Request
{
    /**
     * The router containing the routes that can be accessed
     *
     * @var Router
     */
    private $router;

    /**
     * Create a new request instance
     *
     * @param  Router $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Start the request
     *
     * @return void
     */
    public function start()
    {
        if (array_key_exists($_SERVER['REQUEST_URI'], $this->router->routes)) {
            echo $this->router->access($this->router->routes[$_SERVER['REQUEST_URI']]);
        } else {
            echo 'Are you lost!';
        }
    }
}
