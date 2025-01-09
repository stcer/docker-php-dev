# Dockerfile
# @see https://github.com/docker-library/php/blob/641cf9a226ba8447dcf9338d2a19a59f1102967b/8.1/bookworm/fpm/Dockerfile
# date.timezone = Asia/Shanghai
# date_default_timezone_set()
FROM php:8.2-fpm
ENV TZ=Asia/Shanghai
RUN set -ex && \
    curl -sfL https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer && \
    chmod +x /usr/bin/composer                                                                     && \
    composer self-update                                                       && \
    composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/ && \
    apt-get update && apt-get install -y --no-install-recommends \
        libtidy-dev \
        libtidy-dev \
        libzip-dev \
        libfreetype-dev \
        libjpeg62-turbo-dev \
        libpng-dev && \
    docker-php-ext-install -j$(nproc) iconv pdo_mysql mysqli bcmath pcntl sockets tidy zip gettext calendar && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install -j$(nproc) gd &&  \
    pecl channel-update pecl.php.net && \
    pecl install --configureoptions 'enable-redis-igbinary="no" enable-redis-lzf="no" enable-redis-zstd="no"' redis-6.0.1 && docker-php-ext-enable redis && \
    apt-get install -y libmemcached-dev libssl-dev zlib1g-dev && pecl install memcached-3.2.0 && docker-php-ext-enable memcached && \
    apt-get install -y librdkafka-dev && pecl install rdkafka && docker-php-ext-enable rdkafka && \
    pecl install swoole && docker-php-ext-enable swoole && \
    rm -rf /var/lib/apt/lists/*
EXPOSE 9000
CMD ["php-fpm"]

