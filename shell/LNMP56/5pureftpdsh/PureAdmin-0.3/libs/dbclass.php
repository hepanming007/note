<?php
if (!defined(ACCESS)) {
	echo "can't access";
	exit;
}
class Db {
	function dbconn($host,$dbuser,$dbpass,$dbname) {
		mysql_pconnect($host,$dbuser,$dbpass) or die ("can't connect to mysql server".mysql_error());
		mysql_select_db($dbname) or die ("can't select db:".mysql_error());
		$this->q('set names "utf8"');
	}
	function q($sql){
		$result=mysql_query($sql) or die ("can't query:".mysql_error());
		if (!$result){
			die ("can't query server:".mysql_error());
		}
		return $result;
	}
	function fetch_all($sql) {
		$result=$this->q($sql);
		while($row=mysql_fetch_assoc($result)){
			$rows[]=$row;
		}
		mysql_free_result($result);
		return $rows;
	}
	function fetch_all_admin() {
		$sql="SELECT * FROM admin";
		$result=$this->q($sql);
		while ($row=mysql_fetch_assoc($result)){
			$user=$row["username"];
			$rows[$user]=$row;
		}
		mysql_free_result($result);
		return $rows;
	}
	function sum($sql){
		$result=$this->q($sql);
		$rows=mysql_num_rows($result);
		mysql_free_result($result);
		return $rows;
	}
	function query($sql){
		mysql_db_query($sql);
	}
	//$array_hash=$db->paging($recordofpage,$sql);
	function paging($rop,$sql){
		//get page
		$page=isset($_REQUEST['page'])?intval($_REQUEST['page']):1;
		$total=$this->sum($sql);//all records.
		$pagenum=ceil($total/$rop); //get page total 
		$page=min($pagenum,$page); // if request page > pagenum ,then fix it.
		$page=max(1,$page); //if request page < 1 then fix it.
		$rstart=($page-1)*$rop;
		//$rend=$page*$rop;
		$sql.=" limit $rstart,$rop";
		$rows=$this->fetch_all($sql);
		//echo "rstart:$rstart ,rend: $rend ,rows:",count($rows);
		$rr['total']=$total;
		$rr['pagenum']=$pagenum;
		$rr['crpage']=$page;
		$rr['rows']=$rows;
		$rr['rop']=$rop;
		return $rr;
	}
	function fetch_row($sql){
		$result=$this->q($sql);
		return mysql_fetch_assoc($result);
	}
}
?>
