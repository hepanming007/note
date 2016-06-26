<?php 
define("ACCESS","true");
require_once("lib.php");
if ($_REQUEST['o']=='del'){
	$sql=sprintf("delete from admin where Username='%s'",
		addslashes($_REQUEST['u']));
	if ($_SESSION['admin'] != $_REQUEST['u']){
		$db->q($sql);
	}
}
if ($_REQUEST['o']== 'add'){
	$sql=sprintf("insert into admin (Username,Password) VALUE('%s',md5('%s'))",
	addslashes($_REQUEST['u']),$_REQUEST['p']);
	$chk=sprintf("select * from admin where Username='%s'",addslashes($_REQUEST['u']));
	$row=$db->fetch_all($chk);
	if ($row){
		echo "添加的管理员已经存在";
	}else{
		$db->q($sql);
	}
}
$sql="select * from admin";
$row=$db->fetch_all($sql);
?>
<table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
<thead >
  <tr>
    <td width="251">管理员</td>
    <td width="49">操作</td>
  </tr></thead>
  <?php for($i=0;$i<count($row);$i++){ ?>
  <tr onMouseover="this.bgColor='#D6AF70'" onMouseout="this.bgColor='#FFFFFF'">
    <td><?php echo $row[$i]['Username'] ?></td>
    <td><a href="javascript:deladmin('<?php echo $row[$i]['Username'] ?>');"><img src="images/del.gif" alt="删除" width="12" height="12" border="0"></a></td>
  </tr>
  <?php  } ?>
</table><br />
<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#000000">
  <tr>
    <td width="81" bgcolor="#FFFFFF">用户名</td>
    <td width="216" bgcolor="#FFFFFF"><input name="username" type="text" id="username" /></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">密码</td>
    <td bgcolor="#FFFFFF"><input name="passwd1" type="password" id="passwd1" /></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">确认密码</td>
    <td bgcolor="#FFFFFF"><input name="passwd2" type="password" id="passwd2" /></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF"><input type="button" name="Submit" value="创建管理员" onClick="createadmin();" /></td>
  </tr>
</table>
