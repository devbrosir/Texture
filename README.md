# Texture Project

This repository contains the backend of a web-based system for managing, editing, and previewing textures on real-world environment images (e.g., rooms, kitchens, halls).

The backend is responsible for secure server-side image processing, including texture placement, scaling, repetition, and perspective transformation based on predefined masks. It also provides APIs for admin management, user interactions, WordPress authentication integration (via single-use tokens), manual processing requests, activity tracking, SEO metadata support, and popup banner management.
Some backend responsibilities handled by another node.js project.

All compositing logic is intentionally handled on the server to ensure security, consistency, and protection of processing algorithms.

This project built on top of **Laravel Starter Kit**. Laravel Starter Kit offers a **strict, type-safe, and fail-fast architecture**, making it an excellent choice for projects that aim to follow professional standards from day one.

---

## Getting Started

### Requirements

Make sure you have the following installed:

* PHP **8.4+**
* Composer
* Node.js & npm

---

### Installation

1. Clone the source code
2. ```composer install```
3. ```cp .env.example .env```
4. ```php artisan key:generate```
5. setup database in .env file
6. ```php artisan migrate:fresh --seed```
7. start development server: ```conmposer dev```

---

## Testing & Code Quality

This project comes with a powerful quality-assurance toolchain:

| Command                       | Description                   |
|-------------------------------|-------------------------------|
| `composer test`               | Run all tests and checks      |
| `composer test:unit`          | Run unit tests                |
| `composer test:types`         | Run static analysis (PHPStan) |
| `composer test:type-coverage` | Check type coverage           |
| `composer lint`               | Fix code style issues         |
| `composer test:lint`          | Run lint checks for CI        |

These tools ensure the codebase remains **clean, safe, and maintainable** at all times.

---

## Development Tools

The following tools are integrated into the project:

* **Pest** – Testing framework
* **PHPStan** – Static analysis (with Larastan)
* **Rector** – Automated refactoring
* **Laravel Pint** – PHP code formatting
* **Prettier** – Frontend formatting

Together, they provide a modern and professional development experience.
