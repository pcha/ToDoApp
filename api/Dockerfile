FROM php:8.0-apache

WORKDIR /var/www

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony/bin/symfony /usr/local/bin/symfony

RUN apt-get update

RUN apt-get install -y git

RUN apt-get install -y zlib1g-dev libicu-dev g++ \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-configure opcache \
    && docker-php-ext-install opcache \
    && docker-php-ext-configure pdo_mysql \
    && docker-php-ext-install pdo_mysql
RUN pecl install uopz

COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf
#COPY ./todo-api /var/www

RUN a2enmod rewrite

#RUN echo "Creating app datatabase..." && pwd && ls && php bin/console doctrine:database:create --if-not-exists -n \
#    && echo "Running migrations..." && php bin/console doctrine:migration:migrate -n \
#    && echo "Creating test datatabase..." && php bin/console doctrine:database:create --if-not-exists -n --env=test \
#    && echo "Runnig migrations in test database" && php bin/console doctrine:migration:migrate -n

EXPOSE 80
