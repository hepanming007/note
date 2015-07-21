<?php
/**
 * Class Validate  验证静态类
 */
class Validate{

     CONST DATE     =   'date';
     CONST TIME      =   'time';
     CONST MOBILE    =   'mobile';
     CONST EMAIL     =   'email';
     CONST POST_CODE =   'post_code';
     CONST IP        =   'ip';
     CONST QQ        =   'qq';
     CONST ID_CART   =   'id_card';
     CONST TELEPHONE =   'telephone';
     CONST URL       =   'url';
     CONST REQUIRED  =   'required';
     CONST NUMBERNIC =   'numeric';
     CONST INTEGER       =   'integer';



     public  static $errorMessage = array(
            self::DATE    =>    '日期格式错误',
            self::TIME    =>    '日期格式错误',
            self::MOBILE  =>    '手机格式错误',
            self::EMAIL   =>    '邮箱格式错误',
            self::POST_CODE =>  '邮政编码错误',
            self::IP       =>   'IP格式错误',
            self::QQ       =>   'QQ格式错误',
            self::ID_CART  =>   '身份账号格式错误',
            self::TELEPHONE=>   '电话号码错误',
            self::URL      =>   'URL地址错误',
            self::REQUIRED =>   '字段必填',
            self::NUMBERNIC =>  '数值类型',
            self::INTEGER   =>  '必须为整型',
     );



    /**
     *  检查日期 xxxx-xx-xx
     */
    public static function date($date = ''){
        return preg_match('/^[\d]{4}\-[\d]{1,2}-[\d]{1,2}$/', $date);
    }


    /**
     *  检查完整日期 xxxx-xx-xx xx:xx:xx
     */
    public static function time($date = ''){
        return preg_match('/^[\d]{4}\-[\d]{1,2}-[\d]{1,2} [\d]{1,2}:[\d]{1,2}:[\d]{1,2}$/', $date);
    }


    /**
     *  检查手机号码
     */
    public static  function mobile($mobile = ''){
        return preg_match("/^1[3|4|5|7|8][0-9]\d{8}$/", $mobile);
    }

    /**
     *  检查email格式
     */
    public static function email($email = ''){
        return preg_match("/^[\w\-\.]+@[\w\-]+(\.\w+)+$/", $email);
    }

    /**
     *  检查邮政编码
     */
    public static function postal_code($postal_code = ''){
        return preg_match("/[1-9]{1}(\d+){5}/", $postal_code);
    }

    /**
     *  检查ipv4 地址
     */
    public static function ip($ip){
        return preg_match("/(\d+){1,3}\.(\d+){1,3}\.(\d+){1,3}\.(\d+){1,3}/", $ip);
    }

    /**
     *  检查qq号码
     */
    public static  function qq($qq = ''){
        return preg_match("/^[1-9](\d){4,11}$/", $qq);
    }

    /**
     *  检查身份证号码
     */
    public static  function id_card($id_card = ''){
        return ( preg_match("/^\d{17}(\d|x)$/i", $id_card) || preg_match("/^\d{15}$/i", $id_card) );
    }

    /**
     *  检查性别
     */
    public static function gender($gender){
        return in_array($gender, array(0, 1));
    }

    /**
     *  检查产品编号
     */
    public static function product_no($product_no = ''){
        return preg_match('/^[0-9a-zA-Z-]{1,16}$/', $product_no);
    }

    /**
     *  检查电话号码
     */
    public static function telephone($telephone = ''){
        return preg_match( "/^[\d]+[\d-]*[\d]$/", $telephone);
    }

