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
- Node.js & npm
- MySQL/PostgreSQL or SQLite

### Installation

```bash
# Clone the repository
git clone https://github.com/Abdelali-Arjdal/linkup-social-media.git
cd linkup-social-media

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate
php artisan db:seed  # Optional: seed with test users

# Build assets
npm run build
# OR for development with auto-reload:
npm run dev

# Start the server
php artisan serve
```

Visit **http://localhost:8000** and log in with:
- **Email:** `ahmed.al-mansouri@linkup.com` (or any seeded user)
- **Password:** `password`

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

### Run Development Server
```bash
composer run dev
```

### Run Tests
```bash
php artisan test
```

### Format Code
```bash
composer run format
```

### Build Production Assets
```bash
npm run build
```

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

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

## 📧 Support

Found a bug or have a suggestion? Please [open an issue](https://github.com/Abdelali-Arjdal/linkup-social-media/issues).

---

**Built with ❤️ using Laravel & Tailwind CSS**
