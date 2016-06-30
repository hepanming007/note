#!/bin/bash
#使用说明
#/config.ini里头指定php版本信息
#./php.sh install_dep 安装依赖
#./php.sh install_new 全新安装包括依赖安装
#./php.sh install     全新安装 路径在/usr/local/php
#./php.sh install_m   多版本安装 路径在  /usr/local/$php_version
#./php.sh change_version 切换php版本

. fun.sh
#获取当前路径
cur_dir=$(pwd)
#读取config变量
eval `cat ./config.ini`
echo_blue "this script will install php $version and php-fpm"
#进入安装包目录
echo $download_dir
cd "$download_dir"
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
##使用单个版本
install_php_single(){
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
    cd -
}

init_php_config(){
    #拷贝PHP-FPM配置文件
    php_etc_dir='/usr/local/php/etc'
    cp /usr/local/php/etc/php-fpm.conf.default $php_etc_dir/php-fpm.conf
    sed -i 's/;pid/pid/' $php_etc_dir/php-fpm.conf
    sed -i 's/;error_log/error_log/' $php_etc_dir/php-fpm.conf
    cp /usr/local/php/etc/php-fpm.d/www.conf.default  $php_etc_dir/php-fpm.d/www.conf
    #拷贝PHP.INI配置文件
    cat ./$php_version/php.ini-development|grep -v ';'|grep -v '^$'>$php_etc_dir/php.ini
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

##使多单个版本共存
install_php_mutil(){
    #解压php安装包
    tar zxvf $php_version.tar.gz
    cd $php_version
    #编译安装
    ./configure --prefix=/usr/local/$php_version --with-config-file-path=/usr/local/$php_version/etc --with-mysql=/usr/local/mysql \
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
    cd -
}
##使多单个版本共存
init_php_mutil_config(){
   #拷贝PHP-FPM配置文件
    cur_php_etc_dir=/usr/local/$php_version/etc
    cp $cur_php_etc_dir/php-fpm.conf.default $cur_php_etc_dir/php-fpm.conf
    #cp $cur_php_etc_dir/php-fpm.d/www.conf.default  $cur_php_etc_dir/php-fpm.d/www.conf
    sed -i 's/;pid/pid/' $cur_php_etc_dir/php-fpm.conf
    sed -i 's/;error_log/error_log/' $cur_php_etc_dir/php-fpm.conf
    #拷贝PHP.INI配置文件
     cat ./$php_version/php.ini-development|grep -v ';'|grep -v '^$'>$cur_php_etc_dir/php.ini
}
#使用多个版本fpm切换
change_php_version(){
   pkill php-fpm
   sed  "s/local\/php/local\/$php_version/" $init_d_dir/php-fpm >/etc/init.d/php-fpm
   chmod 755 /etc/init.d/php-fpm
   service php-fpm restart
}


echo `date +"%Y-%m-%d %H:%M:%S"`
#main call
action=$1
case $action in
    'install_dep')
    echo $action
    install_php_dependency
    install_libmcypt
    ;;
    'install_new')
    echo $action
    install_php_dependency
    install_libmcypt
    install_php_single
    init_php_config
    init_php_fpm
    echo_blue "$php_version install sucess...";
    ;;
    'install')
    echo $action
    install_php_single
    init_php_config
    init_php_fpm
    echo_blue "$php_version install sucess...";
    ;;
    'install_m')
    echo $action
    install_php_mutil
    init_php_mutil_config
    ;;
    'change_version')
    # php_version=$2
    change_php_version
    echo_blue "change_php_version  sucess...";
    ;;
    *)
    echo "选择错误，请重选"
    ;;
    esac

echo `date +"%Y-%m-%d %H:%M:%S"`