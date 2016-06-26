<?php
$cfg['dbhost']='localhost'; //mysql host
$cfg['dbname']='pureftpd';  //mysql db name
$cfg['dbuser']='root';		//mysql user
$cfg['dbpasswd']='';		//mysql password

//ftp config
$cfg['page']=15;
//ftp passwd type : TEXT/CRYPT/MD5
$cfg['passwdtype']='TEXT';
//ftp default
$cfg['uid']=1000;  //uid
$cfg['gid']=1000;	//gid
$cfg['dir']='/home/ftphome/'; //dir
$cfg['qf']=0;	//quotafiles
$cfg['qs']=100;	//quotasize
$cfg['ul']=0;	//ULBandwidth
$cfg['dl']=0;	//DLBandwidth
$cfg['ur']=0;	//ULRatio
$cfg['dr']=0;	//DLRatio
$cfg['status']=1; //status
$cfg['ip']= '*';	//ipaddress
?>
