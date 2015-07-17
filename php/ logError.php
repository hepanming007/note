<?php
class logError{
    const LOG_PATH = './log/error_log.log';
    public static $levels = array(
                    E_ERROR  			=>	'Error',
                    E_WARNING			=>	'Warning',
                    E_PARSE				=>	'Parsing Error',
                    8			=>	'Notice',
                    E_CORE_ERROR		=>	'Core Error',
                    E_CORE_WARNING		=>	'Core Warning',
                    E_COMPILE_ERROR		=>	'Compile Error',
                    E_COMPILE_WARNING	=>	'Compile Warning',
                    E_USER_ERROR		=>	'User Error',
                    E_USER_WARNING		=>	'User Warning',
                    E_USER_NOTICE		=>	'User Notice',
                    E_STRICT			=>	'Runtime Notice'
                );
    public static function  log_error_hander($errno,$errstr,$errfile='',$errline='',$errcontext=array())
    {
        $errcode = self::$levels[$errno];
        $log_message = "错误代码:[%s],错误信息:[%s],文件:[%s],行号:[%d],地址:[%s],来源:[%s]";
        $url     = $errcontext['_SERVER']['HTTP_HOST'].$errcontext['_SERVER']['REQUEST_URI'];
        $referer = $errcontext['_SERVER']['HTTP_REFERER'];
        $log_message_format = sprintf($log_message,$errcode,$errstr,$errfile,$errline,$url,$referer);
        error_log($log_message_format.PHP_EOL,3,self::LOG_PATH);
    }
}

set_error_handler(array('logError','log_error_hander'));

/*
trigger_error('触发错误错误');
trigger_error('触发错误错误');
trigger_error('触发错误错误');
trigger_error('触发错误错误');
echo $a['a'];
*/
