<?php

namespace App\Controller\Public\Topic;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TopicViewController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/topic/{id}', name: 'app_topic_view')]
    public function index(
        PostRepository $postRepository,
        int $id
    ): Response
    {
        $topic = $postRepository->findOneBy(['id' => $id]);
        $replies = $postRepository->findBy(['replyTo' => $id], ['id' => 'DESC']);
        if (null === $topic) {
            $this->addFlash('danger', 'Couldn\'t find the topic');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('public/topic/topic_view.html.twig', [
            'topic' => $topic,
            'replies' => $replies
        ]);
    }
}
