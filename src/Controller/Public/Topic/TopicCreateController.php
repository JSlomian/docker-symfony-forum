<?php

namespace App\Controller\Public\Topic;

use App\Entity\Post;
use App\Form\TopicCreateType;
use App\Repository\ForumRepository;
use App\Service\EmailService;
use App\Service\PostService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TopicCreateController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('forum/{id}/topic-create', name: 'app_topic_create')]
    public function index(
        Request         $request,
        ForumRepository $forumRepository,
        PostService     $postService,
        EmailService    $emailService,
        int             $id
    ): Response
    {
        $forum = $forumRepository->findOneBy(['id' => $id]);
        if (null === $forum) {
            $this->addFlash('danger', 'Cannot find forum');
            return $this->redirectToRoute('app_home');
        }
        if (false === $forum->isForum()) {
            $this->addFlash('danger', 'Cannot post in category');
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(TopicCreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $topic = $postService->createTopicFromForm($form, $forum);
            $emailService->notifyThankForCreating($topic);
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
