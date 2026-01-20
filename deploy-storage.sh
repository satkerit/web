#!/bin/bash

# ============================================
# Storage Deployment Script
# ============================================
# Script untuk setup storage di production
# ============================================

echo "============================================"
echo "  Storage Deployment Script"
echo "============================================"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if .env exists
if [ ! -f .env ]; then
    echo -e "${RED}Error: .env file not found!${NC}"
    echo "Please create .env file first."
    exit 1
fi

# Read environment
APP_ENV=$(grep APP_ENV .env | cut -d '=' -f2)
STORAGE_URL=$(grep STORAGE_URL .env | cut -d '=' -f2)
STORAGE_ROOT_PATH=$(grep STORAGE_ROOT_PATH .env | cut -d '=' -f2)

echo "Current Configuration:"
echo "  Environment: $APP_ENV"
echo "  Storage URL: $STORAGE_URL"
echo "  Storage Path: $STORAGE_ROOT_PATH"
echo ""

# Check if production
if [ "$APP_ENV" != "production" ]; then
    echo -e "${YELLOW}Warning: APP_ENV is not set to 'production'${NC}"
    echo "This script is designed for production deployment."
    read -p "Continue anyway? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

# Check if storage config is set
if [ -z "$STORAGE_URL" ] || [ -z "$STORAGE_ROOT_PATH" ]; then
    echo -e "${RED}Error: Storage configuration not set in .env${NC}"
    echo ""
    echo "Please add these to your .env file:"
    echo "  STORAGE_URL=https://yourdomain.com/dev/storage"
    echo "  STORAGE_ROOT_PATH=/home/username/public_html/dev/storage"
    echo "  APP_PUBLIC_PATH=dev"
    exit 1
fi

echo "============================================"
echo "  Step 1: Installing Dependencies"
echo "============================================"
echo ""

composer install --optimize-autoloader --no-dev
if [ $? -ne 0 ]; then
    echo -e "${RED}Error: Composer install failed${NC}"
    exit 1
fi
echo -e "${GREEN}✓ Dependencies installed${NC}"
echo ""

echo "============================================"
echo "  Step 2: Creating Storage Link"
echo "============================================"
echo ""

php artisan storage:link-production --force
if [ $? -ne 0 ]; then
    echo -e "${RED}Error: Failed to create storage link${NC}"
    exit 1
fi
echo -e "${GREEN}✓ Storage link created${NC}"
echo ""

echo "============================================"
echo "  Step 3: Setting Permissions"
echo "============================================"
echo ""

chmod -R 755 storage/
if [ $? -ne 0 ]; then
    echo -e "${YELLOW}Warning: Failed to set storage permissions${NC}"
else
    echo -e "${GREEN}✓ Storage permissions set${NC}"
fi

if [ -d "$STORAGE_ROOT_PATH" ]; then
    chmod -R 755 "$STORAGE_ROOT_PATH"
    if [ $? -ne 0 ]; then
        echo -e "${YELLOW}Warning: Failed to set public storage permissions${NC}"
    else
        echo -e "${GREEN}✓ Public storage permissions set${NC}"
    fi
fi
echo ""

echo "============================================"
echo "  Step 4: Testing Configuration"
echo "============================================"
echo ""

php artisan storage:test
echo ""

echo "============================================"
echo "  Step 5: Clearing Cache"
echo "============================================"
echo ""

php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo -e "${GREEN}✓ Cache cleared${NC}"
echo ""

echo "============================================"
echo "  Deployment Complete!"
echo "============================================"
echo ""
echo "Next steps:"
echo "  1. Test file upload in admin panel"
echo "  2. Check if images display on frontend"
echo "  3. Check browser console for 404 errors"
echo "  4. Test on different pages (news, products, etc.)"
echo ""
echo -e "${GREEN}Storage is now configured for production!${NC}"
