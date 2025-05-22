# Form Management System

A modern form management system built with Laravel, Vue 3, and TypeScript.

## Tech Stack

-   Laravel - PHP web framework
-   Vue 3 - Progressive JavaScript framework
-   TypeScript - JavaScript with syntax for types
-   Vite - Next generation frontend tooling
-   ESLint - Tool for identifying and reporting on patterns in JavaScript/TypeScript
-   Tailwind CSS - A utility-first CSS framework

## Prerequisites

Before you begin, ensure you have the following installed:

-   PHP >= 8.1
-   Composer
-   Node.js >= 16.x
-   npm >= 8.x
-   MySQL >= 8.0 or PostgreSQL >= 13

## Development Setup

1. Clone the repository

    ```bash
    git clone https://github.com/SayefEshan/form-management-system.git
    cd form-management-system
    ```

2. Install PHP dependencies:

    ```bash
    composer install
    ```

3. Install Node.js dependencies:

    ```bash
    npm install
    ```

4. Configure your environment:

    ```bash
    cp .env.example .env
    ```

    Update the following in your `.env` file:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=form_management
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

5. Generate application key:

    ```bash
    php artisan key:generate
    ```

6. Run migrations and seed the database:

    ```bash
    php artisan migrate --seed
    ```

    This will automatically:

    - Create the database if it doesn't exist
    - Run all migrations
    - Seed the database with initial data

7. Start the development servers:

    In one terminal, start the Laravel development server:

    ```bash
    php artisan serve
    ```

    In another terminal, start the Vite development server:

    ```bash
    npm run dev
    ```

    The application will be available at:

    - Laravel backend: http://localhost:8000
    - Vite frontend: http://localhost:5173

    You can log in with the following credentials:

    Admin User:

    ```
    Email: admin@miaki.co
    Password: admin123
    ```

    Regular User:

    ```
    Email: user@miaki.co
    Password: user123
    ```

## Development Workflow

1. **Running Tests**

    ```bash
    php artisan test
    ```

2. **Building for Production**
    ```bash
    npm run build
    ```

## Troubleshooting

1. If you encounter permission issues:

    ```bash
    chmod -R 777 storage bootstrap/cache
    ```

2. If composer dependencies fail to install:

    ```bash
    composer install --ignore-platform-reqs
    ```

3. If npm dependencies fail to install:
    ```bash
    npm install --legacy-peer-deps
    ```

## License

MIT
