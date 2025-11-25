# Render এ Winter E-commerce Store ডিপ্লয় করুন

Render সম্পূর্ণ ফ্রি এবং খুবই সহজ। এই গাইড অনুসরণ করুন।

---

## **ধাপ ১: Render একাউন্ট তৈরি করুন**

1. https://render.com খুলুন
2. "Sign up" ক্লিক করুন
3. **GitHub দিয়ে sign up করুন** (সবচেয়ে সহজ)
4. GitHub authorize করুন

---

## **ধাপ २: নতুন Web Service তৈরি করুন**

1. Render dashboard এ "New +" ক্লিক করুন
2. "Web Service" বেছে নিন
3. **"Deploy from a Git Repository"** ক্লিক করুন

---

## **ধাপ ३: GitHub Repository সংযুক্ত করুন**

1. আপনার `Winter-E-com` repository সিলেক্ট করুন
2. Next ক্লিক করুন

---

## **ধাপ ४: সেটিংস পূরণ করুন**

| সেটিং | মান |
|--------|------|
| **Name** | `winter-e-com` |
| **Branch** | `main` |
| **Runtime** | `PHP` (বা Python) |
| **Build Command** | `composer install` |
| **Start Command** | `php -S 0.0.0.0:10000` |
| **Plan** | `Free` ✅ |

---

## **ধাপ ५: Environment Variables সেট করুন**

"Environment" সেকশনে যান এবং এগুলো যোগ করুন:

```
MYSQLHOST=localhost
MYSQLUSER=root
MYSQLPASSWORD=
MYSQLDATABASE=winter-e-com
```

---

## **ধাপ ६: ডিপ্লয় করুন**

1. নিচে স্ক্রোল করুন
2. "Create Web Service" ক্লিক করুন
3. **অপেক্ষা করুন (5-10 মিনিট)**

---

## **ধাপ ७: লাইভ সাইট দেখুন**

Deploy সম্পন্ন হলে একটি live URL পাবেন:
```
https://winter-e-com.onrender.com
```

---

## **Database সেটআপ**

### **Option A: SQLite ব্যবহার করুন (সহজ)**

`config.php` এ এই code যোগ করুন:
```php
if (getenv('RENDER')) {
    // SQLite ব্যবহার করুন Render এ
    $pdo = new PDO('sqlite:/tmp/winter.db');
} else {
    // Local MySQL
    $pdo = new PDO(...);
}
```

### **Option B: Remote MySQL ব্যবহার করুন**

1. Render এ MySQL database create করুন
2. Database credentials environment variable এ set করুন
3. আপনার `database.sql` import করুন

---

## **Admin Credentials**

```
Email: admin@winter-e-com.com
Password: 12345678
```

---

## **দরকারী লিংক**

- **Render Dashboard**: https://dashboard.render.com
- **Documentation**: https://render.com/docs

---

## **সমস্যা সমাধান**

### **Deploy fail হচ্ছে?**
- Logs দেখুন: Render dashboard → Service → Logs
- Build command check করুন
- `composer.json` সঠিক আছে কিনা দেখুন

### **Database connect হচ্ছে না?**
- Environment variables সেট আছে কিনা check করুন
- Database credentials সঠিক কিনা verify করুন

### **Static files (CSS, JS) load হচ্ছে না?**
- Start command সঠিক আছে কিনা দেখুন
- File permissions check করুন

---

## **পরবর্তী আপডেট ডিপ্লয় করতে**

```bash
git add .
git commit -m "Update message"
git push origin main
```

Render automatically GitHub থেকে pull করে আপডেট ডিপ্লয় করবে।

---

**সফল হলে আমাকে বলবেন!** 🚀
