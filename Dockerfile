# Dockerfile
FROM php:5.6-fpm
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
    && docker-php-ext-install -j$(nproc) iconv mcrypt \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && apt-get install -y libmemcached-dev zlib1g-dev \
    && pecl install memcached-2.2.0 \
    && docker-php-ext-enable memcached \
    && pecl install swoole-1.9.10 \
    && docker-php-ext-enable swoole \
    && curl -fsSL 'https://xcache.lighttpd.net/pub/Releases/3.2.0/xcache-3.2.0.tar.gz' -o xcache.tar.gz \
    && mkdir -p xcache \
    && tar -xf xcache.tar.gz -C xcache --strip-components=1 \
    && rm xcache.tar.gz \
    && ( \
        cd xcache \
        && phpize \
        && ./configure --enable-xcache \
        && make -j$(nproc) \
        && make install \
    ) \
    && rm -r xcache \
    && docker-php-ext-enable xcache

EXPOSE 9000
CMD ["php-fpm"]

