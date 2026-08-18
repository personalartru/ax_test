#!/usr/bin/env bash
set -euo pipefail

SITE_DIR="/workspaces/$(basename "$PWD")"
cd "$SITE_DIR"
echo "Site dir: $SITE_DIR"

# --- 1. PHP extensions needed by WordPress / plugins ---
for ext in mysqli gd zip intl exif; do
  if ! php -m | grep -qi "^$ext$"; then
    echo "-> enable $ext"
    if [ -f "/usr/local/etc/php/conf.d/docker-php-ext-$ext.ini" ] || command -v docker-php-ext-install >/dev/null 2>&1; then
      docker-php-ext-install "$ext" >/dev/null 2>&1 || true
    else
      pecl install "$ext" >/dev/null 2>&1 || true
    fi
  fi
done
apt-get update -q >/dev/null 2>&1
apt-get install -y -q libzip-dev zip mariadb-client >/dev/null 2>&1 || true

# --- 2. wp-cli ---
if ! command -v wp >/dev/null 2>&1; then
  echo "-> install wp-cli"
  curl -o /usr/local/bin/wp -sL https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
  chmod +x /usr/local/bin/wp
fi

# --- 3. wp-config.php (create from sample) ---
if [ ! -f wp-config.php ]; then
  echo "-> generate wp-config.php"
  cp wp-config-sample.php wp-config.php
  perl -pi -e "s/database_name_here/axel/g; s/username_here/axel/g; s/password_here/axelpass/g; s/localhost/db/g" wp-config.php

  SALTS=$(curl -s https://api.wordpress.org/secret-key/1.1/salt/)
  perl -0pi -e "s/define\( *'AUTH_KEY'.*?\n\\);/$SALTS/gs" wp-config.php
fi

# --- 4. Wait for MariaDB ---
echo "-> wait for MariaDB..."
for i in $(seq 1 30); do
  if mysql -h db -u axel -paxelpass -e 'SELECT 1' axel >/dev/null 2>&1; then
    break
  fi
  sleep 2
done

# --- 5. Import Duplicator SQL dump ---
DUMP=$(ls dup-installer/dup-database__*.sql 2>/dev/null | head -1)
if [ -n "$DUMP" ] && [ ! -f .imported ]; then
  echo "-> import $DUMP"
  mysql -h db -u axel -paxelpass axel < "$DUMP"
  touch .imported
fi

# --- 6. Rewrite URLs to localhost:8080 ---
echo "-> replace domain"
wp --path=. --url=http://localhost:8080 search-replace 'https://axel.synapseonline.ru' 'http://localhost:8080' --all-tables --skip-columns=guid --recurse-objects --report-changed-only >/dev/null 2>&1 || true
wp --path=. --url=http://localhost:8080 search-replace 'http://axel.synapseonline.ru' 'http://localhost:8080' --all-tables --skip-columns=guid --recurse-objects --report-changed-only >/dev/null 2>&1 || true
wp --path=. --url=http://localhost:8080 option update home 'http://localhost:8080' >/dev/null 2>&1 || true
wp --path=. --url=http://localhost:8080 option update siteurl 'http://localhost:8080' >/dev/null 2>&1 || true

# --- 7. Permalink structure + .htaccess for Apache ---
wp --path=. rewrite structure '/%postname%/' >/dev/null 2>&1 || true
cat > .htaccess <<'EOF'
# BEGIN WordPress
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
# END WordPress
EOF

# --- 8. Start Apache on 8080 ---
sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf 2>/dev/null || true
sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8080>/' /etc/apache2/sites-available/000-default.conf 2>/dev/null || true
sed -i 's#DocumentRoot /var/www/html#DocumentRoot '"$SITE_DIR"'#' /etc/apache2/sites-available/000-default.conf 2>/dev/null || true
chmod -R 755 "$SITE_DIR"
a2enmod rewrite >/dev/null 2>&1 || true
apachectl -k restart >/dev/null 2>&1 || apache2ctl -k restart >/dev/null 2>&1 || true

echo
echo "==================================================="
echo " Сайт axel восстановлен на http://localhost:8080"
echo " WP admin: http://localhost:8080/wp-admin"
echo "==================================================="