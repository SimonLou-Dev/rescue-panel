FROM php:8.1-fpm

ARG user
ARG uid

WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
#mysql-client \
RUN apt-get install -y wget

#Get php extensions
RUN apt-get install -y php8.1-cli php8.1-dev \
       php8.1-pgsql php8.1-sqlite3 php8.1-gd \
       php8.1-curl php8.1-memcached\
       php8.1-imap php8.1-mysql php8.1-mbstring \
       php8.1-xml php8.1-zip php8.1-bcmath php8.1-soap php8.1-readline \
       php8.1-msgpack php8.1-igbinary php8.1-ldap php8.1-fpm \
       php8.1-redis


# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

## yarn and node
RUN curl -sL https://deb.nodesource.com/setup_17.x | bash - \
  && apt-get install -y nodejs \
  && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
  && echo "deb https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
  && apt-get update \
  && apt-get install -y yarn


# Set php version
RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.1
RUN update-alternatives --set php /usr/bin/php8.1

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory & copy code
COPY --chown=www:www-data . /var/www

USER $user

#Install And pm2
RUN yarn global add pm2

# Copy nginx/php/supervisor configs
RUN cp ./docker/supervisor.conf /etc/supervisord.conf
RUN cp ./docker/php.ini /usr/local/etc/php/conf.d/app.ini
RUN cp ./docker/nginx.conf /etc/nginx/sites-enabled/default

# PHP Error Log Files
RUN mkdir /var/log/php
RUN touch /var/log/php/errors.log && chmod 777 /var/log/php/errors.log

# Deployment steps
RUN composer install --optimize-autoloader --no-dev
RUN yarn update
RUN chmod +x /var/www/docker/run.sh

EXPOSE 80
ENTRYPOINT ["/var/www/docker/run.sh"]

