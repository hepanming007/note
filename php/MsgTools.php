<?php


class MsgTools{

    /**
     * @brief   exit_msg    返回消息
     *
     * @Param   $msg        提示消息
     * @Param   $res        0失败   1成功
     * @Param   $data       返回的数据
     * @Param   $exit_flag  是否立即退出,fastcgi_finish_request应用场景
     *
     * @Returns NULL
     */
    public static function exit_msg($msg, $status = 0,$data='', $exit_flag=true){
        $res = array(
            'status'=>$status,
            'msg'=>$msg,
            'data'=>$data,
        );
        header('Content-type:application/x-javascript');
        if ($exit_flag) {
            exit(self::cncn_json_encode($res));
        } else {
            echo self::cncn_json_encode($res);
        }
    }

    public static function cncn_json_encode($data) {
        return json_encode(Encoding::g2u($data));
    }

    /**
     *判断是否是异步请求
     * @return bool
     */
    public static function is_ajax(){
        $r = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : '';
        return $r == 'xmlhttprequest';
    }

    public static  function show_msg($msg, $url = "") {
        echo "<script type=\"text/javascript\">";
        if (strlen($msg) > 1) {
            echo "alert(\"$msg\");";
        }
        if ($url == "") {
            echo "history.go(-1);";
        } else {
            echo "document.location.href='$url';";
        }
        echo "</script>";

        exit();
    }

    public static function cncn_exit($msg,$url){
        if(self::is_ajax()){
            self::exit_msg($msg);
        }else{
            self::show_msg($msg,$url);
        }
    }

    /**
     * 404跳转
     */
    public static function page_404(){
        header ( "HTTP/1.0 404 Not Found" );
        header ( "Status: 404 Not Found" );
        exit();
    }
    /***
     * 301跳转
     * @param $url
     */
    public static function page_301($url){
        header('HTTP/1.1 301 Moved Permanently');
        header('Location',$url);
        exit();
    }

}
