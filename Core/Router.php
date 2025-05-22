<?php

namespace Core;

use Core\Middleware\Middleware;

class Router
{

    public $routes = [];

    /**
     * $controller - contains the ctrlr class
     * $action - is method within the ctrlr
    */
    public function add($method, $uri, $controller, $action)
    {

        $this->routes[] = [
            'method' => $method,
            'uri' => "/PHP%202025/PHP%20Template/public/index.php/{$uri}",
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
        foreach($this->routes as $route) {

            if($route['uri'] === $uri && $route['method'] === $method) {

                Middleware::resolve($route['middleware'], $route['middleware_role']);

                $ctrlrClass = 'app\Http\Controllers\\' . $route['controller'];
                $ctrlrInstance = new $ctrlrClass();
                $ctrlrActions = get_class_methods($ctrlrInstance);

                foreach($ctrlrActions as $action) {
                    if($route['action'] === $action) {
                        call_user_func([$ctrlrInstance, $action]);
                        return; // added for the abort, so the loop will end
                    }
                }

            }
        }

        abort(404);
    }
}