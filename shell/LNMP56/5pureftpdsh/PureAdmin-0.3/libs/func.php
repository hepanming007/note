<?php
if (!defined(ACCESS)) {
	echo "can't access";
	exit;
}

function getpw($type,$text){
	switch ($type){
		case "TEXT":
			return $text;
			break;
		case "CRYPT":
			return crypt($text);
			break;
		case "MD5":
			return md5($text);
			break;
		default:
			die("未知密码类型，请看config.php->\$cfg\[\'passwdtype\'\]");
	}
}
?>
<?php
function makepaging($rr,$href){
//link this: $herf='javascript:page('LIST','inquiry.php?page=%P%');
//or :$herf='inquiry.php?page=%P%';
$pre=$rr['crpage']-1;
$next=$rr['crpage']+1;
$prelink=str_replace("%P%",$pre,$href);
$nextlink=str_replace("%P%",$next,$href);
?>
<div align="center" class="sabrosus">
<a href="<?php echo $prelink?>">&lt;</a>
<?php for ($i=1;$i<=$rr['pagenum'];$i++){
		$plink=str_replace("%P%",$i,$href);
	if ($rr['crpage'] == $i){
		echo "<span class=\"current\">$i</span>&nbsp;";
	}else{
		echo "<a href=\"$plink\" >$i</a>&nbsp;";
	}
 }
?>
<a href="<?php echo $nextlink ?>">&gt;</a>
</div>
	<div align="center">
	<?php echo $rr['total'] ?> 条记录,每页[<?php echo $rr['rop']?>]条,共[<?php echo $rr['pagenum']?>]页
			</div>
<?php } //end function?>