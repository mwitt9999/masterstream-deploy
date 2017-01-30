FROM php:7-fpm

# Install Server Services
RUN apt-get update && apt-get install -y \
	curl \
	vim \
	git \
	supervisor \
	wget \
	sudo \
	nginx \
	openssh-server

#Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

#Install Nodejs
RUN curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
RUN apt-get install -y nodejs

#Add Supervisor Conf file
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

#Add vhost config for nginx
ADD test-deployment.app.conf /etc/nginx/conf.d/test-deployment.app.conf

#Start supervisord
#CMD ["/usr/bin/supervisord"]

#Expose ports
EXPOSE 8081 22
