#!/bin/bash
#����YUMԴΪ163.com
yum -y install wget
mv /etc/yum.repos.d/CentOS-Base.repo /etc/yum.repos.d/CentOS-Base.repo.backup
wget http://mirrors.163.com/.help/CentOS7-Base-163.repo -O /etc/yum.repos.d/CentOS-Base.repo
yum clean all
yum makecache

#��װ�������
yum -y install gcc make wget cmake vim vixie-cron ntpdate

#�賿4ʱ����ʱ��ͬ��
echo "0 4 * * * /usr/sbin/ntpdate us.pool.ntp.org > /dev/null 2>&1" >> /var/spool/cron/root
/usr/sbin/ntpdate us.pool.ntp.org  > /dev/null 2>&1

#�½�web��mysql�û�
useradd -d /dev/null -s /sbin/nologin webuser -u2001
useradd -d /dev/null -s /sbin/nologin mysql -u2002
