#!/bin/bash
#pure-ftp��װ�ű�
#���ýű�����������ִ�м���
#��װ����
yum -y install gcc gcc-c++ make openssl openssl-devel perl wget
echo "����ɻ�������"
#��������Ŀ¼
if [ -d "/data/download" ];then 
cd /data/download
else
mkdir /data/download
cd /data/download
fi

#��ѹ��װ��
tar xvzf pure-ftpd-1.0.35.tar.gz
echo "�������������"
echo "���ڽ��л�����װ"
#����Ŀ¼
cd /data/download/pure-ftpd-1.0.35
#���ð�װ��Ϣ
./configure --prefix=/usr/local/pureftpd --without-inetd  --with-altlog --with-puredb --with-throttling --with-largefile --with-peruserlimits --with-tls --with-language=simplified-chinese
#���밲װ
make && make install
echo "����ɻ�����װ"
#���Ʒ���ű�����ϵͳ����
cp /data/download/pure-ftpd-1.0.35/contrib/redhat.init /etc/init.d/pure-ftpd
chmod +x /etc/init.d/pure-ftpd
chkconfig --add pure-ftpd
echo "�����ϵͳ����װ"
echo "��������FTP��������"
#����FTP
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

echo "��װ�Ѿ���ɣ������û��󼴿���������"

#���ÿ�������
service pure-ftpd start
chkconfig --add pure-ftpd
chkconfig --level 3 pure-ftpd on


#����ʵ��Ӧ�����������ftp�û�������




