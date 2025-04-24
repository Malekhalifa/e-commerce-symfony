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
        $faker = Factory::create('fr_FR');

        // Création des catégories tech
        $categories = [];
        $categoryNames = [
            'Ordinateurs Portables' => 'Tous les ordinateurs portables et notebooks',
            'Smartphones' => 'Téléphones mobiles et accessoires',
            'Composants PC' => 'Processeurs, cartes mères, RAM et stockage',
            'Périphériques' => 'Claviers, souris, écrans et accessoires',
            'Audio' => 'Casques, enceintes et équipement audio',
            'Réseau' => 'Routeurs, switches et équipement réseau',
            'Gaming' => 'Équipement et accessoires pour gamers',
            'Caméras' => 'Caméras, webcams et équipement photo',
            'Tablettes' => 'Tablettes et accessoires',
            'Accessoires' => 'Câbles, chargeurs et petits accessoires'
        ];

        $categoryImages = [
            'Ordinateurs Portables' => 'https://picsum.photos/seed/laptop/800/600',
            'Smartphones' => 'https://picsum.photos/seed/smartphone/800/600',
            'Composants PC' => 'https://picsum.photos/seed/pcparts/800/600',
            'Périphériques' => 'https://picsum.photos/seed/peripherals/800/600',
            'Audio' => 'https://picsum.photos/seed/audio/800/600',
            'Réseau' => 'https://picsum.photos/seed/network/800/600',
            'Gaming' => 'https://picsum.photos/seed/gaming/800/600',
            'Caméras' => 'https://picsum.photos/seed/camera/800/600',
            'Tablettes' => 'https://picsum.photos/seed/tablet/800/600',
            'Accessoires' => 'https://picsum.photos/seed/accessories/800/600'
        ];

        foreach ($categoryNames as $name => $description) {
            $category = new Category();
            $category->setName($name);
            $category->setDescription($description);
            $category->setImage($categoryImages[$name]);
            $manager->persist($category);
            $categories[$name] = $category;
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

        // Création des produits tech
        $products = [];
        $productTypes = [
            'Ordinateurs Portables' => [
                'prefixes' => ['Pro', 'Elite', 'Ultra', 'Premium', 'Gaming'],
                'suffixes' => ['Series', 'Edition', 'Plus', 'Max', 'Pro'],
                'basePrice' => [1500, 5000],
                'keywords' => ['Laptop', 'Notebook', 'Ultrabook', 'Workstation', 'Gaming Laptop'],
                'imageSeed' => 'laptop',
                'descriptions' => [
                    'Ordinateur portable ultra-performant avec processeur dernière génération, écran haute résolution et autonomie exceptionnelle.',
                    'Notebook professionnel équipé d\'un écran tactile, d\'une grande capacité de stockage et d\'une connectivité complète.',
                    'Ultrabook léger et élégant, parfait pour les professionnels en déplacement avec une autonomie de plus de 10 heures.',
                    'Workstation mobile puissante pour les professionnels exigeants, avec carte graphique dédiée et écran haute résolution.',
                    'Laptop gaming avec écran 144Hz, carte graphique RTX et système de refroidissement avancé pour des performances optimales.'
                ]
            ],
            'Smartphones' => [
                'prefixes' => ['Smart', 'Pro', 'Ultra', 'Elite', 'Premium'],
                'suffixes' => ['Plus', 'Max', 'Pro', 'Elite', 'Ultra'],
                'basePrice' => [800, 2000],
                'keywords' => ['Smartphone', 'Mobile', 'Téléphone', 'Appareil', 'Device'],
                'imageSeed' => 'smartphone',
                'descriptions' => [
                    'Smartphone haut de gamme avec appareil photo professionnel, écran AMOLED et charge rapide.',
                    'Téléphone mobile avec processeur puissant, grande autonomie et système de sécurité avancé.',
                    'Appareil mobile avec écran incurvé, double caméra et reconnaissance faciale.',
                    'Smartphone avec écran 120Hz, charge sans fil et résistant à l\'eau.',
                    'Téléphone avec stockage extensible, batterie longue durée et système d\'exploitation optimisé.'
                ]
            ],
            'Composants PC' => [
                'prefixes' => ['Pro', 'Gaming', 'Elite', 'Ultra', 'Premium'],
                'suffixes' => ['Series', 'Edition', 'Plus', 'Max', 'Pro'],
                'basePrice' => [200, 1500],
                'keywords' => ['CPU', 'GPU', 'RAM', 'SSD', 'Carte Mère'],
                'imageSeed' => 'pcparts',
                'descriptions' => [
                    'Processeur haute performance avec overclocking automatique et refroidissement intégré.',
                    'Carte graphique gaming avec ray tracing, refroidissement avancé et RGB personnalisable.',
                    'Kit RAM haute fréquence avec profil XMP et dissipateur thermique.',
                    'SSD NVMe ultra-rapide avec technologie de cache et endurance élevée.',
                    'Carte mère gaming avec WiFi 6, Bluetooth 5.2 et connectivité USB 3.2.'
                ]
            ],
            'Périphériques' => [
                'prefixes' => ['Pro', 'Gaming', 'Elite', 'Ultra', 'Premium'],
                'suffixes' => ['Series', 'Edition', 'Plus', 'Max', 'Pro'],
                'basePrice' => [50, 500],
                'keywords' => ['Clavier', 'Souris', 'Écran', 'Webcam', 'Imprimante'],
                'imageSeed' => 'peripherals',
                'descriptions' => [
                    'Clavier mécanique avec switches personnalisables, rétroéclairage RGB et repose-poignets ergonomique.',
                    'Souris gaming avec capteur haute précision, boutons programmables et design ergonomique.',
                    'Écran gaming 27" avec taux de rafraîchissement 165Hz, temps de réponse 1ms et HDR.',
                    'Webcam 4K avec micro intégré, autofocus et faible luminosité.',
                    'Imprimante laser multifonction avec impression recto-verso automatique et connectivité WiFi.'
                ]
            ],
            'Audio' => [
                'prefixes' => ['Pro', 'Elite', 'Ultra', 'Premium', 'Gaming'],
                'suffixes' => ['Series', 'Edition', 'Plus', 'Max', 'Pro'],
                'basePrice' => [100, 800],
                'keywords' => ['Casque', 'Enceinte', 'Microphone', 'Amplificateur', 'Mixer'],
                'imageSeed' => 'audio',
                'descriptions' => [
                    'Casque sans fil avec réduction de bruit active, son surround et confort optimal.',
                    'Enceinte Bluetooth avec basses puissants, résistance à l\'eau et autonomie de 20 heures.',
                    'Microphone professionnel avec réduction de bruit, monture anti-vibration et filtre pop.',
                    'Amplificateur DAC avec entrées multiples, égaliseur numérique et sortie casque haute impédance.',
                    'Table de mixage compacte avec effets intégrés, entrées XLR et sortie USB.'
                ]
            ],
            'Réseau' => [
                'prefixes' => ['Pro', 'Elite', 'Ultra', 'Premium', 'Gaming'],
                'suffixes' => ['Series', 'Edition', 'Plus', 'Max', 'Pro'],
                'basePrice' => [100, 1000],
                'keywords' => ['Routeur', 'Switch', 'Modem', 'Point d\'accès', 'Carte réseau'],
                'imageSeed' => 'network',
                'descriptions' => [
                    'Routeur WiFi 6 avec portail invité, contrôle parental et QoS avancé.',
                    'Switch géré avec PoE, VLAN et agrégation de liens.',
                    'Modem-routeur avec fibre optique, téléphonie IP et sécurité intégrée.',
                    'Point d\'accès WiFi avec mesh, roaming et gestion centralisée.',
                    'Carte réseau 2.5G avec Wake-on-LAN et gestion de l\'énergie.'
                ]
            ],
            'Gaming' => [
                'prefixes' => ['Pro', 'Elite', 'Ultra', 'Premium', 'Gaming'],
                'suffixes' => ['Series', 'Edition', 'Plus', 'Max', 'Pro'],
                'basePrice' => [100, 2000],
                'keywords' => ['Manette', 'Clavier Gaming', 'Souris Gaming', 'Casque Gaming', 'Écran Gaming'],
                'imageSeed' => 'gaming',
                'descriptions' => [
                    'Manette sans fil avec retour haptique, paddles arrière et personnalisation avancée.',
                    'Clavier gaming mécanique avec switches optiques, macro et rétroéclairage personnalisable.',
                    'Souris gaming avec capteur 20K DPI, poids ajustable et design ergonomique.',
                    'Casque gaming avec son surround 7.1, micro détachable et coussinets mémoire de forme.',
                    'Écran gaming 32" avec 240Hz, G-Sync et HDR 1000.'
                ]
            ],
            'Caméras' => [
                'prefixes' => ['Pro', 'Elite', 'Ultra', 'Premium', 'Gaming'],
                'suffixes' => ['Series', 'Edition', 'Plus', 'Max', 'Pro'],
                'basePrice' => [200, 1500],
                'keywords' => ['Caméra', 'Webcam', 'Appareil photo', 'Caméscope', 'Objectif'],
                'imageSeed' => 'camera',
                'descriptions' => [
                    'Caméra 4K avec stabilisation d\'image, autofocus et mode nuit.',
                    'Webcam professionnelle avec HDR, micro intégré et monture universelle.',
                    'Appareil photo hybride avec capteur plein format, stabilisation 5 axes et WiFi intégré.',
                    'Caméscope 4K avec zoom optique 20x, écran tactile et double carte mémoire.',
                    'Objectif grand angle avec stabilisation optique, traitement anti-reflet et construction étanche.'
                ]
            ],
            'Tablettes' => [
                'prefixes' => ['Pro', 'Elite', 'Ultra', 'Premium', 'Gaming'],
                'suffixes' => ['Series', 'Edition', 'Plus', 'Max', 'Pro'],
                'basePrice' => [500, 1500],
                'keywords' => ['Tablette', 'iPad', 'Android Tablet', 'Windows Tablet', 'E-reader'],
                'imageSeed' => 'tablet',
                'descriptions' => [
                    'Tablette professionnelle avec stylet actif, écran haute résolution et autonomie de 12 heures.',
                    'Tablette avec écran 120Hz, processeur puissant et connectivité 5G.',
                    'Tablette Windows avec clavier détachable, port USB-C et Windows 11 Pro.',
                    'Tablette Android avec écran AMOLED, charge rapide et haut-parleurs stéréo.',
                    'E-reader avec écran e-ink, éclairage adaptatif et stockage extensible.'
                ]
            ],
            'Accessoires' => [
                'prefixes' => ['Pro', 'Elite', 'Ultra', 'Premium', 'Gaming'],
                'suffixes' => ['Series', 'Edition', 'Plus', 'Max', 'Pro'],
                'basePrice' => [20, 200],
                'keywords' => ['Câble', 'Chargeur', 'Support', 'Protection', 'Accessoire'],
                'imageSeed' => 'accessories',
                'descriptions' => [
                    'Câble USB-C avec charge rapide, transfert de données et construction renforcée.',
                    'Chargeur sans fil avec charge rapide, multi-appareils et design compact.',
                    'Support pour ordinateur portable avec ventilation, hauteur ajustable et câble intégré.',
                    'Coque de protection avec protection militaire, anti-choc et design élégant.',
                    'Kit d\'accessoires complet avec étui, chargeur et écouteurs sans fil.'
                ]
            ]
        ];

        foreach ($categories as $category) {
            $categoryName = $category->getName();
            $productType = $productTypes[$categoryName];

            // Générer 5-10 produits par catégorie
            $numProducts = $faker->numberBetween(5, 10);

            for ($i = 0; $i < $numProducts; $i++) {
                $product = new Product();

                // Génération du nom du produit
                $prefix = $faker->randomElement($productType['prefixes']);
                $suffix = $faker->randomElement($productType['suffixes']);
                $keyword = $faker->randomElement($productType['keywords']);
                $productName = $prefix . ' ' . $keyword . ' ' . $suffix;

                // Génération du prix
                $basePrice = $faker->numberBetween($productType['basePrice'][0], $productType['basePrice'][1]);
                $price = $faker->randomFloat(2, $basePrice * 0.8, $basePrice * 1.2);

                // Génération de la description
                $description = $faker->randomElement($productType['descriptions']);

                // Génération de l'image
                $imageSeed = $productType['imageSeed'] . '_' . $faker->unique()->numberBetween(1, 1000);
                $image = "https://picsum.photos/seed/{$imageSeed}/800/600";

                $product->setName($productName);
                $product->setPrice($price);
                $product->setDescription($description);
                $product->setImage($image);
                $product->setStock($faker->numberBetween(0, 100));
                $product->setCategory($category);

                $manager->persist($product);
                $products[] = $product;
            }
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