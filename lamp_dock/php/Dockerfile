# apacheとphp7.2がインストールされたwebサーバ
FROM php:7.2-apache 

# 設定ファイルをコンテナにコピー
COPY ./php.ini /usr/local/etc/php/

# vim 及び 各種PHPモジュールのインストール、キャッシュ消去
RUN apt-get update && apt-get install -y \
  vim \
  && docker-php-ext-install pdo_mysql mysqli mbstring exif \
  && apt-get clean
