FROM nginx:latest

# Install modules
RUN apt-get update && apt-get install -y \
	curl \
	nano \
	vim \
	git \
	supervisor \
	wget \
	sudo

#Setup Entrypoint and supervisord scripts
COPY ./entrypoint.sh /entrypoint.sh
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

#Add custom Nginx vhost config files
ADD nginx-conf/masterstream-deploy.app.conf /etc/nginx/conf.d/masterstream-deploy.app.conf

#Start Supervisord
#CMD ["/usr/bin/supervisord"]

#Expose ports
EXPOSE 8080
