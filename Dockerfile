FROM php:7.4.6-apache
COPY ./SpotifyBetterCollab /var/www/html/
EXPOSE 80
RUN apt-get update && apt-get install -y libmcrypt-dev \
	&& apt-get install libzip-dev -y \
	&& apt-get install nodejs -y \
	&& apt-get install npm -y \
	&& docker-php-ext-install zip \
	&& docker-php-ext-install mysqli \
	&& docker-php-ext-enable mysqli \
	&& docker-php-ext-install pdo pdo_mysql \
	&& apachectl restart 

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
