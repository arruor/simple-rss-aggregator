FROM php:7.4-fpm

ENV DEBIAN_FRONTEND noninteractive

RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y --no-install-recommends \
            curl \
            libz-dev \
            libzip-dev \
            build-essential \
            libpng-dev \
            libjpeg62-turbo-dev \
            libfreetype6-dev \
            libxml++2.6-dev \
            libgmp-dev \
            zlib1g-dev \
            locales \
            zip \
            jpegoptim optipng pngquant gifsicle \
            vim \
            unzip \
            git \
            zip \
            unzip \
            libpq-dev \
            libjpeg-dev \
            libpng-dev \
            libfreetype6-dev \
            libssl-dev \
            libmcrypt-dev \
            libonig-dev; \
    rm -rf /var/lib/apt/lists/*

RUN set -eux; \
    docker-php-ext-install pdo_mysql; \
    docker-php-ext-install mbstring; \
    docker-php-ext-install exif; \
    docker-php-ext-install pcntl; \
    docker-php-ext-install bcmath; \
    docker-php-ext-install ctype; \
    docker-php-ext-install json; \
    docker-php-ext-install tokenizer; \
    docker-php-ext-install xml; \
    docker-php-ext-configure zip; \
    docker-php-ext-install zip; \
    docker-php-ext-install bcmath; \
    docker-php-ext-install gmp; \
    docker-php-ext-install opcache; \
    docker-php-ext-install mysqli; \
    docker-php-ext-configure intl; \
    docker-php-ext-install intl; \
    docker-php-ext-install gettext; \
    docker-php-ext-configure gd \
            --prefix=/usr \
            --with-jpeg \
            --with-freetype; \
    docker-php-ext-install gd; \
    php -r 'var_dump(gd_info());'

COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY ./xhprof.ini /usr/local/etc/php/conf.d
COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY ./laravel.ini /usr/local/etc/php/conf.d
COPY ./laravel.pool.conf /usr/local/etc/php-fpm.d/

RUN RUN yes | pecl install xdebug \
    sed -i "s/xdebug.remote_autostart=0/xdebug.remote_autostart=1/" /usr/local/etc/php/conf.d/xdebug.ini && \
    sed -i "s/xdebug.remote_enable=0/xdebug.remote_enable=1/" /usr/local/etc/php/conf.d/xdebug.ini && \
    sed -i "s/xdebug.cli_color=0/xdebug.cli_color=1/" /usr/local/etc/php/conf.d/xdebug.ini

RUN curl -L -o /tmp/xhprof.tar.gz "https://github.com/tideways/php-xhprof-extension/archive/v5.0.1.tar.gz" \
    && mkdir -p xhprof \
    && tar -C xhprof -zxvf /tmp/xhprof.tar.gz --strip 1 \
    && ( \
        cd xhprof \
        && phpize \
        && ./configure \
        && make \
        && make install \
    ) \
    && rm -r xhprof \
    && rm /tmp/xhprof.tar.gz

USER root

RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

ARG LOCALE=POSIX
ENV LC_ALL ${LOCALE}

WORKDIR /var/www

CMD ["php-fpm"]

EXPOSE 9000