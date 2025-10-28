# Docker Setup untuk SurveiV2

## Overview
Konfigurasi Docker ini menyediakan environment yang lengkap untuk menjalankan aplikasi Laravel SurveiV2 dengan database MySQL, Redis, dan Nginx.

## Struktur File
```
docker/
├── nginx/
│   └── default.conf          # Konfigurasi Nginx
├── php/
│   └── local.ini             # Konfigurasi PHP
├── mysql/
│   └── my.cnf                # Konfigurasi MySQL
├── supervisor/
│   └── supervisord.conf      # Konfigurasi Supervisor
├── entrypoint.sh             # Script startup production
└── entrypoint.dev.sh         # Script startup development
```

## Environment

### Production
- **File**: `docker-compose.yml`
- **Port**: 80 (HTTP), 443 (HTTPS)
- **Database**: MySQL 8.0
- **Cache**: Redis 7
- **Web Server**: Nginx + PHP-FPM

### Development
- **File**: `docker-compose.dev.yml`
- **Port**: 8000 (Laravel), 3307 (MySQL), 6380 (Redis), 8025 (MailHog)
- **Database**: MySQL 8.0
- **Cache**: Redis 7
- **Mail**: MailHog untuk testing email

## Cara Penggunaan

### 1. Production Deployment
```bash
# Build dan jalankan aplikasi
docker-compose up -d

# Lihat logs
docker-compose logs -f

# Stop aplikasi
docker-compose down
```

### 2. Development
```bash
# Jalankan environment development
docker-compose -f docker-compose.dev.yml up -d

# Lihat logs
docker-compose -f docker-compose.dev.yml logs -f

# Stop environment
docker-compose -f docker-compose.dev.yml down
```

### 3. Build Ulang
```bash
# Production
docker-compose build --no-cache

# Development
docker-compose -f docker-compose.dev.yml build --no-cache
```

## Environment Variables

### Database
- `DB_HOST`: db
- `DB_PORT`: 3306
- `DB_DATABASE`: surveiv2
- `DB_USERNAME`: surveiv2_user
- `DB_PASSWORD`: surveiv2_password

### Redis
- `REDIS_HOST`: redis
- `REDIS_PORT`: 6379

### Application
- `APP_ENV`: production/local
- `APP_DEBUG`: false/true
- `CACHE_DRIVER`: redis
- `SESSION_DRIVER`: redis
- `QUEUE_CONNECTION`: redis

## Volume Persistence
- `db_data`: Data MySQL
- `redis_data`: Data Redis

## Network
- `surveiv2_network`: Network untuk production
- `surveiv2_dev_network`: Network untuk development

## Troubleshooting

### 1. Permission Issues
```bash
# Set permission untuk storage
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/storage
```

### 2. Database Connection
```bash
# Test koneksi database
docker-compose exec app php artisan migrate:status
```

### 3. Clear Cache
```bash
# Clear semua cache
docker-compose exec app php artisan optimize:clear
```

### 4. View Logs
```bash
# Log aplikasi
docker-compose logs app

# Log database
docker-compose logs db

# Log semua service
docker-compose logs
```

## Security Notes
- Ganti password default di production
- Gunakan SSL certificate untuk HTTPS
- Update environment variables sesuai kebutuhan
- Monitor logs secara berkala
