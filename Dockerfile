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
        fastcgi_pass unix:/run/php-fpm.sock; \
        fastcgi_index index.php; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
    } \
}' > /etc/nginx/conf.d/default.conf

RUN echo '[www]' > /etc/php81/php-fpm.d/www.conf && \
    echo 'listen = /run/php-fpm.sock' >> /etc/php81/php-fpm.d/www.conf && \
    echo 'listen.owner = nginx' >> /etc/php81/php-fpm.d/www.conf && \
    echo 'listen.group = nginx' >> /etc/php81/php-fpm.d/www.conf

EXPOSE 80

CMD php-fpm -F -R -O /run/php-fpm.sock & nginx -g 'daemon off;'
