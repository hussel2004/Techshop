# 🔐 Admin Panel Guide - TechShop E-Commerce

## Overview

The admin panel provides complete control over your e-commerce store. Manage products, categories, orders, and view analytics from a centralized dashboard.

---

## Accessing the Admin Panel

**URL**: `http://localhost:8000/admin/dashboard`

**Admin Credentials** (from seeder):
- Email: `admin@techshop.com`
- Password: `password`

**Important**: Only users with `is_admin = true` in the database can access the admin panel.

---

## Admin Features

### 1. Dashboard (`/admin/dashboard`)

**Overview Statistics:**
- Total Products count
- Total Categories count
- Total Orders count
- Total Revenue (from delivered orders)

**Quick Actions:**
- Add New Product
- Add New Category
- View All Orders
- View Store Front  

**Recent Orders Table:**
- Last 10 orders with customer info
- Order status and payment status
- Quick links to order details

**Low Stock Alert:**
- Products with less than 10 items in stock
- Quick link to update each product

---

### 2. Product Management (`/admin/products`)

#### Product List View
**Features:**
- Search products by name or description
- Filter by category
- Filter by status (active/inactive)
- View product details: name, category, price, stock, status
- Quick actions: View, Edit, Delete

**Columns:**
- Product thumbnail (placeholder)
- Product name and description preview
- Category
- Price
- Stock quantity (red warning if < 10)
- Status badge (Active/Inactive)
- Featured star icon
- Action buttons

#### Add New Product (`/admin/products/create`)
**Required Fields:**
- Product Name * (auto-generates slug)
- Category * (dropdown)
- Description * (textarea)
- Price * (decimal, minimum 0)
- Stock Quantity * (integer, minimum 0)

**Optional Fields:**
- Brand
- Is Featured (checkbox - shows on homepage)
- Is Active (checkbox - visible in store)

**Validation:**
- Name required, max 255 characters
- Category must exist
- Price must be positive number
- Stock must be positive integer

#### Edit Product (`/admin/products/{id}/edit`)
- All fields pre-filled with current values
- Same validation as create
- Delete button (with confirmation)
- Updates product slug when name changes

---

### 3. Category Management (`/admin/categories`)

#### Category Grid View
**Display:**
- Cards with gradient backgrounds
- Category name and description
- Product count
- Edit and Delete buttons

**Features:**
- Pagination (12 per page)
- Product count badge
- Link to view products in category

#### Add New Category (`/admin/categories/create`)
**Required Fields:**
- Category Name * (auto-generates slug)

**Optional Fields:**
- Description

**Validation:**
- Name required, max 255, unique
- Auto-generates URL-friendly slug

#### Edit Category (`/admin/categories/{id}/edit`)
- Pre-filled form
- Shows product count statistics
- Delete button (WARNING: cascades to products)
- Slug updates automatically

**Important**: Deleting a category will delete all products in that category due to foreign key cascade.

---

### 4. Order Management (`/admin/orders`)

#### Order List View
**Features:**
- Search by order number
- Filter by order status
- Filter by payment status
- Pagination

**Columns:**
- Order Number (clickable link)
- Customer (name and email)
- Order Date
- Total Amount
- Order Status badge (color-coded)
- Payment Status badge
- Action buttons (View, Edit Status)

**Status Colors:**
- Pending: Gray
- Processing: Yellow
- Shipped: Blue
- Delivered: Green
- Cancelled: Red

**Payment Status Colors:**
- Pending: Yellow
- Paid: Green
- Failed: Red

#### Order Details (`/admin/orders/{id}/show`)
**Displays:**
- Order status overview (4 stat cards)
- Complete list of ordered items
- Order summary (subtotal, shipping, tax, total)
- Customer information (name, email, phone)
- Shipping address
- Action buttons (Update Status, View as Customer)

#### Update Order Status (`/admin/orders/{id}/edit`)
**Editable Fields:**
- Order Status (dropdown)
  - Options: pending, processing, shipped, delivered, cancelled
- Payment Status (dropdown)
  - Options: pending, paid, failed
- Admin Notes (textarea - internal use)

