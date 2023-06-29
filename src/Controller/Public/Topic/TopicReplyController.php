<?php

namespace App\Controller\Public\Topic;

use App\Entity\Post;
use App\Form\TopicCreateType;
use App\Repository\ForumRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TopicReplyController extends AbstractController
{
    #[Route('/topic-reply/{id}', name: 'app_topic_reply')]
    public function index(
        Request                $request,
        EntityManagerInterface $em,
        ForumRepository        $forumRepository,
        PostRepository $postRepository,
        int                    $id
    ): Response
    {
        $topic = $postRepository->findOneBy(['id' => $id]);
        if (null === $topic) {
            $this->addFlash('danger', 'Cannot find topic');
            return $this->redirectToRoute('app_home');
        }
        $forum = $topic->getForum();
        $reply = new Post();
        $form = $this->createForm(TopicCreateType::class, $reply);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reply->setIsTopic(false)
                ->setCreator($this->getUser())
                ->setReplyTo($id)
                ->setForum($forum);
            $reply = $form->getData();
            $em->persist($reply);
            $em->flush();
            $this->addFlash('success', 'You replied.');
            return $this->redirectToRoute('app_topic_view', ['id' => $id]);
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Couldn\'t add new topic');
        }
        return $this->render('public/topic/topic_reply.html.twig', [
            'form' => $form,
        ]);
    }
}
