# Design Document: Comprehensive Testing

## Overview

Dokumen ini menjelaskan arsitektur dan strategi testing untuk aplikasi Laravel company website. Testing akan menggunakan PHPUnit sebagai framework utama dengan pendekatan berlapis: Unit Tests, Feature Tests, dan Integration Tests.

## Architecture

```
tests/
├── TestCase.php                    # Base test case
├── Unit/
│   ├── Models/
│   │   ├── ProductTest.php
│   │   ├── UserTest.php
│   │   ├── NewsTest.php
│   │   ├── ComplaintTest.php
│   │   └── AuditTrailTest.php
│   └── Services/
│       └── ImageServiceTest.php
├── Feature/
│   ├── Public/
│   │   ├── HomePageTest.php
│   │   ├── AboutPagesTest.php
│   │   ├── ProductPagesTest.php
│   │   ├── NewsPagesTest.php
│   │   ├── AuctionPagesTest.php
│   │   ├── ReportPagesTest.php
│   │   └── StaticPagesTest.php
│   ├── Admin/
│   │   ├── AuthenticationTest.php
│   │   ├── DashboardTest.php
│   │   ├── ProductCRUDTest.php      # (existing)
│   │   ├── NewsCRUDTest.php
│   │   ├── AuctionCRUDTest.php
│   │   ├── ReportCRUDTest.php
│   │   ├── HeroSlideCRUDTest.php
│   │   ├── OfficeCRUDTest.php
│   │   ├── CareerCRUDTest.php
│   │   ├── BoardMemberCRUDTest.php
│   │   ├── ComplaintManagementTest.php
│   │   └── UserManagementTest.php
│   ├── Livewire/
│   │   ├── ContactFormTest.php
│   │   ├── ComplaintFormTest.php
│   │   └── NewsIndexTest.php
│   └── Security/
│       ├── RateLimitTest.php
│       └── MaintenanceModeTest.php
└── Integration/
    ├── EmailNotificationTest.php
    └── FileUploadTest.php
```

## Components and Interfaces

### 1. Base Test Case Enhancement

```php
// tests/TestCase.php
abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    protected function createSuperAdmin(): User
    {
        return User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    protected function createEditor(): User
    {
        return User::factory()->create([
            'role' => 'editor',
            'is_active' => true,
        ]);
    }

    protected function withoutSecurityMiddleware()
    {
        return $this->withoutMiddleware([
            \App\Http\Middleware\BlockSuspiciousRequests::class,
            \App\Http\Middleware\CheckMaintenanceMode::class,
            \App\Http\Middleware\LogVisitor::class,
            \App\Http\Middleware\OptimizeResponse::class,
        ]);
    }
}
```

### 2. Model Factories

```php
// database/factories/ProductFactory.php
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'type' => fake()->randomElement(['simpanan_syariah', 'pembiayaan_syariah', 'deposito']),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'interest_rate' => fake()->randomElement(['3% - 5%', '4% - 6%', '5% - 7%']),
            'features' => fake()->sentences(3),
            'requirements' => fake()->sentences(2),
            'benefits' => fake()->sentences(3),
            'image' => null,
            'image_alt' => fake()->sentence(3),
            'is_active' => true,
            'order_position' => fake()->numberBetween(1, 100),
        ];
    }
}

// database/factories/NewsFactory.php
class NewsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(5, true),
            'excerpt' => fake()->paragraph(),
            'featured_image' => null,
            'category' => fake()->randomElement(['berita', 'pengumuman', 'artikel']),
            'is_published' => true,
            'published_at' => now(),
            'author_id' => User::factory(),
            'author' => fake()->name(),
        ];
    }

    public function unpublished(): static
    {
        return $this->state(['is_published' => false, 'published_at' => null]);
    }

    public function scheduled(): static
    {
        return $this->state(['is_published' => true, 'published_at' => now()->addDays(7)]);
    }
}

// database/factories/ComplaintFactory.php
class ComplaintFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ticket_number' => Complaint::generateTicketNumber(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'identity_number' => fake()->numerify('################'),
            'type' => fake()->randomElement(['fraud', 'violation', 'ethics', 'abuse', 'safety', 'other']),
            'subject' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'reported_person' => fake()->name(),
            'reported_department' => fake()->word(),
            'incident_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'incident_location' => fake()->address(),
            'attachments' => null,
            'is_anonymous' => false,
            'status' => 'pending',
            'admin_notes' => null,
            'resolved_at' => null,
        ];
    }

    public function anonymous(): static
    {
        return $this->state(['is_anonymous' => true, 'name' => null, 'email' => null]);
    }
}
```

