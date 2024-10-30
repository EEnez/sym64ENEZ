<?php

namespace App\Entity;

use App\Repository\SectionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SectionRepository::class)]
class Section
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $sectionTitle = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $sectionSlug = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $sectionDetail = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSectionTitle(): ?string
    {
        return $this->sectionTitle;
    }

    public function setSectionTitle(string $sectionTitle): static
    {
        $this->sectionTitle = $sectionTitle;
        return $this;
    }

    public function getSectionSlug(): ?string
    {
        return $this->sectionSlug;
    }

    public function setSectionSlug(string $sectionSlug): static
    {
        $this->sectionSlug = $sectionSlug;
        return $this;
    }

    public function getSectionDetail(): ?string
    {
        return $this->sectionDetail;
    }

    public function setSectionDetail(?string $sectionDetail): static
    {
        $this->sectionDetail = $sectionDetail;
        return $this;
    }
} 