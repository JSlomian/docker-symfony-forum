<?php

namespace App\Entity;

use App\Repository\ForumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ForumRepository::class)]
class Forum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Description = null;

    #[ORM\Column]
    private ?bool $isForum = null;

    #[ORM\Column(nullable: true)]
    private ?int $listOrder = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'forums')]
    private ?self $subforumTo = null;

    #[ORM\OneToMany(mappedBy: 'subforumTo', targetEntity: self::class)]
    private Collection $forums;

    #[ORM\OneToMany(mappedBy: 'forum', targetEntity: Post::class)]
    private Collection $posts;

    public function __construct()
    {
        $this->forums = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function isForum(): ?bool
    {
        return $this->isForum;
    }

    public function setIsForum(bool $isForum): static
    {
        $this->isForum = $isForum;

        return $this;
    }

    public function getListOrder(): ?int
    {
        return $this->listOrder;
    }

    public function setListOrder(?int $listOrder): static
    {
        $this->listOrder = $listOrder;

        return $this;
    }

    public function getSubforumTo(): ?self
    {
        return $this->subforumTo;
    }

    public function setSubforumTo(?self $subforumTo): static
    {
        $this->subforumTo = $subforumTo;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getForums(): Collection
    {
        return $this->forums;
    }

    public function addForum(self $forum): static
    {
        if (!$this->forums->contains($forum)) {
            $this->forums->add($forum);
            $forum->setSubforumTo($this);
        }

        return $this;
    }

    public function removeForum(self $forum): static
    {
        if ($this->forums->removeElement($forum)) {
            // set the owning side to null (unless already changed)
            if ($forum->getSubforumTo() === $this) {
                $forum->setSubforumTo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setForum($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getForum() === $this) {
                $post->setForum(null);
            }
        }

        return $this;
    }
}
