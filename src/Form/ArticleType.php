<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Section;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ArticleType extends AbstractType
{
    private $authChecker;

    public function __construct(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => ['rows' => 15]
            ])
            ->add('sections', EntityType::class, [
                'class' => Section::class,
                'choice_label' => 'sectionTitle',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Sections'
            ]);

        // Ajouter le champ publishedAt uniquement pour les admins
        if ($this->authChecker->isGranted('ROLE_ADMIN')) {
            $builder->add('publishedAt', DateTimeType::class, [
                'label' => 'Date de publication',
                'required' => false,
                'widget' => 'single_text'
            ]);
        } else {
            $builder->add('published', CheckboxType::class, [
                'label' => 'Publier l\'article',
                'required' => false
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
} 