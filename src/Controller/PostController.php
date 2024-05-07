<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/new-post", name="new_post")
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            // Redirect to the list of posts or another page after adding
            return $this->redirectToRoute('blog_index');
        }

        return $this->render('post/post.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
