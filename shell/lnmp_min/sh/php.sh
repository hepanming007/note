#!/bin/bash
. fun.sh
#获取当前路径
cur_dir=$(pwd)
#读取config变量
eval `cat ./config.ini`
echo_blue "this script will install php $version and php-fpm"
#进入安装包目录
cd $download_dir
echo_red "now in dir:".`pwd`

#依赖性安装
install_php_dependency(){
    yum install -y gcc gcc-c++ autoconf libjpeg libjpeg-devel \
    libpng libpng-devel freetype freetype-devel libpng libpng-devel \
    libxml2 libxml2-devel zlib zlib-devel glibc glibc-devel glib2 glib2-devel \
    bzip2 bzip2-devel ncurses curl openssl-devel gdbm-devel db4-devel libXpm-devel \
    libX11-devel gd-devel gmp-devel readline-devel libxslt-devel expat-devel xmlrpc-c xmlrpc-c-devel
}
#编译安装LibMcrypt
install_libmcypt(){
    tar xjvf $libmcrypt_version.tar.bz2
    cd $libmcrypt_version
    ./configure
    make && make install
    cd -
}
#安装php7
install_php_7(){
    #解压php安装包
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
}

init_php_config(){
    #拷贝PHP-FPM配置文件
    cp /usr/local/php/etc/php-fpm.conf.default /usr/local/php/etc/php-fpm.conf
    cp /usr/local/php/etc/php-fpm.d/www.conf.default  /usr/local/php/etc/php-fpm.d/www.conf
    #拷贝PHP.INI配置文件
    cat /usr/local/src/soft/$php_version/php.ini-development|grep -v ';'|grep -v '^$'>/usr/local/php/etc/php.ini
}

init_php_fpm(){
    #拷贝执行脚本到服务目录
    cat $init_d_dir/php-fpm > /etc/init.d/php-fpm
    #修改php-fpm权限
    chmod 755 /etc/init.d/php-fpm
    #开启php-fpm服务
    chkconfig --add php-fpm
    chkconfig --level 3 php-fpm on
    service php-fpm restart
}

install_php_dependency
install_libmcypt
install_php_7
init_php_config
init_php_fpm
echo_blue "$php_version install sucess...";