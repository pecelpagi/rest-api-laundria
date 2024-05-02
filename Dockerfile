FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
    zip \
    unzip

RUN docker-php-ext-install mysqli

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY . .

RUN composer install

CMD [ "php", "-S", "0.0.0.0:80", "./index.php" ]