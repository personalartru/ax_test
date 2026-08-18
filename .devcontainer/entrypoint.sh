#!/usr/bin/env bash
set -euo pipefail

SITE_DIR="/workspaces"
cd "$SITE_DIR"

# --- 1. Start MariaDB ---
if ! mysqladmin ping --silent 2>/dev/null; then
  service mariadb start
fi
for i in $(seq 1 30); do
  mysqladmin ping --silent && break
  sleep 1
done

# --- 2. Create DB + user ---
mysql -uroot <<'SQL'
CREATE DATABASE IF NOT EXISTS axel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'axel'@'localhost' IDENTIFIED BY 'axelpass';
CREATE USER IF NOT EXISTS 'axel'@'127.0.0.1' IDENTIFIED BY 'axelpass';
GRANT ALL PRIVILEGES ON axel.* TO 'axel'@'localhost';
GRANT ALL PRIVILEGES ON axel.* TO 'axel'@'127.0.0.1';
FLUSH PRIVILEGES;
SQL

# --- 3. wp-config.php ---
if [ ! -f wp-config.php ]; then
  wp config create --force \
    --dbname=axel --dbuser=axel --dbpass=axelpass --dbhost=localhost \
    --path=/workspaces >/dev/null 2>&1 || {
      cp wp-config-sample.php wp-config.php
      perl -pi -e "s/database_name_here/axel/g; s/username_here/axel/g; s/password_here/axelpass/g; s/localhost/localhost/g" wp-config.php
    }
fi

# --- 4. Import Duplicator SQL dump (once) ---
DUMP=$(ls dup-installer/dup-database__*.sql 2>/dev/null | head -1)
if [ -n "$DUMP" ] && ! mysql -h127.0.0.1 -uaxel -paxelpass axel -e "SHOW TABLES LIKE 'wp_options'" | grep -q wp_options; then
  echo "==> importing $DUMP"
  mysql -h127.0.0.1 -uaxel -paxelpass axel < "$DUMP"
fi

# --- 5. Rewrite URLs to localhost:8080 ---
wp --path=/workspaces --url=http://localhost:8080 search-replace \
  'https://axel.synapseonline.ru' 'http://localhost:8080' \
  --all-tables --skip-columns=guid --recurse-objects --report-changed-only >/dev/null 2>&1 || true
wp --path=/workspaces --url=http://localhost:8080 search-replace \
  'http://axel.synapseonline.ru' 'http://localhost:8080' \
  --all-tables --skip-columns=guid --recurse-objects --report-changed-only >/dev/null 2>&1 || true
wp --path=/workspaces --url=http://localhost:8080 option update home 'http://localhost:8080' >/dev/null 2>&1 || true
wp --path=/workspaces --url=http://localhost:8080 option update siteurl 'http://localhost:8080' >/dev/null 2>&1 || true
wp --path=/workspaces --url=http://localhost:8080 rewrite structure '/%postname%/' >/dev/null 2>&1 || true

# --- 6. .htaccess for pretty permalinks ---
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

chown -R www-data:www-data "$SITE_DIR" 2>/dev/null || true

echo "==================================================="
echo " axel restored: http://localhost:8080"
echo " wp-admin:      http://localhost:8080/wp-admin"
echo "==================================================="

# --- 7. Run Apache in foreground ---
exec apache2-foreground