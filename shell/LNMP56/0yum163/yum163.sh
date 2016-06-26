#!/bin/bash
#更新YUM源为163.com
yum -y install wget
mv /etc/yum.repos.d/CentOS-Base.repo /etc/yum.repos.d/CentOS-Base.repo.backup
wget http://mirrors.163.com/.help/CentOS7-Base-163.repo -O /etc/yum.repos.d/CentOS-Base.repo
yum clean all
yum makecache

#安装基本组件
yum -y install gcc make wget cmake vim vixie-cron ntpdate

#凌晨4时进行时间同步
echo "0 4 * * * /usr/sbin/ntpdate us.pool.ntp.org > /dev/null 2>&1" >> /var/spool/cron/root
/usr/sbin/ntpdate us.pool.ntp.org  > /dev/null 2>&1

#新建web和mysql用户
useradd -d /dev/null -s /sbin/nologin webuser -u2001
useradd -d /dev/null -s /sbin/nologin mysql -u2002
