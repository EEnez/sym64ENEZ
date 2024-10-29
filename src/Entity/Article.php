<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 160)]
    private ?string $title = null;

    #[ORM\Column(length: 162, unique: true)]
    private ?string $titleSlug = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $articleDateCreate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $articleDatePosted = null;

    #[ORM\Column(type: 'boolean')]
    private bool $published = false;

    public function __construct()
    {
        $this->articleDateCreate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getTitleSlug(): ?string
    {
        return $this->titleSlug;
    }

    public function setTitleSlug(string $titleSlug): static
    {
        $this->titleSlug = $titleSlug;
        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;
        return $this;
    }

    public function getArticleDateCreate(): ?\DateTimeInterface
    {
        return $this->articleDateCreate;
    }

    public function setArticleDateCreate(\DateTimeInterface $articleDateCreate): static
    {
        $this->articleDateCreate = $articleDateCreate;
        return $this;
    }

    public function getArticleDatePosted(): ?\DateTimeInterface
    {
        return $this->articleDatePosted;
    }

    public function setArticleDatePosted(?\DateTimeInterface $articleDatePosted): static
    {
        $this->articleDatePosted = $articleDatePosted;
        return $this;
    }

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): static
    {
        $this->published = $published;
        return $this;
    }
}
