<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Création des catégories
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName($faker->words(2, true));
            $category->setDescription($faker->sentence());
            $manager->persist($category);
            $categories[] = $category;
        }

        // Création des utilisateurs
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setRoles(['ROLE_USER']);
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $users[] = $user;
        }

        // Création d'un admin
        $adminUser = new User();
        $adminUser->setEmail('admin@example.com');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminPassword = $this->passwordHasher->hashPassword($adminUser, 'admin123');
        $adminUser->setPassword($adminPassword);
        $manager->persist($adminUser);
        $users[] = $adminUser;

        // Création des produits
        $products = [];
        for ($i = 0; $i < 50; $i++) {
            $product = new Product();
            $product->setName($faker->words(3, true));
            $product->setPrice($faker->randomFloat(2, 10, 2000));
            $product->setDescription($faker->paragraph());
            $product->setStock($faker->numberBetween(0, 100));
            $product->setCategory($faker->randomElement($categories));
            $manager->persist($product);
            $products[] = $product;
        }

        // Création des commandes
        $statuses = ['Commande passée', 'Commande confirmée', 'Commande livrée'];
        for ($i = 0; $i < 20; $i++) {
            $order = new Order();
            $order->setUser($faker->randomElement($users));
            $order->setDate($faker->dateTimeBetween('-1 month', 'now'));
            $order->setStatus($faker->randomElement($statuses));

            // Ajout de 1 à 5 produits aléatoires
            $numProducts = $faker->numberBetween(1, 5);
            $orderProducts = $faker->randomElements($products, $numProducts);
            foreach ($orderProducts as $product) {
                $order->addProduct($product);
            }

            $manager->persist($order);
        }

        $manager->flush();
    }
}