# E-Commerce Project

A modern e-commerce platform built with Symfony 7.2, featuring a robust architecture and user-friendly interface.

## 🚀 Features

- User authentication and authorization
- Product catalog with categories
- Shopping cart functionality
- Order management
- Admin dashboard
- Responsive design
- Search functionality
- Pagination

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- MySQL/MariaDB
- Symfony CLI (optional but recommended)

## 🛠️ Installation

1. Clone the repository:
```bash
git clone https://github.com/Malekhalifa/e-commerce-symfony.git
cd e-commerce
```

2. Install dependencies:
```bash
composer install
```

3. Configure your environment:
```bash
cp .env .env.local
```
Edit `.env.local` and set your database credentials and other environment variables.

4. Create the database:
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. Load fixtures (optional):
```bash
php bin/console doctrine:fixtures:load
```

6. Start the development server:
```bash
symfony server:start
```

## 🔧 Configuration

The project uses several Symfony bundles and components:

- Doctrine ORM for database management
- Symfony Security for authentication
- KnpPaginatorBundle for pagination
- Twig for templating
- Symfony Asset Mapper for asset management

## 📁 Project Structure

```
├── assets/          # Frontend assets (CSS, JS, images)
├── config/          # Configuration files
├── migrations/      # Database migrations
├── public/          # Public directory
├── src/             # Application source code
│   ├── Controller/  # Controllers
│   ├── Entity/      # Database entities
│   ├── Repository/  # Entity repositories
│   └── ...
├── templates/       # Twig templates
└── tests/           # Test files
```

## 🧪 Testing

Run the test suite:
```bash
php bin/phpunit
```

## 📝 License

This project is proprietary software.

## 👥 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📧 Contact

For any questions or support, please contact [malek.khalifa321@gmail.com] 