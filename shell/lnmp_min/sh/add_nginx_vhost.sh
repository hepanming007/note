#!/bin/bash
site_name=$1

if [ -z ${site_name} ]
then
 echo "missing of site name";
 exit
fi


echo "建立所需站点目录"
mkdir -p /data/www/$site_name/
mkdir -p /data/logs/$site_name/

echo "修改站点配置conf文件"
/bin/cp -a /usr/local/nginx/conf/vhosts/www.test.com.conf /usr/local/nginx/conf/vhosts/$site_name.conf
sed -i 's/www.test.com/'$site_name'/g' /usr/local/nginx/conf/vhosts/$site_name.conf

echo "重启服务生效"
service php-fpm restart



