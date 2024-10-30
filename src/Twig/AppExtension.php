<?php

namespace App\Twig;

use App\Repository\SectionRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $sectionRepository;

    public function __construct(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }

    public function getGlobals(): array
    {
        return [
            'sections' => $this->sectionRepository->findAll(),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('excerpt', [$this, 'createExcerpt']),
        ];
    }

    public function createExcerpt(string $text, int $length = 200): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        
        $excerpt = mb_substr($text, 0, $length);
        $lastSpace = mb_strrpos($excerpt, ' ');
        
        if ($lastSpace !== false) {
            $excerpt = mb_substr($excerpt, 0, $lastSpace);
        }
        
        return $excerpt . '...';
    }
} 