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
