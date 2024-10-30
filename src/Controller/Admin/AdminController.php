<?php

namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function index(ArticleRepository $articleRepository, SectionRepository $sectionRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        return $this->render('admin/index.html.twig', [
            'articles' => $articleRepository->findBy([], ['createdAt' => 'DESC']),
            'sections' => $sectionRepository->findAll()
        ]);
    }
} 