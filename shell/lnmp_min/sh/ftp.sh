#!/bin/bash
#pure-ftp安装脚本
#将该脚本放至服务器执行即可
#安装环境
yum -y install gcc gcc-c++ make openssl openssl-devel perl wget
echo "已完成环境更新"
#获取当前路径
cur_dir=$(pwd)

#读取config变量
eval `cat ./config.ini`

#进入安装包目录
cd ../src

#解压安装包
tar xvzf $ftp_version.tar.gz
echo "正在进行基本安装"
#进入目录
cd $ftp_version
#配置安装信息
./configure --prefix=/usr/local/pureftpd --without-inetd  --with-altlog --with-puredb --with-throttling --with-largefile --with-peruserlimits --with-tls --with-language=simplified-chinese
#编译安装
make && make install
echo "已完成基本安装"
#复制服务脚本设置系统服务
cp ./contrib/redhat.init /etc/init.d/pure-ftpd
chmod +x /etc/init.d/pure-ftpd
chkconfig --add pure-ftpd
echo "已完成系统服务安装"
echo "正在配置FTP基础参数"
#配置FTP
mkdir /usr/local/pureftpd/etc
mkdir -p /usr/local/pureftpd/var/
chmod 755 ./configuration-file/pure-config.pl
cp ./configuration-file/pure-config.pl /usr/local/pureftpd/sbin/
touch /usr/local/pureftpd/etc/pure-ftpd.conf
str=`cat <<EOF 
ChrootEveryone yes
BrokenClientsCompatibility no
MaxClientsNumber 50
Daemonize yes
MaxClientsPerIP 10
VerboseLog no
DisplayDotFiles yes
AnonymousOnly no
NoAnonymous no
SyslogFacility ftp
DontResolve yes
MaxIdleTime 5
PureDB /usr/local/pureftpd/etc/pureftpd.pdb
LimitRecursion 10000 10
AnonymousCanCreateDirs no
MaxLoad 4
PassivePortRange 30000 50000
AntiWarez yes
Umask 133:022
MinUID 100
AllowUserFXP no
AllowAnonymousFXP no
ProhibitDotFilesWrite yes
ProhibitDotFilesRead yes
AutoRename no
AnonymousCantUpload no
LogPID yes
AltLog clf:/usr/local/pureftpd/logs/pureftpd.log
NoChmod no
KeepAllFiles no
CreateHomeDir no
PIDFile /usr/local/pureftpd/var/pure-ftpd.pid
MaxDiskUsage 99
NoRename no
CustomerProof yes
TLS 0
IPV4Only yes
EOF
`
echo "$str">> /usr/local/pureftpd/etc/pure-ftpd.conf
sed -i 's/local\/sbin/local\/pureftpd\/sbin/g;s/etc\/pure-ftpd.conf/usr\/local\/pureftpd\/etc\/pure-ftpd.conf/g;s/var\/run\/pure-ftpd.pid/usr\/local\/pureftpd\/var\/pure-ftpd.pid/g' /etc/init.d/pure-ftpd

echo "安装已经完成，配置用户后即可启动服务。"

#设置开机启动
service pure-ftpd start
chkconfig --add pure-ftpd
chkconfig --level 3 pure-ftpd on
#根据实际应用情况，设置ftp用户和密码