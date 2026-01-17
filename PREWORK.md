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

# To disable the old one:
sudo mv /usr/local/bin/composer /usr/local/bin/composer.old
hash -r
which composer
composer -V

# Node + npm (recommended: Node 22 LTS)  (recommended for switching versions)
brew install nvm
mkdir -p ~/.nvm

echo 'export NVM_DIR="$HOME/.nvm"' >> ~/.zshrc
echo '[ -s "/opt/homebrew/opt/nvm/nvm.sh" ] && . "/opt/homebrew/opt/nvm/nvm.sh"' >> ~/.zshrc
source ~/.zshrc

nvm install 22
nvm use 22

node -v
npm -v

# Recheck
which php
which composer
which node
```

---

## 3) Initialize Laravel 9 Project (first time only)

If the repository doesn't have Laravel installed yet, run this from the repo root:

```bash
# Create Laravel 9 in a temporary location
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

# Configure Laravel
cp .env.example .env
php artisan key:generate

# Install frontend dependencies
npm install

# Test it works
php artisan serve
```

> **Note:** This approach avoids conflicts with existing git repository and custom documentation files.
