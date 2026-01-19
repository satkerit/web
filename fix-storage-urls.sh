#!/bin/bash

# Script untuk mengganti semua Storage::url dengan StorageHelper::url di frontend

echo "🔍 Mencari dan mengganti Storage::url dengan StorageHelper::url..."

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Counter
count=0

# Function to replace Storage::url with StorageHelper::url
replace_storage_url() {
    local file="$1"
    if grep -q "Storage::url" "$file"; then
        echo -e "${YELLOW}📝 Memperbaiki: $file${NC}"
        
        # Backup original file
        cp "$file" "$file.backup"
        
        # Replace Storage::url with \App\Helpers\StorageHelper::url
        sed -i 's/Storage::url(/\\App\\Helpers\\StorageHelper::url(/g' "$file"
        
        # Count replacements
        local replacements=$(grep -c "StorageHelper::url" "$file")
        count=$((count + replacements))
        
        echo -e "${GREEN}✅ Diganti $replacements instance${NC}"
    fi
}

# Find and fix all .blade.php files in frontend
echo "🔍 Mencari file .blade.php di frontend..."

find resources/views/frontend -name "*.blade.php" -type f | while read file; do
    replace_storage_url "$file"
done

# Also check components that might be used in frontend
find resources/views/components -name "*.blade.php" -type f | while read file; do
    if grep -q "Storage::url" "$file"; then
        replace_storage_url "$file"
    fi
done

# Check livewire components
if [ -d "resources/views/livewire" ]; then
    find resources/views/livewire -name "*.blade.php" -type f | while read file; do
        if grep -q "Storage::url" "$file"; then
            replace_storage_url "$file"
        fi
    done
fi

echo ""
echo -e "${GREEN}✅ Selesai! Total penggantian: $count${NC}"
echo ""
echo "📋 File backup tersimpan dengan ekstensi .backup"
echo "🧹 Untuk menghapus backup files:"
echo "   find resources/views -name '*.backup' -delete"
echo ""
echo "🔍 Untuk verifikasi, cek apakah masih ada Storage::url:"
echo "   grep -r 'Storage::url' resources/views/frontend/"
echo ""