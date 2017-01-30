FROM php:7-fpm

# Install modules
RUN apt-get update && apt-get install -y \
	php-pear \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
	libfreetype6-dev \
	libjpeg62-turbo-dev \
	libmcrypt-dev \
	libpng12-dev \
	curl \
	nano \
	vim \
	git \
	supervisor \
	sendmail \
	wget \
	sudo \
	openssh-server

#install nodejs
RUN curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
RUN apt-get install -y nodejs

#install and configure Xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN sed -i '1 a xdebug.remote_autostart=true' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN sed -i '1 a xdebug.remote_mode=req' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN sed -i '1 a xdebug.remote_handler=dbgp' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN sed -i '1 a xdebug.remote_connect_back=1 ' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN sed -i '1 a xdebug.remote_port=9001' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN sed -i '1 a xdebug.remote_host=127.0.0.1' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN sed -i '1 a xdebug.remote_enable=1' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN sed -i '1 a xdebug.idekey="PHPSTORM"' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "alias phpxdebug='php -dxdebug.remote_host=192.168.10.242'" >> ~/.bashrc

#Install PHP Modules
RUN apt-get update && \
    apt-get install -y git libssh2-1 libssh2-1-dev && \
    pecl install ssh2-1.0 && \
    docker-php-ext-enable ssh2

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
	&& docker-php-ext-install gd

RUN docker-php-ext-install mcrypt

RUN docker-php-ext-install pdo pdo_mysql mysqli

#Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

#setup SSH keys
RUN mkdir -p ~/.ssh
RUN mkdir -p /var/www/.ssh

COPY ssh_config /ssh_config
RUN cat /ssh_config >> /etc/ssh/ssh_config

COPY id_rsa /var/www/.ssh/id_rsa
COPY id_rsa.pub /var/www/.ssh/id_rsa.pub

#Setup Entrypoint and supervisord scripts
COPY ./entrypoint.sh /entrypoint.sh
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

#Start Supervisord
CMD ["/usr/bin/supervisord"]
