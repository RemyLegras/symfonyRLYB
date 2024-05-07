<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PostRepository;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog_index')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }
    
    #[Route('/blog/{id}', name: 'blog_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, PostRepository $postRepository): Response
    {
        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException('L\'article demandé n\'existe pas');
        }

        return $this->render('blog/detail.html.twig', [
            'post' => $post,
        ]);
    }
}
