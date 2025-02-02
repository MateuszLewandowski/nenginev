FROM nginx:1.24-alpine

COPY .docker/nginx/nginx.conf /etc/nginx/
COPY .docker/nginx/templates /etc/nginx/templates/

RUN echo "upstream php-upstream { server nenginev_php:9000; }" > /etc/nginx/conf.d/upstream.conf

RUN mkdir -p /var/cache/nginx

ENV BACKEND_SERVICE=nenginev_php
ENV BACKEND_PORT=9000

STOPSIGNAL SIGQUIT