**Features:**
- Order summary display (read-only)
- Customer and order date info
- Save updates with validation

**Note**: Orders cannot be deleted, only cancelled.

---

## Routes Structure

```
/admin/dashboard              - Admin dashboard
/admin/products               - Product list
/admin/products/create        - Add new product
/admin/products/{id}          - View product (redirects to edit)
/admin/products/{id}/edit     - Edit product
/admin/products/{id}          - Delete product (DELETE)

/admin/categories             - Category list
/admin/categories/create      - Add new category
/admin/categories/{id}/edit   - Edit category
/admin/categories/{id}        - Delete category (DELETE)

/admin/orders                 - Order list
/admin/orders/{id}            - View order details
/admin/orders/{id}/edit       - Update order status
```

---

## Middleware & Security

### AdminMiddleware
**Location**: `app/Http/Middleware/AdminMiddleware.php`

**Function**: Checks if authenticated user has `is_admin = true`

**Response**: Returns 403 Forbidden if:
- User is not authenticated
- User does not have admin privileges

**Registration**: `bootstrap/app.php` - aliased as 'admin'

### Route Protection
All admin routes are protected by:
```php
Route::middleware(['auth', 'admin'])
```

This ensures:
1. User must be logged in (`auth`)
2. User must be an admin (`admin`)

---

## Admin User Setup

### Creating Admin Users

#### Method 1: Database Seeder (Existing)
The `UserSeeder` already creates an admin user:
```php
User::create([
    'name' => 'Admin User',
    'email' => 'admin@techshop.com',
    'password' => Hash::make('password'),
    'is_admin' => true,
]);
```

#### Method 2: Tinker (Manual)
```bash
php artisan tinker
```
```php
\App\Models\User::create([
    'name' => 'Your Name',
    'email' => 'youremail@example.com',
    'password' => \Hash::make('your-password'),
    'is_admin' => true,
]);
```

#### Method 3: Direct Database Update
Update an existing user to admin:
```sql
UPDATE users SET is_admin = 1 WHERE email = 'user@example.com';
```

---

## Controller Methods

### DashboardController
- `index()` - Shows dashboard with stats and recent orders

### AdminProductController
- `index(Request $request)` - List products with filters
- `create()` - Show create form
- `store(Request $request)` - Save new product
- `edit(Product $product)` - Show edit form
- `update(Request $request, Product $product)` - Update product
- `destroy(Product $product)` - Delete product

### AdminCategoryController
- `index()` - List categories
- `create()` - Show create form
- `store(Request $request)` - Save new category
- `edit(Category $category)` - Show edit form
- `update(Request $request, Category $category)` - Update category
- `destroy(Category $category)` - Delete category (cascades to products)

### AdminOrderController
- `index(Request $request)` - List orders with filters
- `show(Order $order)` - View order details
- `edit(Order $order)` - Show status update form
- `update(Request $request, Order $order)` - Update order status
- `destroy(Order $order)` - Disabled (orders cannot be deleted)

---

## Views Structure

```
resources/views/admin/
├── dashboard.blade.php           # Main dashboard
├── products/
│   ├── index.blade.php          # Product list
│   ├── create.blade.php         # Add product form
│   └── edit.blade.php           # Edit product form
├── categories/
│   ├── index.blade.php          # Category grid
│   ├── create.blade.php         # Add category form
│   └── edit.blade.php           # Edit category form
└── orders/
    ├── index.blade.php          # Order list
    ├── show.blade.php           # Order details
    └── edit.blade.php           # Update order status
```

All views extend `layouts.app` and use Tailwind CSS.

---

## Tailwind CSS Classes

### Color Scheme
- Primary: Indigo/Purple (`primary-600`, `primary-700`)
- Success: Green (`green-600`, `green-700`)
- Warning: Yellow (`yellow-600`, `yellow-700`)
- Danger: Red (`red-600`, `red-700`)
- Info: Blue (`blue-600`, `blue-700`)

### Common Components
- **Buttons**: `bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-lg`
- **Forms**: `w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500`
- **Badges**: `inline-block px-3 py-1 rounded-full text-xs font-medium`
- **Cards**: `bg-white rounded-lg shadow-md p-6`

