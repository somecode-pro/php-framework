<?php

namespace App\Controllers;

use Somecode\Framework\Controller\AbstractController;
use Somecode\Framework\Http\Response;

class PostController extends AbstractController
{
    public function show(int $id): Response
    {
        return $this->render('posts.html.twig', [
            'postId' => '<script>alert("Boooom!!!")</script>', // $id,
        ]);
    }

    public function create(): Response
    {
        return $this->render('create_post.html.twig');
    }
}
