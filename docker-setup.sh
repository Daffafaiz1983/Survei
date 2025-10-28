#!/bin/bash

# Script untuk setup Docker environment SurveiV2
# Usage: ./docker-setup.sh [dev|prod]

set -e

# Colors untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function untuk print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if Docker is installed
check_docker() {
    if ! command -v docker &> /dev/null; then
        print_error "Docker tidak terinstall. Silakan install Docker terlebih dahulu."
        exit 1
    fi

    if ! command -v docker-compose &> /dev/null; then
        print_error "Docker Compose tidak terinstall. Silakan install Docker Compose terlebih dahulu."
        exit 1
    fi

    print_success "Docker dan Docker Compose sudah terinstall"
}

# Create .env file if not exists
create_env_file() {
    if [ ! -f .env ]; then
        print_status "Membuat file .env dari .env.example..."
        if [ -f .env.example ]; then
            cp .env.example .env
            print_success "File .env berhasil dibuat"
        else
            print_warning "File .env.example tidak ditemukan, membuat .env manual..."
            cat > .env << EOF
APP_NAME="SurveiV2"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
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
EOF
            print_success "File .env berhasil dibuat"
        fi
    else
        print_status "File .env sudah ada"
    fi
}

# Setup development environment
setup_dev() {
    print_status "Setting up development environment..."
    
    create_env_file
    
    print_status "Building development containers..."
    docker-compose -f docker-compose.dev.yml build --no-cache
    
    print_status "Starting development containers..."
    docker-compose -f docker-compose.dev.yml up -d
    
    print_status "Waiting for services to be ready..."
    sleep 10
    
    print_status "Running Laravel setup commands..."
    docker-compose -f docker-compose.dev.yml exec app php artisan key:generate --force
    docker-compose -f docker-compose.dev.yml exec app php artisan migrate --force
    docker-compose -f docker-compose.dev.yml exec app php artisan storage:link
    
    print_success "Development environment berhasil di-setup!"
    print_status "Aplikasi dapat diakses di: http://localhost:8000"
    print_status "MailHog dapat diakses di: http://localhost:8025"
    print_status "Database MySQL: localhost:3307"
    print_status "Redis: localhost:6380"
}

# Setup production environment
setup_prod() {
    print_status "Setting up production environment..."
    
    create_env_file
    
    # Update .env for production
    sed -i 's/APP_ENV=local/APP_ENV=production/' .env
    sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
    
    print_status "Building production containers..."
    docker-compose build --no-cache
    
    print_status "Starting production containers..."
    docker-compose up -d
    
    print_status "Waiting for services to be ready..."
    sleep 15
    
    print_status "Running Laravel setup commands..."
    docker-compose exec app php artisan key:generate --force
    docker-compose exec app php artisan migrate --force
    docker-compose exec app php artisan storage:link
    docker-compose exec app php artisan config:cache
    docker-compose exec app php artisan route:cache
    docker-compose exec app php artisan view:cache
    
    print_success "Production environment berhasil di-setup!"
    print_status "Aplikasi dapat diakses di: http://localhost"
}

# Main script
main() {
    print_status "=== Docker Setup untuk SurveiV2 ==="
    
    check_docker
    
    case "${1:-dev}" in
        "dev"|"development")
            setup_dev
            ;;
        "prod"|"production")
            setup_prod
            ;;
        *)
            print_error "Usage: $0 [dev|prod]"
            print_status "  dev  - Setup development environment (default)"
            print_status "  prod - Setup production environment"
            exit 1
            ;;
    esac
    
    print_success "Setup selesai!"
    print_status "Gunakan 'docker-compose logs -f' untuk melihat logs"
}

# Run main function
main "$@"
