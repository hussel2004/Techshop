# Super Deals / Promotions Feature - Implementation Plan

## Overview
This plan outlines the implementation of a comprehensive promotions/super deals system for the TechShop e-commerce platform. The feature will allow admins to create percentage-based discounts on products or categories with time-based activation, and display these deals to customers through a dedicated page and visual indicators on product listings.

---

## User Requirements Summary

1. **Discount Type**: Percentage-based discounts only (e.g., 20% off, 50% off)
2. **Scope**: Apply promotions to individual products OR entire categories
3. **Time-Based**: Start and end dates for each promotion
4. **Admin Capabilities**:
   - Create/edit/delete promotions
   - Activate/deactivate promotions
   - Select which products/categories are included
5. **Customer Experience**:
   - Dedicated "Super Deals" page showing all active promotions
   - Badge/label on product cards indicating discount
   - Strike-through original price with discounted price displayed
6. **Automatic Application**: Discounts apply automatically (no promo codes required)

---

## Architecture Overview

### Database Design

#### 1. Promotions Table
**Migration**: `create_promotions_table.php`

```sql
Schema:
- id (PK, bigint, auto-increment)
- name (string, 255) - e.g., "Black Friday Sale", "Summer Discount"
- slug (string, 255, unique) - URL-friendly identifier
- description (text, nullable) - Marketing description
- discount_percentage (decimal 5,2) - e.g., 20.00 for 20%
- start_date (datetime) - When promotion becomes active
- end_date (datetime) - When promotion expires
- applies_to (enum: 'product', 'category') - What the promotion targets
- is_active (boolean, default true) - Manual on/off switch
- timestamps

Indexes:
- slug (unique)
- start_date, end_date (for date range queries)
- is_active (for filtering active promotions)
```

#### 2. Promotion-Product Pivot Table
**Migration**: `create_promotion_product_table.php`

```sql
Schema:
- id (PK)
- promotion_id (FK → promotions, cascade delete)
- product_id (FK → products, cascade delete)
- timestamps

Indexes:
- [promotion_id, product_id] (composite unique)
```

#### 3. Promotion-Category Pivot Table
**Migration**: `create_promotion_category_table.php`

```sql
Schema:
- id (PK)
- promotion_id (FK → promotions, cascade delete)
- category_id (FK → categories, cascade delete)
- timestamps

Indexes:
- [promotion_id, category_id] (composite unique)
```

### Model Architecture

#### 1. Promotion Model
**File**: `app/Models/Promotion.php`

**Attributes**:
```php
protected $fillable = [
    'name', 'slug', 'description', 'discount_percentage',
    'start_date', 'end_date', 'applies_to', 'is_active'
];

protected $casts = [
    'discount_percentage' => 'decimal:2',
    'start_date' => 'datetime',
    'end_date' => 'datetime',
    'is_active' => 'boolean',
];
```

**Relationships**:
- `belongsToMany(Product::class)` → products()
- `belongsToMany(Category::class)` → categories()

**Helper Methods**:
- `isActive(): bool` - Checks if promotion is active AND within date range
- `getAppliedProductsAttribute()` - Returns all products affected (direct + via categories)
- `scopeActive($query)` - Query scope for active promotions

**Boot Hook**:
- Auto-generate slug from name on creation

#### 2. Updates to Product Model
**File**: `app/Models/Product.php`

**New Relationships**:
- `belongsToMany(Promotion::class)` → promotions()

**New Accessors/Methods**:
- `getActivePromotionAttribute()` - Returns the first active promotion (prioritizes direct product promotions over category promotions)
- `getDiscountedPriceAttribute()` - Calculates price after discount
- `hasActivePromotionAttribute(): bool` - Quick check if product has any active promotion

#### 3. Updates to Category Model
**File**: `app/Models/Category.php`

**New Relationships**:
- `belongsToMany(Promotion::class)` → promotions()

**New Methods**:
- `getActivePromotionAttribute()` - Returns first active promotion for category

---

## Backend Implementation

