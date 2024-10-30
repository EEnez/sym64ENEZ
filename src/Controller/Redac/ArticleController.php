<?php

namespace App\Controller\Redac;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/redac')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'redac_dashboard')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_REDAC');
        
        return $this->render('redac/index.html.twig', [
            'articles' => $articleRepository->findBy(
                ['author' => $this->getUser()],
                ['createdAt' => 'DESC']
            )
        ]);
    }

    #[Route('/article/new', name: 'redac_article_new')]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_REDAC');
        
        $article = new Article();
        $article->setAuthor($this->getUser());
        $article->setCreatedAt(new \DateTime());
        
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setTitleSlug($slugger->slug($article->getTitle())->lower());
            
            if ($article->isPublished()) {
                $article->setPublishedAt(new \DateTime());
            }
            
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article créé avec succès');
            return $this->redirectToRoute('redac_dashboard');
        }

        return $this->render('redac/article/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/article/{id}/edit', name: 'redac_article_edit')]
    public function edit(
        Article $article,
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_REDAC');
        
        // Vérifier que l'article appartient au rédacteur
        if ($article->getAuthor() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier cet article.');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setTitleSlug($slugger->slug($article->getTitle())->lower());
            
            if ($article->isPublished() && !$article->getPublishedAt()) {
                $article->setPublishedAt(new \DateTime());
            }
            
            $entityManager->flush();

            $this->addFlash('success', 'Article modifié avec succès');
            return $this->redirectToRoute('redac_dashboard');
        }

        return $this->render('redac/article/edit.html.twig', [
            'form' => $form,
            'article' => $article
        ]);
    }

    #[Route('/article/{id}/delete', name: 'redac_article_delete', methods: ['POST'])]
    public function delete(
        Article $article,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_REDAC');
        
        if ($article->getAuthor() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer cet article.');
        }

        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
            $this->addFlash('success', 'Article supprimé avec succès');
        }

        return $this->redirectToRoute('redac_dashboard');
    }
} 