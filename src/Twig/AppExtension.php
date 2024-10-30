<?php

namespace App\Twig;

use App\Repository\SectionRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $sectionRepository;

    public function __construct(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('excerpt', [$this, 'createExcerpt']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_all_sections', [$this, 'getAllSections']),
        ];
    }

    public function createExcerpt(string $text, int $length = 200): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        $excerpt = mb_substr($text, 0, $length);
        $lastSpace = mb_strrpos($excerpt, ' ');
        
        return mb_substr($excerpt, 0, $lastSpace) . '...';
    }

    public function getAllSections(): array
    {
        return $this->sectionRepository->findAll();
    }
} 