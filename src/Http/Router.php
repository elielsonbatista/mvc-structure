<?php

namespace Src\Http;

class Router
{
    /**
     * The list of routes that can be accessed
     *
     * @var array
     */
    public $routes = [];

    /**
     * Add a route to the list
     *
     * @param  string $url
     * @param  string|callable $action
     * @return void
     */
    public function get(string $url, $action): void
    {
        if (strlen($url) && $url[0] != '/') {
            $url = '/' . $url;
        }

        $this->routes[$url] = $action;
    }

    /**
     * Access the route and get the content
     *
     * @param  string|callable $callback
     * @return mixed
     */
    public function access($callback)
    {
        if (is_string($callback)) {
            $split_action = explode('@', $callback);

            return $this->actionResponse(
                CONTROLLERS_NAMESPACE . '\\' . $split_action[0],
                $split_action[1]
            );
        }

        return $callback();
    }

    /**
     * Call an action of a controller
     *
     * @param  string $controller
     * @param  string $action
     * @return mixed
     */
    private function actionResponse(string $controller, string $action)
    {
        return (new $controller)->$action();
    }
}
