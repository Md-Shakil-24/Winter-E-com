# Docker deployment (local + cloud)

This file explains how to run the Winter E-com project with Docker locally, and notes for deploying to Docker hosts (Fly.io, DigitalOcean App Platform, etc.).

## Local quickstart (Docker + docker-compose)

Requirements:
- Docker Desktop (Windows/Mac) or Docker Engine + docker-compose

Commands:

```bash
# From project root
# Build and start containers
docker-compose up --build -d

# Show logs
docker-compose logs -f app

# Stop
docker-compose down
```

Open: http://localhost:8000

The compose file creates two services:
- `app` (PHP/Apache) listening on host port 8000
- `db` (MySQL 8.0) with database `winter_e_com` and root password `example`

The app expects these environment variables (matching `includes/config.php`):
- `MYSQLHOST` (set to `db` in compose)
- `MYSQLUSER` (`root`)
- `MYSQLPASSWORD` (`example`)
- `MYSQLDATABASE` (`winter_e_com`)
- `MYSQLPORT` (`3306`)

### Import database
After the DB container is running, import `database.sql`:

```bash
# Run import from host (MySQL client must be installed)
# Replace path/to/database.sql with actual path in project
cat database.sql | docker exec -i $(docker-compose ps -q db) mysql -uroot -pexample winter_e_com
```

Or use a GUI (MySQL Workbench) and connect to the database on the container.

## Deploy to Docker-capable hosts

### Fly.io
1. Install `flyctl` and login.
2. `fly launch` and follow prompts (it will detect Dockerfile).
3. Add secrets for DB if using external DB, or create a managed database.
4. `fly deploy`

### DigitalOcean App Platform
- Create new app → Connect GitHub repo → Choose Dockerfile build → Set env variables in dashboard → Deploy.

### Other hosts
- Any host that accepts a Docker image or Dockerfile can build and run this project (AWS ECS, GCP Cloud Run, Azure Web App for Containers, etc.).

## Notes
- The container stores MySQL in `db_data` volume. For production use an external managed DB (RDS, PlanetScale, etc.).
- Keep `.env` values out of Git (we ignore `.env`). Instead set production secrets in your host's environment/secrets.
- If you prefer SQLite for quick demos, set `USE_SQLITE=true` in env and ensure persistent volume for `data/winter.db`.

