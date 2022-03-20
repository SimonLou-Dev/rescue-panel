FROM php:8.1-fpm

ARG user
ARG uid

WORKDIR /var/www
VOLUME /var/www/storage

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    lua-zlib-dev \
    libmemcached-dev \
    nginx \
    zip \
    unzip


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*


# Add docker php ext repo
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install php extensions
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions mbstring pdo_mysql zip exif pcntl gd memcached

#Get php extensions
RUN php -m

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

## yarn and node
RUN curl -sL https://deb.nodesource.com/setup_17.x | bash - \
  && apt-get install -y nodejs \
  && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
  && echo "deb https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
  && apt-get update \
  && apt-get install -y yarn



# Set working directory & copy code
COPY --chown=$user:www-data . /var/www

#Install And pm2
RUN mkdir /var/www/.pm2/

# PHP Error Log Files
RUN mkdir /var/log/php
RUN touch /var/log/php/errors.log && chmod 777 /var/log/php/errors.log

# Deployment steps
RUN composer install --optimize-autoloader --no-dev
RUN yarn install
RUN chmod +x /var/www/run.sh
#nginx config
RUN cp ./docker/default.conf /etc/nginx/sites-enabled/default.conf

RUN chown $user -R /var/www/*
RUN chmod 777 -R /var/www/*

EXPOSE 8080
ENTRYPOINT ["/var/www/run.sh"]

