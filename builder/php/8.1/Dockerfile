# Dockerfile
FROM php:8.1-fpm

RUN set -ex && \
    curl -sfL https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer && \
    chmod +x /usr/bin/composer                                                                     && \
    composer self-update --clean-backups 2.2.12                                                     && \
    apt-get update && apt-get install -y --no-install-recommends \
        libtidy-dev \
        libtidy-dev \
        libzip-dev \
        libfreetype-dev \
        libjpeg62-turbo-dev \
        libpng-dev && \
    docker-php-ext-install -j$(nproc) iconv pdo_mysql mysqli bcmath pcntl sockets tidy zip gettext calendar && \
    docker-php-ext-configure gd --with-freetype --with-jpeg  && \
    docker-php-ext-install -j$(nproc) gd && \
    pecl channel-update pecl.php.net && \
    pecl install --configureoptions 'enable-redis-igbinary="no" enable-redis-lzf="no" enable-redis-zstd="no"' redis-6.0.1 && \
    docker-php-ext-enable redis && \
    apt-get install -y libmemcached-dev libssl-dev zlib1g-dev && \
    pecl install memcached-3.2.0 && \
    docker-php-ext-enable memcached && \
    pecl install swoole && \
    docker-php-ext-enable swoole && \
    apt-get install -y libcurl4-gnutls-dev && \
    ln -s /usr/include/x86_64-linux-gnu/curl /usr/include/ && \
    pecl install yar && \
    docker-php-ext-enable yar && \
    pecl install yac && \
    docker-php-ext-enable yac && \
    rm -rf /var/lib/apt/lists/*
EXPOSE 9000
CMD ["php-fpm"]

