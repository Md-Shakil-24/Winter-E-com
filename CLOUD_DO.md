# Deploying Docker image to DigitalOcean App Platform

1. Push your repo to GitHub (already done).
2. Go to DigitalOcean → Apps → Create App.
3. Connect GitHub repo `Md-Shakil-24/Winter-E-com`.
4. Select service type: "Dockerfile" (DigitalOcean will build using your `Dockerfile`).
5. Set environment variables (matching `includes/config.php`):
   - `MYSQLHOST`, `MYSQLUSER`, `MYSQLPASSWORD`, `MYSQLDATABASE`, `MYSQLPORT`.
6. For database: either add Managed Database from DigitalOcean or use an external DB provider.
7. Deploy and monitor build logs.

Notes:
- DigitalOcean App Platform can also auto-scale and attach persistent disks for file storage.
- Make sure to configure volumes if you need persistent `uploads/` directory.
