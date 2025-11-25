<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? escape_output($page_title) . ' - winter-E-com' : 'winter-E-com - Winter Accessories Store'; ?></title>
    <?php
    // Determine the correct path for CSS based on current directory
    $css_path_modern = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../css/modern-theme.css' : 'css/modern-theme.css';
    $css_path_additional = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../css/additional.css' : 'css/additional.css';
    ?>
    <link rel="stylesheet" href="<?php echo $css_path_modern; ?>?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $css_path_additional; ?>?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../index.php' : 'index.php'; ?>" title="Go to Home">
                    <i class="fas fa-snowflake"></i>
                    <span>winter-E-com</span>
                </a>
            </div>
            
            <div class="nav-search">
                <input type="text" id="searchInput" placeholder="Search winter accessories..." />
                <button type="button" id="searchBtn">
                    <i class="fas fa-search"></i>
                </button>
                <div id="searchResults" class="search-results"></div>
            </div>

            <ul class="nav-menu" id="navMenu">
                <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../index.php' : 'index.php'; ?>"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../categories.php' : 'categories.php'; ?>"><i class="fas fa-th-large"></i> Categories</a></li>
                
                <?php if (is_logged_in()): ?>
                    <?php if (is_admin()): ?>
                        <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? 'dashboard.php' : 'admin/dashboard.php'; ?>"><i class="fas fa-cog"></i> Admin</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../cart.php' : 'cart.php'; ?>" class="cart-link">
                            <i class="fas fa-shopping-cart"></i> Cart
                            <span class="cart-badge" id="cartBadge"><?php echo get_cart_count(get_user_id()); ?></span>
                        </a></li>
                        <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../profile.php' : 'profile.php'; ?>"><i class="fas fa-user"></i> Profile</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../logout.php' : 'logout.php'; ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php else: ?>
                    <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../login.php' : 'login.php'; ?>"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../register.php' : 'register.php'; ?>"><i class="fas fa-user-plus"></i> Register</a></li>
                <?php endif; ?>
            </ul>

            <div class="nav-toggle" id="navToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <?php
    $message = get_message();
    if ($message):
    ?>
    <div class="alert alert-<?php echo $message['type']; ?>" id="alertMessage">
        <?php echo escape_output($message['text']); ?>
        <button class="alert-close" onclick="this.parentElement.remove()">×</button>
    </div>
    <?php endif; ?>

    <main class="main-content">
