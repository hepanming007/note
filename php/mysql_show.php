<?php
header("Content-type: text/html; charset=utf-8"); 
/*
MySQL中有很多的基本命令，show命令也是其中之一，在很多使用者中对show命令的使用还容易产生混淆，本文汇集了show命令的众多用法。
1. show tables或show tables from database_name; -- 显示当前数据库中所有表的名称。
2. show databases; -- 显示mysql中所有数据库的名称。
3. show columns from table_name from database_name; 或show columns from database_name.table_name; -- 显示表中列名称。
4. show grants for user_name; -- 显示一个用户的权限，显示结果类似于grant 命令。
5. show index from table_name; -- 显示表的索引。
6. show status; -- 显示一些系统特定资源的信息，例如，正在运行的线程数量。
7. show variables; -- 显示系统变量的名称和值。
8. show processlist; -- 显示系统中正在运行的所有进程，也就是当前正在执行的查询。大多数用户可以查看他们自己的进程，但是如果他们拥有process权限，就可以查看所有人的进程，包括密码。
9. show table status; -- 显示当前使用或者指定的database中的每个表的信息。信息包括表类型和表的最新更新时间。
10. show privileges; -- 显示服务器所支持的不同权限。
11. show create database database_name; -- 显示create database 语句是否能够创建指定的数据库。
12. show create table table_name; -- 显示create database 语句是否能够创建指定的数据库。
13. show engines; -- 显示安装以后可用的存储引擎和默认引擎。
14. show innodb status; -- 显示innoDB存储引擎的状态。
15. show logs; -- 显示BDB存储引擎的日志。
16. show warnings; -- 显示最后一个执行的语句所产生的错误、警告和通知。
17. show errors; -- 只显示最后一个执行语句所产生的错误。
18. show [storage] engines; --显示安装后的可用存储引擎和默认引擎。
 */
 /*状态查看程序*/
$host = '127.0.0.1';
$dbname = 'cgfx';
$username = 'root';
$password = '';
$charset = 'gbk';
$dsn = "mysql:host={$host};port=3306;dbname={$dbname};charset={$charset};";
$action = isset($_GET['action'])?$_GET['action']:1;
$GLOBALS['dbname'] = $dbname;
$GLOBALS['db'] = new PDO($dsn,$username,$password, array(PDO::ATTR_PERSISTENT=>true));

//$show_tables       = $GLOBALS['db']->query("show tables")->fetchAll(PDO::FETCH_COLUMN);
//$show_databases    = $GLOBALS['db']->query("show databases")->fetchAll(PDO::FETCH_ASSOC);
//$show_grants       = $GLOBALS['db']->query("show grants")->fetchAll(PDO::FETCH_ASSOC);
//$show_status       = $GLOBALS['db']->query("show status")->fetchAll(PDO::FETCH_ASSOC);
//$show_variables    = $GLOBALS['db']->query("show variables")->fetchAll();
//$show_processlist  = $GLOBALS['db']->query("show processlist")->fetchAll(PDO::FETCH_ASSOC);
//$show_table_status = $GLOBALS['db']->query("show table status")->fetchAll(PDO::FETCH_ASSOC);
//$show_privileges   = $GLOBALS['db']->query("show privileges")->fetchAll(PDO::FETCH_ASSOC);
//$show_innodb_status= $GLOBALS['db']->query("show innodb status")->fetchAll(PDO::FETCH_ASSOC);
//$show_warnings     =  $GLOBALS['db']->query("show warnings")->fetchAll(PDO::FETCH_ASSOC);
//echo "<pre>";
//
//print_r(implode(",",array_keys($show_table_status[0])));
//echo "'".implode("','",array_keys($show_table_status[0]))."'";
$config_data = array(
	1=>$GLOBALS['db']->query("show tables")->fetchAll(PDO::FETCH_COLUMN),
	2=>$GLOBALS['db']->query("show processlist")->fetchAll(PDO::FETCH_ASSOC),
	3=>'show_tables_html',
	4=>'show_tables_html',
	5=>'show_tables_html',
	6=>'show_tables_html',
	7=>'show_tables_html',
	9=>$GLOBALS['db']->query("show table status")->fetchAll(PDO::FETCH_ASSOC),

);
$config_function = array(
	1=>'show_tables_html',
	2=>'show_processlist_html',
	3=>'show_tables_html',
	4=>'show_tables_html',
	5=>'show_tables_html',
	6=>'show_tables_html',
	7=>'show_tables_html',
	9=>'show_table_status_html',
);
$config_title = array(
	1=>'数据库字典',
	2=>'当前进程状态',
	9=>'数据库表使用情况',
);
 
