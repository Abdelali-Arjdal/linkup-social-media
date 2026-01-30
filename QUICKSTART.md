# Quick Start Guide - LinkUp

## Installation (5 minutes)

```bash
# 1. Install PHP dependencies
composer install

# 2. Install frontend dependencies  
npm install

# 3. Create environment file
cp .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Run migrations
php artisan migrate

# 6. Seed the database with test users
php artisan db:seed

# 7. Build frontend assets
npm run build

# 8. Start the development server
php artisan serve
```

Access the application at **http://localhost:8000**

## Login with Seeded Users

After seeding, you can login with any of the 30 seeded users:

- **Email**: firstname.lastname@linkup.com (lowercase, dots for spaces)
- **Password**: `password`

### Examples:
- `ahmed.al-mansouri@linkup.com` / `password`
- `john.smith@linkup.com` / `password`
- `jean.dupont@linkup.com` / `password`

## Features to Try

### 1. Feed & Posts
- View your personalized feed
- Create new posts
- Like and comment on posts
- Delete your own posts

### 2. Follow System
- Visit user profiles
- Follow/unfollow users
- See follower counts
- Filter feed by followed users

### 3. Messaging
- Send direct messages
- View message history
- See unread message count (badge in navigation)
- Messages appear at bottom in chronological order

### 4. Notifications
- Like notifications
- Comment notifications
- Follow notifications
- Mark as read/read all

### 5. Profile
- View your profile
- Edit profile information
- Upload avatar
- Customize bio
- Change password

### 6. Search
- Find users
- View user profiles
- See their posts and followers

## Development Commands

```bash
# Start dev server with live reload
composer run dev

# Run tests
composer run test

# Format code
composer run format

# Clean cache
php artisan cache:clear

# Clear all cache
php artisan optimize:clear
```

## Testing

### Run All Tests
```bash
php artisan test
```

### Run Specific Tests
```bash
php artisan test tests/Feature/PostTest.php
```

### With Coverage
```bash
php artisan test --coverage
```

## Database

### Reset Database
```bash
# Fresh migration with seed
php artisan migrate:fresh --seed

# Fresh migration only
php artisan migrate:fresh
```

### Run Specific Migration
```bash
php artisan migrate --path=database/migrations/2026_01_30_200000_add_indexes_to_tables.php
```

## Common Issues

### Issue: "sqlite database cannot be opened"
**Solution**: Database file created automatically. If not, create with:
```bash
touch database/database.sqlite
php artisan migrate
```

### Issue: "Node modules not found"
**Solution**: Install dependencies:
```bash
npm install
npm run build
```

### Issue: "Key generation error"
**Solution**: Generate key:
```bash
php artisan key:generate
```

### Issue: "Failed to compile CSS"
**Solution**: Clear cache and rebuild:
```bash
npm run build
php artisan optimize:clear
```

## Environment Variables

Key `.env` variables:

```env
APP_NAME=LinkUp
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database (default: SQLite)
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_DATABASE=linkup

# Mail (default: log)
MAIL_MAILER=log

# Session
SESSION_DRIVER=database
CACHE_STORE=database
```

## Useful URLs

- **Feed**: http://localhost:8000/feed
- **Search**: http://localhost:8000/search
- **Notifications**: http://localhost:8000/notifications
- **Messages**: http://localhost:8000/messages
- **Profile Edit**: http://localhost:8000/profile/edit
- **Logout**: Click profile menu → logout

## File Locations

- **Frontend Views**: `resources/views/`
- **Controllers**: `app/Http/Controllers/`
- **Models**: `app/Models/`
- **Routes**: `routes/`
- **CSS/JS**: `resources/css/` and `resources/js/`
- **Database Migrations**: `database/migrations/`
- **Tests**: `tests/`

## Git Commands

```bash
# Create feature branch
git checkout -b feature/your-feature-name

# Stage changes
git add .

# Commit changes
git commit -m "Add feature description"

# Push to GitHub
git push origin feature/your-feature-name
```

## Next Steps

1. **Explore the Code**
   - Check `app/Models/` for data models
   - Review `app/Http/Controllers/` for business logic
   - Examine `resources/views/` for UI templates

2. **Make Changes**
   - Create feature branch
   - Make your changes
   - Run tests
   - Commit and push

3. **Deploy**
   - Build assets: `npm run build`
   - Run migrations: `php artisan migrate`
   - Configure environment
   - Deploy to server

## Documentation

- **Full README**: See `README_LINKUP.md`
- **Contributing**: See `CONTRIBUTING.md`
- **Improvements**: See `IMPROVEMENTS_SUMMARY.md`

## Support

For issues or questions:
1. Check existing documentation
2. Review error messages carefully
3. Check database connectivity
4. Verify all dependencies installed
5. Review Laravel logs: `storage/logs/laravel.log`

---

**Happy coding! 🚀**
