<?php

namespace App\Controller\Public\Topic;

use App\Entity\Post;
use App\Form\TopicCreateType;
use App\Repository\ForumRepository;
use App\Repository\PostRepository;
use App\Service\EmailService;
use App\Service\PostService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TopicReplyController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/topic-reply/{id}', name: 'app_topic_reply')]
    public function index(
        Request        $request,
        PostService    $postService,
        PostRepository $postRepository,
        EmailService   $emailService,
        int            $id
    ): Response
    {
        $topic = $postRepository->findOneBy(['id' => $id]);
        if (null === $topic) {
            $this->addFlash('danger', 'Cannot find topic');
            return $this->redirectToRoute('app_home');
        }
        $forum = $topic->getForum();
        $form = $this->createForm(TopicCreateType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reply = $postService->createReplyFromForm($id, $form, $forum);
            if ($topic->getCreator()->getEmail() !== $this->getUser()->getUserIdentifier()) {
                $emailService->notifyAboutReply($reply);
            }
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
