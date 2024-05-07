<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Repository\PostRepository;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'app_article')]
    public function index(PostRepository $postRepository): Response
    {
        return $this->redirectToRoute('homepage');
    }

    #[Route('/modifier-article/{id}', name: 'modifier_article')]
    public function modifier(Request $request, EntityManagerInterface $em, Post $article): Response
    {
        $form = $this->createFormBuilder($article)
            ->add('titre', TextType::class)
            ->add('contenu', TextareaType::class)
            ->add('auteur', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Modifier Article'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'L\'article a été modifié avec succès.');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('article/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/supprimer-article/{id}', name: 'supprimer_article')]
    public function supprimer(EntityManagerInterface $em, Post $post): Response
    {
        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'L\'article a été supprimé avec succès.');

        return $this->redirectToRoute('homepage');
    }
}
