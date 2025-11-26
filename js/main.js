// GroceryGo - Main JavaScript File

// DOM Ready
document.addEventListener("DOMContentLoaded", function () {
  initNavbar();
  initSearch();
  initCartActions();
  initFormValidation();
  initAlerts();
  initWinterTheme();
});

// ===========================
// Winter Theme Initialization
// ===========================
function initWinterTheme() {
  const winterTheme = document.querySelector(".winter-theme");
  if (!winterTheme) return;

  const snowflakesContainer = document.querySelector(".snowflakes-bg");
  if (!snowflakesContainer) return;

  // Create snowflakes
  const snowflakeCount = 10;
  for (let i = 0; i < snowflakeCount; i++) {
    const snowflake = document.createElement("div");
    snowflake.className = "snowflake";
    snowflake.textContent = "❄";
    snowflake.style.left = Math.random() * 100 + "%";
    snowflake.style.animationDuration = (Math.random() * 8 + 10) + "s";
    snowflake.style.animationDelay = Math.random() * 5 + "s";
    snowflake.style.fontSize = (Math.random() * 1 + 1.5) + "rem";
    snowflakesContainer.appendChild(snowflake);
  }
}

// ===========================
// Navbar Functions
// ===========================
function initNavbar() {
  const navToggle = document.getElementById("navToggle");
  const navMenu = document.getElementById("navMenu");

  if (navToggle && navMenu) {
    navToggle.addEventListener("click", function () {
      navMenu.classList.toggle("active");
    });

    // Close menu when clicking outside
    document.addEventListener("click", function (event) {
      if (!navToggle.contains(event.target) && !navMenu.contains(event.target)) {
        navMenu.classList.remove("active");
      }
    });
  }
}

// ===========================
// Search Functions
// ===========================
function initSearch() {
  const searchInput = document.getElementById("searchInput");
  const searchBtn = document.getElementById("searchBtn");
  const searchResults = document.getElementById("searchResults");

  if (!searchInput || !searchResults) return;

  let searchTimeout;

  // Real-time search
  searchInput.addEventListener("input", function () {
    clearTimeout(searchTimeout);
    const query = this.value.trim();

    if (query.length < 1) {
      searchResults.classList.remove("active");
      searchResults.innerHTML = "";
      return;
    }

    searchTimeout = setTimeout(() => {
      performSearch(query);
    }, 200);
  });

  // Search button click
  if (searchBtn) {
    searchBtn.addEventListener("click", function () {
      const query = searchInput.value.trim();
      if (query.length >= 1) {
        performSearch(query);
      }
    });
  }

  // Close search results when clicking outside
  document.addEventListener("click", function (event) {
    if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
      searchResults.classList.remove("active");
    }
  });
}

function performSearch(query) {
  const searchResults = document.getElementById("searchResults");

  // If no query provided, read from input
  if (typeof query === 'undefined' || query === null) {
    const inputEl = document.getElementById('searchInput');
    query = inputEl ? inputEl.value.trim() : '';
  }

  if (!query) return;

  // Get the base URL
  const baseUrl =
    window.location.origin + window.location.pathname.substring(0, window.location.pathname.lastIndexOf("/") + 1);
  const apiUrl = baseUrl.includes("/admin/") ? "../api/search.php" : "api/search.php";

  fetch(`${apiUrl}?q=${encodeURIComponent(query)}`)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      if (data.length === 0) {
        searchResults.innerHTML =
          '<div style="padding: 20px; text-align: center; color: #7f8c8d;">No products found</div>';
      } else {
        const imageBasePath = baseUrl.includes("/admin/") ? "../uploads/" : "uploads/";
        searchResults.innerHTML = data
          .map(
            (product) => `
                    <a href="${baseUrl.includes("/admin/") ? "../" : ""}category.php?id=${
              product.category_id || ""
            }" class="search-item">
                        <img src="${imageBasePath}${product.image}" alt="${
              product.name
            }" onerror="this.src='${imageBasePath}default-product.jpg'">
                        <div class="search-item-info">
                            <h4>${product.name}</h4>
                            <p>${product.category}</p>
                        </div>
                        <div class="search-item-price">$${product.price}</div>
                    </a>
                `
          )
          .join("");
      }
      searchResults.classList.add("active");
    })
    .catch((error) => {
      console.error("Search error:", error);
      searchResults.innerHTML =
        '<div style="padding: 20px; text-align: center; color: #e74c3c;">Search failed. Please try again.</div>';
      searchResults.classList.add("active");
    });
}