### Controllers

#### 1. Public PromotionController
**File**: `app/Http/Controllers/PromotionController.php`

**Routes**:
- `GET /super-deals` → index()

**Methods**:

**index()**:
```php
Purpose: Display all active promotions with their products
Logic:
  1. Query Promotion::active() (is_active=true + date range)
  2. Eager load: products.primaryImage, categories
  3. For each promotion, collect all affected products:
     - If applies_to='product': direct products
     - If applies_to='category': all active products in those categories
  4. Return view with promotions collection

View: resources/views/promotions/index.blade.php
```

#### 2. Admin AdminPromotionController
**File**: `app/Http/Controllers/Admin/AdminPromotionController.php`

**Pattern**: Follow AdminProductController CRUD pattern

**Methods**:

1. **index()**: List all promotions with filtering
   ```php
   Filters:
   - search (name)
   - status (active/inactive/expired)
   - applies_to (product/category)

   Display:
   - Name, Type, Discount %, Date Range, Status, Actions
   - Pagination: 20 per page
   - Sort: Latest first

   View: admin/promotions/index.blade.php
   ```

2. **create()**: Show creation form
   ```php
   Data passed to view:
   - All categories (for category selector)
   - All active products (for product selector)

   View: admin/promotions/create.blade.php
   ```

3. **store()**: Create new promotion
   ```php
   Validation:
   - name: required|string|max:255
   - description: nullable|string
   - discount_percentage: required|numeric|min:0|max:100
   - start_date: required|date
   - end_date: required|date|after:start_date
   - applies_to: required|in:product,category
   - is_active: nullable|boolean
   - product_ids: required_if:applies_to,product|array
   - product_ids.*: exists:products,id
   - category_ids: required_if:applies_to,category|array
   - category_ids.*: exists:categories,id

   Logic:
   1. Generate slug from name
   2. Create Promotion record
   3. Attach products OR categories based on applies_to
   4. Redirect to index with success message
   ```

4. **edit()**: Show edit form
   ```php
   Data:
   - Promotion model (with existing relationships)
   - All categories
   - All active products

   View: admin/promotions/edit.blade.php
   ```

5. **update()**: Update existing promotion
   ```php
   Validation: Same as store()

   Logic:
   1. Update promotion fields
   2. Sync products/categories (removes old, adds new)
   3. Redirect to index
   ```

6. **destroy()**: Delete promotion
   ```php
   Logic:
   1. Delete promotion (pivot tables auto-delete via cascade)
   2. Redirect to index
   ```

7. **toggleStatus()**: AJAX method to activate/deactivate
   ```php
   Route: POST /admin/promotions/{id}/toggle-status

   Logic:
   1. Toggle is_active field
   2. Return JSON: {success: true, is_active: boolean}
   ```

### Routes

**File**: `routes/web.php`

```php
// Public Routes
Route::get('/super-deals', [PromotionController::class, 'index'])
    ->name('promotions.index');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... existing routes ...

    Route::resource('promotions', AdminPromotionController::class);
    Route::post('promotions/{promotion}/toggle-status',
        [AdminPromotionController::class, 'toggleStatus'])
        ->name('promotions.toggle-status');
});
```

### Request Validation

**File**: `app/Http/Requests/StorePromotionRequest.php`
**File**: `app/Http/Requests/UpdatePromotionRequest.php`

Following the FormRequest pattern for complex validation.

---

## Frontend Implementation

### Customer Views

#### 1. Super Deals Page
**File**: `resources/views/promotions/index.blade.php`

**Layout**: Extends layouts/app.blade.php

**Structure**:
```
- Hero Section
  - Gradient background (primary-600 to primary-700)
  - Title: "Super Deals"
  - Subtitle: "Limited time offers on your favorite products"

- Promotions Grid (if active promotions exist)
  - For each promotion:
    - Card with shadow-md, rounded-lg
    - Promotion name and description
    - Discount badge (large, colorful)
    - Date range display
    - Grid of affected products (4 columns)
      - Product card with:
        - Image
        - Name
        - Original price (strike-through)
        - Discounted price (text-green-600, bold)
        - Discount percentage badge
        - "View Details" button

- Empty State (if no active promotions)
  - Icon + message: "No active promotions at this time"
  - Link to browse all products
```

