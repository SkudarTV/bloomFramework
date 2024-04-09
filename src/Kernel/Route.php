<?php

namespace Bloom\Kernel;

use Closure;

class Route
{
    const string GET = 'GET';
    const string POST = 'POST';
    private static array $routes = [];
    private string $method;
    private string $url;
    private $action;

    /**
     * @param string $method
     * @param string $url
     * @param array|callable $action
     */
    public function __construct(string $method, string $url, Callable|array $action)
    {
        $this->method = $method;
        $this->url = $url;
        $this->action = $action;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function uri(): string
    {
        return $this->url;
    }

    public function run(): void
    {
        if($this->action instanceof Closure)
        {
            $this->action->call($this);
        }
        else {
            // TODO implement controller
        }
    }

    public static function get($url, Callable|array $action): void
    {
        self::$routes[] = new self(Route::GET, $url, $action);
    }

    public static function post($url, Callable|array $action): void
    {
        self::$routes[] = new self(Route::POST, $url, $action);
    }

    public static function getRoute($method, $uri): ?Route
    {
        var_dump($method, $uri);
        var_dump(self::$routes);
        $methodRoutes = array_filter(self::$routes, fn(Route $route) => $route->method() === $method);
        var_dump($methodRoutes);
        $route = array_filter($methodRoutes, fn(Route $route) => $route->uri() === $uri);
        var_dump($route);

        return array_shift($route);
    }
}