<?php

namespace App\Controller;

use App\Entity\Section;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SectionController extends AbstractController
{
    #[Route('/section/{slug}', name: 'app_section')]
    public function show(string $slug, EntityManagerInterface $entityManager): Response
    {
        $section = $entityManager->getRepository(Section::class)->findOneBy(['sectionSlug' => $slug]);

        if (!$section) {
            throw $this->createNotFoundException('Section not found');
        }

        $articles = $section->getArticles()->filter(fn($article) => $article->isPublished());

        return $this->render('section/show.html.twig', [
            'section' => $section,
            'articles' => $articles
        ]);
    }
} 