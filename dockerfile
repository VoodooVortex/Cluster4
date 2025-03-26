# ใช้ PHP 8.2 พร้อม Apache เป็น base image
# เขียนโค้ดตรงนี้
FROM php:8.2-apache

# เปิดใช้งาน mod_rewrite ของ Apache เพื่อให้ Laravel สามารถใช้ Pretty URLs ได้
RUN a2enmod rewrite
# ตั้งค่าตำแหน่งทำงานเป็น /var/www/html
# เขียนโค้ดตรงนี้
WORKDIR /var/www/html
# คัดลอกโค้ด Laravel ไปยังโฟลเดอร์ /var/www/html ภายใน container
COPY . .

# ติดตั้ง Composer โดยใช้คำสั่ง PHP แทน multi-stage build
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer
# ย้าย Composer ไปที่ /usr/local/bin เพื่อให้เรียกใช้งานง่ายขึ้น

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    libsqlite3-dev \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip


# ตั้งค่า permission ให้ Laravel สามารถทำงานได้อย่างถูกต้อง
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache



# ติดตั้ง dependencies ของ Laravel โดยไม่รวมแพ็กเกจที่ใช้สำหรับ development
# เขียนโค้ดตรงนี้
RUN composer install --no-dev --optimize-autoloader

RUN php artisan storage:link

# เปิดพอร์ต 80 เพื่อให้ container สามารถรับคำขอ HTTP ได้
# เขียนโค้ดตรงนี้
EXPOSE 80

# รัน Apache เมื่อตัว container เริ่มทำงาน
# เขียนโค้ดตรงนี้
CMD ["apache2-foreground"]
