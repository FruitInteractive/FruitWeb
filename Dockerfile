FROM nginx:alpine
RUN rm -rf /usr/share/nginx/html/*
RUN mkdir -p /site
COPY . /site
RUN echo 'server { \
    listen 80; \
    server_name localhost; \
    root /site; \
    index index.html index.htm; \
    location / { try_files $uri $uri/ =404; } \
}' > /etc/nginx/conf.d/default.conf
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
