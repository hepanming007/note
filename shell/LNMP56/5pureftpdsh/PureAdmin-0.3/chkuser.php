<?php
define("ACCESS","true");
require_once("lib.php");
$sql=sprintf("select * from users where User='%s'",addslashes($_REQUEST['u']));
$row=$db->fetch_all($sql);
if ($row){
	$img='unok.gif';
}else{
	$img='ok.gif';
}

?>
<img src="images/<?php echo $img ?>" border="0"  />
