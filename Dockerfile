FROM orsolin/docker-php-5.3-apache:latest

RUN echo "max_execution_time = 600" >> $PHP_INI_DIR/php.ini

WORKDIR /var/www/html

COPY . /var/www/html

RUN chmod -R 775 /var/www/html/crm
RUN chown -R www-data:www-data /var/www/html/crm

CMD ["apache2-foreground"]