---

## Flash Messages

The admin panel uses Laravel's session flash messages:

**Success Messages:**
- Product created/updated/deleted successfully
- Category created/updated/deleted successfully
- Order updated successfully

**Error Messages:**
- Validation errors (shown per field)
- Order deletion attempt (not allowed)

**Display**: Messages appear at the top of pages via `layouts/app.blade.php`

---

## Best Practices

### Product Management
1. Always set stock quantity when adding products
2. Mark popular items as "Featured" to show on homepage
3. Inactive products won't appear in store but remain in database
4. Keep product names concise (auto-generates clean URLs)

### Category Management
5. Use descriptive category names
6. Be cautious deleting categories (cascades to products)
7. Keep category descriptions brief

### Order Management
8. Update order status as orders progress
9. Mark payment as "paid" after confirming payment
10. Use admin notes for internal tracking
11. Orders cannot be deleted (data integrity)

### Stock Management
12. Monitor low stock alerts on dashboard
13. Update stock quantities after receiving inventory
14. Set products to inactive instead of deleting if temporarily unavailable

---

## Workflow Examples

### Adding a New Product
1. Navigate to `/admin/products`
2. Click "+ Add New Product"
3. Fill in all required fields
4. Check "Featured" if it should appear on homepage
5. Check "Is Active" to make it visible in store
6. Click "Create Product"
7. Product appears in list and store

### Processing an Order
1. New order appears on dashboard
2. Click order number to view details
3. Verify order items and customer info
4. Click "Update Status"
5. Change status to "Processing"
6. Mark payment as "Paid" after confirming
7. Update to "Shipped" when package is sent
8. Update to "Delivered" when confirmed

### Managing Stock
1. Check dashboard for low stock alerts
2. Click "Update →" on low stock items
3. Increase stock quantity
4. Click "Update Product"
5. Product removed from low stock list

---

## Troubleshooting

### "403 Forbidden" when accessing admin panel
**Cause**: User doesn't have admin privileges
**Fix**: Update user's `is_admin` field to `true` in database

### Can't delete category
**Cause**: Category has products
**Fix**: This is intentional - deleting category will also delete all products. Confirm the action.

### Low stock alert not showing
**Cause**: No products with stock < 10
**Fix**: This is correct behavior, alert only shows when needed

### Order doesn't appear after checkout
**Cause**: Check if order was created in database
**Fix**: Review CheckoutController logs for errors

---

## Database Queries

### Find all admins
```sql
SELECT * FROM users WHERE is_admin = 1;
```

### Products low on stock
```sql
SELECT * FROM products WHERE stock_quantity < 10 ORDER BY stock_quantity ASC;
```

### Recent orders
```sql
SELECT * FROM orders ORDER BY created_at DESC LIMIT 10;
```

### Revenue by status
```sql
SELECT status, SUM(total_amount) as revenue
FROM orders
GROUP BY status;
```

---

## Future Enhancements

Potential additions to admin panel:
- [ ] Sales analytics and charts
- [ ] Product image upload
- [ ] Bulk product import/export
- [ ] Email notifications for orders
- [ ] Customer management
- [ ] Inventory history tracking
- [ ] Coupon/discount management
- [ ] Product reviews moderation
- [ ] Shipping label printing
- [ ] Advanced reporting

---

## Support

For issues or questions:
- Check [QUICKSTART.md](QUICKSTART.md) for basic setup
- Review [PROJECT_COMPLETE.md](PROJECT_COMPLETE.md) for full project overview
- See [ECOMMERCE_GUIDE.md](ECOMMERCE_GUIDE.md) for customer-facing features

---

## Summary

The admin panel is a complete management system for your e-commerce store with:
- ✅ Dashboard with analytics
- ✅ Full product CRUD operations
- ✅ Category management
- ✅ Order tracking and status updates
- ✅ Low stock alerts
- ✅ Search and filtering
- ✅ Responsive Tailwind CSS design
- ✅ Role-based access control
- ✅ Flash message feedback

**Admin URL**: `http://localhost:8000/admin/dashboard`
**Login**: `admin@techshop.com` / `password`

Happy managing! 🚀
