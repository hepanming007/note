<?php
/**
 * Class Curl  curl简单封装  支持get post login download
 */
class Curl
{
    /**
     *  cookie存储文件位置
     */
    CONST COOKIE_FILE = 'cookiefile';

    /**
     *  日志文件路径
     */
    CONST CURL_LOG_PATH = './curl.log';

    /**
     *  响应的头部信息
     * @var array
     */
    public static $responseHeaders = array();

    /**
     * @brief                  get请求
     * @param $url             请求的url
     * @param array $param     请求的参数
     * @param array $header    头部数据
     * @param int $timeout     超时时间
     * @param int $followAction 是否允许被抓取的链接跳转
     * @param int $gzip         是否启用gzip压缩
     * @param string $format    格式
     * @param int $log       是否启用日志
     * @return mixed
     */
    public static function get($url, $param = array(), $login=false,$format = 'html',$header = array(), $timeout = 3, $followAction = 0, $gzip = 0,$log=0)
    {
        $ch = curl_init();
        if (is_array($param)) {
            $url = $url . '?' . http_build_query($param);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)");
        if($login)
        {
            curl_setopt($ch,CURLOPT_COOKIEFILE,self::COOKIE_FILE);
            //curl_setopt($ch,CURLOPT_COOKIE,session_name().'='.session_id());
        }
        if ($followAction) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //允许被抓取的链接跳转
        }
        if ($gzip) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip, deflate'));
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        }

        //curl_setopt($ch, CURLOPT_REFERER, '');
        $data = curl_exec($ch);
        if ($format == 'json') {
            $data = json_decode($data, true);
        }

        self::$responseHeaders = curl_getinfo($ch);
        if($log){
            if($format=='html'){
                self::_logCurlInfo($ch,$param,'');
            }else{
                self::_logCurlInfo($ch,$param,$data);
            }
        }
        curl_close($ch);
        return $data;
    }
    /**
     * @brief                   post请求
     * @param $url              请求的url地址
     * @param array $param      请求的参数
     * @param array $header     http头
     * @param int $ssl          是否启用ssl
     * @param string $format    返回的格式
     * @param int $log          是否启用日志
     * @return mixed
     */
    public static function post($url, $param = array(),$login=false,$format = 'json',$header = array(), $ssl = 0,$log=0)
    {
        $ch = curl_init();
        if (is_array($param)) {
            $urlparam = http_build_query($param);
        } else if (is_string($param)) { //json字符串
            $urlparam = $param;
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120); //设置超时时间
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回原生的（Raw）输出
        curl_setopt($ch, CURLOPT_POST, 1); //POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $urlparam); //post数据

        if($login)
        {
            curl_setopt($ch,CURLOPT_COOKIEFILE,self::COOKIE_FILE);
            curl_setopt($ch,CURLOPT_COOKIE,session_name().'='.session_id());
        }

        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if ($ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        }
        $data = curl_exec($ch);
        self::$responseHeaders = curl_getinfo($ch);
        if ($format == 'json') {
            $data = json_decode($data, true);
        }
        if($log){
            if($format=='html'){
                self::_logCurlInfo($ch,$param,'');
            }else{
                self::_logCurlInfo($ch,$param,$data);
            }
        }
        curl_close($ch);
        return $data;
    }


    /**
     * curl登陆
     * @param $url   登陆的url地址
     * @param $param 参数 用户名密码等
     * @return mixed
     */
    public static function login($url,$param,$ssl=false)
    {
        $ch = curl_init();
        if (is_array($param)) {
            $postParam = http_build_query($param);
        } else if (is_string($param)) { //json字符串
            $postParam = $param;
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);           // 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch,CURLOPT_COOKIESESSION,1);             //启用时curl会仅仅传递一个session cookie，忽略其他的cookie，
        curl_setopt($ch,CURLOPT_COOKIEJAR,self::COOKIE_FILE);//连接结束后保存cookie信息的文件。
        curl_setopt($ch, CURLOPT_HEADER, 0);                 //启用时会将头文件的信息作为数据流输出。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);	     //页面跳转
        curl_setopt($ch, CURLOPT_POST, 1);                   //启用时会发送一个常规的POST请求
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postParam);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("application/x-www-form-urlencoded;charset=utf-8","Content-length: ".strlen($param)));
        if ($ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        }
        $data =  curl_exec($ch);
        self::$responseHeaders = curl_getinfo($ch);
        return $data;
    }

    /**
     * 批量获取url结果
     * @param $url_array     批量的url
     * @param int $wait_usec 每個 connect 要間隔多久
     * @return array|bool
     */
    public static function async_get_url($url_array, $wait_usec = 0)
    {
        if (!is_array($url_array))
            return false;

        $wait_usec = intval($wait_usec);

        $data = array();
        $handle = array();
        $running = 0;
        try {
            $mh = curl_multi_init(); // multi curl handler
            $i = 0;
            foreach ($url_array as $url) {
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return don't print
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip, deflate'));
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 302 redirect
                curl_setopt($ch, CURLOPT_MAXREDIRS, 7);

                curl_multi_add_handle($mh, $ch); // 把 curl resource 放進 multi curl handler 裡

                $handle[$i++] = $ch;
            }

            /* 執行 */
            /* 此種做法會造成 CPU loading 過重 (CPU 100%)
            do {
                curl_multi_exec($mh, $running);

                if ($wait_usec > 0) // 每個 connect 要間隔多久
                    usleep($wait_usec); // 250000 = 0.25 sec
            } while ($running > 0);
            */

            /* 此做法就可以避免掉 CPU loading 100% 的問題 */
            // 參考自: http://www.hengss.com/xueyuan/sort0362/php/info-36963.html
            /* 此作法可能會發生無窮迴圈 */
            /*
            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);

            while ($active and $mrc == CURLM_OK) {
               if (curl_multi_select($mh) != -1) {
                    do {
                        $mrc = curl_multi_exec($mh, $active);
                    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
               }
            }
            */

            /*
            // 感謝 Ren 指點的作法. (需要在測試一下)
            // curl_multi_exec的返回值是用來返回多線程處裡時的錯誤，正常來說返回值是0，也就是說只用$mrc捕捉返回值當成判斷式的迴圈只會運行一次，而真的發生錯誤時，有拿$mrc判斷的都會變死迴圈。
            // 而curl_multi_select的功能是curl發送請求後，在有回應前會一直處於等待狀態，所以不需要把它導入空迴圈，它就像是會自己做判斷&自己決定等待時間的sleep()。
            */

            do {
                curl_multi_exec($mh, $running);
                curl_multi_select($mh);
            } while ($running > 0);

            /* 讀取資料 */
            foreach ($handle as $i => $ch) {

                $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if (($status == 200 || $status == 302 || $status == 301)) {
                    echo $url_array[$i] . PHP_EOL;
                    $content = curl_multi_getcontent($ch);
                    $data[$i] = (curl_errno($ch) == 0) ? $content : false;
                }
                curl_close($ch);
                curl_multi_remove_handle($mh, $ch); /* 移除 handle*/
            }
            curl_multi_close($mh);
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
        return $data;
    }


    /**
     * 请求信息记录日志
     * @param $ch       curl句柄
     * @param $request  请求参数
     * @param $response 响应结果
     */
    private static function _logCurlInfo($ch,$request,$response)
    {
        $info = curl_getinfo($ch);
        $resultFormat =  "耗时:[%s] 返回状态:[%s] 请求的url[%s] 请求参数:[%s] 响应结果:[%s] 大小:[%s]kb 速度:[%s]kb/s";
        $resultLogMsg =  sprintf($resultFormat,$info['total_time'],$info['http_code'],$info['url'],var_export($request,true),var_export($response,true),$info['size_download']/1024,$info['speed_download']/1024);
        error_log($resultLogMsg.PHP_EOL,3,self::CURL_LOG_PATH);
    }

    /**
     *  下载远程文件
     * @param $remoteUrl  远程文件地址
     * @param $localFile  本地文件地址
     * @param bool $login 是否需要登陆
     */
    public static function download($remoteUrl,$localFile,$login=false)
    {
        $ch = curl_init($remoteUrl);
        $fp = fopen($localFile,'w');
        curl_setopt($ch,CURLOPT_FILE,$fp);//这个文件将是你放置传送的输出文件，默认是STDOUT.
        curl_setopt($ch,CURLOPT_HEADER,0);//启用时会将头文件的信息作为数据流输出
        if($login)
        {
            curl_setopt($ch,CURLOPT_COOKIE,self::COOKIE_FILE);
        }
        curl_exec($ch);
        self::$responseHeaders = curl_getinfo($ch);
        curl_close($ch);
        fclose($fp);
    }
}


/* example:
    echo Curl::get('http://www.baidu.com');
    Curl::login('http://www.imooc.com/user/login','username=yangyun4814@gmail.com&password=yang995224814&remember=1');
    echo Curl::get('http://www.imooc.com/space/index','',true);

    Curl::downLoad('http://192.168.1.7:701/hotel/32586','1.html');
    echo "<pre>";
    print_r(Curl::$responseHeaders);
    $arr = Curl::post('127.0.0.1/test/test.php',['a'=>1,'b'=>2],'',0);
    var_dump($arr);
*/
