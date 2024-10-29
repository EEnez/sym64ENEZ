<?php

namespace App\Entity;

use App\Repository\SectionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SectionRepository::class)]
class Section
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $sectionTitle = null;

    #[ORM\Column(length: 105, unique: true)]
    private ?string $sectionSlug = null;

    #[ORM\Column(length: 500, nullable: true)]
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