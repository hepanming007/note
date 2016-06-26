<?php
define("ACCESS","true");
require_once("lib.php");
if ($_REQUEST['o'] == 'edit'){
	$sql=sprintf("update users set Uid='%s',Gid='%s',QuotaFiles='%s',QuotaSize='%s',
		Dir='%s',ULBandwidth='%s',DLBandwidth='%s',Ipaddress='%s',Status='%s',Comment='%s',
		ULRatio='%s',DLRatio='%s' where User='%s'",$_REQUEST['uid'],$_REQUEST['gid'],
		$_REQUEST['qf'],$_REQUEST['qs'],$_REQUEST['dir'],$_REQUEST['ul'],$_REQUEST['dl'],$_REQUEST['ip'],
		$_REQUEST['st'],addslashes($_REQUEST['comment']),$_REQUEST['ur'],$_REQUEST['dr'],
		$_REQUEST['user']);
	$db->q($sql);
	if ($_REQUEST['nochpw'] == 'yes'){
		$sql=sprintf("update users set Password='%s' where User='%s'",
		getpw($cfg['passwdtype'],$_REQUEST['passwd']),$_REQUEST['user']);
		$db->q($sql);
	}
}
$user=$_REQUEST['user'];
if (!$user){echo "unknow user";exit;};
$sql=sprintf("select * from users where User='%s'",addslashes($user));
$row=$db->fetch_row($sql);
?>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#000000">
  <tr>
    <td width="65" bgcolor="#FFFFFF">用户名</td>
    <td width="222" bgcolor="#FFFFFF">
	<input name="user" type="text" id="user"  size="20" readonly value="<?php echo $row['User']?>" />
	</td>
    <td width="205" bgcolor="#FFFFFF">用户ID(uid)
      <input name="uid" type="text" id="uid" value="<?php echo $row['Uid'] ?>" size="5" /></td>
    <td width="208" bgcolor="#FFFFFF">组ID（gid)
      <input name="gid" type="text" id="gid" value="<?php echo $row['Gid'] ?>" size="5" /></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">密码：</td>
    <td bgcolor="#FFFFFF"><input name="passwd" type="text" id="passwd" value="<?php echo $row['Password'] ?>" />
     <br /> <label>
      <input name="nochpw" type="checkbox" id="nochpw" value="yes">
      <span style="color:#993366">更改密码</span></label></td>
    <td bgcolor="#FFFFFF">文件数量
      <input name="qf" type="text" id="qf" value="<?php echo $row['QuotaFiles'] ?>" size="5" /></td>
    <td bgcolor="#FFFFFF">磁盘限额
      <input name="qs" type="text" id="qs" value="<?php echo $row['QuotaSize'] ?>" size="5" />
      kb</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">目录</td>
    <td bgcolor="#FFFFFF"><input name="dir" type="text" id="dir" value="<?php echo $row['Dir'] ?>" />
        </td>
    <td bgcolor="#FFFFFF">上传带宽
      <input name="ul" type="text" id="ul" value="<?php echo $row['ULBandwidth'] ?>" size="5" />
      kb/s</td>
    <td bgcolor="#FFFFFF">下载带宽
      <input name="dl" type="text" id="dl" value="<?php echo $row['DLBandwidth'] ?>" size="5" />
      kb/s</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">状态</td>
    <td bgcolor="#FFFFFF"><label>
      <input name="status" id="status" type="radio" value="1" <?php if ($row['Status']){ echo 'checked="checked"';}?>  onclick="switchst('1');"/>
      启用</label>
        <label>
        <input type="radio" id="status" name="status" value="0" <?php if (!$row['Status']){ echo 'checked="checked"';}?> onclick="switchst('0');"/>
          停用</label>
      <input name="hidden" type="hidden" id="st" value="<?php echo $row['Status'] ?>" /></td>
    <td colspan="2" bgcolor="#FFFFFF">上传下载比率
      <input name="ur" type="text" id="ur" value="<?php echo $row['ULRatio'] ?>" size="3" />
      ：
      <input name="dr" type="text" id="dr" value="<?php echo $row['DLRatio'] ?>" size="3" /></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">IP限制</td>
    <td bgcolor="#FFFFFF"><label>
      <textarea name="ip" id="ip"><?php echo $row['Ipaddress'] ?></textarea>
    </label></td>
    <td colspan="2" valign="top" bgcolor="#FFFFFF">备注
      <label>
        <textarea name="comment" cols="20" id="comment"><?php echo $row['Comment'] ?></textarea>
      </label></td>
  </tr>
</table><br />
<div align="center"><input type="button" value="保存" onClick="Ok('edit')">&nbsp;<input type="button" value="关闭" onClick="Close();"></div>
