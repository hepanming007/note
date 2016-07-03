#!/bin/bash
site_name=$1
echo "建立所需站点目录"
mkdir -p /data/www/$site_name/
mkdir -p /data/logs/$site_name/

echo "修改站点配置conf文件"
/bin/cp -a /usr/local/nginx/conf/vhosts/www.test.com.conf /usr/local/nginx/conf/vhosts/$dn.conf
sed -i 's/www.test.com/'$site_name'/g' /usr/local/nginx/conf/vhosts/$site_name.conf

echo "修改站点配置php.ini"
echo " " > /usr/local/php/etc/php.ini
echo "[dba]" > /usr/local/php/etc/php.ini
echo "[PATH=/data/www/'$site_name']" > /usr/local/php/etc/php.ini
echo "open_basedir=/data/www/'$site_name'/:/tmp" > /usr/local/php/etc/php.ini

echo "重启服务生效"
service php-fpm restart



