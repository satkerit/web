# Design Document: Dynamic Menu Access

## Overview

Fitur Hak Akses Dinamis Menu Admin memungkinkan super_admin untuk mengkonfigurasi menu yang dapat diakses oleh setiap role secara dinamis melalui interface admin. Selain itu, semua user dapat mengakses halaman profil untuk melihat/edit informasi dan mengganti password.

## Architecture

```
app/
├── Models/
│   ├── AdminMenu.php              # Model untuk menu admin
│   └── AdminMenuPermission.php    # Model untuk permission per role
├── Http/Controllers/Admin/
│   ├── MenuPermissionController.php  # Controller untuk konfigurasi menu
│   └── ProfileController.php         # Controller untuk profil user
├── Http/Middleware/
│   └── CheckMenuPermission.php    # Middleware untuk cek akses menu
│
database/
├── migrations/
│   └── create_admin_menus_table.php
├── seeders/
│   └── AdminMenuSeeder.php
│
resources/views/
├── admin/menu-permissions/
│   └── index.blade.php
├── admin/profile/
│   └── edit.blade.php
├── layouts/admin/
│   ├── menu.blade.php             # Dynamic menu rendering
│   └── menu-item.blade.php        # Menu item component
```

## Components and Interfaces

### 1. AdminMenu Model

```php
class AdminMenu extends Model
{
    protected $fillable = [
        'key',           // unique identifier
        'name',          // display name
        'route',         // route name
        'icon',          // SVG icon path
        'section',       // section/group name
        'order',         // display order
        'is_active',     // active status
    ];

    public function permissions(): HasMany;
    public function canAccess(string $role): bool;
    public static function getMenusForRole(string $role): Collection;
    public static function getGroupedMenusForRole(string $role): array;
    public static function clearCache(): void;
}
```

### 2. AdminMenuPermission Model

```php
class AdminMenuPermission extends Model
{
    protected $fillable = [
        'admin_menu_id',
        'role',
        'can_access',
    ];
}
```

### 3. CheckMenuPermission Middleware

Middleware yang memeriksa apakah user memiliki akses ke menu berdasarkan route yang diakses.

## Data Models

### AdminMenu Table Schema

| Column    | Type    | Description                   |
| --------- | ------- | ----------------------------- |
| id        | bigint  | Primary key                   |
| key       | string  | Unique identifier             |
| name      | string  | Display name                  |
| route     | string  | Route name                    |
| icon      | string  | SVG icon path (nullable)      |
| section   | string  | Section/group name (nullable) |
| order     | integer | Display order                 |
| is_active | boolean | Active status                 |

### AdminMenuPermission Table Schema

| Column        | Type    | Description                |
| ------------- | ------- | -------------------------- |
| id            | bigint  | Primary key                |
| admin_menu_id | bigint  | Foreign key to admin_menus |
| role          | string  | Role name                  |
| can_access    | boolean | Access permission          |

## Correctness Properties

### Property 1: Menu Access Control

_For any_ user with a specific role, they SHALL only see menus that have `can_access = true` for their role.

**Validates: Requirements 2.1**

### Property 2: Route Protection

_For any_ request to an admin route, if the user's role does not have access to the corresponding menu, the system SHALL return 403 forbidden.

**Validates: Requirements 2.2**

### Property 3: Profile Access

_For any_ authenticated admin user, they SHALL be able to access their profile page regardless of role.

**Validates: Requirements 3.5**

### Property 4: Password Validation

_For any_ password change request, the system SHALL verify the current password before allowing the change.

**Validates: Requirements 4.1**

## Error Handling

### Validation Errors

```php
// Profile validation
'name.required' => 'Nama wajib diisi.',
'email.required' => 'Email wajib diisi.',
'email.unique' => 'Email sudah digunakan.',

// Password validation
'current_password.required' => 'Password saat ini wajib diisi.',
'password.required' => 'Password baru wajib diisi.',
'password.confirmed' => 'Konfirmasi password tidak cocok.',
'password.min' => 'Password minimal 8 karakter.',
```

## Testing Strategy

### Unit Tests

-   Test AdminMenu model methods
-   Test permission checking logic

### Feature Tests

-   Test menu permission configuration
-   Test profile update
-   Test password change
-   Test route protection
