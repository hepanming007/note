<?php
/**
 * Class Excel
 */
class Excel
{
    /** mime类型
     * @var array
     */
    public static $mimes = array(
        'csv' => array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel'),
        'xls' => array('application/excel', 'application/vnd.ms-excel', 'application/msexcel'),
    );

    /**下载文件
     * @param string $filename 文件名称
     * @param string $data     数据字符串
     * @return bool
     */
    public static function force_download($filename = '', $data = '')
    {
        if ($filename == '' OR $data == '') {
            return FALSE;
        }
        if (FALSE === strpos($filename, '.')) {
            return FALSE;
        }
        // Grab the file extension
        $x = explode('.', $filename);
        $extension = end($x);
        $mime = (is_array(self::$mimes[$extension])) ? self::$mimes[$extension][0] : self::$mimes[$extension];
        // Generate the server headers
        header('Content-Type: "' . $mime . '"');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header("Content-Transfer-Encoding: binary");
        header('Pragma: public');
        header("Content-Length: " . strlen($data));
        exit($data);
    }

    /**
     * 编码转换
     * @param type $string
     * @return string
     */
    public static function charset($string){
        return iconv('utf-8', 'gbk', $string);
    }

    /**
     * 格式化excel
     * @param $th_arr  th头部信息
     * @param $list_arr 表数据信息
     * @return string
     */
    public static function format_excel_data($th_arr, $list_arr)
    {
        $th_str = '<tr bgcolor="#FFFF00">';
        $tr_str = '';
        foreach ($th_arr as $key => $th) {
            $th_str .= '<th width=' . $th['width'] . '>' . self::charset($th['name']) . '</th>';
        }
        $th_all_keys    =    array_keys($th_arr['0']);
        $first_key_name = array_shift($th_all_keys);
       $last_key_name  = end($th_arr)['key'];
        foreach($list_arr as $list)
        {
            foreach($list as $key=>$list_detail){
                if($key==$first_key_name){
                    $tr_str .= '<tr>';
                }
                $tr_str .= '<td>' . self::charset($list_detail) . '</td>';
                if($key==$last_key_name)
                {
                    $tr_str .= '</tr>';
                }
            }

        }
        $th_str .= '</tr>';



        $total_num = count($list_arr);
        $date_time = date('Y年m月d日 H:i:s');
        $total_str = <<<TOTAL
<tr bgcolor="#888888">
    <th align="center" colspan="9">总共 {$total_num} 条记录 下载时间:{$date_time} </th>
</tr>
TOTAL;
        $total_str = self::charset($total_str);
        $excel_data = '<table>' . $th_str . $tr_str . $total_str . '</table>';
        return $excel_data;
    }
}


// example 
/*
$th_arr = [
    ['key'=>'name','width'=>200,'name'=>'姓名'],
    ['key'=>'sno','width'=>200,'name'=>'学号'],
];
$list_arr = [
    ['sno'=>1,'name'=>'test1'],
    ['sno'=>2,'name'=>'test2'],
    ['sno'=>3,'name'=>'test3'],
    ['sno'=>4,'name'=>'test4'],
];
$filename = 'test_'. date('YmdHi'). '.xls';
$format_data = Excel::format_excel_data($th_arr,$list_arr);
echo $format_data;
Excel::force_download($filename,$format_data);
