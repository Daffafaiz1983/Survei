# SurveiV2

Aplikasi survei berbasis Laravel dengan fitur lengkap untuk manajemen survei, fasilitas, dan laporan.

## 🚀 Quick Start dengan Docker

### Prerequisites
- Docker Desktop (Windows/Mac) atau Docker Engine (Linux)
- Docker Compose

### Setup Aplikasi

#### Windows (PowerShell)
```powershell
# Clone repository
git clone <repository-url>
cd surveiv2

# Setup development environment
.\docker-setup.ps1 dev

# Setup production environment
.\docker-setup.ps1 prod
```

#### Linux/Mac (Bash)
```bash
# Clone repository
git clone <repository-url>
cd surveiv2

# Setup development environment
./docker-setup.sh dev

# Setup production environment
./docker-setup.sh prod
```

### Akses Aplikasi
- **Development**: http://localhost:8000
- **Production**: http://localhost

## 📋 Fitur

- ✅ Manajemen Survei
- ✅ Manajemen Fasilitas
- ✅ Sistem Laporan
- ✅ Dashboard Admin
- ✅ Authentication & Authorization
- ✅ Queue System
- ✅ Cache dengan Redis
- ✅ Docker Support

## 🐳 Docker Commands

```bash
# Development
make dev              # Start development
make dev-logs         # View logs
make dev-stop         # Stop development

# Production
make prod             # Start production
make prod-logs        # View logs
make prod-stop        # Stop production

# Laravel Commands
make migrate          # Run migrations
make seed             # Run seeders
make cache-clear      # Clear caches
make shell            # Access container
```

## 📚 Dokumentasi

- [Docker Setup](DOCKER.md) - Dokumentasi lengkap Docker
- [Docker Quick Start](README-DOCKER.md) - Quick start guide
- [Laravel Documentation](https://laravel.com/docs)

## 🛠️ Tech Stack

- **Backend**: Laravel 12, PHP 8.2
- **Frontend**: Blade, Tailwind CSS, Alpine.js
- **Database**: MySQL 8.0
- **Cache**: Redis 7
- **Queue**: Redis
- **Web Server**: Nginx
- **Containerization**: Docker, Docker Compose

## 📄 About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
