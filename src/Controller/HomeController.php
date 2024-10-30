<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'articles' => $articleRepository->findLatestPublished(10)
        ]);
    }

    #[Route('/section/{slug}', name: 'app_section')]
    public function section(
        string $slug,
        ArticleRepository $articleRepository,
        SectionRepository $sectionRepository
    ): Response {
        $section = $sectionRepository->findOneBy(['sectionSlug' => $slug]);

        if (!$section) {
            throw $this->createNotFoundException('Section not found');
        }

        return $this->render('home/section.html.twig', [
            'section' => $section,
            'articles' => $articleRepository->findBySection($section),
        ]);
    }

    #[Route('/article/{slug}', name: 'app_article')]
    public function article(
        string $slug,
        ArticleRepository $articleRepository
    ): Response {
        $article = $articleRepository->findOneBy(['titleSlug' => $slug]);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        return $this->render('home/article.html.twig', [
            'article' => $article,
        ]);
    }
} 