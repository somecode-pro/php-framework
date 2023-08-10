<?php

namespace App\Controllers;

use App\Services\YouTubeService;
use Somecode\Framework\Controller\AbstractController;
use Somecode\Framework\Http\Response;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly YouTubeService $youTube,
    ) {
    }

    public function index(): Response
    {
        phpinfo();
        dd(123);

        return $this->render('home.html.twig', [
            'youTubeChannel' => $this->youTube->getChannelUrl(),
        ]);
    }
}
