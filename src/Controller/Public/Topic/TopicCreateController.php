<?php

namespace App\Controller\Public\Topic;

use App\Entity\Post;
use App\Form\TopicCreateType;
use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TopicCreateController extends AbstractController
{
    #[Route('forum/{id}/topic-create', name: 'app_topic_create')]
    public function index(
        Request                $request,
        EntityManagerInterface $em,
        ForumRepository        $forumRepository,
        int                    $id
    ): Response
    {
        $forum = $forumRepository->findOneBy(['id' => $id, 'isForum' => true]);
        if (null === $forum) {
            $this->addFlash('danger', 'Cannot post in category');
            return $this->redirectToRoute('app_home');
        }
        $topic = new Post();
        $form = $this->createForm(TopicCreateType::class, $topic);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $topic->setIsTopic(true)
                ->setCreator($this->getUser())
                ->setReplyTo(null)
                ->setForum($forum);
            $topic = $form->getData();
            $em->persist($topic);
            $em->flush();
            $this->addFlash('success', 'Topic added');
            return $this->redirectToRoute('app_topic_view', ['id' => $topic->getId()]);
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Couldn\'t add new topic');
        }
        return $this->render('public/topic/topic_create.html.twig', [
            'form' => $form,
        ]);
    }
}
