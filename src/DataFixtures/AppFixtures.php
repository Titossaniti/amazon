<?php

namespace App\DataFixtures;

use App\Entity\Commercant;
use App\Entity\Category;
use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $categories = [];
        $categoryNames = ['Électronique', 'Vêtements', 'Alimentation', 'Maison', 'Sports', 'Livres'];

        // Création des catégories
        foreach ($categoryNames as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $categories[] = $category;
        }

        $commercantNames = ['Anthill', 'Bvendor', 'Commette', 'Darty', 'Elek', 'Franck And Co', 'Gwlade', 'HM', 'Interest', 'Joy Seller'];
        // Création des commerçants
        foreach ($commercantNames as $commercantName) {
            $commercant = new Commercant();
            $commercant->setName($commercantName);
            $commercant->setPassword($this->passwordHasher->hashPassword($commercant, 'password123'));
            $manager->persist($commercant);

            // Création des articles pour chaque commerçant
            for ($i = 0; $i < 10; $i++) {
                $article = new Article();
                $article->setName('Article ' . $i . ' de ' . $commercantName);
                $article->setDescription('Description de l\'article ' . $i . ' vendu par ' . $commercantName);
                $article->setPrice(rand(10, 100));
                $article->setStock(rand(1, 50));
                $article->setCategory($categories[array_rand($categories)]);
                $article->setCommercant($commercant);
                $manager->persist($article);
            }
        }

        $manager->flush();
    }
}