**Styling Pattern**: Follow home.blade.php hero + product grid patterns

#### 2. Update Product Cards (Partial)
**Files to modify**:
- `resources/views/home.blade.php` (featured products)
- `resources/views/products/index.blade.php` (product listing)
- `resources/views/products/show.blade.php` (product detail)

**Changes**:
```blade
@if($product->has_active_promotion)
  <!-- Discount Badge (top-right absolute positioning) -->
  <div class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
    {{ $product->active_promotion->discount_percentage }}% OFF
  </div>

  <!-- Price Display -->
  <div class="mt-2">
    <span class="text-gray-400 line-through text-sm">
      ${{ number_format($product->price, 2) }}
    </span>
    <span class="text-green-600 font-bold text-lg ml-2">
      ${{ number_format($product->discounted_price, 2) }}
    </span>
  </div>
@else
  <!-- Regular Price -->
  <span class="text-gray-900 font-bold text-lg">
    ${{ number_format($product->price, 2) }}
  </span>
@endif
```

#### 3. Navigation Update
**File**: `resources/views/layouts/app.blade.php`

Add "Super Deals" link to main navigation:
```blade
<a href="{{ route('promotions.index') }}"
   class="nav-link {{ request()->routeIs('promotions.*') ? 'active' : '' }}">
    Super Deals
</a>
```

### Admin Views

#### 1. Promotions List
**File**: `resources/views/admin/promotions/index.blade.php`

**Layout**: Extends layouts/app.blade.php (admin section)

**Structure**:
```
- Header
  - Title: "Manage Promotions"
  - "Create New Promotion" button (primary-600)

- Filter Bar
  - Search input (name)
  - Status dropdown (All, Active, Inactive, Expired)
  - Type dropdown (All, Product-based, Category-based)
  - Apply/Clear buttons

- Promotions Table
  Columns:
  - Name
  - Type (badge: "Product" or "Category")
  - Discount (e.g., "20%")
  - Date Range (start - end)
  - Status (badge: Active/Inactive/Expired with color coding)
  - Actions (Edit, Toggle Status, Delete)

- Pagination
```

**Status Badge Colors**:
- Active (green): within date range + is_active=true
- Inactive (gray): is_active=false
- Expired (red): past end_date

#### 2. Create/Edit Promotion Form
**Files**:
- `resources/views/admin/promotions/create.blade.php`
- `resources/views/admin/promotions/edit.blade.php`

**Form Structure**:
```
- Promotion Details Section
  - Name (text input)
  - Description (textarea)
  - Discount Percentage (number input, min=0, max=100)
  - Start Date (datetime-local input)
  - End Date (datetime-local input)
  - Is Active (checkbox)

- Apply To Section (radio buttons)
  - [ ] Individual Products
  - [ ] Product Categories

- Product Selector (shown if "Individual Products" selected)
  - Multi-select dropdown or checkboxes
  - Search filter
  - Shows: product name, category, price

- Category Selector (shown if "Product Categories" selected)
  - Multi-select or checkboxes
  - Shows: category name, product count

- Action Buttons
  - "Create Promotion" / "Update Promotion" (primary)
  - "Cancel" (secondary, back to index)
```

**JavaScript**:
- Toggle product/category selector based on "applies_to" radio selection
- Form validation (end_date > start_date, discount 0-100)

---

## Business Logic & Calculations

### Discount Priority Rules

When a product could have multiple promotions (direct + category):
1. **Direct product promotions** take precedence over category promotions
2. If multiple direct promotions exist, use the one with **highest discount_percentage**
3. Only ONE promotion applies per product at a time

### Active Promotion Detection

