#!/bin/bash

# Setup Production Storage Script
# Untuk memperbaiki masalah image preview di production

echo "=========================================="
echo "Setup Production Storage"
echo "=========================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# Get current directory (Laravel project root)
LARAVEL_ROOT=$(pwd)
print_info "Laravel project root: $LARAVEL_ROOT"

# Ask for public_html path
echo ""
read -p "Enter path to public_html/dev directory (e.g., /home/user/public_html/dev): " PUBLIC_PATH

# Validate public path
if [ ! -d "$PUBLIC_PATH" ]; then
    print_error "Directory does not exist: $PUBLIC_PATH"
    exit 1
fi

print_success "Public path validated: $PUBLIC_PATH"

# Define paths
STORAGE_TARGET="$LARAVEL_ROOT/storage/app/public"
STORAGE_LINK="$PUBLIC_PATH/storage"

print_info "Storage target: $STORAGE_TARGET"
print_info "Storage symlink: $STORAGE_LINK"

# Create storage target directory if it doesn't exist
if [ ! -d "$STORAGE_TARGET" ]; then
    mkdir -p "$STORAGE_TARGET"
    print_success "Created storage target directory"
fi

# Remove existing symlink if it exists
if [ -L "$STORAGE_LINK" ]; then
    echo ""
    read -p "Storage symlink already exists. Remove it? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        rm "$STORAGE_LINK"
        print_success "Removed existing symlink"
    else
        print_info "Keeping existing symlink"
        exit 0
    fi
fi

# Create symlink
if ln -s "$STORAGE_TARGET" "$STORAGE_LINK"; then
    print_success "Storage symlink created successfully!"
else
    print_error "Failed to create storage symlink"
    exit 1
fi

# Update .env file
ENV_FILE="$LARAVEL_ROOT/.env"
if [ -f "$ENV_FILE" ]; then
    print_info "Updating .env file..."
    
    # Get APP_URL from .env
    APP_URL=$(grep "^APP_URL=" "$ENV_FILE" | cut -d'=' -f2)
    if [ -z "$APP_URL" ]; then
        APP_URL="https://yourdomain.com"
        print_warning "APP_URL not found in .env, using default: $APP_URL"
    fi
    
    # Remove trailing slash and create storage URL
    APP_URL=$(echo "$APP_URL" | sed 's:/*$::')
    STORAGE_URL="$APP_URL/dev/storage"
    
    # Remove existing storage config
    sed -i '/^# Production Storage Configuration$/,/^$/d' "$ENV_FILE"
    sed -i '/^STORAGE_URL=/d' "$ENV_FILE"
    sed -i '/^STORAGE_PUBLIC_PATH=/d' "$ENV_FILE"
    
    # Add new storage config
    echo "" >> "$ENV_FILE"
    echo "# Production Storage Configuration" >> "$ENV_FILE"
    echo "STORAGE_URL=$STORAGE_URL" >> "$ENV_FILE"
    echo "STORAGE_PUBLIC_PATH=$STORAGE_LINK" >> "$ENV_FILE"
    
    print_success "Updated .env file"
    print_info "STORAGE_URL=$STORAGE_URL"
    print_info "STORAGE_PUBLIC_PATH=$STORAGE_LINK"
else
    print_warning ".env file not found, skipping configuration update"
fi

# Test the setup
echo ""
print_info "Testing storage setup..."

# Test 1: Check if symlink is valid
if [ -L "$STORAGE_LINK" ] && [ "$(readlink "$STORAGE_LINK")" = "$STORAGE_TARGET" ]; then
    print_success "Symlink is valid"
else
    print_error "Symlink is invalid"
    exit 1
fi

# Test 2: Create test file
TEST_FILE="$STORAGE_TARGET/test-storage-setup.txt"
TEST_CONTENT="Storage setup test - $(date)"

if echo "$TEST_CONTENT" > "$TEST_FILE"; then
    print_success "Can write to storage directory"
    
    # Test 3: Check if file is accessible via symlink
    SYMLINK_TEST_FILE="$STORAGE_LINK/test-storage-setup.txt"
    if [ -f "$SYMLINK_TEST_FILE" ] && [ "$(cat "$SYMLINK_TEST_FILE")" = "$TEST_CONTENT" ]; then
        print_success "File accessible via symlink"
        
        # Clean up test file
        rm "$TEST_FILE"
        print_success "Test completed successfully"
    else
        print_error "File not accessible via symlink"
        exit 1
    fi
else
    print_error "Cannot write to storage directory"
    exit 1
fi

# Set proper permissions
chmod 755 "$STORAGE_LINK"
chmod -R 755 "$STORAGE_TARGET"
print_success "Set proper permissions"

# Clear Laravel cache
if [ -f "$LARAVEL_ROOT/artisan" ]; then
    print_info "Clearing Laravel cache..."
    cd "$LARAVEL_ROOT"
    php artisan config:clear > /dev/null 2>&1
    php artisan cache:clear > /dev/null 2>&1
    print_success "Laravel cache cleared"
fi

echo ""
echo "=========================================="
print_success "Production storage setup completed!"
echo "=========================================="
echo ""
print_info "Next steps:"
echo "1. Test image upload in admin panel"
echo "2. Check if image preview works"
echo "3. Verify storage URLs in browser"
echo ""
print_info "If you encounter issues, check the troubleshooting guide in:"
print_info "SETUP_PRODUCTION_STORAGE.md"
echo ""