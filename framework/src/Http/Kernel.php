<?php

namespace Somecode\Framework\Http;

use League\Container\Container;
use Somecode\Framework\Http\Exceptions\HttpException;
use Somecode\Framework\Routing\RouterInterface;

class Kernel
{
    public function __construct(
        private RouterInterface $router,
        private Container $container
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);

            $response = call_user_func_array($routeHandler, $vars);
        } catch (HttpException $e) {
            $response = new Response($e->getMessage(), $e->getStatusCode());
        } catch (\Throwable $e) {
            $response = new Response($e->getMessage(), 500);
        }

        return $response;
    }
}
