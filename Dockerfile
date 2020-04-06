FROM silarhi/php-apache:7.1

ENV TERM="xterm" \
    DEBIAN_FRONTEND="noninteractive" \
    COMPOSER_ALLOW_SUPERUSER=1

EXPOSE 80
WORKDIR /app

# Install dependencies
RUN apt-get update -q && apt-get install -qy \
    git \
    gnupg \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxslt-dev \
    libzip-dev

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-configure zip --with-libzip && \
    docker-php-ext-install -j$(nproc) exif gd intl xsl zip && \
    pecl install apcu && \
    docker-php-ext-enable apcu

COPY docker/apache.conf /etc/apache2/conf-enabled/zz-app.conf
