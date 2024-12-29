<?php

namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function index(
        ArticleRepository $articleRepository,
        UserRepository $userRepository,
        CommentRepository $commentRepository,
        SectionRepository $sectionRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        return $this->render('admin/index.html.twig', [
            'stats' => [
                'articles' => $articleRepository->count([]),
                'users' => $userRepository->count([]),
                'comments' => $commentRepository->count([]),
                'sections' => $sectionRepository->count([])
            ],
            'recent_articles' => $articleRepository->findBy([], ['createdAt' => 'DESC'], 5),
            'sections' => $sectionRepository->findAll()
        ]);
    }
} 