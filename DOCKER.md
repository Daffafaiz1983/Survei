# Docker Setup untuk SurveiV2

Dokumentasi lengkap untuk menjalankan aplikasi Laravel SurveiV2 menggunakan Docker.

## 📋 Daftar Isi

- [Persyaratan](#persyaratan)
- [Struktur File](#struktur-file)
- [Quick Start](#quick-start)
- [Environment](#environment)
- [Cara Penggunaan](#cara-penggunaan)
- [Troubleshooting](#troubleshooting)
- [Production Deployment](#production-deployment)

## 🔧 Persyaratan

- Docker 20.10+
- Docker Compose 2.0+
- Git
- Minimal 4GB RAM
- Minimal 10GB disk space

## 📁 Struktur File

```
surveiv2/
├── Dockerfile                 # Production Docker image
├── Dockerfile.dev            # Development Docker image
├── docker-compose.yml        # Production environment
├── docker-compose.dev.yml    # Development environment
├── .dockerignore             # Docker ignore file
├── docker-setup.sh           # Setup script
├── Makefile                  # Docker commands helper
├── docker/                   # Docker configurations
│   ├── nginx/
│   │   └── default.conf      # Nginx configuration
│   ├── php/
│   │   └── local.ini         # PHP configuration
│   ├── mysql/
│   │   └── my.cnf            # MySQL configuration
│   ├── supervisor/
│   │   └── supervisord.conf  # Supervisor configuration
│   ├── entrypoint.sh         # Production entrypoint
│   ├── entrypoint.dev.sh     # Development entrypoint
│   └── README.md             # Docker documentation
└── DOCKER.md                 # This file
```

## 🚀 Quick Start

### 1. Clone Repository
```bash
git clone <repository-url>
cd surveiv2
```

### 2. Setup Environment
```bash
# Development (default)
./docker-setup.sh dev

# Production
./docker-setup.sh prod
```

### 3. Akses Aplikasi
- **Development**: http://localhost:8000
- **Production**: http://localhost

## 🌍 Environment

### Development Environment
- **Laravel**: http://localhost:8000
- **MailHog**: http://localhost:8025
- **MySQL**: localhost:3307
- **Redis**: localhost:6380

### Production Environment
- **Laravel**: http://localhost
- **MySQL**: localhost:3306
- **Redis**: localhost:6379

## 💻 Cara Penggunaan

### Menggunakan Makefile (Recommended)

```bash
# Development
make dev              # Start development environment
make dev-logs         # View development logs
make dev-stop         # Stop development environment

# Production
make prod             # Start production environment
make prod-logs        # View production logs
make prod-stop        # Stop production environment

# Laravel Commands
make migrate          # Run migrations
make seed             # Run seeders
make cache-clear      # Clear all caches
make shell            # Access app container

# Maintenance
make clean            # Clean containers and volumes
make status           # View container status
```

### Menggunakan Docker Compose Langsung

```bash
# Development
docker-compose -f docker-compose.dev.yml up -d
docker-compose -f docker-compose.dev.yml logs -f
docker-compose -f docker-compose.dev.yml down

# Production
docker-compose up -d
docker-compose logs -f
docker-compose down
```

### Menggunakan Setup Script

```bash
# Development
./docker-setup.sh dev

# Production
./docker-setup.sh prod
```

## 🔧 Laravel Commands

### Database Operations
```bash
# Run migrations
make migrate

# Fresh migrations with seeders
make migrate-fresh

# Run seeders
make seed

# Access database shell
make shell-db
```

### Cache Management
```bash
# Clear all caches
make cache-clear

# Cache configuration
make cache-config

# Cache routes
make cache-route

# Cache views
make cache-view
```

### Queue Management
```bash
# Start queue worker
make queue-work

# View failed jobs
make queue-failed

# Retry failed jobs
make queue-retry
```

### Testing
```bash
# Run tests
make test

# Run tests with coverage
make test-coverage
```

## 🐛 Troubleshooting

### 1. Permission Issues
```bash
# Fix storage permissions
make shell
chown -R www-data:www-data /var/www/html/storage
chmod -R 755 /var/www/html/storage
```

### 2. Database Connection Issues
```bash
# Check database status
make status

# Test database connection
make shell
php artisan migrate:status
```

### 3. Container Won't Start
```bash
# Check logs
make logs

# Rebuild containers
make build

# Clean and restart
make clean
make dev  # or make prod
```

### 4. Port Already in Use
```bash
# Check what's using the port
netstat -tulpn | grep :80
netstat -tulpn | grep :8000

# Stop conflicting services
sudo systemctl stop apache2  # or nginx
```

### 5. Memory Issues
```bash
# Check Docker memory usage
docker stats

# Increase Docker memory limit in Docker Desktop
# Settings > Resources > Memory
```

## 🚀 Production Deployment

### 1. Environment Configuration
```bash
# Update .env for production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database credentials
DB_HOST=db
DB_DATABASE=surveiv2
DB_USERNAME=surveiv2_user
DB_PASSWORD=strong_password_here

# Redis configuration
REDIS_HOST=redis
```

### 2. SSL Certificate (Optional)
```bash
# Copy SSL certificates to docker/nginx/ssl/
cp your-cert.crt docker/nginx/ssl/
cp your-key.key docker/nginx/ssl/

# Update nginx configuration for HTTPS
```

### 3. Production Deployment
```bash
# Build and start production
make prod

# Setup application
make setup

# Monitor logs
make logs
```

### 4. Backup Database
```bash
# Create backup
docker-compose exec db mysqldump -u surveiv2_user -p surveiv2 > backup.sql

# Restore backup
docker-compose exec -T db mysql -u surveiv2_user -p surveiv2 < backup.sql
```

## 📊 Monitoring

### Container Status
```bash
make status
```

### Resource Usage
```bash
docker stats
```

### Logs
```bash
# All services
make logs

# Specific service
make logs-app
make logs-db
make logs-redis
```

## 🔒 Security

### Production Security Checklist
- [ ] Change default passwords
- [ ] Use strong database passwords
- [ ] Enable SSL/HTTPS
- [ ] Update environment variables
- [ ] Regular security updates
- [ ] Monitor logs
- [ ] Backup data regularly

### Environment Variables Security
```bash
# Never commit .env to version control
echo ".env" >> .gitignore

# Use strong passwords
DB_PASSWORD=$(openssl rand -base64 32)
```

## 📝 Notes

- Development environment menggunakan volume mounting untuk hot reload
- Production environment menggunakan multi-stage build untuk optimasi
- Semua data persistent disimpan dalam Docker volumes
- Queue worker berjalan otomatis di production
- Health checks tersedia untuk monitoring

## 🆘 Support

Jika mengalami masalah:
1. Check logs: `make logs`
2. Check status: `make status`
3. Restart services: `make restart`
4. Clean and rebuild: `make clean && make build`

Untuk bantuan lebih lanjut, silakan buka issue di repository.
