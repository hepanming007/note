<?php
/**
 * Class SqlHelper  sql语句工具类
 */
class SqlHelper
{
    /**
     * 生成批量插入sql语句
     * @param $table       表名称
     * @param $insert_data 插入语句的字段和values值
     * @return string
     */
    public static function batch_insert_sql($table, $insert_data)
    {
        if (!isset($insert_data['field']) || !isset($insert_data['values'])) {
            return '';
        }

        $sql = "INSERT INTO {$table} " . $insert_data['field'];
        $values = 'VALUES ' . implode(',', $insert_data['values']);
        $sql .= $values;
        return $sql;
    }

    /**
     * 生成批量更新的sql语句
     * @param $table       表名称
     * @param $update_data 批量更新的字段、字段数组、values
     * @return string      生成 on duplicate key update的sql语句
     */
    public static function batch_update_sql($table, $update_data)
    {
        if (!isset($update_data['field']) ||
            !isset($update_data['field_arr']) ||
            !isset($update_data['values'])
        ) {
            return '';
        }

        $duplicate_update_str = '';
        foreach ($update_data['field_arr'] as $field) {
            $duplicate_update_str .= "{$field} = values({$field}), ";
        }
        $duplicate_update_str = substr(trim($duplicate_update_str), 0, -1);

        $sql = "INSERT INTO {$table} " . $update_data['field'];
        $values = 'VALUES ';
        $values .= implode(',', $update_data['values']);
        $sql .= $values;
        $sql .= 'on duplicate key update ';
        $sql .= $duplicate_update_str;
        return $sql;
    }


    /**
     * @param $table
     * @param $insert_data
     * @return string
     */
    public static function batch_multiple_insert_sql($table, $insert_data)
    {
        if (!isset($insert_data['field']) ||
            !isset($insert_data['values_arr'])
        ) {
            return '';
        }

        $sql_arr = array();
        $sql = '';
        foreach ($insert_data['values_arr'] as $insert_value) {
            $new_insert_data = array(
                'field' => $insert_data['field'],
                'values' => $insert_value,
            );
            $sql_arr[] = self::batch_insert_sql($table, $new_insert_data);
        }
        $sql = implode('; ', $sql_arr);
        return $sql;
    }

    /**
     * @param $table
     * @param $update_data
     * @return string
     */
    public static function batch_multiple_update_sql($table, $update_data)
    {
        if (!isset($update_data['field']) ||
            !isset($update_data['field_arr']) ||
            !isset($update_data['values_arr'])
        ) {
            return '';
        }
        $sql_arr = array();
        $sql = '';
        foreach ($update_data['values_arr'] as $update_values) {
            $new_update_data = array(
                'field' => $update_data['field'],
                'field_arr' => $update_data['field_arr'],
                'values' => $update_values,
            );
            $sql_arr[] = self::batch_update_sql($table, $new_update_data);
        }
        $sql = implode('; ', $sql_arr);
        return $sql;
    }

    /**
     * 生成插入字段
     * @param $iarr  字段数组
     * @return string 字段字符串
     */
    public static function  insert_field($iarr)
    {
        $fstr = '';
        foreach ($iarr as $key => $val) {
            $fstr .= '`' . $val . '`, ';
        }
        $fstr = '(' . substr($fstr, 0, -2) . ')';
        return $fstr;
    }

    /**
     * 生成插入的值
     * @param $iarr    数组
     * @return string  字符串('xxx','xxxx')
     */
    public static function  insert_values($iarr)
    {
        if (is_array($iarr)) {
            $fstr = '1';
            $vstr = '';
            foreach ($iarr as $key => $val) {
                $vstr .= '\'' . $val . '\', ';
            }
            if ($fstr) {
                $vstr = '(' . substr($vstr, 0, -2) . ')';
                return $vstr;
            } else {
                return ('');
            }
        } else {
            return ('');
        }
    }

}
