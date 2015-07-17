<?php

 
/**
 * php 数组助手类
 * Class ArrayHelper
 * @package app\helper
 */
class ArrayHelper {
    /**
     * @brief   get_ids_arr     取得某个二维数组里的id集合
     *-----------------------------------------------
     *  $arr = array(
     *              array('line_id'  =>  1, 'title'    =>  '标题1',),
     *              array('line_id'  =>  2, 'title'    =>  '标题2',),
     *          );
     *  $line_ids = $this->share->get_ids_arr($arr);
     *-----得到--------------------------------------
     *  $line_ids = array(1, 2);
     *-----------------------------------------------
     * @Param   $arr            原始数组
     * @Param   $field          需要的字段：如 id, line_id, cid...
     * @Param   $zero           是否增加一个元素0，防止空数组导致where_in('id', $ids)出错
     *
     * @Returns Array
     */
    public static function get_ids_arr($arr = array(array('id'=>1, 'other'=>''),), $field = 'id', $zero = false){
        $new_arr = array();
        foreach ($arr as $ak=>$av) {
            if (!array_key_exists($field, $av)) {
                break;      //非法数组
            }
            $new_arr[] = $av[$field];
        }
        if (empty($new_arr) && $zero) {
            $new_arr[] = 0;
        }

        return $new_arr ? array_unique($new_arr) : $new_arr;
    }


    /**
     * @brief   reform_arr  重组数组
     * --------------------------------------------
     *  $arr = array(
     *              array('line_id'  =>  11, 'title'    =>  '标题1',),
     *              array('line_id'  =>  22, 'title'    =>  '标题2',),
     *          );
     *  $new_arr = $this->share->reform_arr($arr);
     * ----得到------------------------------------
     *  array(
     *          11=>array('line_id'  =>  11, 'title'    =>  '标题1',),
     *          22=>array('line_id'  =>  22, 'title'    =>  '标题2',),
     *      );
     * --------------------------------------------
     * @Param   $arr
     * @Param   $field
     *
     * @Returns Array
     */
    public static function reform_arr($arr = array(array('id'=>1, 'other'=>''),), $field = 'id'){
        $new_arr = array();
        if (!is_array($arr)) {
            return $new_arr;
        }
        foreach ($arr as $av) {
            if (!is_array($av)) {
                break;
            }
            if (!array_key_exists($field, $av)) {
                break;
            }
            if (!isset($new_arr[$av[$field]])) {
                $new_arr[$av[$field]] = $av;
            }
        }
        return $new_arr;
    }

