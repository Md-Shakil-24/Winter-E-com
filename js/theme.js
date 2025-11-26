// ============================================
// HERO SLIDER WITH IMAGE SUPPORT
// ============================================

class HeroSlider {
    constructor(containerId = 'heroSlider') {
        this.currentSlide = 0;
        this.autoPlayInterval = null;
        this.slides = [
            {
                title: '❄️ Welcome to winter-E-com',
                subtitle: 'Your Ultimate Destination for Premium Winter Accessories',
                description: 'Discover stylish, warm, and durable winter wear for every season',
                image: 'https://i.ibb.co.com/209DMpWv/winter-pic.jpg',
                cta: 'Shop Now'
            },
            {
                title: '🧥 Premium Jackets & Coats',
                subtitle: 'Stay Warm with Our Exclusive Collection',
                description: 'Waterproof, insulated, and stylish winter jackets for all occasions',
                image: 'https://i.ibb.co.com/s972gbxP/jaket.jpg',
                cta: 'Shop Jackets'
            },
            {
                title: '❄️ Winter Essentials',
                subtitle: 'Everything You Need for the Cold Season',
                description: 'From scarves to boots, we have all your winter accessories',
                image: 'https://i.ibb.co.com/bjdZtVQD/drawn-winter-clothes-pack-free-vector.jpg',
                cta: 'Browse Accessories'
            },
            {
                title: '🎉 Special Winter Deals',
                subtitle: 'Up to 50% Off on Selected Items',
                description: 'Limited time offers on your favorite winter products',
                image: 'https://i.ibb.co.com/PvPxqKkC/win.jpg',
                cta: 'View Deals'
            }
        ];

        this.init();
    }

    init() {
        const slider = document.getElementById('heroSlider');
        if (!slider) return;

        // Create slider HTML
        let slidesHTML = '<div class="slider-wrapper">';
        this.slides.forEach((slide, index) => {
            slidesHTML += `
                <div class="slider-item ${index === 0 ? 'active' : ''}" style="--bg-image: url('${slide.image}')">
                    <div class="slider-content">
                        <h1>${slide.title}</h1>
                        <p>${slide.subtitle}</p>
                        <p style="font-size: 1rem; opacity: 0.95;">
                            ${slide.description}
                        </p>
                        <a href="categories.php" class="btn btn-primary">
                            <i class="fas fa-shopping-bag"></i> ${slide.cta}
                        </a>
                    </div>
                </div>
            `;
        });
        slidesHTML += '</div>';

        // Add controls
        slidesHTML += '<div class="slider-controls">';
        slidesHTML += '<div class="slider-dots">';
        this.slides.forEach((_, index) => {
            slidesHTML += `<div class="slider-dot ${index === 0 ? 'active' : ''}" onclick="window.heroSlider.goToSlide(${index})"></div>`;
        });
        slidesHTML += '</div>';
        slidesHTML += '<button class="slider-arrow" onclick="window.heroSlider.prevSlide()"><i class="fas fa-chevron-left"></i></button>';
        slidesHTML += '<button class="slider-arrow" onclick="window.heroSlider.nextSlide()"><i class="fas fa-chevron-right"></i></button>';
        slidesHTML += '</div>';

        slider.innerHTML = slidesHTML;

        // Auto-play slider
        this.autoPlay();
    }

    nextSlide() {
        this.currentSlide = (this.currentSlide + 1) % this.slides.length;
        this.updateSlider();
        this.resetAutoPlay();
    }

    prevSlide() {
        this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.updateSlider();
        this.resetAutoPlay();
    }

    goToSlide(index) {
        this.currentSlide = index;
        this.updateSlider();
        this.resetAutoPlay();
    }

    updateSlider() {
        const items = document.querySelectorAll('.slider-item');
        const dots = document.querySelectorAll('.slider-dot');

        items.forEach((item, index) => {
            item.classList.toggle('active', index === this.currentSlide);
        });

        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === this.currentSlide);
        });
    }

    autoPlay() {
        this.autoPlayInterval = setInterval(() => this.nextSlide(), 5000);
    }

    resetAutoPlay() {
        clearInterval(this.autoPlayInterval);
        this.autoPlay();
    }
}

