#参考地址:http://blog.csdn.net/wendi_0506/article/details/39478369
#依赖包安装
yum -y install bison gcc gcc-c++  autoconf automake zlib* libxml* ncurses-devel libtool-ltdl-devel* make cmake
#获取当前路径
cur_dir=$(pwd)
#读取config变量
eval `cat ./config.ini`
#进入安装包目录
echo $download_dir && cd $download_dir
#建立用户
useradd -d /dev/null -s /sbin/nologin mysql -u2002
#建立数据存放目录，mysql程序则安装在 /usr/local/mysql 目录中
mkdir /data/mysql
chown -R mysql:mysql /data/mysql
#解压mysql安装包
tar zxvf $mysql_version.tar.gz
cd $mysql_version
#编译安装MYSQL
cmake . -DCMAKE_INSTALL_PREFIX=/usr/local/mysql -DMYSQL_DATADIR=/data/mysql -DSYSCONFDIR=/etc/
#-DDOWNLOAD_BOOST=1 -DWITH_BOOST=/usr/local/boost  5.7以后需要添加此选项
make && make install

#编辑/etc/my.cnf
cat $cur_dir/my.cnf > /etc/my.cnf

#建立数据库，64位系统必须指定目录
/usr/local/mysql/scripts/mysql_install_db --user=mysql --basedir=/usr/local/mysql --datadir=/data/mysql
#mysqld --initialize --user=mysql --basedir=/usr/local/mysql/ --datadir=/data/mysql /  5.7
#配置服务脚本
cat $cur_dir/mysqld > /etc/rc.d/init.d/mysqld
chmod 755 /etc/rc.d/init.d/mysqld
chkconfig --add mysqld
chkconfig --level 3 mysqld on

#取消严格模式
sed -i 's/sql_mode=NO_ENGINE_SUBSTITUTION,STRICT_TRANS_TABLES/#sql_mode=NO_ENGINE_SUBSTITUTION,STRICT_TRANS_TABLES/g' /usr/local/mysql/my.cnf
echo "sql_mode=NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" >> /usr/local/mysql/my.cnf

#启动服务，后续还需设置密码和安全优化，待处理
service mysqld restart