A promotion is "active" if ALL conditions are met:
1. `is_active = true`
2. `current_datetime >= start_date`
3. `current_datetime <= end_date`

### Price Calculation

```php
// In Product model
public function getDiscountedPriceAttribute() {
    $promotion = $this->active_promotion;

    if (!$promotion) {
        return $this->price;
    }

    $discount_amount = ($this->price * $promotion->discount_percentage) / 100;
    return $this->price - $discount_amount;
}
```

### Cart & Checkout Integration

**Important**: Discounted prices should be applied at checkout
- Update CartController to use `$product->discounted_price` instead of `$product->price`
- Store both original price and discount in order_items for record-keeping
- Optional: Add `discount_percentage` and `promotion_name` columns to order_items table

---

## Database Seeder (Optional)

**File**: `database/seeders/PromotionSeeder.php`

Create sample promotions:
1. "Weekend Flash Sale" - 30% off on iPhone 15 Pro Max (product-based)
2. "Mobile Madness" - 20% off all Mobile Phones (category-based)
3. "Accessory Bonanza" - 15% off Accessories category (category-based)

---

## File Changes Summary

### New Files to Create

**Migrations**:
1. `database/migrations/YYYY_MM_DD_create_promotions_table.php`
2. `database/migrations/YYYY_MM_DD_create_promotion_product_table.php`
3. `database/migrations/YYYY_MM_DD_create_promotion_category_table.php`

**Models**:
1. `app/Models/Promotion.php`

**Controllers**:
1. `app/Http/Controllers/PromotionController.php`
2. `app/Http/Controllers/Admin/AdminPromotionController.php`

**Requests**:
1. `app/Http/Requests/StorePromotionRequest.php`
2. `app/Http/Requests/UpdatePromotionRequest.php`

**Views - Customer**:
1. `resources/views/promotions/index.blade.php`

**Views - Admin**:
1. `resources/views/admin/promotions/index.blade.php`
2. `resources/views/admin/promotions/create.blade.php`
3. `resources/views/admin/promotions/edit.blade.php`

**Seeders (Optional)**:
1. `database/seeders/PromotionSeeder.php`

### Files to Modify

**Models**:
1. `app/Models/Product.php` - Add promotions relationship + accessors
2. `app/Models/Category.php` - Add promotions relationship

**Routes**:
1. `routes/web.php` - Add promotion routes

**Views**:
1. `resources/views/layouts/app.blade.php` - Add "Super Deals" to navigation
2. `resources/views/home.blade.php` - Update product cards with discount badges
3. `resources/views/products/index.blade.php` - Update product cards
4. `resources/views/products/show.blade.php` - Update price display
5. `resources/views/admin/dashboard.blade.php` - Add promotions quick link (optional)

**Controllers (Optional)**:
1. `app/Http/Controllers/CartController.php` - Use discounted_price
2. `app/Http/Controllers/CheckoutController.php` - Store promotion details in order

---

## Implementation Steps (Recommended Order)

### Phase 1: Database & Models (Backend Foundation)
1. Create migrations for promotions, promotion_product, promotion_category tables
2. Run migrations
3. Create Promotion model with relationships and helper methods
4. Update Product model (add relationships + accessors)
5. Update Category model (add relationships)
6. Test relationships in tinker

### Phase 2: Admin CRUD (Admin Management)
1. Create AdminPromotionController with CRUD methods
2. Create StorePromotionRequest and UpdatePromotionRequest validation classes
3. Add admin routes to web.php
4. Create admin views (index, create, edit)
5. Test creating/editing/deleting promotions

### Phase 3: Public Display (Customer Experience)
1. Create PromotionController with index method
2. Add public route (/super-deals)
3. Create promotions/index.blade.php view
4. Update navigation in layouts/app.blade.php
5. Test Super Deals page displays active promotions

### Phase 4: Product Integration (Visual Indicators)
1. Update product card displays in home.blade.php
2. Update product card displays in products/index.blade.php
3. Update product detail in products/show.blade.php
4. Test badges and strike-through pricing appear correctly

