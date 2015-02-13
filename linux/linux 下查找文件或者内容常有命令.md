# whereis <程序名称>
查找软件的安装路径

    -b 只查找二进制文件
    -m 只查找帮助文件
    -s 只查找源代码
    -u 排除指定类型文件
    -f 只显示文件名
    -B <目录> 在指定目录下查找二进制文件
    -M <目录> 在指定目录下查找帮助文件
    -S <目录> 在指定目录下查找源代码
    例子：
    [root@www ~]# whereis ifconfig 
    [root@www ~]# whereis -m passwd 查找帮助手册

# locate <文件名称>
在文件索引数据库中搜索文件

	-d <数据库路径> 搜索指定数据库
	updatedb 更新文件索引数据库
    [root@www ~]# locate passwd


# find [路径] <表达式>
find [PATH] [option] [action]

find path -option [-print] [ -exec -ok command] {} \;

find命令的参数

	 path:  find命令所查找的目录路径。例如用.来表示当前目录，用/来表示系统根目录
     option：过滤的选项 跟文件类型、大小、权限、时间、用户、组相关
	-print: find命令将匹配的文件输出到标准输出
	-exec:  find命令对匹配的文件执行该参数所给出的shell命令。
	        相应命令的形式为'command' { } \;，注意{ }和\；之间的空格。
	-ok：   和-exec的作用相同，只不过以一种更为安全的模式来执行该参数所给出的shell命令，
            在执行每一个命令之前，都会给出提示，让用户来确定是否执行。
    作用同xrags
	例：find . -name .svn | xargs rm -rf
    
option参数

    -name   filename   #查找名为filename的文件
    -perm   permission #按执行权限来查找
    -user   username   #按文件属主来查找
    -group groupname   #按组来查找
    -mtime   -n +n #按文件更改时间来查找文件，-n指n天以内，+n指n天以前
    -atime-n +n#按文件访问时间来查GIN: 0px">
    -ctime-n +n#按文件创建时间来查找文件，-n指n天以内，+n指n天以前
    -nogroup   #查无有效属组的文件，即文件的属组在/etc/groups中不存在
    -nouser#查无有效属主的文件，即文件的属主在/etc/passwd中不存
    -newer   f1 !f2#查更改时间比f1新但比f2旧的文件
    -typeb/d/c/p/l/f   #查是块设备、目录、字符设备、管道、符号链接、普通文件
    -size  n[c]#查长度为n块[或n字节]的文件
    -depth #使查找在进入子目录前先行查找完本目录
    -fstype  #查位于某一类型文件系统中的文件，这些文件系统类型通常可 在/etc/  fstab中找到
    -mount #查文件时不跨越文件系统mount点
    -follow#如果遇到符号链接文件，就跟踪链接所指的文件
    -cpio%;#查位于某一类型文件系统中的文件，这些文件系统类型通常可 在/etc/fstab中找到
    -mount #查文件时不跨越文件系统mount点
    -follow#如果遇到符号链接文件，就跟踪链接所指的文件
    -cpio  #对匹配的文件使用cpio命令，将他们备份到磁带设备中
    -prune #忽略某个目录
    -empty #查找空文件

 
从文件内容查找匹配指定字符串的行：
$ grep "被查找的字符串" 文件名
从文件内容查找与正则表达式匹配的行：
$ grep –e “正则表达式” 文件名
查找时不区分大小写：
$ grep –i "被查找的字符串" 文件名
查找匹配的行数：
$ grep -c "被查找的字符串" 文件名
从文件内容查找不匹配指定字符串的行：
$ grep –v "被查找的字符串" 文件名

从根目录开始查找所有扩展名为.log的文本文件，并找出包含”ERROR”的行
find / -type f -name "*.log" | xargs grep "ERROR"

系统查找到httpd.conf文件后即时在屏幕上显示httpd.conf文件信息。 
find/-name"httpd.conf"-ls

在根目录下查找某个文件
find . -name "test"

在某个目录下查找包含某个字符串的文件
grep -r "zh_CN" ./