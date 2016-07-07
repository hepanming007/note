#!/bin/bash
. fun.sh
#获取当前路径
cur_dir=$(pwd)
#读取config变量
eval `cat ./config.ini`
#进入安装包目录
echo $download_dir && cd $download_dir

install_memcached(){
    echo "====== Installing memcached ======"
    #yum -y install libevent-devel
    tar -zxvf $memcached_version.tar.gz
    cd  $memcached_version
    ./configure --prefix=/usr/local/memcached
    make &&make install
    ln -sf /usr/local/memcached/bin/memcached /usr/bin/memcached
    cd -
    cp $init_d_dir/memcached /etc/init.d/memcached
    chmod +x /etc/init.d/memcached
    useradd -s /sbin/nologin nobody
    if [ ! -d /var/lock/subsys ]; then
      mkdir -p /var/lock/subsys
    fi
    start_up memcached
    /etc/init.d/memcached start
    if  [ -s /usr/local/memcached/bin/memcached ]; then
        echo "====== Memcached install completed ======"
        echo "Memcached installed successfully, enjoy it!"
    else
        sed -i "/memcached.so/d" /usr/local/php/etc/php.ini
        echo "Memcached install failed!"
    fi
}


un_install_memcached(){
    echo "You will uninstall Memcached..."
    sed -i '/memcache.so/d' /usr/local/php/etc/php.ini
    sed -i '/memcached.so/d' /usr/local/php/etc/php.ini
    restart_php
    remove_start_up memcached
    echo "Delete Memcached files..."
    rm -rf /usr/local/libmemcached
    rm -rf /usr/local/memcached
    rm -rf /etc/init.d/memcached
    rm -rf /usr/bin/memcached
    echo "Uninstall Memcached completed."
}

echo `date +"%Y-%m-%d %H:%M:%S"`
#main call
action=$1
case $action in
    'install')
    install_memcached
    ;;
    'uninstall')
    un_install_memcached
    ;;
    *)
    echo "选择错误，请重选"
    ;;
    esac
echo `date +"%Y-%m-%d %H:%M:%S"`

