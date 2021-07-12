## Docker hub

Current PHP version 8.0.8

https://hub.docker.com/repository/docker/martkcz/google-app-php

## Usage with google app engine

app.yml
```yaml
runtime: custom
env: flex
```

Dockerfile
```dockerfile
FROM martkcz/google-app-php

# configs
# that is not necessary
COPY ./conf/nginx-app.conf /etc/nginx/conf.d/nginx-app.conf

## application files
ADD . /app
RUN chown -R www-data.www-data /app
```

## Nginx config
default /etc/nginx/conf.d/nginx-app.conf

```apacheconf
location / {
  # try to serve files directly, fallback to controller
  try_files $uri /index.php?$args;
}
```

## Customize with command line
Enables http -> https and www -> non-www redirection
```dockerfile
CMD ["/startup/run.bash", "--https", "--non-www"]
```

### Arguments

`--port="8080"` - (default: 8080) set port

`--https` - enable http to https redirection

`--non-www` - enable www to non-www redirection

`--cache-css-js-long` - cache css and js for long time

`--cache-media-long` - cache images, icons, video, audio, HTC for long time

`--xdebug` - enable xdebug

`--memory-limit="64M"` - (default: 64M) php memory limit

`--max-execution-time="30"` - (default: 30) php max execution time

`--max-input-time="30"` - (default: 30) php max input time

`--mkdir="/app/var/tmp" --mkdir="/app/var/log"` - makes directories

## Development with docker-compose

```yaml
version: '3.3'

services:
  app:
    image: martkcz/google-app-php
    ports:
      - "80:8080"
    restart: always
    command: /startup/run.bash --xdebug --memory-limit="256M" --max-execution-time="60" --mkdir="/app/var/log" --mkdir="/app/var/tmp"
    volumes:
      - "./:/app"
```
