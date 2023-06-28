<?php

namespace App\Controller\Public;

use App\Repository\ForumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumViewController extends AbstractController
{
    #[Route('/forum/{id}', name: 'app_forum_view')]
    public function index(ForumRepository $forumRepository, int $id): Response
    {
        $forums = $forumRepository->findBy(['id' => $id]);
        return $this->render('public/index.html.twig', [
            'forums' => $forums,
        ]);
    }
}
