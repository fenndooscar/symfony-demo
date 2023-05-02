FROM php:8.2.4-fpm-alpine as php-runtime

ARG DOCKER_USER_UID
ARG DOCKER_USER_GID

RUN set -x \
        # install permanent dependencies
        && apk add --no-cache \
            postgresql-dev \
            icu-libs \
            zip \
            libzip-dev \
            zlib-dev \
            bash \
        && echo "-- Installed permanent dependencies! --\n"

RUN set -x \
        # install build-time dependencies
        && apk add --no-cache --virtual .build-deps \
            shadow \
            linux-headers \
            build-base \
            openssl \
            make \
            g++ \
            icu-dev \
            autoconf \
            gcc \
            pkgconf \
            git \
        && echo "-- Installed build-time dependencies! --\n"

RUN set -x \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure bcmath --enable-bcmath \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN set -x \
        # install PHP extensions (CFLAGS usage reason - https://bit.ly/3ALS5NU)
        && CFLAGS="$CFLAGS -D_GNU_SOURCE" docker-php-ext-install -j$(nproc) \
            sockets \
            pcntl \
            intl \
            zip \
            pdo \
            pdo_pgsql \
            1>/dev/null \
        && echo "-- Installed PHP extensions! --\n"

RUN set -x \
        && docker-php-source delete \
        && apk del .build-deps \
        && rm -R /tmp/pear \
        && echo "-- Cleaned up success! --\n"

COPY --from=ochinchina/supervisord:latest /usr/local/bin/supervisord /usr/local/bin/supervisord
RUN echo "-- Installed supervisord success! --\n"

RUN set -eux; \
    addgroup --system --gid 110 nginx; \
    adduser --system --disabled-password --ingroup nginx --no-create-home --home /nonexistent --gecos "nginx user" --shell /bin/false --uid 110 nginx; \
    echo "-- install nginx --\n"; \
    apk add --no-cache nginx nginx-mod-http-geoip; \
    rm /etc/nginx/conf.d/default* || true ; \
    ln -sf /proc/1/fd/1 /var/log/nginx/access.log; \
    ln -sf /proc/1/fd/2 /var/log/nginx/error.log; \
    mkdir /var/run/php-fpm; \
    mkdir /var/cache/nginx; \
    chmod 755 /var/cache/nginx; \
    chown -R ${DOCKER_USER_UID}:${DOCKER_USER_GID} /var/lib/nginx; \
    chown ${DOCKER_USER_UID}:${DOCKER_USER_GID} /var/run/php-fpm; \
    chown ${DOCKER_USER_UID}:${DOCKER_USER_GID} /var/run/nginx; \
    echo "-- Installed nginx success! --\n";

COPY docker/php/php-fpm.d/prod.conf /usr/local/etc/php-fpm.d/prod.conf
COPY docker/php/php-fpm.d/php-pool.conf /usr/local/etc/php-fpm.d/php-pool.conf
COPY docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord /etc/supervisor
RUN echo "-- Copied configs success! --\n"

WORKDIR /srv/app

COPY ./app /srv/app/

RUN set -x \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && mkdir -p /tmp/composer/cache \
    && mkdir -p /.composer/cache \
    && chown -R ${DOCKER_USER_UID}:${DOCKER_USER_GID} /tmp/composer/cache \
    && chown -R ${DOCKER_USER_UID}:${DOCKER_USER_GID} /.composer/cache \
    && chmod -R 755 /tmp/composer/cache \
    && chmod -R 755 /.composer/cache \
    && composer dump-autoload --optimize \
    && composer check-platform-reqs \
    && php /srv/app/bin/console cache:warmup \
    && echo "-- Composer installed success! --\n" \
    && curl -LsS https://get.symfony.com/cli/installer -o /tmp/symfony-installer \
    && chmod a+x /tmp/symfony-installer \
    && /tmp/symfony-installer --install-dir=/usr/local/bin \
    && chmod a+x /usr/local/bin/symfony \
    && echo "-- Symfony installed success! --\n"

ENTRYPOINT [""]
