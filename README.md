# Laravel api template

## Run Locally

Clone the project

```bash
  git clone git@gitlab.hexide-digital.com:Study/laraveltemplateislmbased.git
```

Go to the project directory

```bash
  cd my-project
```

Copy env.example and set it up (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

```bash
  cp .env.example .env
```

Install dependencies

```bash
  composer install
```

Install npm packages and build project

```bash
  npm install && npm run dev
```

Generate keys

```bash
  php artisan key:generate && php artisan jwt:secret
```

Generate storage symlink

```bash
  php artisan storage:link
```

Run migrations and seeders

```bash
  php artisan migrate --seed
```

Run server

```bash
  php artisan serve
```

If it`s web project you need to install yaap/theme package

```bash
  composer require yaap/theme
```

And create default theme

```bash
  php artisan theme:create default
```

And init this in web routes (Theme::init('default');):

![img.png](img.png)
