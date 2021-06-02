<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comments_index")
     * 
     * @param AdRepository $repo
     * @return void
     */
    public function index(CommentRepository $repo, $page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Comment::class)
                    ->setLimit(5)
                    ->setPage($page);

        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet à un administrateur de modifier un commentaire
     * 
     * @Route("/admin/comment/{id}/edit", name="admin_comment_edit")
     *
     * @return void
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', "Le commentaire n° {$comment->getId()} a bien été modifié");
        }

        return $this->render('admin/comment/edit.html.twig', [
            'form' => $form->createView(),
            'comment' => $comment
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     *
     * @Route("/admin/comment/{id}/delete", name="admin_comment_delete")
     * 
     * @param Comment $comment
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function delete(Comment $comment, EntityManagerInterface $manager)
    {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash('success', "Le commentaire de <strong>{$comment->getAuthor()->getFullName()}</strong> a bien été supprimé !");
        
        return $this->redirectToRoute('admin_comments_index');
    }
}