// ============================================
// THEME TOGGLE FUNCTIONALITY
// ============================================

class ThemeManager {
    constructor() {
        this.themeName = 'dark';
        this.init();
    }

    init() {
        // Get saved theme from localStorage
        const savedTheme = localStorage.getItem('theme') || 'dark';
        this.setTheme(savedTheme);
        this.setupThemeToggle();
    }

    setTheme(theme) {
        this.themeName = theme;
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        this.updateToggleIcon();
    }

    toggleTheme() {
        const newTheme = this.themeName === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);
    }

    setupThemeToggle() {
        // Create theme toggle button if it doesn't exist
        const existing = document.querySelector('.theme-toggle');
        if (!existing) {
            this.createThemeToggleButton();
        } else {
            existing.addEventListener('click', () => this.toggleTheme());
        }
    }

    createThemeToggleButton() {
        const toggle = document.createElement('button');
        toggle.className = 'theme-toggle';
        toggle.setAttribute('aria-label', 'Toggle dark/light mode');
        toggle.innerHTML = this.themeName === 'dark' ? '☀️' : '🌙';
        toggle.addEventListener('click', () => this.toggleTheme());

        const navbar = document.querySelector('.navbar .container');
        if (navbar) {
            navbar.appendChild(toggle);
        }
    }

    updateToggleIcon() {
        const toggle = document.querySelector('.theme-toggle');
        if (toggle) {
            toggle.innerHTML = this.themeName === 'dark' ? '☀️' : '🌙';
        }
    }

    getTheme() {
        return this.themeName;
    }
}

// ============================================
// INITIALIZE ON PAGE LOAD
// ============================================

let themeManager;

document.addEventListener('DOMContentLoaded', () => {
    // Initialize slider
    window.heroSlider = new HeroSlider('heroSlider');

    // Initialize theme manager
    themeManager = new ThemeManager();

    // Mobile menu toggle
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (navToggle) {
        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });
    }

    // Close mobile menu when link is clicked
    if (navMenu) {
        const navLinks = navMenu.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
            });
        });
    }

    // Navbar Scroll Hide/Show Effect
    class NavbarScroller {
        constructor() {
            this.navbar = document.querySelector('.navbar');
            this.lastScrollY = 0;
            this.isHidden = false;
            this.scrollThreshold = 50;
            
            if (this.navbar) {
                window.addEventListener('scroll', () => this.handleScroll(), { passive: true });
            }
        }
        
        handleScroll() {
            const currentScrollY = window.scrollY;
            
            // Scrolling down - hide navbar
            if (currentScrollY > this.lastScrollY && currentScrollY > this.scrollThreshold) {
                if (!this.isHidden) {
                    this.navbar.classList.add('scroll-hide');
                    this.navbar.classList.remove('scroll-show');
                    this.isHidden = true;
                }
            } 
            // Scrolling up or at top - show navbar
            else if (currentScrollY < this.lastScrollY || currentScrollY <= this.scrollThreshold) {
                if (this.isHidden) {
                    this.navbar.classList.remove('scroll-hide');
                    this.navbar.classList.add('scroll-show');
                    this.isHidden = false;
                }
            }
            
            this.lastScrollY = currentScrollY;
        }
    }

    new NavbarScroller();

    // Search functionality is handled in `js/main.js` to avoid duplicate handlers

    // Winter theme background animation (snowflakes)
    initWinterTheme();
});

// ============================================
// SEARCH FUNCTIONALITY
// ============================================

