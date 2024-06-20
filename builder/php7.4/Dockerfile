# Dockerfile
FROM php:7.4-fpm
RUN curl -sfL https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer && \
    chmod +x /usr/bin/composer                                                                     && \
    composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/ && \
    composer self-update --clean-backups 2.2.12                                                     && \
    apt-get update && apt-get install -y \
        libtidy-dev \
        libzip-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev && \
    docker-php-ext-install -j$(nproc) iconv pdo_mysql mysqli bcmath pcntl sockets tidy zip gettext calendar && \
    docker-php-ext-configure gd --with-freetype --with-jpeg  && \
    docker-php-ext-install -j$(nproc) gd && \
    pecl channel-update pecl.php.net && \
    pecl install redis-5.3.7 && \
    docker-php-ext-enable redis && \
    apt-get install -y libmemcached-dev libssl-dev zlib1g-dev && \
    pecl install memcached-3.2.0 && \
    docker-php-ext-enable memcached && \
    pecl install swoole-4.8.12 && \
    docker-php-ext-enable swoole && \
    rm -rf /var/lib/apt/lists/*
EXPOSE 9000
CMD ["php-fpm"]

