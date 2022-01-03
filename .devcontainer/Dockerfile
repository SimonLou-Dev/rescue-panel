ARG VARIANT="focal"
FROM mcr.microsoft.com/vscode/devcontainers/base:0-${VARIANT}

EXPOSE 8000

RUN apt update -y
RUN apt upgrade -y
RUN apt install -y ca-certificates apt-transport-https software-properties-common

# [Optional] Uncomment this section to install additional OS packages.
# RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
#     && apt-get -y install --no-install-recommends <your-package-list-here>
RUN apt-get install -y curl zip lsb-release unzip gnupg \
    && mkdir -p ~/.gnupg \
    && chmod 600 ~/.gnupg \
    && echo "disable-ipv6" >> ~/.gnupg/dirmngr.conf \
    && apt-key adv --homedir ~/.gnupg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys E5267A6C \
    && apt-key adv --homedir ~/.gnupg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys C300EE8C \
    && echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu focal main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update

RUN apt-get install -y php8.1-cli php8.1-dev \
       php8.1-pgsql php8.1-sqlite3 php8.1-gd \
       php8.1-curl php8.1-memcached\
       php8.1-imap php8.1-mysql php8.1-mbstring \
       php8.1-xml php8.1-zip php8.1-bcmath php8.1-soap php8.1-readline \
       php8.1-msgpack php8.1-igbinary php8.1-ldap php8.1-fpm \
       php8.1-redis mysql\*

RUN curl -fsSL https://deb.nodesource.com/setup_17.x | sudo -E bash -
RUN apt install -y nodejs
RUN apt install -y php-cli unzip
RUN node -v
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
RUN npm install --global yarn

RUN echo "xdebug.mode=coverage" >> /etc/php/8.1/cli/php.ini
RUN echo "xdebug.mode=coverage" >> /etc/php/8.1/fpm/php.ini
RUN update-alternatives --set php /usr/bin/php8.1