async function performSearch() {
    const searchInput = document.querySelector('#searchInput');
    const query = searchInput.value.trim();

    if (!query) return;

    try {
        const response = await fetch(`api/search.php?q=${encodeURIComponent(query)}`);
        const data = await response.json();

        const resultsContainer = document.querySelector('#searchResults');
        resultsContainer.innerHTML = '';

        if (data.success && data.products.length > 0) {
            data.products.forEach(product => {
                const item = document.createElement('div');
                item.className = 'search-item';
                item.innerHTML = `
                    <strong>${escapeHtml(product.name)}</strong>
                    <div style="font-size: 0.875rem; color: var(--text-secondary);">
                        $${parseFloat(product.price).toFixed(2)}
                    </div>
                `;
                item.addEventListener('click', () => {
                    window.location.href = `product.php?id=${product.id}`;
                });
                resultsContainer.appendChild(item);
            });
            resultsContainer.classList.add('active');
        } else {
            resultsContainer.innerHTML = '<div class="search-item" style="text-align:center; padding: 2rem;">No products found</div>';
            resultsContainer.classList.add('active');
        }
    } catch (error) {
        console.error('Search error:', error);
    }
}

// ============================================
// UTILITY FUNCTIONS
// ============================================

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function initWinterTheme() {
    // Create snowflake animation for winter theme
    const style = document.createElement('style');
    style.textContent = `
        @keyframes snowfall {
            0% {
                transform: translateY(-10vh) translateX(0);
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) translateX(100px);
                opacity: 0;
            }
        }

        .snowflake {
            position: fixed;
            top: -10vh;
            color: #fff;
            font-size: 1em;
            font-weight: bold;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
            z-index: 1;
            pointer-events: none;
            opacity: 0.7;
        }

        .snowflake.animate {
            animation: snowfall 10s linear forwards;
        }
    `;
    document.head.appendChild(style);

    // Generate snowflakes occasionally
    const createSnowflake = () => {
        const snowflake = document.createElement('div');
        snowflake.className = 'snowflake';
        snowflake.textContent = '❄';
        snowflake.style.left = Math.random() * 100 + 'vw';
        snowflake.style.animationDuration = (Math.random() * 5 + 8) + 's';
        document.body.appendChild(snowflake);

        setTimeout(() => {
            snowflake.classList.add('animate');
        }, 100);

        setTimeout(() => {
            snowflake.remove();
        }, 14000);
    };

    // Create snowflakes at intervals
    setInterval(createSnowflake, 500);
}

// ============================================
// CART FUNCTIONALITY
// ============================================

async function addToCart(productId) {
    try {
        const response = await fetch('api/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        });

        const data = await response.json();

        if (data.success) {
            showAlert('Product added to cart!', 'success');
            updateCartBadge();
        } else {
            showAlert(data.message || 'Error adding to cart', 'danger');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('Error adding to cart', 'danger');
    }
}

function updateCartBadge() {
    const badge = document.querySelector('#cartBadge');
    if (badge) {
        // Fetch current cart count
        fetch('api/get_cart_count.php')
            .then(res => res.json())
            .then(data => {
                if (data.count) {
                    badge.textContent = data.count;
                }
            });
    }
}

// ============================================
// ALERT FUNCTIONALITY
// ============================================

function showAlert(message, type = 'success') {
    const alertContainer = document.createElement('div');
    alertContainer.className = `alert alert-${type}`;
    alertContainer.innerHTML = `
        ${message}
        <button class="alert-close">×</button>
    `;

    const main = document.querySelector('.main-content');
    if (main) {
        main.insertBefore(alertContainer, main.firstChild);
    }

    const closeBtn = alertContainer.querySelector('.alert-close');
    closeBtn.addEventListener('click', () => {
        alertContainer.remove();
    });

    setTimeout(() => {
        alertContainer.remove();
    }, 5000);
}

// ============================================
// FORM VALIDATION
// ============================================

function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function validatePassword(password) {
    return password.length >= 6;
}

// ============================================
// EXPORT FOR USE IN OTHER FILES
// ============================================

window.themeManager = themeManager;
window.addToCart = addToCart;
window.showAlert = showAlert;
window.validateEmail = validateEmail;
window.validatePassword = validatePassword;
