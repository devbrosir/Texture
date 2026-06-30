FROM nginx:alpine
COPY --from=texture:latest /var/www/html/public /var/www/html/public
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
