# apacheとphp7.2がインストールされたwebサーバ
FROM php:7.2-apache 

# 設定ファイルをコンテナにコピー
COPY ./php.ini /usr/local/etc/php/

RUN apt-get update && apt-get install -y \
  libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
  git vim unzip \
  && docker-php-ext-install pdo_mysql mysqli mbstring gd iconv exif\
  && apt-get clean
