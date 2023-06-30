<?php

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

readonly class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
        private PostRepository  $postRepository,
        private LoggerInterface $logger
    )
    {
    }

    private function sendTemplatedEmail(TemplatedEmail $email): void
    {
        try {
            $this->mailer->send($email);
        } catch (\Exception|TransportExceptionInterface $e) {
            $this->logger->log(LogLevel::ERROR, "Email failed {$e}");
        }
    }

    public function getTemplatedEmail(string|Address $from, string|Address $to, string $subject, ?string $twigTemplate, array $context): TemplatedEmail
    {
        return (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($twigTemplate)
            ->context($context);
    }

    public function notifyAboutReply(Post $post): void
    {
        $topic = $this->postRepository->findOneBy(['id' => $post->getReplyTo()]);
        $email = $this->getTemplatedEmail(
            new Address('super@forum.com', 'It is a forum!'),
            new Address($topic->getCreator()->getEmail()),
            "New reply to {$topic->getTitle()}",
            'mail/mail_reply_notification.html.twig',
            ['topic' => $topic]
        );
        $this->sendTemplatedEmail($email);
    }

    public function notifyThankForCreating(Post $topic): void
    {
        $email = $this->getTemplatedEmail(
            new Address('super@forum.com', 'It is a forum!'),
            new Address($topic->getCreator()->getEmail()),
            "Thanks for posting {$topic->getTitle()}!",
            'mail/mail_topic_thanks_notification.html.twig',
            ['topic' => $topic]
        );
        $this->sendTemplatedEmail($email);
    }

}
