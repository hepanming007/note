#!/bin/bash
. fun.sh
#获取当前路径
cur_dir=$(pwd)
#读取config变量
eval `cat ./config.ini`
#进入安装包目录
echo $download_dir && cd $download_dir
redis_ext(){
    echo "====== Installing php redis ext ======"
    sed -i '/redis.so/d' /usr/local/php/etc/php.ini
    get_php_ext_dir
    zend_ext="${zend_ext_dir}redis.so"
    if [ -s "${zend_ext}" ]; then
        rm -f "${zend_ext}"
    fi

    if [ -s ${redis_version} ]; then
        rm -rf ${redis_version}
    fi

    if echo `get_php_versoin`|grep -Eqi '^7.';then
        rm -rf phpredis
        git clone -b php7 https://github.com/phpredis/phpredis.git
        cd phpredis
    else
        download_files http://pecl.php.net/get/${redis_version}.tgz ${redis_version}.tgz
        tar -zxvf ${redis_version}.tgz
    fi
    /usr/local/php/bin/phpize
    ./configure --with-php-config=/usr/local/php/bin/php-config
    make && make install
    echo  'extension = "redis.so"' >>/usr/local/php/etc/php.ini
    restart_php
    echo "====== Installing php redis sucess ======"
}

memcached_ext(){
    sed -i '/memcache.so/d' /usr/local/php/etc/php.ini
    sed -i '/memcached.so/d' /usr/local/php/etc/php.ini
    get_php_ext_dir
    zend_ext= "${zend_ext_dir}memcached.so"
    if [ -s "${zend_ext}" ]; then
        rm -f "${zend_ext}"
    fi
    echo "Install memcached php extension..."
    download_files https://launchpad.net/libmemcached/1.0/1.0.18/+download/${libmemcached_version}.tar.gz
    tar -zxvf ${libmemcached_version}.tar.gz && cd $libmemcached_version
    ./configure --prefix=/usr/local/libmemcached --with-memcached
    make && make install
    cd -
    if echo `get_php_versoin`| grep -Eqi '^7.';then
        rm -rf php-memcached
        git clone -b php7 https://github.com/php-memcached-dev/php-memcached.git
        cd php-memcached
    else
        download_files http://pecl.php.net/get/${memcached_version}.tgz ${memcached_version}.tgz
        tar -zxvf  ${memcached_version}.tgz ${memcached_version} && cd $memcached_version
    fi
    /usr/local/php/bin/phpize
    ./configure --with-php-config=/usr/local/php/bin/php-config --enable-memcached --with-libmemcached-dir=/usr/local/libmemcached --disable-memcached-sasl
    make && make install
    echo  'extension = "memcached.so"' >>/usr/local/php/etc/php.ini
    echo "Install memcached php extension sucess..."
}


echo `date +"%Y-%m-%d %H:%M:%S"`
#main call
action=$1
case $action in
    'redis')
    redis_ext
    ;;
    'memcached')
     memcached_ext
    ;;
    *)
    echo "选择错误，请重选"
    ;;
    esac
echo `date +"%Y-%m-%d %H:%M:%S"`