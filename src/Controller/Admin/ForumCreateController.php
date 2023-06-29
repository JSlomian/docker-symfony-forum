<?php

namespace App\Controller\Admin;

use App\Entity\Forum;
use App\Form\ForumCreateType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumCreateController extends AbstractController
{
    #[Route('/admin/forum/create', name: 'app_admin_forum_create')]
    public function index(
        Request                $request,
        EntityManagerInterface $em
    ): Response
    {
        $forum = new Forum();
        $form = $this->createForm(ForumCreateType::class, $forum);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $forum = $form->getData();
            $em->persist($forum);
            $em->flush();
            $this->addFlash('success', 'Forum added');
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Couldn\'t add new forum');
        }
        return $this->render('admin/forum_create.html.twig', [
            'form' => $form,
        ]);
    }
}
