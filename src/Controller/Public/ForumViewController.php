<?php

namespace App\Controller\Public;

use App\Repository\ForumRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumViewController extends AbstractController
{
    #[Route('/forum/{id}', name: 'app_forum_view')]
    public function index(
        ForumRepository $forumRepository,
        PostRepository $postRepository,
        int $id
    ): Response
    {
        $forum = $forumRepository->findOneBy(['id' => $id]);
        if (null === $forum) {
            $this->addFlash('danger', 'Forum doesn\'t exist');
            return $this->redirectToRoute('app_home');
        }
        $topics = $postRepository->findBy(['forum' => $forum, 'isTopic' => true], ['id' => 'DESC']);
        return $this->render('public/forum_view.html.twig', [
            'forum' => $forum,
            'topics' => $topics
        ]);
    }
}
