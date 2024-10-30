<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/{slug}', name: 'app_article')]
    public function show(
        string $slug,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $article = $entityManager->getRepository(Article::class)->findOneBy([
            'titleSlug' => $slug,
            'published' => true
        ]);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setArticle($article);
            
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté');
            return $this->redirectToRoute('app_article', ['slug' => $article->getTitleSlug()]);
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView(),
        ]);
    }
} 