# GroceryGo - Quick Installation Guide

## 🚀 Quick Start (5 Minutes)

### Step 1: Prerequisites

Make sure you have XAMPP, WAMP, or LAMP installed on your system.

### Step 2: Extract Files

Extract the project folder to:

- **Windows (XAMPP):** `C:\xampp\htdocs\Project`
- **Windows (WAMP):** `C:\wamp64\www\Project`
- **Linux:** `/var/www/html/Project`

### Step 3: Start Services

1. Open XAMPP/WAMP Control Panel
2. Start **Apache** and **MySQL** services
3. Wait for both to show green/running status

### Step 4: Create Database

1. Open browser and go to: `http://localhost/phpmyadmin`
2. Click "New" on the left sidebar
3. Enter database name: `grocerygo`
4. Click "Create"
5. Click on the `grocerygo` database
6. Click "Import" tab at the top
7. Click "Choose File" and select `database.sql` from your project folder
8. Click "Go" at the bottom
9. Wait for success message

### Step 5: Configure (Optional)

If using different MySQL credentials:

1. Open `includes/config.php`
2. Update lines 3-5 with your credentials:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### Step 6: Add Default Product Image

1. Find or create a default product image (400x400px recommended)
2. Save it as `default-product.jpg`
3. Place it in the `uploads/` folder
4. Or use any placeholder image from the internet

### Step 7: Open Application

1. Open browser
2. Go to: `http://localhost/Project/`
3. You should see the GroceryGo homepage

### Step 8: Login

**Admin Access:**

- Email: `admin@grocerygo.com`
- Password: `admin123`

**Create User Account:**

- Click "Register" in the top menu
- Fill in the form
- Login with your new credentials

## ✅ Verification Checklist

- [ ] Apache is running
- [ ] MySQL is running
- [ ] Database `grocerygo` created
- [ ] Database imported successfully (26 queries executed)
- [ ] Can access http://localhost/Project/
- [ ] Can see GroceryGo homepage
- [ ] Can login as admin
- [ ] Can register new user

## 🔧 Common Issues & Solutions

### Issue 1: "Database connection failed"

**Solution:**

- Check if MySQL is running in XAMPP/WAMP
- Verify database name is `grocerygo`
- Check credentials in `includes/config.php`

### Issue 2: "Page not found" or 404 error

**Solution:**

- Verify files are in correct folder: `htdocs/Project/`
- Check URL: `http://localhost/Project/` (not just `localhost`)
- Restart Apache

### Issue 3: Blank white page

**Solution:**

- Check if PHP is enabled in Apache
- Look for PHP errors in XAMPP/error_log
- Verify PHP version is 7.4 or higher

### Issue 4: Images not showing

**Solution:**

- Create `uploads/` folder in project root
- Add `default-product.jpg` image
- Check folder permissions (Windows: usually no issue)

### Issue 5: Search not working

**Solution:**

- Check if JavaScript is enabled in browser
- Open browser console (F12) for errors
- Verify `api/` folder exists

## 📱 Test the Application

### Test as Regular User:

1. Register a new account
2. Browse categories
3. Search for a product
4. Add items to cart
5. View cart
6. Proceed to checkout
7. Place an order
8. View order history in profile

### Test as Admin:

1. Login with admin credentials
2. View dashboard statistics
3. Add a new product
4. Edit existing product
5. Add a new category
6. View users list
7. View orders

## 🎯 Next Steps

After successful installation:

1. Add more products through admin panel
2. Upload real product images
3. Customize categories
4. Test all features
5. Create user accounts for testing

## 📞 Need Help?

If you encounter issues:

1. Check the main README.md file
2. Review the troubleshooting section
3. Verify all installation steps
4. Check file permissions
5. Look at browser console for JavaScript errors
6. Check PHP error logs in XAMPP

## 🎉 Success!

If you can login and see the homepage, congratulations! Your GroceryGo installation is complete.

**Important:** For production use, remember to:

- Change default admin password
- Update database credentials
- Enable SSL/HTTPS
- Implement additional security measures
- Backup your database regularly

---

**Installation Time:** ~5 minutes  
**Difficulty:** Easy  
**Support:** Check README.md for detailed documentation
