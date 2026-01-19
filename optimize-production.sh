#!/bin/bash

# Script untuk optimize production setelah fix storage

echo "🚀 Optimizing Production..."

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

# Clear all caches
print_info "Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
print_success "Caches cleared"

# Optimize for production
print_info "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
print_success "Production optimization completed"

# Dump autoloader
print_info "Optimizing autoloader..."
composer dump-autoload --optimize --no-dev
print_success "Autoloader optimized"

# Set proper permissions
print_info "Setting proper permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
print_success "Permissions set"

# Check storage symlink
if [ -L "public/storage" ]; then
    print_success "Storage symlink exists"
else
    print_warning "Storage symlink not found, creating..."
    php artisan storage:link
    print_success "Storage symlink created"
fi

echo ""
echo "🎉 Production optimization completed!"
echo ""
print_info "Next steps:"
echo "1. Test website functionality"
echo "2. Check image loading"
echo "3. Verify logo display"
echo "4. Monitor error logs"
echo ""
print_info "To monitor logs:"
echo "tail -f storage/logs/laravel.log"
echo ""