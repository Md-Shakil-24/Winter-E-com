# winter-E-com - Winter Accessories Store

A complete, secure, and responsive e-commerce web application for selling premium winter accessories built with PHP, MySQL, HTML, CSS, and Vanilla JavaScript.

![License](https://img.shields.io/badge/license-MIT-blue.svg) ![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg) ![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)

## 🌟 Features

### User Features

- **User Authentication** - Secure registration and login system with password hashing
- **Browse Winter Accessories** - Multiple product categories (Jackets, Scarves, Gloves, Boots, and more)
- **Product Search** - Real-time search functionality with instant results
- **Shopping Cart** - Add/remove items, update quantities
- **Checkout System** - Simple checkout process with order tracking
- **User Profile** - Update profile information and change password
- **Order History** - View all past orders

### Admin Features

- **Dashboard** - Overview with statistics (users, products, orders, revenue)
- **Product Management** - Full CRUD operations for winter accessories
- **Category Management** - Add, edit, delete accessory categories
- **User Management** - View all registered users
- **Order Management** - View and track all orders
- **Image Upload** - Upload product images

### Security Features

- Password hashing using `password_hash()` and `password_verify()`
- PHP Sessions for user state management
- SQL injection prevention with prepared statements (PDO)
- XSS protection with output escaping
- Input sanitization and validation
- Role-based access control (Admin/User)

### Design Features

- Winter-themed design with snowflake animations
- Fully responsive (Mobile, Tablet, Desktop)
- Clean white space and consistent layout
- Smooth hover effects and animations
- Modern CSS (Flexbox & Grid)
- FontAwesome icons

## 📋 Requirements

- **Web Server:** Apache (XAMPP, WAMP, or LAMP)
- **PHP:** Version 7.4 or higher
- **MySQL:** Version 5.7 or higher
- **Browser:** Any modern web browser

## 🚀 Installation

### Step 1: Download and Extract

1. Download or clone this repository
2. Extract the files to your web server directory:
   - **XAMPP:** `C:\xampp\htdocs\Project`
   - **WAMP:** `C:\wamp64\www\Project`
   - **LAMP:** `/var/www/html/Project`

### Step 2: Create Database

1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database named `grocerygo`
3. Import the database:
   - Click on the `grocerygo` database
   - Go to the "Import" tab
   - Choose the file `database.sql` from the project folder
   - Click "Go" to import

### Step 3: Configure Database Connection

1. Open `includes/config.php`
2. Update database credentials if needed:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Your MySQL username
define('DB_PASS', '');            // Your MySQL password
define('DB_NAME', 'grocerygo');
```

### Step 4: Create Uploads Folder

1. Create a folder named `uploads` in the project root directory
2. Make sure it has write permissions (for Windows, no action needed)
3. Add a placeholder image named `default-product.jpg` in the uploads folder

### Step 5: Start Your Server

1. Start Apache and MySQL from XAMPP/WAMP/LAMP control panel
2. Open your browser and navigate to:
   - `http://localhost/Project/`

## 🔑 Default Admin Credentials

Use these credentials to login as admin:

- **Email:** admin@grocerygo.com
- **Password:** admin123

## 📁 Project Structure

```
Project/
│
├── admin/                      # Admin panel files
│   ├── dashboard.php          # Admin dashboard
│   ├── products.php           # Product management
│   ├── product_add.php        # Add new product
│   ├── product_edit.php       # Edit product
│   ├── categories.php         # Category management
│   ├── users.php              # User management
│   ├── orders.php             # Order management
│   ├── order_details.php      # Order details view
│   └── sidebar.php            # Admin sidebar navigation
│
├── api/                        # API endpoints
│   ├── search.php             # Product search API
│   ├── add_to_cart.php        # Add to cart API
│   ├── remove_from_cart.php   # Remove from cart API
│   └── update_cart.php        # Update cart API
│
├── css/                        # Stylesheets
│   ├── style.css              # Main stylesheet
│   └── additional.css         # Additional styles
│
├── includes/                   # PHP includes
│   ├── config.php             # Database config & functions
│   ├── header.php             # Header template
│   └── footer.php             # Footer template
│
├── js/                         # JavaScript files
│   └── main.js                # Main JavaScript file
│
├── uploads/                    # Product images
│   └── default-product.jpg    # Default product image
│
├── index.php                   # Homepage
├── login.php                   # Login page
├── register.php                # Registration page
├── logout.php                  # Logout handler
├── categories.php              # All categories page
├── category.php                # Single category page
├── cart.php                    # Shopping cart
├── checkout.php                # Checkout page
├── profile.php                 # User profile
├── database.sql                # Database schema
└── README.md                   # This file
```

## 🎨 Technologies Used

- **Backend:** PHP (PDO for database)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Icons:** FontAwesome 6.4.0
- **Design:** Flexbox, CSS Grid, Media Queries

## 🔒 Security Features Implemented

1. **Password Security**

   - Passwords hashed using `password_hash()` with bcrypt
   - Password verification using `password_verify()`

2. **SQL Injection Prevention**

   - All queries use PDO prepared statements
   - User input is parameterized

3. **XSS Protection**

   - All output escaped using `htmlspecialchars()`
   - Custom `escape_output()` function

4. **Session Security**

   - Secure session management
   - Role-based access control
   - Session validation on every page

5. **Input Validation**
   - Server-side validation for all forms
   - Client-side validation with JavaScript
   - Data sanitization before processing

## 📱 Responsive Design

The application is fully responsive and works on:

- 📱 Mobile devices (320px and up)
- 📱 Tablets (768px and up)
- 💻 Laptops (992px and up)
- 🖥️ Desktops (1200px and up)

## 🛠️ Main Functions

### User Functions

- Register new account
- Login/Logout
- Browse products by category
- Real-time product search
- Add products to cart
- Update cart quantities
- Remove items from cart
- Checkout and place orders
- View order history
- Update profile information
- Change password

### Admin Functions

- View dashboard statistics
- Add/Edit/Delete products
- Add/Edit/Delete categories
- View all users
- View all orders
- View order details
- Upload product images

## 🔍 Database Schema

### Tables

1. **users** - User accounts (id, username, email, password, role, created_at)
2. **categories** - Product categories (id, name, description, created_at)
3. **products** - Products (id, category_id, name, price, description, stock, image, created_at)
4. **cart** - Shopping cart (id, user_id, product_id, quantity, added_at)
5. **orders** - Orders (id, user_id, total_amount, status, date)
6. **order_items** - Order items (id, order_id, product_id, quantity, price)

### Relationships

- Products → Categories (Foreign Key)
- Cart → Users & Products (Foreign Keys)
- Orders → Users (Foreign Key)
- Order Items → Orders & Products (Foreign Keys)

## 🎯 Sample Data

The database includes sample data:

- 1 Admin user (admin@grocerygo.com / admin123)
- 11 Winter Accessory categories (Jackets, Scarves, Gloves, Boots, Hats, Sweaters, Thermal Layers, Accessories, Snow Sports, Bags, Sale)
- 44 Premium winter products across all categories

## ⚙️ Configuration

### Database Configuration

Edit `includes/config.php` to change database settings:

```php
define('DB_HOST', 'localhost');     // Database host
define('DB_USER', 'root');          // Database username
define('DB_PASS', '');              // Database password
define('DB_NAME', 'grocerygo');     // Database name
```

### Upload Directory

- Default upload directory: `uploads/`
- Maximum file size: Set in PHP configuration (php.ini)
- Allowed formats: JPG, JPEG, PNG, GIF

## 📝 Usage Guide

### For Users

1. Register a new account or use demo credentials
2. Browse categories or use the search bar
3. Add products to your cart
4. Go to cart and update quantities if needed
5. Proceed to checkout
6. Place your order
7. View orders in your profile

### For Admins

1. Login with admin credentials
2. Access admin panel from navigation
3. Manage products, categories, users, and orders
4. Upload product images when adding/editing products
5. View statistics on the dashboard

## 🐛 Troubleshooting

### Database Connection Error

- Check if MySQL is running
- Verify database credentials in `includes/config.php`
- Ensure `grocerygo` database exists

### Image Upload Not Working

- Check if `uploads/` folder exists
- Verify folder has write permissions
- Check PHP `upload_max_filesize` and `post_max_size` settings

### Session Issues

- Clear browser cookies and cache
- Check if session.save_path is writable
- Verify PHP session configuration

### Search Not Working

- Check if JavaScript is enabled
- Verify `api/search.php` file exists
- Check browser console for errors

## 📄 License

This project is licensed under the MIT License - feel free to use it for personal or commercial projects.

## 👨‍💻 Author

Developed as a demonstration of PHP, MySQL, HTML, CSS, and JavaScript skills.

## 🙏 Acknowledgments

- FontAwesome for icons
- Modern web standards for HTML5/CSS3
- PHP and MySQL communities

## 📞 Support

For issues or questions:

1. Check the troubleshooting section
2. Review the code comments
3. Verify all installation steps were completed

## 🔄 Future Enhancements

Potential features for future versions:

- Payment gateway integration
- Email notifications
- Product reviews and ratings
- Wishlist functionality
- Advanced filtering and sorting
- Order tracking system
- Invoice generation
- Admin dashboard charts
- Multi-language support
- Dark mode toggle

---

**Note:** This is a demo project. For production use, additional security measures and optimizations should be implemented.

**Version:** 1.0.0  
**Last Updated:** November 2025
