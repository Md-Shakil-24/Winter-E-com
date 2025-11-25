# Heroku ডিপ্লয়মেন্ট গাইড - Winter E-commerce Store

এই গাইড অনুসরণ করে আপনার Winter E-commerce Store কে Heroku তে ডিপ্লয় করুন।

---

## **পূর্বশর্ত:**

1. **Heroku Account তৈরি করুন**
   - https://www.heroku.com এ যান
   - "Sign Up" ক্লিক করুন
   - আপনার email দিয়ে রেজিস্টার করুন

2. **Heroku CLI ইনস্টল করুন**
   - Windows: https://cli-assets.heroku.com/branches/main/heroku-windows-x64.exe ডাউনলোড করুন
   - ইনস্টলেশন সম্পন্ন করুন
   - Terminal/PowerShell রিস্টার্ট করুন

3. **Git ইনস্টল করুন** (যদি না থাকে)
   - https://git-scm.com/download/win

---

## **ডিপ্লয়মেন্ট ধাপ:**

### **ধাপ ১: Heroku এ লগইন করুন**
```powershell
heroku login
```
- Browser খুলবে, আপনার Heroku credentials দিয়ে লগইন করুন
- অনুমতি দিন এবং ফিরে আসুন

### **ধাপ ২: Heroku অ্যাপ্লিকেশন তৈরি করুন**
```powershell
cd c:\xampp\htdocs\Project
heroku create winter-e-com
```

**বা** এই লিংক থেকে তৈরি করুন:
- https://dashboard.heroku.com/new?template=https://github.com/Md-Shakil-24/Winter-E-com

### **ধাপ ३: MySQL Database যোগ করুন**

```powershell
# ClearDB MySQL add করুন (Free tier)
heroku addons:create cleardb:ignite
```

এটি স্বয়ংক্রিয়ভাবে environment variables সেট করবে।

### **ধাপ ४: Database URL পান**
```powershell
heroku config:get CLEARDB_DATABASE_URL
```

আউটপুট এরকম হবে:
```
mysql://username:password@host/dbname
```

### **ধাপ ५: Database মাইগ্রেশন করুন**

**অপশন A: phpMyAdmin ব্যবহার করে**
1. https://www.phpmyadmin.net খুলুন (বা আপনার local phpMyAdmin)
2. CLEARDB_DATABASE_URL এ উল্লেখিত credentials ব্যবহার করুন
3. `database.sql` ফাইল import করুন

**অপশন B: Command Line থেকে**
```powershell
# CLEARDB_DATABASE_URL নিন
$url = heroku config:get CLEARDB_DATABASE_URL

# URL parse করুন এবং connect করুন
# mysql://user:pass@host/dbname → mysql -h host -u user -p pass dbname

mysql -h [host] -u [user] -p [password] [dbname] < database.sql
```

### **ধাপ ६: ডিপ্লয় করুন**

```powershell
# সব ফাইল add করুন
git add .

# Commit করুন
git commit -m "Deploy to Heroku"

# Heroku এ push করুন
git push heroku main
```

### **ধাপ ७: লাইভ সাইট দেখুন**

```powershell
# Browser এ খুলুন
heroku open
```

---

## **সমস্যা সমাধান:**

### **Database connection error হচ্ছে?**
```powershell
# Environment variables দেখুন
heroku config

# Logs দেখুন
heroku logs --tail
```

### **CLEARDB যোগ করা যাচ্ছে না?**
```powershell
# Payment method add করুন (free tier এর জন্যও প্রয়োজন)
# https://dashboard.heroku.com/account/billing

# তারপর try করুন:
heroku addons:create cleardb:ignite
```

### **Database restore করতে হচ্ছে?**
```powershell
# Heroku dashboard থেকে directly MySQL backup করুন
# অথবা command line থেকে:

# Step 1: Database export করুন
mysqldump -h [host] -u [user] -p[password] [dbname] > backup.sql

# Step 2: পুনরায় import করুন
mysql -h [host] -u [user] -p[password] [dbname] < backup.sql
```

---

## **দরকারী Heroku Commands:**

```powershell
# Logs দেখুন
heroku logs --tail

# Config variables দেখুন
heroku config

# App একবার রিস্টার্ট করুন
heroku restart

# Dyno type পরিবর্তন করুন
heroku ps:scale web=1

# App এ SSH এ প্রবেশ করুন (one-off dyno)
heroku run bash

# Database connect করুন
heroku run mysql CLEARDB_DATABASE_URL
```

---

## **Admin Credentials:**

- **Email:** admin@winter-e-com.com
- **Password:** 12345678

---

## **পরবর্তী আপডেট ডিপ্লয় করতে:**

```powershell
git add .
git commit -m "Description of changes"
git push heroku main
```

---

**সমস্যা হলে সাহায্যের জন্য বলবেন!** 🚀
