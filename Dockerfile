FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx

RUN mkdir -p /site /run/nginx /run/php-fpm /etc/nginx/conf.d

COPY . /site

RUN echo 'server { \
    listen 80; \
    server_name localhost; \
    root /site; \
    index index.php index.html; \
    location / { try_files $uri $uri/ /index.php?$query_string; } \
    location ~ \.php$ { \
        include fastcgi_params; \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
    } \
}' > /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD php-fpm -D && nginx -g 'daemon off;'
