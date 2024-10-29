<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Article;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    private SluggerInterface $slugger;

    public function __construct(UserPasswordHasherInterface $hasher, SluggerInterface $slugger)
    {
        $this->hasher = $hasher;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = [];

        // Admin user
        $admin = new User();
        $admin->setUsername('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($admin, 'admin'))
            ->setFullname($faker->name())
            ->setEmail($faker->email())
            ->setUniqid(uniqid())
            ->setIsActive(true);
        $manager->persist($admin);
        $users[] = $admin;

        // Redac users
        for ($i = 1; $i <= 5; $i++) {
            $redac = new User();
            $redac->setUsername('redac' . $i)
                ->setRoles(['ROLE_REDAC'])
                ->setPassword($this->hasher->hashPassword($redac, 'redac' . $i))
                ->setFullname($faker->name())
                ->setEmail($faker->email())
                ->setUniqid(uniqid())
                ->setIsActive(true);
            $manager->persist($redac);
            $users[] = $redac;
        }

        // Regular users
        for ($i = 1; $i <= 24; $i++) {
            $user = new User();
            $fullname = $faker->name();
            $user->setUsername($this->slugger->slug(strtolower('user' . $i)))
                ->setRoles(['ROLE_USER'])
                ->setPassword($this->hasher->hashPassword($user, 'user' . $i))
                ->setFullname($fullname)
                ->setEmail($faker->email())
                ->setUniqid(uniqid())
                // Set 3 out of 4 users as active
                ->setIsActive($i % 4 !== 0);
            $manager->persist($user);
            $users[] = $user;
        }

        $admins = [$admin];
        $redacs = array_slice($users, 1, 5);
        $allAuthors = array_merge($admins, $redacs);

        for ($i = 0; $i < 160; $i++) {
            $article = new Article();
            $title = $faker->sentence(6);
            
            $article->setTitle($title)
                ->setTitleSlug($this->slugger->slug($title))
                ->setText($faker->paragraphs(5, true))
                ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                ->setAuthor($faker->randomElement($allAuthors));

            // 75% de chance d'être publié
            if ($faker->boolean(75)) {
                $article->setPublishedAt(
                    $faker->dateTimeBetween($article->getCreatedAt())
                );
            }

            // Randomly assign a user to each article
            $article->setUser($users[array_rand($users)]);

            $manager->persist($article);
        }

        $manager->flush();
    }
}
