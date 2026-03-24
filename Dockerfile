FROM nginx:alpine

RUN apk add --no-cache php81 php81-fpm php81-opcache php81-mysqli php81-phar php81-json

RUN mkdir -p /site /run/nginx /run/php-fpm

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

CMD php-fpm81 -F && nginx -g 'daemon off;'
