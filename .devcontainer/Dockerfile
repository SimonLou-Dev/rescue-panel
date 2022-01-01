# See here for image contents: https://github.com/microsoft/vscode-dev-containers/tree/v0.187.0/containers/ubuntu/.devcontainer/base.Dockerfile

# [Choice] Ubuntu version: bionic, focal
ARG VARIANT="focal"
FROM mcr.microsoft.com/vscode/devcontainers/base:0-${VARIANT}

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

RUN apt-get install -y --fix-missing php7.4-cli php7.4-dev php7.4-gd \
       php7.4-xdebug php7.4-sqlite3  \
       php7.4-curl php7.4-memcached\
       php7.4-mysql php7.4-mbstring \
       php7.4-xml php7.4-zip php7.4-mysqlnd php7.4-json \
       php7.4-ldap php7.4-fpm php7.4-pdo php7.4-soap php7.4-msgpack \
       php7.4-redis php7.4-dom mysql\*

RUN curl -fsSL https://deb.nodesource.com/setup_17.x | sudo -E bash -
RUN apt install -y nodejs
RUN apt install -y php-cli unzip
RUN node -v
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
RUN npm install --global yarn

RUN echo "xdebug.mode=coverage" >> /etc/php/7.4/cli/php.ini
RUN echo "xdebug.mode=coverage" >> /etc/php/7.4/fpm/php.ini


