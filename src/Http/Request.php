<?php

namespace Src\Http;

use Router;

class Request
{
    private $router;

    public function __construct($router)
    {
        $this->router = $router;
    }

    public function start()
    {
        if (array_key_exists($_SERVER['REQUEST_URI'], $this->router->routes)) {
            echo $this->router->access($this->router->routes[$_SERVER['REQUEST_URI']]);
        } else {
            echo 'Rapaz, tu se perdeu hein!';
        }
    }
}
