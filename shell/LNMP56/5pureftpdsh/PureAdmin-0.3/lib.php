<?php
if (!ACCESS){echo "can't access";exit;};
require_once("config.php");
require_once("libs/dbclass.php");
require_once("libs/func.php");
session_start();
if (!$_SESSION['admin']){
	header("location:index.php");
}
$db=new Db;
$db->dbconn($cfg['dbhost'],$cfg['dbuser'],$cfg['dbpasswd'],$cfg['dbname']);
$TITLE="PureAdmin v 0.3 （PureFtp 后台管理程序）";
?>
