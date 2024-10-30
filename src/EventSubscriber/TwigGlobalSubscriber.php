<?php

namespace App\EventSubscriber;

use App\Repository\SectionRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class TwigGlobalSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $sectionRepository;

    public function __construct(Environment $twig, SectionRepository $sectionRepository)
    {
        $this->twig = $twig;
        $this->sectionRepository = $sectionRepository;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        // Ajouter les sections comme variable globale Twig
        $this->twig->addGlobal('sections', $this->sectionRepository->findAll());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
} 