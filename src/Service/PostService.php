<?php

namespace App\Service;

use App\Entity\Forum;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;

readonly class PostService
{
    public function __construct(
        private Security               $security,
        private EntityManagerInterface $em
    )
    {
    }

    public function createTopicFromForm(FormInterface $formAnswers, Forum $forum): Post
    {
        $topic = $formAnswers->getData();
        $topic->setIsTopic(true)
            ->setCreator($this->security->getUser())
            ->setReplyTo(null)
            ->setForum($forum);
        $this->em->persist($topic);
        $this->em->flush();
        return $topic;
    }

    public function createReplyFromForm(int $id, FormInterface $formAnswers, Forum $forum): Post
    {
        $reply = $formAnswers->getData();
        $reply->setIsTopic(false)
            ->setCreator($this->security->getUser())
            ->setReplyTo($id)
            ->setForum($forum);
        $this->em->persist($reply);
        $this->em->flush();
        return $reply;
    }
}