// ===========================
// Cart Functions
// ===========================
function initCartActions() {
  // Add to cart buttons
  document.querySelectorAll(".add-to-cart-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const productId = this.getAttribute("data-product-id");
      addToCart(productId, this);
    });
  });

  // Remove from cart buttons
  document.querySelectorAll(".remove-from-cart-btn").forEach((button) => {
    button.addEventListener("click", function () {
      if (confirm("Are you sure you want to remove this item from your cart?")) {
        const productId = this.getAttribute("data-product-id");
        removeFromCart(productId);
      }
    });
  });
}

function addToCart(productId, button) {
  const originalText = button.innerHTML;
  button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
  button.disabled = true;

  const formData = new FormData();
  formData.append("product_id", productId);
  formData.append("quantity", 1);

  fetch("api/add_to_cart.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showNotification(data.message, "success");
        updateCartBadge(data.cart_count);

        // Add animation
        button.innerHTML = '<i class="fas fa-check"></i> Added!';
        button.style.background = "#27ae60";

        setTimeout(() => {
          button.innerHTML = originalText;
          button.style.background = "";
          button.disabled = false;
        }, 2000);
      } else {
        showNotification(data.message, "error");
        button.innerHTML = originalText;
        button.disabled = false;
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("Failed to add to cart", "error");
      button.innerHTML = originalText;
      button.disabled = false;
    });
}

function removeFromCart(productId) {
  const formData = new FormData();
  formData.append("product_id", productId);

  fetch("api/remove_from_cart.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showNotification(data.message, "success");
        updateCartBadge(data.cart_count);
        // Reload page to update cart
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      } else {
        showNotification(data.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("Failed to remove from cart", "error");
    });
}

function updateQuantity(productId, change) {
  const qtyInput = document.getElementById(`qty-${productId}`);
  if (!qtyInput) return;

  const currentQty = parseInt(qtyInput.value);
  const newQty = currentQty + change;
  const maxQty = parseInt(qtyInput.max);

  if (newQty < 1 || newQty > maxQty) {
    if (newQty > maxQty) {
      showNotification("Cannot exceed available stock", "error");
    }
    return;
  }

  qtyInput.value = newQty;
  updateCartQuantity(productId, newQty);
}

function updateQuantityInput(productId) {
  const qtyInput = document.getElementById(`qty-${productId}`);
  if (!qtyInput) return;

  const newQty = parseInt(qtyInput.value);
  const maxQty = parseInt(qtyInput.max);

  if (newQty < 1) {
    qtyInput.value = 1;
    return;
  }

  if (newQty > maxQty) {
    qtyInput.value = maxQty;
    showNotification("Cannot exceed available stock", "error");
    return;
  }

  updateCartQuantity(productId, newQty);
}

function updateCartQuantity(productId, quantity) {
  const formData = new FormData();
  formData.append("product_id", productId);
  formData.append("quantity", quantity);

  fetch("api/update_cart.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Reload page to update totals
        window.location.reload();
      } else {
        showNotification(data.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("Failed to update cart", "error");
    });
}

function updateCartBadge(count) {
  const cartBadge = document.getElementById("cartBadge");
  if (cartBadge) {
    cartBadge.textContent = count;

    // Add animation
    cartBadge.style.transform = "scale(1.5)";
    setTimeout(() => {
      cartBadge.style.transform = "scale(1)";
    }, 300);
  }
}

