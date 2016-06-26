<?php
define("ACCESS","true");
require_once("lib.php");
if ($_REQUEST['o'] == 'del'){
	$sql=sprintf("delete from users where User='%s' limit 1",addslashes($_REQUEST['u']));
	$db->q($sql);
}
$sql="select * from users";
$rr=$db->paging($cfg['page'],$sql);
$row=$rr['rows'];
?>
<div><input type="button" value="新建..." onClick="Add();" /></div><br />
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
<thead>
  <tr>
    <td width="99">用户</td>
    <td width="104">用户ID/组ID</td>
    <td width="112">目录</td>
    <td width="147">文件数量/磁盘限制</td>
    <td width="154">上传速度/下载速度</td>
    <td width="84">操作</td>
  </tr></thead>
  <?php for($i=0;$i<count($row);$i++){ ?>
  <tr onMouseover="this.bgColor='#D6AF70'" onMouseout="this.bgColor='#FFFFFF'">
    <td><img src="images/ftpuser.gif" alt="用户" width="16" height="18"><?php echo $row[$i]['User']?></td>
    <td><?php echo $row[$i]['Uid'],"/",$row[$i]['Gid']?></td>
    <td><?php echo $row[$i]['Dir'] ?></td>
    <td align="center"><?php echo $row[$i]['QuotaFiles'],"/",$row[$i]['QuotaSize'] ?> MB</td>
    <td align="center"><?php echo $row[$i]['ULBandwidth'],"/",$row[$i]['DLBandwidth'] ?></td>
    <td><a href="javascript:edit('<?php echo $row[$i]['User'] ?>');"><img src="images/edit.gif" alt="编辑" width="16" height="16" border="0"></a>&nbsp;
	  <a href="javascript:del('<?php echo $row[$i]['User'] ?>');"><img src="images/del.gif" alt="删除" width="12" height="12" border="0"></a>&nbsp;
	  <?php if ($row[$i]['Status']){$lock='lock_open.gif';$next='0';}else{$lock='lock_closed.gif';$next='1';} ?>
	  <span id="lock<?php echo $row[$i]['User'] ?>"><a href="javascript:lock('<?php echo $row[$i]['User'] ?>','<?php echo $next ?>');"><img src="images/<?php echo $lock ?>" alt="状态" width="14" height="18" border="0" /></a></span>	  </td>
  </tr>
  <?php } ?>
</table><br />
<?php 
$href="javascript:getdata('#list','list.php?page=%P%');";
makepaging($rr,$href);
?>