$html = call_user_func($config_function[$action],$config_data[$action]);

$title = $config_title[$action];

//输出
echo '<html>
<head>
<title>'.$title.'</title>
<style>
body,td,th {font-family:"宋体"; font-size:12px;}
/*
table{border-collapse:collapse;border:1px solid #CCC;background:#efefef;}
table th{text-align:left; font-weight:bold;height:26px; line-height:26px; font-size:12px; border:1px solid #CCC;}
table td{height:20px; font-size:12px; border:1px solid #CCC;background-color:#fff;}
.c1{ width: 120px;}
.c2{ width: 120px;}
.c3{ width: 70px;}
.c4{ width: 80px;}
.c5{ width: 80px;}
.c6{ width: 270px;}
*/
table caption{text-align:left; background-color:#fff; line-height:2em; font-size:16px; font-weight:bold; }
table { border-collapse: collapse; mso-table-layout-alt: fixed;border: 1px solid rgb(204, 204, 204);background: rgb(239, 239, 239);margin-bottom: 30px;}       
table th { text-align: left;  font-weight: bold; height: 26px;line-height: 26px;font-size: 12px;border: 1px solid rgb(204, 204, 204);
 background:#005fbf;border-right: 1pt solid rgb(79, 129, 189);border-top: 1pt solid rgb(79, 129, 189);border-bottom: 1pt solid rgb(79, 129, 189); border-left: 1pt solid rgb(79, 129, 189);}
table th span { color: rgb(255, 255, 255);}
.td1 {height: 20px;font-size: 12px;border: 1px solid rgb(204, 204, 204);background-color: #aad4ff;border-right: 1pt solid rgb(79, 129, 189);border-top: none;border-bottom: 1pt solid rgb(79, 129, 189);
}
.c1 {width: 120px;border-left: 1pt solid rgb(79, 129, 189);
}
.c2 {width: 140px;}
.c3 {width: 70px;}
.c4 {width: 80px;}
.c5 {width: 80px;}
.c6 {width: 270px;}
.td2 {height: 20px;font-size: 12px;border: 1px solid rgb(204, 204, 204);background-color: rgb(255, 255, 255);border-right: 1pt solid rgb(79, 129, 189);border-top: none;border-bottom: 1pt solid rgb(79, 129, 189);}
tr:hover {background-color: #ffffaa;}
tr:hover td {background:none;}
</style>
</head>
<body>';
echo '<h1 style="text-align:center;">'.$title.'</h1>';
echo $html;
echo '</body></html>';



function byteFormat($bytes, $unit = "", $decimals = 2) {
    $units = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);

    $value = 0;
    if ($bytes > 0) {
        // Generate automatic prefix by bytes
        // If wrong prefix given
        if (!array_key_exists($unit, $units)) {
            $pow = floor(log($bytes)/log(1024));
            $unit = array_search($pow, $units);
        }

        // Calculate byte value by prefix
        $value = ($bytes/pow(1024,floor($units[$unit])));
    }

    // If decimals is not numeric or decimals is less than 0
    // then set default value
    if (!is_numeric($decimals) || $decimals < 0) {
        $decimals = 2;
    }

    // Format output
    return sprintf('%.' . $decimals . 'f '.$unit, $value);
}

function compare($x,$y)
{ 
	if($x['rows'] == $y['rows']) 
		return 0; 
	elseif($x['rows']-$y['rows']>0) 
		return -1; 
	else 
		return 1; 
}



function show_tables_html($show_tables){

	foreach($show_tables as $k=>$t){
		$tables[$k]['TABLE_NAME'] = $t;
		$sql  = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE table_name = '{$t}'  AND table_schema = '{$GLOBALS['dbname']}'";
		$table_comment =  $GLOBALS['db']->query($sql)->fetch(PDO::FETCH_ASSOC);
		$tables[$k]['TABLE_COMMENT'] = $table_comment['TABLE_COMMENT'];
		$sql  = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '{$t}'  AND table_schema = '{$GLOBALS['dbname']}'";
		$table_columns =  $GLOBALS['db']->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		$tables[$k]['COLUMN'] = $table_columns;
	}
	$html = '';
	//循环所有表
	foreach ($tables AS $k=>$v) {
	 
		$html .= '<table  border="1" cellspacing="0" cellpadding="0" align="center" width="750px">';
		$html .= '<caption>' . $v['TABLE_NAME'] .'  '. $v['TABLE_COMMENT']. '</caption>';
		$html .= '<tbody>
		<tr>
		<th><span>字段名</span></th>
		<th><span>数据类型</span></th>
		<th><span>默认值</span></th>
		<th><span>允许非空</span></th>
		<th><span>自动递增</span></th>
		<th><span>备注</span></th>
		</tr>';
		$html .= '';
	  $i=1;
		foreach ($v['COLUMN'] AS $f) {
			$td = $i%2?'td2':'td1';
			$i++;
			$html .= "<tr><td class='c1 {$td}'>" . $f['COLUMN_NAME'] . '</td>';
			$html .= "<td class='c2 $td '>" . $f['COLUMN_TYPE'] . '</td>';
			$html .= "<td class='c3 $td '>&nbsp;" . $f['COLUMN_DEFAULT'] . '</td>';
			$html .= "<td class='c4 $td '>&nbsp;" . $f['IS_NULLABLE'] . '</td>';
			$html .= "<td class='c5 $td '>". ($f['EXTRA']=='auto_increment'?'是':'&nbsp;') . '</td>';
			$html .= "<td class='c6 $td '>&nbsp;" . $f['COLUMN_COMMENT'] . '</td>';
			$html .= "</tr>";
		}
		$html .= '</tbody></table>';
	}
	return $html;
}

function show_table_status_html($show_table_status){
	foreach($show_table_status as $table_status){
		$formart_table_status[] = array(
			'name'              =>  $table_status['Name'],
			'rows'              =>  $table_status['Rows']. '&nbsp;&nbsp;&nbsp;&nbsp;',
			'auto_increment'    =>  $table_status['Auto_increment']. '&nbsp;&nbsp;&nbsp;&nbsp;',
			'data_length'       =>  byteFormat($table_status['Data_length'],'MB'),
			'index_length'      =>  byteFormat($table_status['Index_length'],'MB'),
			'creation_time'     =>  date('Y-m-d', strtotime($table_status['Create_time'])),
			'comment'           =>  $table_status['Comment'],
		);
	}
	usort($formart_table_status,'compare');
	$html = '';
	$html .= '<table  border="1" cellspacing="0" cellpadding="0" align="center" width="100%">';
	$html .= '<caption>数据库表的使用情况</caption>';
	$html .= '<tbody>
	<tr>
	<th><span>name</span></th>
	<th><span>rows</span></th>
	<th><span>auto_increment</span></th>
	<th><span>data_length</span></th>
	<th><span>index_length</span></th>
	<th><span>creation_time</span></th>
	<th><span>comment</span></th>
	</tr>';
	foreach ($formart_table_status AS $k=>$v) {

		$html .= '<tr>';
		$td = $k%2?'td2':'td1';
		$html .= "<td class='c1 {$td}'> {$v['name']}</td>";
		$html .= "<td class='c2 {$td} '> {$v['rows']}</td>";
		$html .= "<td class='c2 {$td} '> {$v['auto_increment']}</td>";
		$html .= "<td class='c2 {$td} '> {$v['data_length']}</td>";
		$html .= "<td class='c2 {$td} '> {$v['index_length']}</td>";
		$html .= "<td class='c2 {$td} '> {$v['creation_time']}</td>";
		$html .= "<td class='c2 {$td} '> {$v['comment']}</td>";
		$html .= "</tr>";
	}
	$html .= '</tbody></table>';
	return $html;
}

function show_processlist_html($data)
{
	return array_to_table($data);
	
}

function array_to_table($data,$title=''){
	$html = '';
	$html .= '<table  border="1" cellspacing="0" cellpadding="0" align="center" width="100%">';
	$html .= "<caption>{$title}</caption>";
	$html .= '<tbody><tr>';
	$all_keys = array_keys($data[0]);
	foreach($all_keys as $v){
			$html .="<th><span>{$v}</span></th>";
	}
	$html .='</tr>';
	
	foreach ($data AS $k=>$v) {

		$html .= '<tr>';
		$td = $k%2?'td2':'td1';
		$html_td = '';
		$html_td .= "<td class='c1 {$td}'>";
		$html_td .= implode("</td><td class='c1 {$td}'>",$v);
		$html_td .= "</td>";
		// $html .= "<td class='c1 {$td}'> {$v['name']}</td>";
		$html .= $html_td."<br/>";
		$html .= "</tr>";
	}
	$html .= '</tbody></table>';
	return $html;

}

 

