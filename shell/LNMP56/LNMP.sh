#!/bin/bash
#基于64位最小化版Centos 7安装
#必须先配置好网络
#拷贝当前目录的所有文件到服务器上
#直接在当前目录运行
#由于中文会产生乱码，最好是使用winscp拷贝文件到服务器

#获取当前路径
LNMP_dir=$(pwd)

#读取config变量
eval `cat ./config.ini`

#给各个脚本赋执行权限
chmod +x $LNMP_dir/0yum163/yum163.sh
chmod +x $LNMP_dir/1nginxsh/nginxsetup.sh
chmod +x $LNMP_dir/2mysqlsh/mysqlsetup.sh
chmod +x $LNMP_dir/3phpsh/phpsetup.sh
#输出提示框
echo "###########################################"
echo "0、安装基础组件，自动更新时间"
echo "1、一键安装LNMP环境"
echo "2、仅安装Nginx"
echo "3、仅安装Mysql"
echo "4、仅安装php"
echo "###########################################"
echo " "
echo "请输入要选择项目的数字(0,1,2,3,4,5,6,7,8,9):"
read num
echo "您所选的项目是$num"



#条件分支
case $num in
	0)
	cd $LNMP_dir/0yum163
	./yum163.sh
	echo "更新YUM源为163.com完成"
	;;

	1)
	cd $LNMP_dir/0yum163
	./yum163.sh
	cd $LNMP_dir/1nginxsh
	./nginxsetup.sh
	cd $LNMP_dir/2mysqlsh
	./mysqlsetup.sh
	cd $LNMP_dir/3phpsh
	./phpsetup.sh
	echo "LNMP环境安装完成"
	;;

	2)
	cd $LNMP_dir/1nginxsh
	./nginxsetup.sh
	echo "Nginx安装完成"
	;;

	3)
	cd $LNMP_dir/2mysqlsh
	./mysqlsetup.sh
	echo "Mysql安装完成"
	;;

	4)
	cd $LNMP_dir/3phpsh
	./phpsetup.sh
	echo "PHP安装完成"
	;;

	*)
	echo "选择错误，请重选"
	./LNMP.sh
	;;
esac








