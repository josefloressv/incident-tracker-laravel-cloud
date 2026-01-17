# Mac Setup — Verify & Install Tools (Incident Tracker / Laravel 9)

This repo expects a working local dev toolchain for **Laravel 9**:
- PHP **8.2.x** (recommended)
- Composer **2.x**
- Node **22 LTS** (recommended) + npm
- A database (SQLite recommended for local speed)

---

## 1) Verify installed versions

Run:

```bash
php -v
composer -V
node -v
npm -v
```

**Expected output (verified working versions):**
```
PHP 8.2.30 (cli) (built: Dec 16 2025 17:18:12) (NTS)
Composer version 2.9.3 2025-12-30 13:40:17
v22.22.0
10.9.4
```

---

## 2) Install / Fix on macOS (Apple Silicon) via Homebrew

```bash
# PHP 8.2 (Laravel 9 friendly)
brew install php@8.2
brew unlink php || true
brew link --overwrite --force php@8.2
php -v

# Composer
brew install composer || brew upgrade composer
which composer
composer -V

# If old Composer conflicts, disable it:
sudo mv /usr/local/bin/composer /usr/local/bin/composer.old 2>/dev/null || true
hash -r
which composer
composer -V

# Node + npm (recommended: Node 22 LTS via nvm)
brew install nvm
mkdir -p ~/.nvm

echo 'export NVM_DIR="$HOME/.nvm"' >> ~/.zshrc
echo '[ -s "/opt/homebrew/opt/nvm/nvm.sh" ] && . "/opt/homebrew/opt/nvm/nvm.sh"' >> ~/.zshrc
source ~/.zshrc

nvm install 22
nvm use 22
nvm alias default 22  # Set as default

node -v
npm -v

# Final verification
which php
which composer
which node
php artisan --version  # Should show Laravel Framework 9.x.x (after Laravel is installed)
```

---

## 3) Initialize Laravel 9 Project (first time only)

If the repository doesn't have Laravel installed yet, run this from the repo root:

```bash
# Create Laravel 9 in a temporary location (avoids .git conflicts)
mkdir -p /tmp/incident-tracker-tmp
composer create-project laravel/laravel /tmp/incident-tracker-tmp "^9.0"
cd /tmp/incident-tracker-tmp

# Disable automatic security blocking for this project only
composer config audit.block-insecure false
composer install

cd -

# Move Laravel files into the current repo root
# (preserves .git, README.md, TODO.md, PREWORK.md, .github/)
rsync -a --exclude=.git --exclude=README.md --exclude=TODO.md --exclude=PREWORK.md --exclude=.github /tmp/incident-tracker-tmp/ ./

# Cleanup
rm -rf /tmp/incident-tracker-tmp

# Configure Laravel environment
cp .env.example .env
php artisan key:generate

# Verify installation
php artisan --version  # Should show: Laravel Framework 9.x.x
```

---

## 4) Configure Database (SQLite recommended for local dev)

Edit `.env`:

```bash
# Option A: SQLite (fastest for local, zero config)
DB_CONNECTION=sqlite
# Comment out these lines:
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

# Option B: MySQL/Postgres (if you prefer)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=incident_tracker
# DB_USERNAME=root
# DB_PASSWORD=your_password
```

Create the SQLite database file:

```bash
touch database/database.sqlite
```

---

## 5) Install Dependencies & Run Migrations

```bash
# Install PHP dependencies
composer install

# Install frontend dependencies
npm install

# Run migrations (creates tables)
php artisan migrate
```

---

## 6) Start Development Servers

**Terminal 1 - Vite (frontend assets with hot-reload):**
```bash
npm run dev
# Runs on http://localhost:5173
# Auto-refreshes browser when you edit .blade.php, .js, .css files
```

**Terminal 2 - Laravel (backend):**
```bash
php artisan serve
# Runs on http://localhost:8000
# Visit this URL in your browser
```

**Terminal 3 - Queue Worker (later, when using jobs):**
```bash
php artisan queue:work
# Processes background jobs (notifications, emails, etc.)
```

---

## 7) Verify Everything Works

Visit **http://localhost:8000** in your browser. You should see the Laravel welcome page.

Check logs if issues:
```bash
tail -f storage/logs/laravel.log
```

---

## Common Issues

### "Class 'SQLite3' not found"
```bash
# Install PHP SQLite extension
brew install php@8.2
pecl install sqlite3  # If needed
# Restart terminal
```

### Vite connection refused
```bash
# Make sure npm run dev is running in a separate terminal
# Check if port 5173 is blocked by firewall
```

### Permission errors on storage/
```bash
chmod -R 775 storage bootstrap/cache
```

### storage/logs/laravel.log: No such file or directory
```bash
# Laravel only creates storage/logs/laravel.log after it logs something
# Force Laravel to write a log entry
php artisan tinker --execute="\\Log::debug('log test');"

# validate
tail -f storage/logs/laravel.log
```
---

> **Note:** This workflow avoids conflicts with the existing git repository and preserves custom documentation files while installing Laravel 9 fresh.
