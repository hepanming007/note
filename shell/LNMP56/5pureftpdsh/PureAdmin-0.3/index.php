<?php
define ("ACCESS","true");
require_once("libs/dbclass.php");
require_once("config.php");
session_start();
if ($_REQUEST['o'] =='logout'){
	$_SESSION['admin']=NULL;
	header("location:index.php");
}
if ($_REQUEST['o']=='login'){
	$db=new Db;
	$db->dbconn($cfg['dbhost'],$cfg['dbuser'],$cfg['dbpasswd'],$cfg['dbname']);
	if ($_SESSION['scode'] == $_REQUEST['code']){
		$sql=sprintf("select * from admin where Username='%s' AND Password=md5('%s')",
		addslashes($_REQUEST['user']),addslashes($_REQUEST['passwd']));
		$r=$db->fetch_row($sql);
		if ($r){
			$_SESSION['admin']=$r['Username'];
			header("location:main.php");
		}else{
			$err="用户名或密码错误!<br />";
		}
		
	}else{
		$err="验证码错误!<br />";
	}	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PureAdmin 0.3</title>
</head>

<body>
<div align="center"><h1>PureFtpd 后台管理 v0.3</h1></div>
<?php if ($err){ ?>
<div align="center" style="color:#FF0000"><?php echo $err; ?></div>
<?php } ?>
<form id="form1" name="form1" method="post" action="">
  <table width="250" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#000000">
    <tr>
      <td width="179" bgcolor="#FFFFFF">用户名：</td>
      <td width="221" bgcolor="#FFFFFF"><input name="user" type="text" id="user" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF">密&nbsp;&nbsp;&nbsp;&nbsp;码：</td>
      <td bgcolor="#FFFFFF"><input name="passwd" type="password" id="passwd" /></td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF">验证码：</td>
      <td bgcolor="#FFFFFF"><input name="code" type="text" id="code" size="5" />
        &nbsp;
	  <img src="code.php" border="0" width="42" height="20" />
	  </td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF"><input name="o" type="hidden" id="o" value="login" /></td>
      <td bgcolor="#FFFFFF"><input type="submit" name="Submit" value="登录" />
      <input type="reset" name="Submit2" value="重置" /></td>
    </tr>
  </table>
</form>
<div align="center"><a href="http://www.yiyou.org" target="_blank">开源易有</a></div>
</body>

</html>
