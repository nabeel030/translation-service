# Translation Service

**Translation Service** is an API-driven multilingual translation management system designed to support modern web and mobile applications. It provides a robust backend solution to manage, organize, and deliver translations efficiently for various locales such as `en`, `fr`, `es`, and more.

This service is built to meet both performance and technical standards, with the following core capabilities:

- 🔤 **Locale Management**: Store and manage translations for multiple languages with easy extensibility for adding new locales.
- 🏷️ **Tag-Based Contextualization**: Organize translations using tags (e.g., `mobile`, `desktop`, `web`) for better context awareness across platforms.
- 🔍 **Searchable API**: Create, update, view, and search translations using intuitive RESTful endpoints based on tags, keys, or content.
- 🔄 **Live JSON Export**: Provide frontend frameworks (like Vue.js) with always up-to-date translation data through a dedicated JSON export endpoint.

Designed with scalability and flexibility in mind, this service enables seamless localization workflows for any frontend application while ensuring up-to-date content delivery at all times.

## 🚀 Design Pattern
- Clean architecture using the Repository Design Pattern
- Docker support for easy development and deployment

---

## 🛠️ Installation Guide

### Prerequisites

- PHP 8.2
- Composer
- Laravel CLI
- Docker & Docker Compose (if using Docker)

---

### 📦 Local Installation
1. **Clone the repository**
   ```bash
   git clone https://github.com/nabeel030/translation-service.git
   cd translation-service

2. **Create .env from .env.example**
    ```bash
    cp .env.example .env

3. **Install Dependencies**
    ```bash
    composer install

4. **Generate application key**
    ```bash
    php artisan key:generate

5. **Create database tables and seed**
    ```bash
    php artisan migrate --seed

6. **Start the development server**
    ```bash
    php artisan serve
    

