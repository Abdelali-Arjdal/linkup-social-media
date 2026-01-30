# LinkUp

LinkUp is a Laravel-based social platform for sharing posts, following people, and messaging. It ships with a lightweight UI built on Tailwind CSS.

## Features
- Profiles with avatar and bio
- Posts, comments, and likes
- Follow system and personalized feed
- Direct messaging
- Notifications
- Search
- Responsive layout for desktop and mobile

## Requirements
- PHP 8.2+
- Composer
- Node.js & npm
- SQLite (default) or MySQL/PostgreSQL

## Quick start
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm run build
php artisan serve
```

The app will be available at `http://localhost:8000`.

## Seeded users
The database seeder creates 30 test users. Log in with any email from the seeded data using the password `password`.

Example:
- `ahmed.al-mansouri@linkup.com` / `password`

## Useful routes
- Feed: `/feed`
- Search: `/search`
- Notifications: `/notifications`
- Messages: `/messages`
- Profile edit: `/profile/edit`

## Development
```bash
# Start the dev server with file watching
composer run dev

# Run tests
composer run test

# Format code
composer run format
```

## API documentation
See `API_DOCUMENTATION.md` for endpoint details.

## Project structure
```
app/            Application logic
config/         Framework configuration
database/       Migrations, factories, seeders
resources/      Blade views, JS, and CSS
routes/         Web and auth routes
tests/          Feature tests
```

## Contributing
Please read `CONTRIBUTING.md` before opening a pull request.
