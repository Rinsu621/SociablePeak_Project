# Sociolytics

Sociolytics is a Laravel project for building a social media web application.

## Getting Started

Follow these steps to set up the project locally:

### Prerequisites

- PHP >= 8
- Composer
- MySQL >= 5.7
- Git

### Installation

1. Clone the repository:

    ```bash
    git clone git@github.com:BipinJoshi2052/sociolytics.git
    ```

2. Navigate to the project directory:

    ```bash
    cd sociolytics
    ```

3. Install PHP dependencies using Composer:

    ```bash
    composer install
    ```

4. Generate an application key:

    ```bash
    php artisan key:generate
    ```

5. Copy the `.env.example` file to `.env`:

    ```bash
    cp .env.example .env
    ```

6. Create a MySQL database named `sociolite`.

7. Modify the database configuration in the `.env` file with your MySQL username and password:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=sociolite
    DB_USERNAME=your_mysql_username
    DB_PASSWORD=your_mysql_password
    ```

8. Migrate the database:

    ```bash
    php artisan migrate
    ```

### Usage

Start the development server:

```bash
php artisan serve
