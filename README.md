# LinkUp - Social Media Platform

<p align="center">
  <strong>A modern, secure social media platform built with Laravel 12 and Tailwind CSS</strong>
</p>

---

## 🌟 Overview

**LinkUp** is a full-featured social media platform where users can share posts, connect with friends, engage through comments and likes, send direct messages, and explore content in a safe and intuitive environment.

Built with **Laravel 12** (PHP 8.2+), **Tailwind CSS**, and **Alpine.js** with a focus on security, performance, and user experience.

---

## ✨ Key Features

### 👥 **Social Features**
- User profiles with customizable avatars and bios
- Follow/unfollow system with follower lists
- Create, edit, and delete posts
- AJAX-based comments without page reloads
- Like/unlike posts with instant feedback
- Direct messaging with unread indicators
- Real-time notifications for likes, comments, and follows
- User discovery and search functionality

### 🔒 **Security**
- Email verification required
- CSRF & XSS protection
- Rate limiting on sensitive actions
- Authorization policies for content ownership
- Soft deletes for user data preservation
- Secure password hashing (Bcrypt)

### ⚡ **Performance**
- Database indexing for optimal query speed
- AJAX interactions (no full-page reloads)
- Eager loading to prevent N+1 queries
- Responsive mobile-first design

---

## 🚀 Quick Start

### Requirements
- PHP 8.2+
- Composer
- Node.js & npm (v16+)
- MySQL 5.7+ (or 8.0+)

### Step-by-Step Installation

#### 1. Clone the Repository
```bash
git clone https://github.com/Abdelali-Arjdal/linkup-social-media.git
cd linkup-social-media
```

#### 2. Install PHP Dependencies
```bash
composer install
```

#### 3. Install Node.js Dependencies
```bash
npm install
```

#### 4. Setup Environment Configuration
```bash
cp .env.example .env
```

Then edit the `.env` file and update the following database configuration:

```dotenv
APP_NAME=LinkUp
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reseau_social_v2
DB_USERNAME=root
DB_PASSWORD=
```

**Database Setup Instructions:**

Before running migrations, create the database in MySQL:

```bash
# Using MySQL CLI
mysql -u root -p
```

Then in the MySQL prompt:
```sql
CREATE DATABASE reseau_social_v2;
EXIT;
```

Or use phpMyAdmin to create a new database named `reseau_social_v2`.

#### 5. Generate Application Key
```bash
php artisan key:generate
```

#### 6. Run Database Migrations
```bash
php artisan migrate
```

This will create all necessary tables:
- users
- posts
- comments
- likes
- follows
- notifications
- messages
- conversations
- And all supporting tables

#### 7. (Optional) Seed Test Data
```bash
php artisan db:seed
```

This creates 30 test users with diverse names for testing purposes.

#### 8. Build Frontend Assets
```bash
# For production build
npm run build

# OR for development with hot reload
npm run dev
```

Run this in a separate terminal if using hot reload.

#### 9. Start the Development Server
```bash
php artisan serve
```

The application will be available at **http://localhost:8000**

### First Login

Use any of the seeded users to login:
- **Email:** `ahmed.al-mansouri@linkup.com` (example)
- **Password:** `password`

Or register a new account at the signup page.

---

## 📚 Documentation

For detailed documentation, please see:

- **[README_LINKUP.md](README_LINKUP.md)** — Complete project documentation
- **[QUICKSTART.md](QUICKSTART.md)** — Quick setup guide
- **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)** — API endpoints
- **[CONTRIBUTING.md](CONTRIBUTING.md)** — Contribution guidelines
- **[PRE_PUSH_CHECKLIST.md](PRE_PUSH_CHECKLIST.md)** — Quality assurance checklist

---

## 🛠️ Development

### Run Development Server with Auto-Reload
```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start frontend build watcher
npm run dev
```

Both servers should be running simultaneously during development:
- Laravel: http://localhost:8000
- Vite HMR: http://localhost:5173

### Database Management

**View the Database:**
Using phpMyAdmin or MySQL CLI:
```bash
mysql -u root reseau_social_v2
SHOW TABLES;
```

**Reset Database (Clear All Data):**
```bash
php artisan migrate:fresh
```

**Reset Database with Test Data:**
```bash
php artisan migrate:fresh --seed
```

**Rollback Last Migration:**
```bash
php artisan migrate:rollback
```

### Run Tests
```bash
php artisan test
```

### Format Code (PSR-12 Standard)
```bash
composer run format
```

### Build Production Assets
```bash
npm run build
```

This creates optimized CSS and JavaScript in `public/build/`

---

## 📊 Tech Stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Blade, Tailwind CSS, Alpine.js
- **Build Tool:** Vite
- **Database:** MySQL/PostgreSQL/SQLite
- **Testing:** PHPUnit
- **CI/CD:** GitHub Actions

---

## 🔐 Security Features

✅ Input validation and sanitization  
✅ CSRF protection on all forms  
✅ XSS prevention (strip_tags on user content)  
✅ Rate limiting (30 posts/hr, 100 likes/hr, 50 follows/hr)  
✅ Authorization policies  
✅ Email verification  
✅ Secure password hashing  
✅ Soft deletes for data preservation  

---

## 🤝 Contributing

We welcome contributions! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 🔧 Troubleshooting

### MySQL Connection Issues

**Error: "SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost'"**
- Check that MySQL is running
- Verify your `DB_USERNAME` and `DB_PASSWORD` in `.env`
- Ensure the database user exists and has proper permissions

**Error: "SQLSTATE[HY000]: General error: 1030 Got error 28 from storage engine"**
- Check disk space on your MySQL data directory
- Restart MySQL service

**Error: "Unknown database 'reseau_social_v2'"**
- Create the database first:
  ```bash
  mysql -u root -p -e "CREATE DATABASE reseau_social_v2;"
  ```
- Then run migrations: `php artisan migrate`

### Laravel Issues

**Error: "No application encryption key has been generated"**
```bash
php artisan key:generate
```

**Error: "Class 'PDO' not found"**
- Ensure PHP PDO MySQL extension is installed
- On Windows: check `php.ini` has `extension=pdo_mysql` uncommented
- On Mac/Linux: `brew install php php-mysql`

**Port 8000 already in use**
```bash
php artisan serve --port=8001
```

**Missing node_modules**
```bash
npm install
```

### Node.js / Build Issues

**Error: "npm command not found"**
- Install Node.js from https://nodejs.org/

**Error during `npm run dev`**
```bash
npm install
npm run dev
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

## 📧 Support

Found a bug or have a suggestion? Please [open an issue](https://github.com/Abdelali-Arjdal/linkup-social-media/issues).

---

**Built with ❤️ using Laravel & Tailwind CSS**
