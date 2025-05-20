# Form Management System

A modern form management system built with Laravel, Vue 3, and TypeScript.

## Tech Stack

- Laravel - PHP web framework
- Vue 3 - Progressive JavaScript framework
- TypeScript - JavaScript with syntax for types
- Vite - Next generation frontend tooling
- ESLint - Tool for identifying and reporting on patterns in JavaScript/TypeScript
- Tailwind CSS - A utility-first CSS framework

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
4. Copy `.env.example` to `.env` and configure your environment
   ```bash
   cp .env.example .env
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Run migrations:
   ```bash
   php artisan migrate
   ```
7. Start the development server:
   ```bash
   php artisan serve
   ```
8. Start the Vite development server:
   ```bash
   npm run dev
   ```

## License

MIT
