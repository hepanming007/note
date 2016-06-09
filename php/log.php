<?php
error_reporting(E_ALL);
define('LOG_PATH','./test/');
$base_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];

$filename = isset($_GET['file_name'])?$_GET['file_name']:'';
$filter   = isset($_GET['filter']) &&  !empty($_GET['filter'])?$_GET['filter']:'*';
$download = isset($_GET['download'])?$_GET['download']:'';
$dir 	  = isset($_GET['dir'])&&!empty($_GET['dir'])?urldecode($_GET['dir']):'';

//todo:: ip limit
if(!(strpos($dir,'.')===false)){
	exit('secure error');
}
?>
<form>
<input type="hidden" name="dir" value="<?=$dir?>"/>
<input type="text"  name="filter" style="width:492px;height:40px;"> 
<input type="submit" value="search" style="width:70px;height:35px;">
<input type="button" value="clearn" onclick="javascript:window.location.href='http://<?=$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']?>'" style="width:70px;height:35px;">
<input type="button" value="donwload" onclick="javascript:window.location.href='http://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'&download=1'?>'" style="width:70px;height:35px;">
</form>
<?php

if (empty($filename)) {
	$dir = $dir?$dir.'/':'';
    $log_path = LOG_PATH .$dir.$filter;
	$table = '<table width="400" border="0">';
    foreach (glob($log_path) as $file_path) {
	     $file_time = date('Y-m-d H:i:s',filectime($file_path));
		if(is_dir($file_path)){
			 $file_path = str_replace(LOG_PATH, '', $file_path);
			 $table .= "<tr><td>{$file_time}</td><td><a  href='$base_url?&dir={$file_path}'>$file_path<a></td></tr>";
		}else{
		     $file_path = str_replace(LOG_PATH, '', $file_path);
		     $basename = basename($file_path);
		     $table .= "<tr><td>{$file_time}</td><td><a  href='$base_url?&file_name={$file_path}'>$basename<a></td></tr>";
		}
    }
	echo $table;
}else{
	if($download){
		ob_end_clean();
		header("Content-Disposition: attachment; filename='{$filename}'"); 
		readfile(LOG_PATH.$filename); 
		exit(); 
	}else{
		echo $filename."<hr>";
		echo highlight_file(LOG_PATH.$filename);
	}
}

?>
