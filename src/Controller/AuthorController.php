<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author/{id}', name: 'app_author')]
    public function show(User $user): Response
    {
        return $this->render('author/show.html.twig', [
            'author' => $user,
            'articles' => $user->getArticles()->filter(fn($article) => $article->isPublished())
        ]);
    }
} 