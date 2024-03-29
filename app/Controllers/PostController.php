<?php

namespace App\Controllers;

use App\Entities\Post;
use App\Services\PostService;
use Somecode\Framework\Controller\AbstractController;
use Somecode\Framework\Http\RedirectResponse;
use Somecode\Framework\Http\Response;

class PostController extends AbstractController
{
    public function __construct(
        private PostService $service
    ) {
    }

    public function show(int $id): Response
    {
        $post = $this->service->findOrFail($id);

        return $this->render('posts.html.twig', [
            'post' => $post,
        ]);
    }

    public function create(): Response
    {
        return $this->render('create_post.html.twig');
    }

    public function store()
    {
        $post = Post::create(
            $this->request->input('title'),
            $this->request->input('body'),
        );

        $post = $this->service->save($post);

        $this->request->getSession()->setFlash('success', 'Пост успешно создан!');

        return new RedirectResponse("/posts/{$post->getId()}");
    }
}
