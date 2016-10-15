#!/bin/bash
#参考文档:http://my.oschina.net/liucao/blog/470241
#安装前的准备工作
yum -y install gcc gcc-c++ autoconf automake zlib zlib-devel openssl openssl-devel pcre-devel

#新建web和mysql用户
useradd -d /dev/null -s /sbin/nologin webuser -u2001

#获取当前路径
cur_dir=$(pwd)

#读取config变量
eval `cat ./config.ini`

#进入安装包目录
cd ../src

#解压安装包
tar xzvf nginx-$nginx_version.tar.gz

#进入nginx源文件目录
cd nginx-$nginx_version

#版本信息伪装
sed -i 's/'$nginx_version'/7.5.7600 16385/g;s/"nginx\/" NGINX_VERSION/"Microsoft-IIS\/" NGINX_VERSION/g;s/"NGINX"/"Microsoft-IIS"/g' ./src/core/nginx.h

#编译安装
./configure --prefix=/usr/local/nginx --with-http_stub_status_module
make && make install

cd  $cur_dir
#设置日志切割脚本每日切割日志，配置日志切割脚本
mkdir /usr/local/nginx/var

#站点conf文件
mkdir /usr/local/nginx/conf/vhosts -p

#建立好文件存放目录,测试目录为test
mkdir -p /data/www/www.test.com/
mkdir -p /data/logs/www.test.com/

#备份并修改nginx.conf
mv /usr/local/nginx/conf/nginx.conf /usr/local/nginx/conf/nginx.conf.bak
cat $config_dir/nginx/nginx.conf > /usr/local/nginx/conf/nginx.conf

#配置测试子站信息
cat $config_dir/nginx/www.test.com.conf > /usr/local/nginx/conf/vhosts/www.test.com.conf

#定时切割日志
cat $cur_dir/logcron.sh > /usr/local/nginx/sbin/logcron.sh

#将 logcron.sh 加入定时任务
echo "0 0 * * * /bin/bash  /usr/local/nginx/sbin/logcron.sh" >> /var/spool/cron/root

#为 logcron.sh 脚本设置可执行属性
chmod +x /usr/local/nginx/sbin/logcron.sh

#设置服务脚本,创建NGINX开机启动脚本
cat $init_d_dir/nginx > /etc/init.d/nginx
#为 nginx.sh 脚本设置可执行属性
chmod +x /etc/init.d/nginx

#添加 Nginx 为系统服务（开机自动启动）
chkconfig --add nginx
chkconfig nginx on

service nginx start