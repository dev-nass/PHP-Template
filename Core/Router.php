<?php

namespace Core;

use Core\Middleware\Middleware;

class Router
{

    private $routes = [];

    /**
     * $controller - contains the ctrlr class
     * $action - is method within the ctrlr
     */
    private function add($method, $uri, $controller, $action)
    {

        $this->routes[] = [
            'method' => $method,
            'uri' => $_ENV['APP_URL'] . "$uri",
            'controller' => $controller,
            'action' => $action,
            'middleware' => null,
            'middleware_role' => null,
        ];

        // added for middewares
        return $this;
    }

    public function get($uri, $controller, $action)
    {
        return $this->add('GET', $uri, $controller, $action);
    }

    public function post($uri, $controller, $action)
    {
        return $this->add('POST', $uri, $controller, $action);
    }

    /**
     * For adding middlewares 
     * to every last last route elem.
     */
    public function only($key, $role = null)
    {
        $this->routes[array_key_last($this->routes['middleware'])] = $key;
        $this->routes[array_key_last($this->routes['middleware_role'])] = $role;
    }

    /**
     * Routes the page based on
     * the request.
     */
    public function route($uri, $method)
    {

        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === $method) {

                Middleware::resolve($route['middleware'], $route['middleware_role']);

                $controller_class_path = '\\App\Http\Controllers\\' . $route['controller'];
                $controller_class_instance = new $controller_class_path();
                $controller_class_actions = get_class_methods($controller_class_instance);

                foreach ($controller_class_actions as $action) {
                    if ($action === $route['action']) {
                        call_user_func([$controller_class_instance, $action]);
                        return;
                    }
                }

            }
        }

        abort(404);
    }
}