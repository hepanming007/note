#!/bin/bash
. fun.sh
#获取当前路径
cur_dir=$(pwd)
#读取config变量
eval `cat ./config.ini`
#进入安装包目录
echo $download_dir && cd $download_dir
install_redis()
{
    echo "====== Installing Redis ======"
    #解压安装包
    tar xzvf $redis_version.tar.gz
    #进入redis源文件目录
    cd $redis_version
    if [ `is_os_64_bit` ] ; then
        make PREFIX=/usr/local/redis install
    else
        make CFLAGS="-m32 -march=native" LDFLAGS="-m32"  PREFIX=/usr/local/redis install
    fi
    mkdir -p /usr/local/redis/etc/
    cp redis.conf  /usr/local/redis/etc/
    sed -i 's/daemonize no/daemonize yes/g' /usr/local/redis/etc/redis.conf
    sed -i 's/^# bind 127.0.0.1/bind 127.0.0.1/g' /usr/local/redis/etc/redis.conf
    cp $init_d_dir/redis /etc/init.d/redis
    chmod +x /etc/init.d/redis
    echo "Add to auto start..."
    start_up redis #开机启动
    /etc/init.d/redis start

    if [ -s /usr/local/redis/bin/redis-server ]; then
        echo "====== Redis install completed ======"
        echo "Redis installed successfully, enjoy it!"
    else
        echo "Redis install failed!"
    fi
}

uninstall_redis()
{
    echo "You will uninstall Redis..."
    sed -i '/redis.so/d' /usr/local/php/etc/php.ini
    restart_php
    remove_start_up redis
    echo "Delete Redis files..."
    rm -rf /usr/local/redis
    rm -rf /etc/init.d/redis
    echo "Uninstall Redis completed."
}

echo `date +"%Y-%m-%d %H:%M:%S"`
#main call
action=$1
case $action in
    'install')
    install_redis
    ;;
    'uninstall')
    uninstall_redis
    ;;
    *)
    echo "选择错误，请重选"
    ;;
    esac
echo `date +"%Y-%m-%d %H:%M:%S"`