    /**
     *  url地址(简单检查是否以http://开头)
     */
    public static function url($url = ''){
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
    public static function chinese($str){
        //return preg_match('/^[\xa1-\xff]+$/', $str);
        return preg_match('/^[\x80-\xff]+$/', $str);
    }


    /**
     * Required
     *
     * @access	public
     * @param	string
     * @return	bool
     */
    public static function required($str)
    {
        if ( ! is_array($str))
        {
            return (trim($str) == '') ? FALSE : TRUE;
        }
        else
        {
            return ( ! empty($str));
        }
    }


    /**
     * Minimum Length
     *
     * @access	public
     * @param	string
     * @param	value
     * @return	bool
     */
    public static function min_length($str, $val)
    {
        if (preg_match("/[^0-9]/", $val))
        {
            return FALSE;
        }

        if (function_exists('mb_strlen'))
        {
            return (mb_strlen($str) < $val) ? FALSE : TRUE;
        }

        return (strlen($str) < $val) ? FALSE : TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Max Length
     *
     * @access	public
     * @param	string
     * @param	value
     * @return	bool
     */
    public static function max_length($str, $val)
    {
        if (preg_match("/[^0-9]/", $val))
        {
            return FALSE;
        }

        if (function_exists('mb_strlen'))
        {
            return (mb_strlen($str) > $val) ? FALSE : TRUE;
        }

        return (strlen($str) > $val) ? FALSE : TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Exact Length
     *
     * @access	public
     * @param	string
     * @param	value
     * @return	bool
     */
    public static function exact_length($str, $val)
    {
        if (preg_match("/[^0-9]/", $val))
        {
            return FALSE;
        }

        if (function_exists('mb_strlen'))
        {
            return (mb_strlen($str) != $val) ? FALSE : TRUE;
        }

        return (strlen($str) != $val) ? FALSE : TRUE;
    }

    // --------------------------------------------------------------------


    /**
     * Numeric
     *
     * @access	public
     * @param	string
     * @return	bool
     */
    public static function numeric($str)
    {
        return (bool)preg_match( '/^[\-+]?[0-9]*\.?[0-9]+$/', $str);

    }

    // --------------------------------------------------------------------

    /**
     * Is Numeric
     *
     * @access	public
     * @param	string
     * @return	bool
     */
    public static function is_numeric($str)
    {
        return ( ! is_numeric($str)) ? FALSE : TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Integer
     *
     * @access	public
     * @param	string
     * @return	bool
     */
    public static function integer($str)
    {
        return (bool) preg_match('/^[\-+]?[0-9]+$/', $str);
    }

    // --------------------------------------------------------------------

    /**
     * Decimal number
     *
     * @access	public
     * @param	string
     * @return	bool
     */
    public static function decimal($str)
    {
        return (bool) preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $str);
    }

    // --------------------------------------------------------------------

    /**
     * Greather than
     *
     * @access	public
     * @param	string
     * @return	bool
     */
    public static  function greater_than($str, $min)
    {
        if ( ! is_numeric($str))
        {
            return FALSE;
        }
        return $str > $min;
    }

    // --------------------------------------------------------------------

    /**
     * Less than
     *
     * @access	public
     * @param	string
     * @return	bool
     */
    public static function less_than($str, $max)
    {
        if ( ! is_numeric($str))
        {
            return FALSE;
        }
        return $str < $max;
    }

    /**
     * Valid Base64
     *
     * Tests a string for characters outside of the Base64 alphabet
     * as defined by RFC 2045 http://www.faqs.org/rfcs/rfc2045
     *
     * @access	public
     * @param	string
     * @return	bool
     */
    public static function base64($str)
    {
        return (bool) ! preg_match('/[^a-zA-Z0-9\/\+=]/', $str);
    }

}

/*
 *
 *
 *
 用法：
<?php
require './helper/Loader.php';
class Val{

    public $rules = [
        'name'=>  array(Validate::DATE,Validate::REQUIRED),
        'time' => Validate::TIME,
        'sno'  => Validate::INTEGER,

    ];

    public $data = array(
        'name'=>'',
        'time'=>'test',
        'sno'=>'test',
    );

    public $_errMessage = array();


    public function run()
    {
        foreach($this->rules as $key=>$rule){
               if(is_array($rule)){
                    foreach($rule as $sub_rule){
                        if(!Validate::$sub_rule($this->data[$key])){
                            $this->_errMessage[$key] = Validate::$errorMessage[$sub_rule];
                        }
                    }
               }else{
                   if(!Validate::$rule($this->data[$key])){
                       $this->_errMessage[$key] = Validate::$errorMessage[$rule];
                   }
               }

        }
    }
}

$val = new Val();
$val->run();
echo "<pre>";
ArrayHelper::p($val->_errMessage);
 *
 *
 *
 */
