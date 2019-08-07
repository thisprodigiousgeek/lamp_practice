# apacheとphp7.2がインストールされたwebサーバ
FROM php:7.2-apache 

# 設定ファイルをコンテナにコピー
COPY ./php.ini /usr/local/etc/php/

# pdoなどのライブラリをインストール
RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif

# vim をインストール
RUN apt-get install -y vim
