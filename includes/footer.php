    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><i class="fas fa-snowflake"></i> winter-E-com</h3>
                    <p>Your premium destination for stylish and warm winter accessories.</p>
                </div>
                
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../index.php' : 'index.php'; ?>">Home</a></li>
                        <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../categories.php' : 'categories.php'; ?>">Categories</a></li>
                        <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../about.php' : 'about.php'; ?>">About Us</a></li>
                        <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../contact.php' : 'contact.php'; ?>">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Categories</h4>
                    <ul>
                        <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../category.php?id=1' : 'category.php?id=1'; ?>">Jackets & Coats</a></li>
                        <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../category.php?id=2' : 'category.php?id=2'; ?>">Scarves & Wraps</a></li>
                        <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../category.php?id=3' : 'category.php?id=3'; ?>">Gloves & Mittens</a></li>
                        <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../category.php?id=4' : 'category.php?id=4'; ?>">Winter Boots</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Contact Us</h4>
                    <p><i class="fas fa-envelope"></i> info@winter-ecom.com</p>
                    <p><i class="fas fa-phone"></i> +1 800 WINTER 1</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> winter-E-com. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../js/theme.js' : 'js/theme.js'; ?>"></script>
    <script src="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../js/main.js' : 'js/main.js'; ?>"></script>
</body>
</html>
