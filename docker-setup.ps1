# PowerShell script untuk setup Docker environment SurveiV2
# Usage: .\docker-setup.ps1 [dev|prod]

param(
    [Parameter(Position=0)]
    [ValidateSet("dev", "prod", "development", "production")]
    [string]$Environment = "dev"
)

# Function untuk print colored output
function Write-Status {
    param([string]$Message)
    Write-Host "[INFO] $Message" -ForegroundColor Blue
}

function Write-Success {
    param([string]$Message)
    Write-Host "[SUCCESS] $Message" -ForegroundColor Green
}

function Write-Warning {
    param([string]$Message)
    Write-Host "[WARNING] $Message" -ForegroundColor Yellow
}

function Write-Error {
    param([string]$Message)
    Write-Host "[ERROR] $Message" -ForegroundColor Red
}

# Check if Docker is installed
function Test-Docker {
    try {
        $dockerVersion = docker --version
        $composeVersion = docker-compose --version
        Write-Success "Docker dan Docker Compose sudah terinstall"
        Write-Host "Docker: $dockerVersion" -ForegroundColor Gray
        Write-Host "Compose: $composeVersion" -ForegroundColor Gray
        return $true
    }
    catch {
        Write-Error "Docker atau Docker Compose tidak terinstall. Silakan install Docker Desktop terlebih dahulu."
        return $false
    }
}

# Create .env file if not exists
function New-EnvFile {
    if (-not (Test-Path ".env")) {
        Write-Status "Membuat file .env dari env.example..."
        if (Test-Path "env.example") {
            Copy-Item "env.example" ".env"
            Write-Success "File .env berhasil dibuat"
        }
        else {
            Write-Warning "File env.example tidak ditemukan, membuat .env manual..."
            $envContent = @"
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
"@
            $envContent | Out-File -FilePath ".env" -Encoding UTF8
            Write-Success "File .env berhasil dibuat"
        }
    }
    else {
        Write-Status "File .env sudah ada"
    }
}

# Setup development environment
function Start-DevEnvironment {
    Write-Status "Setting up development environment..."
    
    New-EnvFile
    
    Write-Status "Building development containers..."
    docker-compose -f docker-compose.dev.yml build --no-cache
    
    Write-Status "Starting development containers..."
    docker-compose -f docker-compose.dev.yml up -d
    
    Write-Status "Waiting for services to be ready..."
    Start-Sleep -Seconds 10
    
    Write-Status "Running Laravel setup commands..."
    docker-compose -f docker-compose.dev.yml exec app php artisan key:generate --force
    docker-compose -f docker-compose.dev.yml exec app php artisan migrate --force
    docker-compose -f docker-compose.dev.yml exec app php artisan storage:link
    
    Write-Success "Development environment berhasil di-setup!"
    Write-Status "Aplikasi dapat diakses di: http://localhost:8000"
    Write-Status "MailHog dapat diakses di: http://localhost:8025"
    Write-Status "Database MySQL: localhost:3307"
    Write-Status "Redis: localhost:6380"
}

# Setup production environment
function Start-ProdEnvironment {
    Write-Status "Setting up production environment..."
    
    New-EnvFile
    
    # Update .env for production
    $envContent = Get-Content ".env"
    $envContent = $envContent -replace "APP_ENV=local", "APP_ENV=production"
    $envContent = $envContent -replace "APP_DEBUG=true", "APP_DEBUG=false"
    $envContent | Out-File -FilePath ".env" -Encoding UTF8
    
    Write-Status "Building production containers..."
    docker-compose build --no-cache
    
    Write-Status "Starting production containers..."
    docker-compose up -d
    
    Write-Status "Waiting for services to be ready..."
    Start-Sleep -Seconds 15
    
    Write-Status "Running Laravel setup commands..."
    docker-compose exec app php artisan key:generate --force
    docker-compose exec app php artisan migrate --force
    docker-compose exec app php artisan storage:link
    docker-compose exec app php artisan config:cache
    docker-compose exec app php artisan route:cache
    docker-compose exec app php artisan view:cache
    
    Write-Success "Production environment berhasil di-setup!"
    Write-Status "Aplikasi dapat diakses di: http://localhost"
}

# Main script
function Main {
    Write-Status "=== Docker Setup untuk SurveiV2 ==="
    
    if (-not (Test-Docker)) {
        exit 1
    }
    
    switch ($Environment) {
        { $_ -in @("dev", "development") } {
            Start-DevEnvironment
        }
        { $_ -in @("prod", "production") } {
            Start-ProdEnvironment
        }
        default {
            Write-Error "Usage: .\docker-setup.ps1 [dev|prod]"
            Write-Status "  dev  - Setup development environment (default)"
            Write-Status "  prod - Setup production environment"
            exit 1
        }
    }
    
    Write-Success "Setup selesai!"
    Write-Status "Gunakan 'docker-compose logs -f' untuk melihat logs"
}

# Run main function
Main
