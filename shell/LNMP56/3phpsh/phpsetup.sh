#!/bin/bash

#依赖性安装
yum install -y gcc gcc-c++ autoconf libjpeg libjpeg-devel \
libpng libpng-devel freetype freetype-devel libpng libpng-devel \
libxml2 libxml2-devel zlib zlib-devel glibc glibc-devel glib2 glib2-devel \
bzip2 bzip2-devel ncurses curl openssl-devel gdbm-devel db4-devel libXpm-devel \
libX11-devel gd-devel gmp-devel readline-devel libxslt-devel expat-devel xmlrpc-c xmlrpc-c-devel

#获取当前路径
cur_dir=$(pwd)

#读取config变量
eval `cat ../config.ini`

#进入安装包目录
cd ../soft

#编译安装LibMcrypt
tar xjvf $libmcrypt_version.tar.bz2
cd $libmcrypt_version
./configure
make && make install

#解压php安装包
cd ..
tar zxvf $php_version.tar.gz
cd $php_version

#编译安装
./configure --prefix=/usr/local/php --with-config-file-path=/usr/local/php/etc --with-mysql=/usr/local/mysql \
--with-mysql-sock --with-mysqli=/usr/local/mysql/bin/mysql_config \
--enable-fpm --enable-soap --with-libxml-dir --enable-cli \
--with-openssl --with-mcrypt --with-mhash --with-pcre-regex --with-sqlite3 \
--with-zlib --enable-bcmath --with-iconv --with-bz2 --enable-calendar --with-curl \
--with-cdb --enable-dom --enable-exif --enable-fileinfo --enable-filter --with-pcre-dir \
--enable-ftp --with-gd --with-openssl-dir --with-jpeg-dir --with-png-dir --with-zlib-dir \
--with-freetype-dir --enable-gd-native-ttf --enable-gd-jis-conv --with-gettext --with-gmp \
--with-mhash --enable-json --enable-mbstring --disable-mbregex --disable-mbregex-backtrack \
--with-libmbfl --with-onig --enable-pdo --with-pdo-mysql --with-zlib-dir --with-pdo-sqlite \
--with-readline --enable-session --enable-shmop --enable-simplexml --enable-sockets \
--enable-sysvmsg --enable-sysvsem --enable-sysvshm --enable-wddx \
--with-libxml-dir --with-xsl --enable-zip --enable-mysqlnd-compression-support --with-pear \
--with-xmlrpc --enable-opcache
make && make install

#拷贝PHP-FPM配置文件
cat $cur_dir/php-fpm.conf > /usr/local/php/etc/php-fpm.conf

#拷贝PHP.INI配置文件
cat $cur_dir/php.ini > /usr/local/php/etc/php.ini

#拷贝执行脚本到服务目录
cat $cur_dir/php-fpm > /etc/init.d/php-fpm

#修改php-fpm权限
chmod 755 /etc/init.d/php-fpm

#开启php-fpm服务
chkconfig --add php-fpm
chkconfig --level 3 php-fpm on
service php-fpm restart

