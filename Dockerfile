FROM nginx:alpine

RUN apk add --no-cache php8 php8-fpm php8-opcache php8-mysqli php8-json php8-session php8-phar

RUN mkdir -p /site /run/nginx /run/php-fpm

COPY . /site

RUN echo 'server { \
    listen 80; \
    server_name localhost; \
    root /site; \
    index index.php index.html; \
    location / { try_files $uri $uri/ /index.php?$query_string; } \
    location ~ \.php$ { \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        include fastcgi_params; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
    } \
}' > /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD php-fpm8 -F && nginx -g 'daemon off;'
