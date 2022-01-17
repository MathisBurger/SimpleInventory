FROM node:17-alpine AS webBuild

WORKDIR /webBuild
COPY . .
RUN npm i 
RUN npm run build

FROM composer:2 AS phpVendor

WORKDIR /deps
COPY . .
RUN composer install
FROM tobi312/php:8.0-apache AS final

WORKDIR /var/www/html
RUN mkdir vendor
COPY --from=webBuild ./webBuild .
COPY --from=phpVendor ./deps/vendor ./vendor
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN php /var/www/html/bin/console doctrine:migrations:migrate --no-interaction

EXPOSE 80
