#!/bin/bash
# This script run at 00:00
# The Nginx logs path
log_dir="/data/logs"
#The definition of variables
dir_names=`ls -l /data/logs |awk '{print  $9}'`
source_path=/data

#The path for Nginx logs path by cuted
date=`date -d "yesterday" +"%Y%m%d"`

#Change logformat as combined and cut Nginx logs
#Log Cutting
for i in ${dir_names[*]}
do

	if [ -d "${log_dir}/$i" ];then
		/bin/mv ${log_dir}/$i/access.log ${log_dir}/$i/access${date}.log
		/bin/mv ${log_dir}/$i/error.log ${log_dir}/$i/error${date}.log
	fi

	#echo ${log_dir}/$i

done
#Reopen Nginx logs file
kill -USR1 `cat  /usr/local/nginx/var/nginx.pid`

#remind the file of log
find /data/logs/*/access*.log -mtime +60 |xargs rm -f
find /data/logs/*/error*.log -mtime +120 |xargs rm -f
