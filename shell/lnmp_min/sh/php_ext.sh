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
    cd $download_dir
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





echo `date +"%Y-%m-%d %H:%M:%S"`
#main call
action=$1
case $action in
    'redis')
    redis_ext
    ;;
    'memcached')
    uninstall_redis
    ;;
    *)
    echo "选择错误，请重选"
    ;;
    esac
echo `date +"%Y-%m-%d %H:%M:%