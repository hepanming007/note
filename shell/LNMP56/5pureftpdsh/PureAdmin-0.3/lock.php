<?php
define("ACCESS","true");
require_once("lib.php");
$user=$_REQUEST['u'];
$lock=$_REQUEST['l'];
$sql=sprintf("update users set Status='%s' WHERE User='%s'",
		addslashes($lock),addslashes($user));
//echo $sql;
$db->q($sql);
if ($lock=='1'){
	$img='lock_open.gif';
	$next='0';
}else{
	$img='lock_closed.gif';
	$next=1;
}		
?>
<a href="javascript:lock('<?php echo $user ?>','<?php echo $next ?>');"><img src="images/<?php echo $img ?>" alt="状态" width="14" height="18" border="0" /></a>