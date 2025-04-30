# Dockerfile
FROM wordpress:php8.0-apache

# Install WP-CLI into the same container that holds WP core
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp

# Ensure WP core files are writable by www-data
RUN chown -R www-data:www-data /var/www/html