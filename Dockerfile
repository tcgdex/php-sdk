FROM php:8.1

WORKDIR /usr/src/app

# Install deps
RUN apt-get update \
    && apt-get install --yes --no-install-recommends \
       git wget zip unzip \
    && apt-get clean \
    && rm --recursive --force /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install PHP extensions
# RUN docker-php-ext-configure intl \
#     && docker-php-ext-configure gd --with-freetype --with-jpeg \
#     && docker-php-ext-install pdo mysqli pdo_mysql zip intl gd

# Copy the PHP config
# COPY php.ini /usr/local/etc/php/php.ini

# Copy composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
