<?php
define("ACCESS","true");
require_once("lib.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $TITLE ?></title>
<script src="libs/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	//$("#list").fadeIn("slow"); 
	getdata("#list","list.php");
});
function getdata(obj,url){
	$(obj).empty().append('<img src="images/loading.gif">正在更新数据...');
	$.get(url,function(data){
		$(obj).empty().append(data);
		}
	);
}
function postdata(obj,url,params){
	$(obj).empty().append('<img src="images/loading.gif">正在提交数据...');
	$.post(url,params,function(data){
		$(obj).empty().append(data);
		}
	);
}
function Close(){
	$("#editor").fadeOut("slow"); 
	//$("#editor").empty();
	//window.opener=null;
	//window.open('','_self');
	//window.close();
}
function Add(){
	getdata("#editor","adduser.php");
	$("#editor").fadeIn("slow"); 
}
function App(){
	var U=$("#user").val();
	var D=$("#tdir").val();
	$("#dir").val('');
	$("#dir").val(D+U);
}
function Ok(o){
	if (!$("#user").val()){alert("请输入用户名！");return false;}
	var params='user='+$("#user").val();
	params=params+'&passwd='+$("#passwd").val();
	params=params+'&uid='+$("#uid").val();
	params=params+'&gid='+$("#gid").val();
	params=params+'&qf='+$("#qf").val();
	params=params+'&qs='+$("#qs").val();
	params=params+'&dir='+$("#dir").val();
	params=params+'&ul='+$("#ul").val();
	params=params+'&dl='+$("#dl").val();
	params=params+'&st='+$("#st").val();
	params=params+'&ur='+$("#ur").val();
	params=params+'&dr='+$("#dr").val();
	params=params+'&ip='+$("#ip").val();
	params=params+'&comment='+$("#comment").val();
	if (o =='add'){
		params=params+'&o=add';
		postdata('#editor','adduser.php',params);
		getdata('#list','list.php');
	}else{
		params=params+'&o=edit&nochpw='+$("#nochpw").val();
		postdata('#editor','edit.php',params);
	}
}
function switchst(v){
	$("#st").val(v);
}
function chkUser(){
	var U=$("#user").val();
	if (U){
		getdata('#chk','chkuser.php?u='+U);
	}
}
function del(u){
	if (confirm("你真的要删除用户："+u+" 吗？")){
		getdata('#list','list.php?o=del&u='+u);
	}
}
function edit(u){
	getdata('#editor','edit.php?user='+u);
	$("#editor").fadeIn("slow");
}
function createadmin(){
	var err=false;
	var u=$("#username").val();
	var p1=$("#passwd1").val();
	var p2=$("#passwd2").val()
	if (!u){err="请输入用户名！\n";};
	if (!p1){err=err+"请输入密码!\n";};
	if (p1 != p2 ){err=err+"两次密码不一样\n";}
	if (err){
		alert(err);
		return false;
	}
	postdata("#list",'admin.php','o=add&u='+u+'&p='+p1);
}
function deladmin(u){
	if (confirm("真的要删除该管理员吗?")){
		getdata("#list",'admin.php?o=del&u='+u);
	}
}
function listadmin(){
	$("#editor").empty();
	getdata("#list",'admin.php');
}
function lock(u,l){
	var obj="#lock"+u;
	var url='lock.php?u='+u+'&l='+l;
	$(obj).empty();
	$.get(url,function (data){
		$(obj).append(data);
		}
	);
	//getdata(obj,'lock.php?u='+u+'&l='+l);
}
</script>
<style type="text/css">
<!--
body {
	font-size: 14px;
}
DIV.sabrosus {
	PADDING-RIGHT: 3px; PADDING-LEFT: 3px; PADDING-BOTTOM: 3px; MARGIN: 3px; PADDING-TOP: 3px; TEXT-ALIGN: center
}
DIV.sabrosus A {
	BORDER-RIGHT: #9aafe5 1px solid; PADDING-RIGHT: 5px; BORDER-TOP: #9aafe5 1px solid; PADDING-LEFT: 5px; PADDING-BOTTOM: 2px; BORDER-LEFT: #9aafe5 1px solid; COLOR: #2e6ab1; MARGIN-RIGHT: 2px; PADDING-TOP: 2px; BORDER-BOTTOM: #9aafe5 1px solid; TEXT-DECORATION: none
}
DIV.sabrosus A:hover {
	BORDER-RIGHT: #2b66a5 1px solid; BORDER-TOP: #2b66a5 1px solid; BORDER-LEFT: #2b66a5 1px solid; COLOR: #000; BORDER-BOTTOM: #2b66a5 1px solid; BACKGROUND-COLOR: lightyellow
}
DIV.pagination A:active {
	BORDER-RIGHT: #2b66a5 1px solid; BORDER-TOP: #2b66a5 1px solid; BORDER-LEFT: #2b66a5 1px solid; COLOR: #000; BORDER-BOTTOM: #2b66a5 1px solid; BACKGROUND-COLOR: lightyellow
}
DIV.sabrosus SPAN.current {
	BORDER-RIGHT: navy 1px solid; PADDING-RIGHT: 5px; BORDER-TOP: navy 1px solid; PADDING-LEFT: 5px; FONT-WEIGHT: bold; PADDING-BOTTOM: 2px; BORDER-LEFT: navy 1px solid; COLOR: #fff; MARGIN-RIGHT: 2px; PADDING-TOP: 2px; BORDER-BOTTOM: navy 1px solid; BACKGROUND-COLOR: #2e6ab1
}
DIV.sabrosus SPAN.disabled {
	BORDER-RIGHT: #929292 1px solid; PADDING-RIGHT: 5px; BORDER-TOP: #929292 1px solid; PADDING-LEFT: 5px; PADDING-BOTTOM: 2px; BORDER-LEFT: #929292 1px solid; COLOR: #929292; MARGIN-RIGHT: 2px; PADDING-TOP: 2px; BORDER-BOTTOM: #929292 1px solid
}
thead {
	color: #FFFFFF;
	background-color: #000066;
}
-->
</style>
</head>

<body><div align="center">欢迎：<?php echo $_SESSION['admin'] ?>&nbsp;&nbsp;
<input type="button" value="FTP用户" onclick="getdata('#list','list.php');"/>&nbsp;
<input type="button" value="后台管理员" onclick="listadmin();" />&nbsp;
<input type="button"  value="登出" onclick="javascript:location.href='index.php?o=logout'" />
&nbsp;<input type="button" value="开源易有作品" onclick="window.open('http://www.yiyou.org')" /></div>
<br /><br />
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><div id="list"></div></td>
  </tr>
  <tr>
    <td><br /><div id="editor"></div></td>
  </tr>
</table>
</body>
</html>