### 3. Unit Test Structure

```php
// tests/Unit/Models/UserTest.php
class UserTest extends TestCase
{
    /** @test */
    public function super_admin_role_is_correctly_identified(): void
    {
        $user = User::factory()->create(['role' => 'super_admin']);

        $this->assertTrue($user->isSuperAdmin());
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isEditor());
    }

    /** @test */
    public function has_role_accepts_string_and_array(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasRole(['admin', 'editor']));
        $this->assertFalse($user->hasRole('super_admin'));
    }
}

// tests/Unit/Models/ComplaintTest.php
class ComplaintTest extends TestCase
{
    /** @test */
    public function generates_unique_ticket_number_with_correct_format(): void
    {
        $ticketNumber = Complaint::generateTicketNumber();

        $this->assertMatchesRegularExpression('/^WBS-\d{8}-[A-Z0-9]{6}$/', $ticketNumber);
    }

    /** @test */
    public function status_label_returns_correct_indonesian_translation(): void
    {
        $complaint = Complaint::factory()->create(['status' => 'pending']);

        $this->assertEquals('Menunggu', $complaint->status_label);
    }
}
```

### 4. Feature Test Structure

```php
// tests/Feature/Public/HomePageTest.php
class HomePageTest extends TestCase
{
    /** @test */
    public function home_page_returns_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('frontend.pages.home');
    }
}

// tests/Feature/Admin/AuthenticationTest.php
class AuthenticationTest extends TestCase
{
    /** @test */
    public function unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function inactive_user_cannot_login(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }
}
```

## Data Models

### Test Data Relationships

```
User (admin/super_admin/editor)
├── News (author_id)
├── AuditTrail (user_id)
└── manages: Products, Auctions, Reports, etc.

Product
├── type: simpanan_syariah | pembiayaan_syariah | deposito
├── features: JSON array
├── requirements: JSON array
└── benefits: JSON array

News
├── author_id -> User
├── images -> NewsImage[]
└── is_published, published_at

Complaint
├── ticket_number: WBS-YYYYMMDD-XXXXXX
├── status: pending | in_review | investigating | resolved | closed
└── is_anonymous: boolean
```

## Correctness Properties

_A property is a characteristic or behavior that should hold true across all valid executions of a system-essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees._

### Property 1: Model Array Casting Consistency

_For any_ Product model with features, requirements, or benefits data, retrieving these attributes SHALL always return PHP arrays regardless of how the data was stored.

**Validates: Requirements 1.1**

### Property 2: Slug Generation Correctness

_For any_ Product or News model with a name/title, the generated slug SHALL be a valid URL-friendly string (lowercase, hyphenated, no special characters).

**Validates: Requirements 1.2**

### Property 3: User Role Method Consistency

_For any_ User with a specific role, the corresponding role check method (isSuperAdmin, isAdmin, isEditor) SHALL return true, and other role methods SHALL return false (except isAdmin which returns true for super_admin).

**Validates: Requirements 1.3, 1.4**

### Property 4: Ticket Number Format Validity

_For any_ generated Complaint ticket number, it SHALL match the pattern `WBS-YYYYMMDD-XXXXXX` where YYYYMMDD is the current date and XXXXXX is a 6-character alphanumeric string.

**Validates: Requirements 1.5**

### Property 5: Published Scope Filtering

_For any_ collection of News articles, the published scope SHALL only return articles where is_published is true AND published_at is less than or equal to the current timestamp.

**Validates: Requirements 1.6**

### Property 6: Audit Trail Recording

_For any_ model using the Auditable trait, creating or updating the model SHALL result in a corresponding AuditTrail record with correct action type and data.

**Validates: Requirements 1.7, 1.8**

### Property 7: Admin Route Protection

_For any_ admin route, an unauthenticated request SHALL result in a redirect to the login page.

