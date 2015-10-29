<?php
/**
 * 生成mysql数据字典
 */
//配置数据库
$dbserver   = "127.0.0.1";
$dbusername = "root";
$dbpassword = "";
$database      = "cgfx";
//其他配置
$title = '数据字典';
$mysql_conn = @mysql_connect("$dbserver", "$dbusername", "$dbpassword") or die("Mysql connect is error.");
mysql_select_db($database, $mysql_conn);
mysql_query('SET NAMES gbk', $mysql_conn);
$table_result = mysql_query('show tables', $mysql_conn);
//取得所有的表名
while ($row = mysql_fetch_array($table_result)) {
    $tables[]['TABLE_NAME'] = $row[0];
}
//循环取得所有表的备注及表中列消息
foreach ($tables AS $k=>$v) {
    $sql  = 'SELECT * FROM ';
    $sql .= 'INFORMATION_SCHEMA.TABLES ';
    $sql .= 'WHERE ';
    $sql .= "table_name = '{$v['TABLE_NAME']}'  AND table_schema = '{$database}'";
    $table_result = mysql_query($sql, $mysql_conn);
    while ($t = mysql_fetch_array($table_result) ) {
        $tables[$k]['TABLE_COMMENT'] = $t['TABLE_COMMENT'];
    }
    $sql  = 'SELECT * FROM ';
    $sql .= 'INFORMATION_SCHEMA.COLUMNS ';
    $sql .= 'WHERE ';
    $sql .= "table_name = '{$v['TABLE_NAME']}' AND table_schema = '{$database}'";
    $fields = array();
    $field_result = mysql_query($sql, $mysql_conn);
    while ($t = mysql_fetch_array($field_result) ) {
        $fields[] = $t;
    }
    $tables[$k]['COLUMN'] = $fields;
}
mysql_close($mysql_conn);
$html = '';
//循环所有表
foreach ($tables AS $k=>$v) {
    //$html .= '<p><h2>'. $v['TABLE_COMMENT'] . '&nbsp;</h2>';
    $html .= '<table  border="1" cellspacing="0" cellpadding="0" align="center" width="750px">';
    $html .= '<caption>' . $v['TABLE_NAME'] .'  '. $v['TABLE_COMMENT']. '</caption>';
    $html .= '<tbody><tr><th><span>字段名</span></th><th><span>数据类型</span></th><th><span>默认值</span></th>
    <th><span>允许非空</span></th>
    <th><span>自动递增</span></th><th><span>备注</span></th></tr>';
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
    $html .= '</tbody></table></p>';
}
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
?>
