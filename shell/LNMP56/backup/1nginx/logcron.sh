#!/bin/bash
# This script run at 00:00

# The Nginx logs path
log_dir="/data/logs"
#The definition of variables
dir_names=(www)
source_path=/data

#The path for Nginx logs path by cuted
date=`date -d "yesterday" +"%Y%m%d"`

#Change logformat as combined and cut Nginx logs

#Existence of judgment directory
for i in ${dir_names[*]}
do
        if [ ! -d "$log_dir/$i" ];then
                mkdir -p $log_dir/$i
        fi
done

#Log Cutting
for i in ${dir_names[*]}
do
        cd $source_path/$i
        dir_web=`ls -l /data/$i |awk '{print  $9}'`
        for j in ${dir_web[*]}
        do
                /bin/mv ${log_dir}/$i/access.log ${log_dir}/$j/access${date}.log
                /bin/mv ${log_dir}/$i/error.log ${log_dir}/$j/error${date}.log
        done
done


#Reopen Nginx logs file
kill -USR1 `cat  /usr/local/nginx/var/nginx.pid`

#remind the file of log
find /data/logs/*/access*.log -mtime +60 |xargs rm -f
find /data/logs/*/error*.log -mtime +120 |xargs rm -f
