FROM martkcz/php:8.0.8

RUN apt-get update
## nginx
RUN apt-get install -y nginx
## supervisor
RUN apt-get install -y supervisor

## Config files
COPY ./conf/supervisord.conf /etc/supervisor/supervisord.conf
COPY ./conf/nginx.conf /etc/nginx/nginx.conf
COPY ./conf/fastcgi_params.conf /etc/nginx/fastcgi_params
COPY ./conf/php-fpm.conf /etc/php-fpm-user.conf
COPY ./conf/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./conf/nginx-app.conf /etc/nginx/conf.d/nginx-app.conf

## Executables
COPY ./builder /startup/builder
COPY ./bin/run.bash /startup/run.bash

## Empty files
RUN touch /etc/nginx/conf.d/nginx-gen.conf

WORKDIR /app

CMD ["/startup/run.bash"]
EXPOSE 8080
