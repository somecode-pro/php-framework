<?php

namespace Somecode\Framework\Http;

class Kernel
{
    public function handle(Request $request): Response
    {
        // example: controller -> $content
        $content = '<h1>Hello, World!</h1>';

        return new Response($content);
    }
}
