<?php
//1000 2000  3000 4000
class ExceptionCode{
    const SUCCESS         = 0;
    const FROM_ERROR     = 1000;//表单验证错误
    const LOGIC_ERROR    = 2000;//逻辑验证错误
    const DB_ERROR       = 3000;//数据库验证错误
    const API_ERROR      = 4000;//api接口错误
}

abstract class BaseException extends Exception{
        public $message;
        public $code;
        public $data;
        public function __construct($code,$message,$data=''){
            $this->code    = $code;
            $this->message = $message;
            $this->data    = $data;
            parent::__construct($this->message, $this->code);
        }

        //默认异常处理函数
        public function handle(){
            //这里可以把异常信息持久化到数据库，文件中，方便排错。
            echo '--异常已经存入数据库:' . $this->message;
        }

        public function getData(){
            return $this->data;
        }
}

/**
 * 异常处理句柄
 */
class ExceptionHandle{

    /**
     * 默认异常处理函数
     * @param  [type] $e [description]
     * @return [type]    [description]
     * @author zhoushen
     */
    static function deal($exception){

        //执行用户自定义异常处理函数
        if( method_exists($exception, 'handle') ){
            $exception->handle();
        }

        if(static::isAjax() ){
            exit(json_encode(array('code' =>$exception->getCode(),'msg'  =>$exception->getMessage(),'data'=>$exception->getData())));
        }else{

            echo "非异步请求出错处理";
            //这里可以include你站点的异常处理页面
           // echo;
        }
    }

    /**
     * 判断是否是ajax
     * @return boolean [description]
     * @author zhoushen
     */
    static function isAjax(){
        $r = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : '';
        return $r == 'xmlhttprequest';
    }
}


class FlightException extends BaseException{
    public function handle(){
        echo "<pre>";
        print_r($this->message);
        print_r($this->code);
        echo __FILE__.__LINE__;
//        print_r($this);

    }
}
