#!/bin/bash
php_tools_menu(){
  #输出提示框
    echo "###########################################"
    echo "0、切换版本到php5"
    echo "1、切换版本到php5.6"
    echo "2、切换版本到php5.5"
    echo "3、切换版本到php5.4"
    echo "###########################################"
    echo " "
    echo "请输入要选择项目的数字(0,1,2,3,4,5,6,7,8,9):"
    echo "您所选的项目是$num"
    read num
    #条件分支
    case $num in
        0)
        echo $num
        ;;
        1)
        echo $num
        ;;
        2)
        echo $num
        ;;
        3)
        echo $num
        ;;

        4)
        echo $num
        ;;

        *)
        echo "选择错误，请重选"
        ./main.sh
        ;;
    esac
}
php_menu(){
    #输出提示框
    echo "###########################################"
    echo "0、安装php7"
    echo "1、安装php5.6"
    echo "2、安装php5.5"
    echo "3、安装php5.4"
    echo "###########################################"
    echo " "
    echo "请输入要选择项目的数字(0,1,2,3,4,5,6,7,8,9):"
    echo "您所选的项目是$num"
    read num
    #条件分支
    case $num in
        0)
        echo $num
        php_tools_menu
        ;;
        1)
        echo $num
        php_tools_menu
        ;;
        2)
        echo $num
        php_tools_menu
        ;;
        3)
        echo $num
        php_tools_menu
        ;;

        4)
        echo $num
        php_tools_menu
        ;;

        *)
        echo "选择错误，请重选"
         php_menu
        ;;
    esac
}

php_menu




