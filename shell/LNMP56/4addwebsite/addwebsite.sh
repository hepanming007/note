#!/bin/bash

echo "按前面的安装步骤，会产生test.com的站点"
echo "脚本实现站点的域名等配置更改"
echo "##请勿输错，否则要手动更改配置信息!##"
echo "涉及到的目录：/usr/local/nginx/conf/vhosts/，/usr/local/php/etc/php.ini"
echo "请输入站点的域名(如www.xxxx.com)"
read dn
echo "您输入站点的域名是:$dn"
echo " "

echo "建立所需站点目录"
mkdir -p /data/www/$dn/
mkdir -p /data/logs/$dn/

echo "修改站点配置conf文件"
/bin/cp -a /usr/local/nginx/conf/vhosts/www.test.com.conf /usr/local/nginx/conf/vhosts/$dn.conf
sed -i 's/www.test.com/'$dn'/g' /usr/local/nginx/conf/vhosts/$dn.conf

echo "修改站点配置php.ini"
echo " " > /usr/local/php/etc/php.ini
echo "[dba]" > /usr/local/php/etc/php.ini
echo "[PATH=/data/www/'$dn']" > /usr/local/php/etc/php.ini
echo "open_basedir=/data/www/'$dn'/:/tmp" > /usr/local/php/etc/php.ini

echo "重启服务生效"
service php-fpm restart



