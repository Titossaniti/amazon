<?php

namespace App\DataFixtures;

use App\Entity\User;
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

    public function load(ObjectManager $manager): void
    {
        // Define categories
        $categories = ['Électronique', 'Vêtements', 'Alimentation', 'Maison', 'Sports', 'Livres'];
        $categoryEntities = [];

        foreach ($categories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $categoryEntities[$categoryName] = $category;
        }

        // Define users
        $usernames = ['user1', 'user2', 'user3', 'user4', 'user5'];
        $commercants = ['Anthill', 'Bvendor', 'Com&Co', 'Darty', 'Durant', 'KMP', 'Light', 'GW et MD', 'Vendeur2Fou', 'Zseller'];

        $allUsers = array_merge($usernames, $commercants);
        $userEntities = [];

        foreach ($allUsers as $username) {
            $user = new User();
            $user->setUsername($username);
            $user->setEmail($username . '@example.com');
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $manager->persist($user);
            $userEntities[$username] = $user;
        }

        // Define articles for commercants
        $articles = [
            ['category' => 'Électronique', 'name' => 'Smartphone', 'description' => 'Un smartphone moderne avec de nombreuses fonctionnalités', 'price' => 699.99, 'stock' => 50],
            ['category' => 'Vêtements', 'name' => 'T-shirt', 'description' => 'Un t-shirt confortable en coton', 'price' => 19.99, 'stock' => 200],
            ['category' => 'Alimentation', 'name' => 'Pâtes', 'description' => 'Pâtes de blé dur', 'price' => 2.99, 'stock' => 500],
            ['category' => 'Maison', 'name' => 'Table', 'description' => 'Table en bois massif', 'price' => 150.00, 'stock' => 30],
            ['category' => 'Sports', 'name' => 'Ballon de football', 'description' => 'Ballon de football taille standard', 'price' => 24.99, 'stock' => 100],
            ['category' => 'Livres', 'name' => 'Roman', 'description' => 'Un roman passionnant à lire', 'price' => 12.99, 'stock' => 75]
        ];

        foreach ($commercants as $commercant) {
            foreach ($articles as $articleData) {
                $article = new Article();
                $article->setCategory($categoryEntities[$articleData['category']]);
                $article->setName($articleData['name']);
                $article->setDescription($articleData['description']);
                $article->setPrice($articleData['price']);
                $article->setStock($articleData['stock']);
                $article->setUser($userEntities[$commercant]);
                $manager->persist($article);
            }
        }

        $manager->flush();
    }
}
