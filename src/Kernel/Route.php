<?php

namespace Bloom\Kernel;

class Route
{
    const string GET = 'GET';
    const string POST = 'POST';
    private static array $routes = [];
    private string $type;
    private string $url;
    private $action;

    /**
     * @param string $type
     * @param string $url
     * @param array|callable $action
     */
    public function __construct(string $type, string $url, Callable|array $action)
    {
        $this->type = $type;
        $this->url = $url;
        $this->action = $action;
    }


    public static function get($url, Callable|array $action): void
    {
        self::$routes[] = new self(Route::GET, $url, $action);
    }

    public static function post($url, Callable|array $action): void
    {
        self::$routes[] = new self(Route::POST, $url, $action);
    }
}