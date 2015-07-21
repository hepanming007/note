<?php
/**
 * Class Validate  验证静态类
 */
class Validate{

    /**
     *  检查日期 xxxx-xx-xx
     */
    public static function check_date($date = ''){
        return preg_match('/^[\d]{4}\-[\d]{1,2}-[\d]{1,2}$/', $date);
    }


    /**
     *  检查完整日期 xxxx-xx-xx xx:xx:xx
     */
    public static function check_time($date = ''){
        return preg_match('/^[\d]{4}\-[\d]{1,2}-[\d]{1,2} [\d]{1,2}:[\d]{1,2}:[\d]{1,2}$/', $date);
    }


    /**
     *  检查手机号码
     */
    public static  function check_mobile($mobile = ''){
        return preg_match("/^1[3|4|5|7|8][0-9]\d{8}$/", $mobile);
    }

    /**
     *  检查email格式
     */
    public static function check_email($email = ''){
        return preg_match("/^[\w\-\.]+@[\w\-]+(\.\w+)+$/", $email);
    }

    /**
     *  检查邮政编码
     */
    public static function check_postal_code($postal_code = ''){
        return preg_match("/[1-9]{1}(\d+){5}/", $postal_code);
    }

    /**
     *  检查ipv4 地址
     */
    public static function check_ip($ip){
        return preg_match("/(\d+){1,3}\.(\d+){1,3}\.(\d+){1,3}\.(\d+){1,3}/", $ip);
    }

    /**
     *  检查qq号码
     */
    public static  function check_qq($qq = ''){
        return preg_match("/^[1-9](\d){4,11}$/", $qq);
    }

    /**
     *  检查身份证号码
     */
    public static  function check_id_card($id_card = ''){
        return ( preg_match("/^\d{17}(\d|x)$/i", $id_card) || preg_match("/^\d{15}$/i", $id_card) );
    }

    /**
     *  检查性别
     */
    public static function check_gender($gender){
        return in_array($gender, array(0, 1));
    }

    /**
     *  检查产品编号
     */
    public static function check_product_no($product_no = ''){
        return preg_match('/^[0-9a-zA-Z-]{1,16}$/', $product_no);
    }

    /**
     *  检查电话号码
     */
    public static function check_telephone($telephone = ''){
        return preg_match( "/^[\d]+[\d-]*[\d]$/", $telephone);
    }

    /**
     *  url地址(简单检查是否以http://开头)
     */
    public static function check_url($url = ''){
        return preg_match('/^http[s]?:\/\/.*?/i', $url);
    }

    /**
     *  检查是否全中文
     *  ------------------------------
    中文双字节字符编码范围

    1. GBK (GB2312/GB18030)
    x00-xff GBK双字节编码范围
    x20-x7f ASCII
    xa1-xff 中文 gb2312
    x80-xff 中文 gbk
    2. UTF-8 (Unicode)
    u4e00-u9fa5 (中文)
    x3130-x318F (韩文
    xAC00-xD7A3 (韩文)
    u0800-u4e00 (日文)
     */
    public static function check_chinese($str){
        //return preg_match('/^[\xa1-\xff]+$/', $str);
        return preg_match('/^[\x80-\xff]+$/', $str);
    }

}

