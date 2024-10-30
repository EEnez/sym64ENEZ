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
        $faker = Factory::create();

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

        // Create 6 sections
        $sections = [];
        for ($i = 0; $i < 6; $i++) {
            $section = new Section();
            $title = $faker->unique()->word();
            $section->setSectionTitle($title)
                   ->setSectionSlug($this->slugger->slug($title)->lower())
                   ->setSectionDetail($faker->sentence());
            
            $manager->persist($section);
            $sections[] = $section;
        }

        // Create 2-40 articles for each section
        foreach ($sections as $section) {
            $numArticles = rand(2, 40);
            for ($i = 0; $i < $numArticles; $i++) {
                $article = new Article();
                $title = $faker->sentence();
                
                $randomUser = $users[array_rand($users)];
                if (!$randomUser instanceof User) {
                    throw new \RuntimeException('Invalid user selected');
                }

                $article->setTitle($title)
                       ->setTitleSlug($this->slugger->slug($title)->lower())
                       ->setText($faker->paragraphs(rand(3, 6), true))
                       ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                       ->setAuthor($randomUser)
                       ->addSection($section);
                
                if ($faker->boolean(75)) {
                    $article->setPublishedAt(
                        $faker->dateTimeBetween($article->getCreatedAt())
                    );
                }

                $manager->persist($article);
            }
        }

        $manager->flush();
    }
}
