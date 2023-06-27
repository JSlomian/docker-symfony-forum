<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Content = null;

    #[ORM\Column]
    private ?bool $IsTopic = null;

    #[ORM\Column(nullable: true)]
    private ?int $ReplyTo = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Creator = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(?string $Title): static
    {
        $this->Title = $Title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(string $Content): static
    {
        $this->Content = $Content;

        return $this;
    }

    public function isIsTopic(): ?bool
    {
        return $this->IsTopic;
    }

    public function setIsTopic(bool $IsTopic): static
    {
        $this->IsTopic = $IsTopic;

        return $this;
    }

    public function getReplyTo(): ?int
    {
        return $this->ReplyTo;
    }

    public function setReplyTo(?int $ReplyTo): static
    {
        $this->ReplyTo = $ReplyTo;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->Creator;
    }

    public function setCreator(?User $Creator): static
    {
        $this->Creator = $Creator;

        return $this;
    }
}