**Validates: Requirements 3.1**

### Property 8: Inactive User Login Prevention

_For any_ User with is_active set to false, attempting to authenticate SHALL fail regardless of correct credentials.

**Validates: Requirements 3.5**

### Property 9: CRUD Operation Persistence

_For any_ valid model data submitted through admin CRUD operations, the data SHALL be correctly persisted to the database and retrievable with the same values.

**Validates: Requirements 4.1, 4.3, 4.5, 4.6, 4.7, 4.8, 4.9**

### Property 10: Validation Error Response

_For any_ invalid model data submitted through admin CRUD operations, the system SHALrn validation errors without persisting any data.

**Validates: Requirements 4.2**

### Property 11: Model Deletion Completeness

_For any_ model deleted through admin operations, the model SHALL no longer exist in the database after deletion.

**Validates: Requirements 4.4**

### Property 12: Factory Model Validity

_For any_ model factory, calling create() SHALL produce a valid model instance that passes all database constraints and model validation.

**Validates: Requirements 11.1-11.10**

## Error Handling

### Test Failure Scenarios

1. **Database Connection Errors**: Tests use in-memory SQLite, failures indicate schema issues
2. **Missing Factories**: Tests will fail with "Call to undefined method factory()"
3. **Missing Routes**: Tests will fail with "Route [name] not defined"
4. **Middleware Conflicts**: Use `withoutSecurityMiddleware()` helper to bypass security middleware in tests
5. **File System Errors**: Use `Storage::fake()` for file upload tests

### Error Response Patterns

```php
// Validation errors
$response->assertSessionHasErrors(['field_name']);
$response->assertInvalid(['field_name']);

// Authentication errors
$response->assertRedirect(route('login'));
$this->assertGuest();

// Authorization errors
$response->assertForbidden(); // 403
$response->assertUnauthorized(); // 401

// Not found errors
$response->assertNotFound(); // 404
```

## Testing Strategy

### Test Framework

-   **PHPUnit 11.x**: Primary testing framework (already installed)
-   **Laravel Testing Helpers**: HTTP tests, database assertions, mocking
-   **Faker**: Test data generation (already installed)
-   **Mockery**: Mocking framework for unit tests (already installed)

### Test Organization

1. **Unit Tests** (`tests/Unit/`): Test individual classes in isolation

    - Models: Test attributes, casts, scopes, relationships
    - Services: Test business logic methods

2. **Feature Tests** (`tests/Feature/`): Test HTTP endpoints and full request lifecycle

    - Public routes: Test accessibility and response
    - Admin routes: Test CRUD operations with authentication
    - Livewire: Test component interactions

3. **Integration Tests** (`tests/Integration/`): Test component interactions
    - Email: Test notification queuing
    - File Upload: Test storage operations

### Test Execution

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run specific test file
php artisan test tests/Unit/Models/UserTest.php

# Run with coverage
php artisan test --coverage
```

### Test Data Strategy

-   Use factories for all test data generation
-   Use `RefreshDatabase` trait for database isolation
-   Use `Storage::fake()` for file system isolation
-   Use `Mail::fake()` for email testing
-   Use `Queue::fake()` for queue testing

### Property-Based Testing Notes

While PHP doesn't have a widely-adopted property-based testing library like QuickCheck, the properties defined above will be implemented as parameterized tests using PHPUnit's data providers where applicable:

```php
/**
 * @dataProvider roleProvider
 */
public function test_role_methods_return_correct_values(string $role, array $expected): void
{
    $user = User::factory()->create(['role' => $role]);

    $this->assertEquals($expected['isSuperAdmin'], $user->isSuperAdmin());
    $this->assertEquals($expected['isAdmin'], $user->isAdmin());
    $this->assertEquals($expected['isEditor'], $user->isEditor());
}

public static function roleProvider(): array
{
    return [
        'super_admin' => ['super_admin', ['isSuperAdmin' => true, 'isAdmin' => true, 'isEditor' => false]],
        'admin' => ['admin', ['isSuperAdmin' => false, 'isAdmin' => true, 'isEditor' => false]],
        'editor' => ['editor', ['isSuperAdmin' => false, 'isAdmin' => false, 'isEditor' => true]],
    ];
}
```
