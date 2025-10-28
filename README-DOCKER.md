# 🐳 Docker Setup untuk SurveiV2

Konfigurasi Docker lengkap untuk menjalankan aplikasi Laravel SurveiV2 di server atau development environment.

## 🚀 Quick Start

### 1. Prerequisites
- Docker Desktop (Windows/Mac) atau Docker Engine (Linux)
- Docker Compose
- Git

### 2. Setup Aplikasi

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

### 3. Akses Aplikasi
- **Development**: http://localhost:8000
- **Production**: http://localhost

## 📋 Environment yang Tersedia

### Development Environment
- **Laravel App**: http://localhost:8000
- **MailHog**: http://localhost:8025 (untuk testing email)
- **MySQL**: localhost:3307
- **Redis**: localhost:6380

### Production Environment
- **Laravel App**: http://localhost
- **MySQL**: localhost:3306
- **Redis**: localhost:6379

## 🛠️ Commands yang Tersedia

### Menggunakan Makefile (Linux/Mac)
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

# Maintenance
make clean            # Clean containers
make status           # View status
```

### Menggunakan Docker Compose
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

## 🔧 Konfigurasi

### Environment Variables
File `.env` akan dibuat otomatis dengan konfigurasi default:

```env
APP_NAME="SurveiV2"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=surveiv2
DB_USERNAME=surveiv2_user
DB_PASSWORD=surveiv2_password

REDIS_HOST=redis
REDIS_PORT=6379

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Database Credentials
- **Database**: surveiv2
- **Username**: surveiv2_user
- **Password**: surveiv2_password
- **Root Password**: root_password

## 📁 Struktur Docker

```
docker/
├── nginx/
│   └── default.conf          # Nginx configuration
├── php/
│   └── local.ini             # PHP settings
├── mysql/
│   └── my.cnf                # MySQL configuration
├── supervisor/
│   └── supervisord.conf      # Process management
├── entrypoint.sh             # Production startup
├── entrypoint.dev.sh         # Development startup
└── README.md                 # Docker documentation
```

## 🐛 Troubleshooting

### 1. Port Already in Use
```bash
# Check what's using port 80
netstat -tulpn | grep :80

# Stop conflicting services
sudo systemctl stop apache2
sudo systemctl stop nginx
```

### 2. Permission Issues
```bash
# Fix storage permissions
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/storage
```

### 3. Database Connection Issues
```bash
# Check database status
docker-compose ps

# Test connection
docker-compose exec app php artisan migrate:status
```

### 4. Container Won't Start
```bash
# Check logs
docker-compose logs

# Rebuild containers
docker-compose build --no-cache

# Clean and restart
docker-compose down -v
docker-compose up -d
```

## 🚀 Production Deployment

### 1. Update Environment
```bash
# Edit .env file
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Use strong passwords
DB_PASSWORD=your_strong_password_here
```

### 2. Deploy
```bash
# Start production
docker-compose up -d

# Setup application
docker-compose exec app php artisan key:generate --force
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

### 3. SSL Certificate (Optional)
```bash
# Copy certificates to docker/nginx/ssl/
cp your-cert.crt docker/nginx/ssl/
cp your-key.key docker/nginx/ssl/

# Update nginx configuration for HTTPS
```

## 📊 Monitoring

### View Logs
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f db
docker-compose logs -f redis
```

### Container Status
```bash
docker-compose ps
```

### Resource Usage
```bash
docker stats
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

## 📝 Notes

- Development environment menggunakan volume mounting untuk hot reload
- Production environment menggunakan multi-stage build untuk optimasi
- Semua data persistent disimpan dalam Docker volumes
- Queue worker berjalan otomatis di production
- Health checks tersedia untuk monitoring

## 🆘 Support

Jika mengalami masalah:
1. Check logs: `docker-compose logs -f`
2. Check status: `docker-compose ps`
3. Restart services: `docker-compose restart`
4. Clean and rebuild: `docker-compose down -v && docker-compose up -d`

Untuk dokumentasi lengkap, lihat [DOCKER.md](DOCKER.md).
