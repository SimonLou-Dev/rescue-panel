FROM nginx:latest

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
ENV TZ=UTC
EXPOSE 80

VOLUME /usr/share/nginx/bcfd

# Copy & set WorkDir
WORKDIR /usr/share/nginx/bcfd
COPY . /usr/share/nginx/bcfd

# Env Key & base pakadge
RUN apt-get update \
    && apt-get install -y gnupg gosu mysql\* curl ca-certificates zip curl lsb-release unzip git sqlite3 libcap2-bin libpng-dev python2 python3-pip \
    && mkdir -p ~/.gnupg \
    && chmod 600 ~/.gnupg \
    && echo "disable-ipv6" >> ~/.gnupg/dirmngr.conf \
    && apt-key adv --homedir ~/.gnupg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys E5267A6C \
    && apt-key adv --homedir ~/.gnupg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys C300EE8C \
    && echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu focal main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update

# PHP
RUN apt-get install -y php7.4-cli php7.4-dev \
       php7.4-pgsql php7.4-sqlite3 php7.4-gd \
       php7.4-curl \
       php7.4-imap php7.4-mysql php7.4-mbstring \
       php7.4-xml php7.4-zip php7.4-bcmath php7.4-soap php7.4-readline \
       php7.4-msgpack php7.4-igbinary php7.4-ldap php7.4-fpm \
       php7.4-redis

# Composer
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

#mysql-client \
RUN apt-get install -y wget


## yarn and node
RUN curl -sL https://deb.nodesource.com/setup_17.x | bash - \
  && apt-get install -y nodejs \
  && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
  && echo "deb https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
  && apt-get update \
  && apt-get install -y yarn

# Postgresql CLient
RUN apt-get install -y postgresql-client \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Set php version
RUN setcap "cap_net_bind_service=+ep" /usr/bin/php7.4
RUN update-alternatives --set php /usr/bin/php7.4

#Prepare app
COPY ./docker/start-container /usr/local/bin/start-container
COPY ./docker/default.conf /etc/nginx/conf.d/default.conf

#Install And start web dep
RUN yarn global add pm2
RUN pm2 start queueworker.yml
RUN composer install
RUN php artisan storage:link
RUN yarn install
RUN yarn build
RUN php artisan cache:clear
RUN php artisan storage:link

## Permission
RUN chown www-data -R ./*
RUN chmod 777 -R ./*

