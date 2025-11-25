# .env File Configuration Guide

## 📝 .env ফাইল কি?

`.env` ফাইল আপনার **sensitive configuration** রাখে যেমন:
- Database credentials
- API keys
- Environment variables
- Secret keys

## ⚠️ গুরুত্বপূর্ণ

**এই ফাইলটি Git এ push করবেন না!** এটি `.gitignore` এ আছে এবং স্বয়ংক্রিয়ভাবে ignore হবে।

---

## 🚀 Render এ .env সেটআপ করা

Render এ, environment variables directly dashboard এ সেট করতে হয়:

### ধাপ ১: Render Dashboard এ যান
```
https://dashboard.render.com
  → আপনার service select করুন
  → Settings tab খুলুন
```

### ধাপ २: Environment Variables সেট করুন

**"Add Environment Variable"** ক্লিক করুন এবং এই variables যোগ করুন:

| Key | Value |
|-----|-------|
| `MYSQLHOST` | MySQL host এর URL |
| `MYSQLUSER` | Database user |
| `MYSQLPASSWORD` | Database password |
| `MYSQLDATABASE` | Database name |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |

### ধাপ ३: সংরক্ষণ করুন

সব variables সেট করার পর, service automatically redeploy হবে।

---

## 📋 .env ফাইলের বিষয়বস্তু

```env
# Database Configuration
MYSQLHOST=localhost
MYSQLUSER=root
MYSQLPASSWORD=
MYSQLDATABASE=winter-e-com
MYSQLPORT=3306

# Application Configuration
APP_ENV=production
APP_DEBUG=false
APP_URL=https://winter-e-com.onrender.com

# Session Configuration
SESSION_DRIVER=files
SESSION_LIFETIME=120

# Security
ENCRYPT_KEY=winter-e-com-secret-key-2025

# File Upload
UPLOAD_MAX_SIZE=5242880
UPLOAD_ALLOWED_TYPES=jpg,jpeg,png,gif
UPLOAD_DIR=uploads

# Display Errors (Production)
DISPLAY_ERRORS=false
ERROR_LOG=logs/error.log
```

---

## 🔄 Local Development এ .env ব্যবহার

### Local `.env` তৈরি করুন:
```env
# Local Development
MYSQLHOST=localhost
MYSQLUSER=root
MYSQLPASSWORD=
MYSQLDATABASE=winter-e-com
APP_ENV=local
APP_DEBUG=true
```

### PHP তে Load করুন:

আপনার `config.php` এ এই code যোগ করুন (শুরুতে):

```php
<?php
// Load .env file
if (file_exists(__DIR__ . '/../.env')) {
    $envLines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envLines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
            putenv(trim($key) . '=' . trim($value));
        }
    }
}
```

---

## 🛡️ নিরাপত্তা টিপস

✅ **কখনো commit করবেন না** `.env` ফাইল GitHub এ  
✅ **Render এ সরাসরি set করুন** sensitive values  
✅ **Local এ different values** রাখুন production থেকে  
✅ **Strong keys generate করুন** encryption এর জন্য  
✅ **Regular update করুন** passwords এবং keys  

---

## 🔧 Environment Specific Configuration

### Development (.env.local)
```env
APP_ENV=local
APP_DEBUG=true
DISPLAY_ERRORS=true
```

### Production (.env at Render)
```env
APP_ENV=production
APP_DEBUG=false
DISPLAY_ERRORS=false
```

---

## 📚 Example Use Cases

### Database Configuration:
```php
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db = getenv('MYSQLDATABASE');
```

### Application Logic:
```php
if (getenv('APP_ENV') === 'production') {
    // Production logic
} else {
    // Development logic
}
```

### File Upload Configuration:
```php
$maxSize = getenv('UPLOAD_MAX_SIZE');
$allowedTypes = explode(',', getenv('UPLOAD_ALLOWED_TYPES'));
```

---

## ✅ Checklist

- [ ] `.env` ফাইল তৈরি করেছেন
- [ ] `.gitignore` তে `.env` আছে
- [ ] Local values সঠিক সেট করেছেন
- [ ] Render dashboard এ variables সেট করেছেন
- [ ] Credentials সুরক্ষিত রাখেছেন
- [ ] Strong encryption key generate করেছেন

---

**আপনার sensitive data এখন সুরক্ষিত!** 🔒

---

*Last Updated: November 25, 2025*
