<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/comment')]
class CommentController extends AbstractController
{
    #[Route('/', name: 'admin_comment_index')]
    public function index(
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $query = $entityManager->getRepository(Comment::class)
            ->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery();

        $comments = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_comment_edit')]
    public function edit(
        Comment $comment,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            $this->addFlash('success', 'Commentaire modifié avec succès');
            return $this->redirectToRoute('app_article', [
                'slug' => $comment->getArticle()->getTitleSlug()
            ]);
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_comment_delete', methods: ['POST'])]
    public function delete(
        Comment $comment,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Commentaire supprimé avec succès');
        }

        return $this->redirectToRoute('admin_comment_index');
    }
} 