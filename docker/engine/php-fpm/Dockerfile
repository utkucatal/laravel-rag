FROM php:8.4-fpm

WORKDIR /var/www/html

# System deps + PHP extension build deps
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        zip \
        nano \
        curl \
        procps \
        iproute2 \
        ca-certificates \
        gnupg \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libwebp-dev \
        libicu-dev \
        libonig-dev \
        libxml2-dev \
        libpq-dev \
        libcurl4-openssl-dev \
        default-mysql-client \
        postgresql-client \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions Laravel needs (mysql + postgres + common)
RUN docker-php-ext-configure gd --with-jpeg --with-freetype --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mysqli \
        pdo_pgsql \
        pgsql \
        gd \
        intl \
        zip \
        bcmath \
        mbstring \
        exif \
        pcntl \
        sockets \
        opcache

# Xdebug + Redis (debug + cache/queue driver)
RUN pecl install xdebug redis \
    && docker-php-ext-enable xdebug redis

# Prefer IPv4 over IPv6 in getaddrinfo — avoids composer/curl hanging on a
# dead IPv6 route inside the container (Docker Desktop has no IPv6 egress).
RUN echo 'precedence ::ffff:0:0/96  100' >> /etc/gai.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node 22 LTS (for Tailwind / Vite / Livewire asset build)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/* \
    && npm install -g npm@latest

# Match host user so mounted files are not root-owned (override at build if needed)
ARG UID=1000
ARG GID=1000
RUN groupmod -g ${GID} www-data 2>/dev/null || true; \
    usermod -u ${UID} -g ${GID} www-data 2>/dev/null || true

CMD ["php-fpm"]
