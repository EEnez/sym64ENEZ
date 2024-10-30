<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Section;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private SluggerInterface $slugger
    ) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $admin = new User();
        $admin->setUsername('admin')
              ->setRoles(['ROLE_ADMIN'])
              ->setUniqid($faker->unique()->uuid())
              ->setEmail('admin@example.com')
              ->setPassword($this->passwordHasher->hashPassword($admin, 'admin'))
              ->setFullname('Administrator')
              ->setIsActive(true);
        $manager->persist($admin);

        $users = [$admin];

        for ($i = 1; $i <= 5; $i++) {
            $redac = new User();
            $redac->setUsername("redac{$i}")
                 ->setRoles(['ROLE_REDAC'])
                 ->setUniqid($faker->unique()->uuid())
                 ->setEmail("redac{$i}@example.com")
                 ->setPassword($this->passwordHasher->hashPassword($redac, "redac{$i}"))
                 ->setFullname($faker->name())
                 ->setIsActive(true);
            $manager->persist($redac);
            $users[] = $redac;
        }

        for ($i = 1; $i <= 24; $i++) {
            $user = new User();
            $user->setUsername("user{$i}")
                 ->setRoles(['ROLE_USER'])
                 ->setUniqid($faker->unique()->uuid())
                 ->setEmail("user{$i}@example.com")
                 ->setPassword($this->passwordHasher->hashPassword($user, "user{$i}"))
                 ->setFullname($faker->name())
                 ->setIsActive($faker->boolean(75));
            $manager->persist($user);
        }

     
        $sectionsData = [
            'Actualités' => 'L\'essentiel de l\'actualité nationale et internationale',
            'Technologie' => 'Découvrez les dernières innovations et tendances technologiques',
            'Culture' => 'Art, littérature, cinéma et spectacles vivants',
            'Sciences' => 'Explorez les avancées scientifiques et les découvertes',
            'Économie' => 'Analyses et décryptages de l\'actualité économique',
            'Sport' => 'Toute l\'actualité sportive et les résultats en direct'
        ];

        $sections = [];
        foreach ($sectionsData as $title => $detail) {
            $section = new Section();
            $section->setSectionTitle($title)
                   ->setSectionSlug($this->slugger->slug($title)->lower())
                   ->setSectionDetail($detail);
            $manager->persist($section);
            $sections[] = $section;
        }

        $articles = [];
        $totalArticles = 0;

        
        foreach ($sections as $section) {
       
            for ($i = 0; $i < 2; $i++) {
                $article = new Article();
                $title = $faker->realText(60);
                
                $article->setTitle($title)
                       ->setTitleSlug($this->slugger->slug($title)->lower())
                       ->setText($faker->realText(1500))
                       ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                       ->setAuthor($users[array_rand($users)])
                       ->addSection($section);
                
                if ($faker->boolean(75)) {
                    $publishedAt = $faker->dateTimeBetween($article->getCreatedAt());
                    $article->setPublishedAt($publishedAt)
                           ->setPublished(true);
                }

                $manager->persist($article);
                $articles[] = $article;
                $totalArticles++;
            }
        }

    
        while ($totalArticles < 160) {
            $section = $faker->randomElement($sections);
            
          
            $sectionArticleCount = 0;
            foreach ($articles as $existingArticle) {
                if ($existingArticle->getSections()->contains($section)) {
                    $sectionArticleCount++;
                }
            }
            
           
            if ($sectionArticleCount >= 40) {
                continue;
            }

            $article = new Article();
            $title = $faker->realText(60);
            
            $article->setTitle($title)
                   ->setTitleSlug($this->slugger->slug($title)->lower())
                   ->setText($faker->realText(1500))
                   ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                   ->setAuthor($users[array_rand($users)])
                   ->addSection($section);
            
            if ($faker->boolean(75)) {
                $publishedAt = $faker->dateTimeBetween($article->getCreatedAt());
                $article->setPublishedAt($publishedAt)
                       ->setPublished(true);
            }

            $manager->persist($article);
            $articles[] = $article;
            $totalArticles++;
        }

        $manager->flush();
    }
}
