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
    private ?string $Name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Description = null;

    #[ORM\Column]
    private ?bool $IsForum = null;

    #[ORM\Column(nullable: true)]
    private ?int $ListOrder = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'forums')]
    private ?self $SubforumTo = null;

    #[ORM\OneToMany(mappedBy: 'SubforumTo', targetEntity: self::class)]
    private Collection $forums;

    public function __construct()
    {
        $this->forums = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

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

    public function isIsForum(): ?bool
    {
        return $this->IsForum;
    }

    public function setIsForum(bool $IsForum): static
    {
        $this->IsForum = $IsForum;

        return $this;
    }

    public function getListOrder(): ?int
    {
        return $this->ListOrder;
    }

    public function setListOrder(?int $ListOrder): static
    {
        $this->ListOrder = $ListOrder;

        return $this;
    }

    public function getSubforumTo(): ?self
    {
        return $this->SubforumTo;
    }

    public function setSubforumTo(?self $SubforumTo): static
    {
        $this->SubforumTo = $SubforumTo;

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
}