### Phase 5: Cart Integration (Optional but Recommended)
1. Update CartController to use discounted prices
2. Update checkout flow to record promotion details
3. Test end-to-end purchase with promotion

### Phase 6: Seeding & Testing
1. Create PromotionSeeder with sample data
2. Run seeder
3. Full system test (create promotion → view on Super Deals → see badges → purchase)

---

## Testing Checklist

### Admin Tests
- [ ] Create promotion for individual products
- [ ] Create promotion for categories
- [ ] Edit existing promotion (change dates, products, percentage)
- [ ] Delete promotion
- [ ] Toggle promotion active/inactive status
- [ ] Verify validation errors (end_date < start_date, discount > 100, etc.)
- [ ] Filter promotions by status and type
- [ ] Search promotions by name

### Customer Tests
- [ ] View Super Deals page with active promotions
- [ ] Verify products display original + discounted price
- [ ] Verify discount badge appears on product cards
- [ ] Verify correct discount percentage calculation
- [ ] Verify expired promotions don't show
- [ ] Verify inactive promotions don't show
- [ ] Verify category-based promotions apply to all products in category
- [ ] Verify direct product promotions override category promotions

### Edge Cases
- [ ] Product belongs to category with promotion + has direct promotion (direct wins)
- [ ] Promotion becomes active/expires (date range changes)
- [ ] Promotion with 0% discount (edge case)
- [ ] Promotion with 100% discount (free product)
- [ ] Delete product that has promotion (cascade handling)
- [ ] Delete category that has promotion (cascade handling)

---

## UI/UX Considerations

### Design Consistency
- Use primary indigo color (#6366f1) for CTAs
- Use red (#ef4444) for discount badges
- Use green (#10b981) for discounted prices
- Follow Tailwind spacing patterns (gap-4, gap-6, p-4, p-6)
- Shadow-md with hover:shadow-xl on interactive cards
- Rounded-lg corners throughout

### Accessibility
- Use semantic HTML (headings, lists, buttons vs divs)
- Ensure sufficient color contrast for discount badges
- Add aria-labels for screen readers on discount badges
- Keyboard navigation support for product selectors in admin

### Responsive Design
- Mobile-first approach
- Product grid: 1 column (mobile), 2 (tablet), 4 (desktop)
- Admin tables: horizontal scroll on mobile
- Form inputs: full-width on mobile

---

## Future Enhancements (Post-MVP)

1. **Promo Codes**: Add code field for manual entry at checkout
2. **Usage Limits**: Limit number of times a promotion can be used
3. **User-Specific**: Target promotions to specific user groups
4. **Stacking**: Allow multiple promotions to stack (multiplicative)
5. **BOGO**: Buy-one-get-one deals
6. **Minimum Purchase**: Require minimum cart value for promotion
7. **Analytics**: Track promotion performance (views, conversions, revenue)
8. **Email Notifications**: Notify customers when promotions start
9. **Countdown Timers**: Display time remaining on promotions
10. **Featured Promotions**: Highlight specific promotions on homepage

---

## Estimated Complexity

**Time Estimate**: Medium complexity feature
- Database setup: ~1 hour
- Model relationships: ~1 hour
- Admin CRUD: ~3 hours
- Public display: ~2 hours
- Product integration: ~2 hours
- Testing: ~2 hours

**Total**: ~11 hours for complete implementation

**Dependencies**:
- Existing Laravel setup ✓
- Tailwind CSS ✓
- Authentication system ✓
- Admin middleware ✓

---

## Notes

- All timestamps use Laravel's `created_at` and `updated_at` conventions
- Soft deletes NOT implemented (can be added if needed)
- Image uploads for promotions NOT included (can be added later)
- Email notifications NOT included in MVP
- All monetary calculations maintain 2 decimal precision
- Slugs auto-generated using `Str::slug()` helper
- Follows existing codebase patterns (fillable arrays, casts, FormRequests)

---

This plan is ready for implementation. Each phase can be implemented incrementally and tested independently.
