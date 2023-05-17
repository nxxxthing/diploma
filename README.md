# Веб-додаток кабінету аспіранта
Метою веб-додатку кабінету аспіранта є створення системи в якій студент
зможе переглянути як розклад своєї групи, так і взаємодіяти із науковим
керівником, шляхом відправлення частин своєї наукової роботи на оцінку.
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
