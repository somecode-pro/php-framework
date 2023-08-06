<?php

namespace Somecode\Framework\Http;

use Somecode\Framework\Routing\RouterInterface;

class Kernel
{
    public function __construct(
        private RouterInterface $router
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request);

            $response = call_user_func_array($routeHandler, $vars);
        } catch (\Throwable $exception) {
            $response = new Response($exception->getMessage(), 500);
        }

        return $response;
    }
}
