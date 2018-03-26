<?php

namespace Src\Http;

class Router
{
    private $controllers_path = '\\App\\Http\\Controllers';
    public $routes = [];

    public function get($url, $action)
    {
        if (strlen($url) && $url[0] != '/') {
            $url = '/' . $url;
        }

        $this->routes[$url] = $action;
    }

    public function access($callback)
    {
        if (is_string($callback)) {
            $split_action = explode('@', $callback);

            return $this->actionResponse(
                $this->controllers_path . '\\' . $split_action[0],
                $split_action[1]
            );
        } else {
            return $callback();
        }
    }

    private function actionResponse($controller, $action)
    {
        $controller = new $controller();
        return $controller->$action();
    }
}