// ===========================
// Form Validation
// ===========================
function initFormValidation() {
  // Login form
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value;

      if (!validateEmail(email)) {
        e.preventDefault();
        showNotification("Please enter a valid email address", "error");
        return false;
      }

      if (password.length < 6) {
        e.preventDefault();
        showNotification("Password must be at least 6 characters", "error");
        return false;
      }
    });
  }

  // Register form
  const registerForm = document.getElementById("registerForm");
  if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
      const username = document.getElementById("username").value.trim();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value;
      const confirmPassword = document.getElementById("confirm_password").value;

      if (username.length < 3) {
        e.preventDefault();
        showNotification("Username must be at least 3 characters", "error");
        return false;
      }

      if (!validateEmail(email)) {
        e.preventDefault();
        showNotification("Please enter a valid email address", "error");
        return false;
      }

      if (password.length < 6) {
        e.preventDefault();
        showNotification("Password must be at least 6 characters", "error");
        return false;
      }

      if (password !== confirmPassword) {
        e.preventDefault();
        showNotification("Passwords do not match", "error");
        return false;
      }
    });
  }

  // Product form
  const productForm = document.getElementById("productForm");
  if (productForm) {
    productForm.addEventListener("submit", function (e) {
      const name = document.getElementById("name").value.trim();
      const price = parseFloat(document.getElementById("price").value);
      const stock = parseInt(document.getElementById("stock").value);

      if (name.length < 3) {
        e.preventDefault();
        showNotification("Product name must be at least 3 characters", "error");
        return false;
      }

      if (price <= 0) {
        e.preventDefault();
        showNotification("Price must be greater than 0", "error");
        return false;
      }

      if (stock < 0) {
        e.preventDefault();
        showNotification("Stock cannot be negative", "error");
        return false;
      }
    });
  }
}

function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

// ===========================
// Notifications
// ===========================
function showNotification(message, type = "info") {
  // Remove existing notifications
  const existingNotification = document.querySelector(".notification-toast");
  if (existingNotification) {
    existingNotification.remove();
  }

  const notification = document.createElement("div");
  notification.className = `notification-toast notification-${type}`;
  notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${getNotificationIcon(type)}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">×</button>
    `;

  document.body.appendChild(notification);

  // Animate in
  setTimeout(() => {
    notification.style.transform = "translateX(0)";
    notification.style.opacity = "1";
  }, 10);

  // Auto remove after 5 seconds
  setTimeout(() => {
    notification.style.transform = "translateX(400px)";
    notification.style.opacity = "0";
    setTimeout(() => {
      notification.remove();
    }, 300);
  }, 5000);
}

function getNotificationIcon(type) {
  switch (type) {
    case "success":
      return "check-circle";
    case "error":
      return "exclamation-circle";
    case "warning":
      return "exclamation-triangle";
    default:
      return "info-circle";
  }
}

// ===========================
// Alerts
// ===========================
function initAlerts() {
  const alertMessage = document.getElementById("alertMessage");
  if (alertMessage) {
    setTimeout(() => {
      alertMessage.style.opacity = "0";
      alertMessage.style.transform = "translateY(-20px)";
      setTimeout(() => {
        alertMessage.remove();
      }, 300);
    }, 5000);
  }
}

// ===========================
// Delete Confirmations
// ===========================
document.querySelectorAll(".delete-btn").forEach((button) => {
  button.addEventListener("click", function (e) {
    if (!confirm("Are you sure you want to delete this item? This action cannot be undone.")) {
      e.preventDefault();
      return false;
    }
  });
});

// ===========================
// Utility Functions
// ===========================

// Debounce function for search
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Format currency
function formatCurrency(amount) {
  return "$" + parseFloat(amount).toFixed(2);
}

// Smooth scroll to top
function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
}

// Add smooth scroll to anchor links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    const href = this.getAttribute("href");
    if (href !== "#" && href !== "#!") {
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    }
  });
});

// ===========================
// CSS for Notifications (injected)
// ===========================
const notificationStyles = `
    <style>
        .notification-toast {
            position: fixed;
            top: 100px;
            right: 20px;
            min-width: 300px;
            max-width: 400px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10000;
            transform: translateX(400px);
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .notification-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .notification-content i {
            font-size: 1.3rem;
        }
        
        .notification-success {
            border-left: 4px solid #27ae60;
        }
        
        .notification-success i {
            color: #27ae60;
        }
        
        .notification-error {
            border-left: 4px solid #e74c3c;
        }
        
        .notification-error i {
            color: #e74c3c;
        }
        
        .notification-warning {
            border-left: 4px solid #f39c12;
        }
        
        .notification-warning i {
            color: #f39c12;
        }
        
        .notification-info {
            border-left: 4px solid #3498db;
        }
        
        .notification-info i {
            color: #3498db;
        }
        
        .notification-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #7f8c8d;
            padding: 0 5px;
            line-height: 1;
        }
        
        .notification-close:hover {
            color: #2c3e50;
        }
    </style>
`;

// Inject notification styles
document.head.insertAdjacentHTML("beforeend", notificationStyles);

console.log("GroceryGo JavaScript initialized successfully!");
