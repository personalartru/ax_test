FROM php:8.3-apache-bullseye

# --- PHP-extensions & tools needed by WordPress + plugins ---
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
        mariadb-server \
        default-mysql-client \
        libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libicu-dev zip unzip curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install mysqli gd zip intl exif pdo_mysql \
    && a2enmod rewrite headers \
    && curl -sSL -o /usr/local/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x /usr/local/bin/wp \
    && printf 'ServerName localhost\n' >> /etc/apache2/apache2.conf \
    && rm -rf /var/lib/apt/lists/*

# --- Apache: listen on 8080, docroot = workspace ---
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
RUN printf 'Listen 8080\n' > /etc/apache2/ports.conf

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

CMD ["entrypoint.sh"]