    /**
     * Builds a map (key-value pairs) from a multidimensional array or an array of objects.
     * The `$from` and `$to` parameters specify the key names or property names to set up the map.
     * Optionally, one can further group the map according to a grouping field `$group`.
     *
     * For example,
     *
     * ~~~
     * $array = [
     *     ['id' => '123', 'name' => 'aaa', 'class' => 'x'],
     *     ['id' => '124', 'name' => 'bbb', 'class' => 'x'],
     *     ['id' => '345', 'name' => 'ccc', 'class' => 'y'],
     * ];
     *
     * $result = ArrayHelper::map($array, 'id', 'name');
     * // the result is:
     * // [
     * //     '123' => 'aaa',
     * //     '124' => 'bbb',
     * //     '345' => 'ccc',
     * // ]
     *
     * $result = ArrayHelper::map($array, 'id', 'name', 'class');
     * // the result is:
     * // [
     * //     'x' => [
     * //         '123' => 'aaa',
     * //         '124' => 'bbb',
     * //     ],
     * //     'y' => [
     * //         '345' => 'ccc',
     * //     ],
     * // ]
     * ~~~
     *
     * @param array $array
     * @param string|\Closure $from
     * @param string|\Closure $to
     * @param string|\Closure $group
     * @return array
     */
    public static function map($array, $from, $to, $group = null)
    {
        if(!is_array($array)){
            return array();
        }
        $result = [];
        foreach ($array as $element) {
            if(!array_key_exists($from,$element) OR !array_key_exists($to,$element))
            {
                continue;
            }
            $key   = $element[$from];
            $value = $element[$to];
            if ($group !== null) {
                if(!array_key_exists($group,$element))
                {
                    continue;
                }
                $result[$element[$group]][$key] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }


    /**
     * @brief   get_ids_str     取得某个二维数组里的id集合,组成字符串
     *-----------------------------------------------
     *  Array
    (
    [0] => Array
    (
    [id] => 9613
    [total] => 4
    )
    [1] => Array
    (
    [id] => 1234
    [total] => 5
    )

    )
     *  $line_ids = $this->share->get_ids_str($arr);
     *-----得到--------------------------------------
     *  $line_ids = 9613,1234;
     *-----------------------------------------------
     * @Param   $arr            原始数组
     * @Param   $field          需要的字段：如 id, line_id, cid...
     * @Param   $zero           是否增加一个元素0，防止空数组导致WHERE id IN('')出错
     *
     * @Returns string
     */
    public static function get_ids_str($arr = array(array('id'=>1, 'other'=>''),), $field = 'id', $zero = false){
        return join(',', self::get_ids_arr($arr, $field));
    }

    /**
     * @brief   array_pop_ele_byval     根据指定值剔除数组中的元素
     * ---------------------------------------------
     *  Example
     *      $arr = array('a'=>'hello', 'b'=>'abc', 'c'=>'hello');
     *      $arr = $this->share->array_pop_ele_byval($arr, 'hello');
     *      print_r($arr);exit;
     * --------------------------------------------
     * @Param   $arr
     * @Param   $val
     *
     * @Returns Array
     */
    public static function array_pop_ele_byval($arr, $val = ''){
        if (!is_array($arr)) {
            return false;
        }
        foreach ($arr as $ak=>$av) {
            if ($av == $val) {
                unset($arr[$ak]);
            }
        }
        return $arr;
    }


    /**
     * @brief   array_pop_ele_bykey     根据指定指定下标剔除元素
     * ---------------------------------------------
     *  Example
     *      $arr = array('a'=>'hello', 'b'=>'abc', 'c'=>'hello');
     *      $arr = $this->share->array_pop_ele_bykey($arr, 'a');
     *      print_r($arr);exit;
     * --------------------------------------------
     * @Param   $arr
     * @Param   $key
     *
     * @Returns Array
     */
    public static function array_pop_ele_bykey($arr, $key=''){
        if (!is_array($arr)) {
            return false;
        }
        foreach ($arr as $ak=>$av) {
            if ($ak == $key) {
                unset($arr[$ak]);
            }
        }
        return $arr;
    }


    /**
     * @brief   array2sort  二维数组 根据指定下标 排序(冒泡)    保持索引关系
     * --------------------------------------------------------------------
     * $arr = array(
     *     'a'=>array( 'key1'=>3,   'key2'=>50,),
     *     'b'=>array( 'key1'=>79,  'key2'=>30,),
     *     'c'=>array( 'key1'=>8,   'key2'=>40,),
     *     'd'=>array( 'key1'=>55,  'key2'=>20,),
     *     11=>array( 'key1'=>2,   'key2'=>300,),
     *     );
     *  $arr = array2sort($arr, 'key2', 'a');print_r($arr);
     *--------------------------------------------------------------------
     * @Param   $arr        待排序数组,(既可以是索引数组，也可以是关系型数组)
     * @Param   $key        要排序的下标
     * @Param   $sort       d-降序 a-升序
     *
     * @Returns Array
     */
    public function array2sort($arr, $key='', $sort = 'd'){
        $n = count($arr);
        $tmp = array();
        if (empty($arr) || empty($key) || !in_array($sort, array('d', 'a'))) {
            return $arr;
        }
        foreach ($arr as $ak=>$av) {            //为保持索引关系，将Key压入数组最后一个元素值保存
            array_push($arr[$ak], $ak);
        }
        $arr = array_values($arr);
        for ($i = 0; $i < $n; $i++) {
            for ($j = $n-1; $j > $i; $j--) {
                //降序排列
                if ($sort == 'd') {
                    if (isset($arr[$i][$key]) && $arr[$i][$key] < $arr[$j][$key]) {
                        $tmp = $arr[$i];
                        $arr[$i] = $arr[$j];
                        $arr[$j] = $tmp;
                    }

                    //升序排列
                } else {
                    if (isset($arr[$i][$key]) && $arr[$i][$key] > $arr[$j][$key]) {
                        $tmp = $arr[$j];
                        $arr[$j] = $arr[$i];
                        $arr[$i] = $tmp;
                    }
                }
            }
        }

        $new_arr = array();
        foreach ($arr as $ak=>$av) {        //为保持索引关系，将最右一个元素值key，拿出来放到下标里
            $tmp_key = array_pop($arr[$ak]);
            $new_arr[$tmp_key] = $arr[$ak];
        }
        return $new_arr;
    }
}
