# winter-E-com - Quick Reference Guide

## 🚀 Quick Commands

### Start the Application

1. Start XAMPP/WAMP Control Panel
2. Start Apache & MySQL
3. Open: `http://localhost/Project/`

### Admin Access

- URL: `http://localhost/Project/login.php`
- Email: `admin@grocerygo.com`
- Password: `admin123`

### Database

- Database Name: `grocerygo`
- phpMyAdmin: `http://localhost/phpmyadmin`

---

## 📁 Important File Locations

### Configuration

- Database Config: `includes/config.php`
- Apache Config: `.htaccess`

### Main Pages

- Homepage: `index.php`
- Login: `login.php`
- Register: `register.php`
- Cart: `cart.php`
- Checkout: `checkout.php`

### Admin Panel

- Dashboard: `admin/dashboard.php`
- Products: `admin/products.php`
- Categories: `admin/categories.php`
- Users: `admin/users.php`
- Orders: `admin/orders.php`

### API Endpoints

- Search: `api/search.php?q={query}`
- Add to Cart: `api/add_to_cart.php` (POST)
- Remove from Cart: `api/remove_from_cart.php` (POST)
- Update Cart: `api/update_cart.php` (POST)

---

## 🔧 Common Tasks

### Add New Product (Admin)

1. Login as admin
2. Go to Admin → Products
3. Click "Add New Product"
4. Fill form and upload image
5. Click "Add Product"

### Add New Category (Admin)

1. Login as admin
2. Go to Admin → Categories
3. Fill form at top
4. Click "Add Category"

### Create User Account

1. Click "Register" in menu
2. Fill registration form
3. Click "Register"
4. Login with new credentials

### Place an Order (User)

1. Browse categories or search
2. Click "Add to Cart" on products
3. Click cart icon in menu
4. Review items
5. Click "Proceed to Checkout"
6. Click "Place Order"

---

## 🔑 Database Tables

### Users Table

- Stores user accounts
- Fields: id, username, email, password, role, created_at

### Products Table

- Stores all products
- Fields: id, category_id, name, price, description, stock, image, created_at

### Categories Table

- Product categories
- Fields: id, name, description, created_at

### Cart Table

- User shopping carts
- Fields: id, user_id, product_id, quantity, added_at

### Orders Table

- Completed orders
- Fields: id, user_id, total_amount, status, date

### Order Items Table

- Order line items
- Fields: id, order_id, product_id, quantity, price

---

## 🎨 CSS Classes Reference

### Buttons

- `.btn` - Base button
- `.btn-primary` - Blue button
- `.btn-secondary` - Gray button
- `.btn-danger` - Red button
- `.btn-sm` - Small size
- `.btn-block` - Full width

### Alerts

- `.alert` - Base alert
- `.alert-success` - Green success
- `.alert-error` - Red error
- `.alert-info` - Blue info

### Layout

- `.container` - Max-width wrapper
- `.page-header` - Page title section
- `.products-grid` - Product grid layout
- `.admin-layout` - Admin panel layout

---

## 🔒 Security Functions

### PHP Functions (config.php)

```php
sanitize_input($data)        // Clean user input
escape_output($data)          // Prevent XSS
is_logged_in()               // Check if user logged in
is_admin()                   // Check if user is admin
require_login()              // Force login
require_admin()              // Force admin access
get_user_id()                // Get current user ID
set_message($msg, $type)     // Set flash message
get_message()                // Retrieve flash message
```

---

## 📊 Sample Categories

1. Flash Sale
2. Winter Sale
3. Fruits & Vegetables
4. Meat & Fish
5. Cooking
6. Sauce & Pickles
7. Dairy & Eggs
8. Candy & Chocolate
9. Frozen & Canned
10. Diabetic Foods
11. Ice-creams

---

## 🐛 Troubleshooting Quick Fixes

### Can't login?

- Check MySQL is running
- Verify database imported
- Clear browser cookies

### Images not showing?

- Check `uploads/` folder exists
- Add default-product.jpg image
- Check file permissions

### Search not working?

- Enable JavaScript in browser
- Check console (F12) for errors
- Verify api/search.php exists

### Page is blank?

- Check PHP error log
- Verify PHP version 7.4+
- Check file paths in includes

---

## 📱 Responsive Testing

Test on these viewports:

- **Mobile:** 375x667 (iPhone SE)
- **Tablet:** 768x1024 (iPad)
- **Laptop:** 1366x768 (HD)
- **Desktop:** 1920x1080 (Full HD)

---

## ⚡ Performance Tips

1. Enable gzip compression (.htaccess)
2. Optimize images before upload
3. Use browser caching
4. Minimize database queries
5. Use PDO prepared statements (already implemented)

---

## 📈 Project Stats

- **Files:** 35+
- **Lines of Code:** 5000+
- **Tables:** 6
- **Categories:** 11
- **Sample Products:** 24
- **User Roles:** 2

---

## 🎓 Learning Resources

### PHP

- PDO Documentation
- Password Hashing
- Sessions Management

### Security

- OWASP Top 10
- SQL Injection Prevention
- XSS Protection

### Frontend

- Flexbox Guide
- CSS Grid
- Responsive Design
- JavaScript ES6+

---

## 📞 Quick Help

### Issue: Database error

**Check:** MySQL running, database exists, config.php settings

### Issue: 404 Not Found

**Check:** Files in htdocs/Project/, correct URL

### Issue: Can't add to cart

**Check:** Logged in as user (not admin), JavaScript enabled

### Issue: Images missing

**Check:** uploads/ folder, default-product.jpg exists

---

## ✅ Pre-Launch Checklist

- [ ] Database imported successfully
- [ ] Can access homepage
- [ ] Can login as admin
- [ ] Can register new user
- [ ] Can add products to cart
- [ ] Can place orders
- [ ] Search works
- [ ] Mobile responsive
- [ ] All categories visible
- [ ] Admin panel accessible

---

## 🔄 Regular Maintenance

### Daily

- Check error logs
- Monitor disk space (uploads)

### Weekly

- Backup database
- Review new users
- Check order status

### Monthly

- Update sample products
- Archive old orders
- Review security

---

## 🎯 Quick Actions

### Reset Admin Password

```sql
UPDATE users
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE email = 'admin@grocerygo.com';
-- Password becomes: admin123
```

### Clear All Carts

```sql
DELETE FROM cart;
```

### View Order Statistics

```sql
SELECT COUNT(*) as total_orders,
       SUM(total_amount) as total_revenue
FROM orders;
```

---

## 📝 Notes

- Always backup before making changes
- Test features after modifications
- Keep security patches updated
- Document custom changes
- Use version control (Git) recommended

---

**Last Updated:** November 24, 2025  
**Version:** 1.0.0  
**Status:** Production Ready
