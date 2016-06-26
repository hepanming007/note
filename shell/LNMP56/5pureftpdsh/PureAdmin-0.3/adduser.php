<?php
define("ACCESS","true");
require_once("lib.php");
//print_r($_REQUEST);
if ($_REQUEST['o'] =='add'){
	$sql=sprintf("INSERT INTO users (User,
	Password,Uid,Gid,Dir,QuotaFiles,QuotaSize,
	ULBandwidth,DLBandwidth,Ipaddress,
	Comment,Status,ULRatio,DLRatio)VALUE(
	'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
	addslashes($_REQUEST['user']),getpw($cfg['passwdtype'],$_REQUEST['passwd']),
	$_REQUEST['uid'],$_REQUEST['gid'],$_REQUEST['dir'],$_REQUEST['qf'],$_REQUEST['qs'],
	$_REQUEST['ul'],$_REQUEST['dl'],$_REQUEST['ip'],addslashes($_REQUEST['comment']),
	$_REQUEST['st'],$_REQUEST['ur'],$_REQUEST['dr']
	);
	$chk=sprintf("select * from users where User='%s'",addslashes($_REQUEST['user']));
	$row=$db->fetch_all($chk);
	if ($row){
		echo "用户已存在";
	}else{
		echo "添加成功";
		$db->q($sql);
	}
	//echo $sql;
}
?>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#000000">
  <tr>
    <td width="65" bgcolor="#FFFFFF">用户名</td>
    <td width="222" bgcolor="#FFFFFF"><input name="user" type="text" id="user" onkeyup="App();" size="20" onblur="chkUser();" />
    <span id="chk"></span></td>
    <td width="205" bgcolor="#FFFFFF">用户ID(uid)
    <input name="uid" type="text" id="uid" value="<?php echo $cfg['uid'] ?>" size="5" /></td>
    <td width="208" bgcolor="#FFFFFF">组ID（gid)
    <input name="gid" type="text" id="gid" value="<?php echo $cfg['gid'] ?>" size="5" /></td>
  </tr>
  <tr>
  <td bgcolor="#FFFFFF">密码：</td>
  <td bgcolor="#FFFFFF"><input name="passwd" type="text" id="passwd" /></td>
  <td bgcolor="#FFFFFF">文件数量
    <input name="qf" type="text" id="qf" value="<?php echo $cfg['qf'] ?>" size="5" /></td>
  <td bgcolor="#FFFFFF">磁盘限额
    <input name="qs" type="text" id="qs" value="<?php echo $cfg['qs'] ?>" size="5" />
    MB</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">目录</td>
    <td bgcolor="#FFFFFF"><input name="dir" type="text" id="dir" value="<?php echo $cfg['dir'] ?>" />
    <input name="tdir" type="hidden" id="tdir" value="<?php echo $cfg['dir'] ?>" /></td>
    <td bgcolor="#FFFFFF">上传带宽
    <input name="ul" type="text" id="ul" value="<?php echo $cfg['ul'] ?>" size="5" />
    KB/S</td>
    <td bgcolor="#FFFFFF">下载带宽
    <input name="dl" type="text" id="dl" value="<?php echo $cfg['dl'] ?>" size="5" />
    KB/S</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">状态</td>
    <td bgcolor="#FFFFFF"><label>
      <input name="status" id="status" type="radio" value="1" <?php if ($cfg['status']){ echo 'checked="checked"';}?>  onclick="switchst('1');"/>
    启用</label><label>
    <input type="radio" id="status" name="status" value="0" <?php if (!$cfg['status']){ echo 'checked="checked"';}?> onclick="switchst('0');"/>
    停用</label><input type="hidden" id="st" value="<?php echo $cfg['status'] ?>" /></td>
    <td colspan="2" bgcolor="#FFFFFF">上传下载比率
    <input name="ur" type="text" id="ur" value="<?php echo $cfg['ur'] ?>" size="3" />
    ：
    <input name="dr" type="text" id="dr" value="<?php echo $cfg['dr'] ?>" size="3" /></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">IP限制</td>
    <td bgcolor="#FFFFFF"><label>
      <textarea name="ip" id="ip"><?php echo $cfg['ip'] ?></textarea>
    </label></td>
    <td colspan="2" valign="top" bgcolor="#FFFFFF">备注
      <label>
      <textarea name="comment" cols="20" id="comment"></textarea>
    </label></td>
  </tr>
</table>
<div align="center"><input type="button" value="保存" onClick="Ok('add')">&nbsp;<input type="button" value="关闭" onClick="Close();"></div>
