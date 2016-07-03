color_text()
{
  echo -e " \e[0;$2m$1\e[0m"
}
echo_red()
{
  echo $(color_text "$1" "31")
}
echo_green()
{
  echo $(color_text "$1" "32")
}
echo_yellow()
{
  echo $(color_text "$1" "33")
}
echo_blue()
{
  echo $(color_text "$1" "34")
}


get_php_ext_dir()
{
    cur_php_version=`/usr/local/php/bin/php -r 'echo PHP_VERSION;'`
    if echo "${cur_php_version}" | grep -Eqi '^5.2.'; then
       zend_ext_dir="/usr/local/php/lib/php/extensions/no-debug-non-zts-20060613/"
    elif echo "${cur_php_version}" | grep -Eqi '^5.3.'; then
       zend_ext_dir="/usr/local/php/lib/php/extensions/no-debug-non-zts-20090626/"
    elif echo "${cur_php_version}" | grep -Eqi '^5.4.'; then
       zend_ext_dir="/usr/local/php/lib/php/extensions/no-debug-non-zts-20100525/"
    elif echo "${cur_php_version}" | grep -Eqi '^5.5.'; then
       zend_ext_dir="/usr/local/php/lib/php/extensions/no-debug-non-zts-20121212/"
    elif echo "${cur_php_version}" | grep -Eqi '^5.6.'; then
       zend_ext_dir="/usr/local/php/lib/php/extensions/no-debug-non-zts-20131226/"
   elif echo "${cur_php_version}" | grep -Eqi '^7.0.'; then
       zend_ext_dir="/usr/local/php/lib/php/extensions/no-debug-non-zts-20151012/"
    fi
}

is_os_64_bit(){
  if [[ `getconf WORD_BIT` = '32' && `getconf LONG_BIT` = '64' ]] ; then
     echo true
  else
    echo false
  fi
}

start_up()
{
    init_name=$1
    echo "Add ${init_name} service at system startup..."
    chkconfig --add ${init_name}
    chkconfig ${init_name} on
}

remove_start_up()
{
    init_name=$1
    echo "Removing ${init_name} service at system startup..."
    chkconfig ${init_name} off
    chkconfig --del ${init_name}
}


restart_php()
{
    if [ -s /usr/local/apache/bin/httpd ] && [ -s /usr/local/apache/conf/httpd.conf ] && [ -s /etc/init.d/httpd ]; then
        echo "Restarting Apache......"
        /etc/init.d/httpd restart
    else
        echo "Restarting php-fpm......"
        /etc/init.d/php-fpm restart
    fi
}

get_php_versoin(){
  echo  `/usr/local/php/bin/php -r 'echo PHP_VERSION;'`
}



download_files()
{
    local url=$1
    local filename=$2
    if [ -s "${filename}" ]; then
        echo "${filename} [found]"
    else
        echo "Notice: ${filename} not found!!!download now..."
        wget -c --progress=bar:force ${url}
    fi
}