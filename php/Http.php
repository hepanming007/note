<?php

namespace Tools;
/**
 * Class Http  curl简单封装  支持get post login download
 * 支持级联操作
 */
class Http
{
    public $debug_level = 1;
    public $request_header = '';
    public $request_body = '';
    public $request_headers = array();
    public $options = array();
    public $response_headers = array();
    public $response_header_lines = array();
    public $fp;
    public $status_code = null;
    public $error_code = 0;
    public $error_info = '';
    public $debug_info = '';
    CONST COOKIE_FILE = 'cookiefile';
    CONST CURL_LOG_PATH = './curl.log';
    public static $last_log = array();
    private static $_instance = '';

    public function default_options()
    {
        return array(
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; rv:43.0) Gecko/20100101 Firefox/43.0',
            CURLOPT_MAXREDIRS => 5, // 最大重定向次数
            CURLOPT_TIMEOUT => 30, // 接口请求的超时时间
            CURLOPT_FOLLOWLOCATION => true, // 是否继续请求 Location header 指向的 URL 地址
            CURLOPT_HEADER => 0, // 在输出中包含 HTTP头
            CURLOPT_RETURNTRANSFER => true, // 以字符串形式返回 HTTP 响应，而不是在页面直接输出内容
            CURLOPT_FAILONERROR => false, // 在发生错误时，不返回错误页面（例如 404页面）
            CURLOPT_CONNECTTIMEOUT => 15, // 连接超时时间
            CURLOPT_SSL_VERIFYHOST => 2, // 2 - 检查公用名是否存在，并且是否与提供的主机名匹配
            CURLOPT_SSL_VERIFYPEER => 0, // 网站SSL证书验证，不推荐设为0或false，设为0或false不能抵挡中间人攻击
            // ref. http://cn2.php.net/manual/en/function.curl-setopt.php#110457
            // Turning off CURLOPT_SSL_VERIFYPEER allows man in the middle (MITM) attacks, which you don't want!
            //   CURLOPT_CAINFO         => __DIR__ . '/cacert.pem',  // CA证书
            // 如需更新，从 http://curl.haxx.se/ca/cacert.pem 获取
            //  CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
            // ref. http://www.cnblogs.com/cfinder010/p/3192380.html
            // 如果开启了IPv6，curl默认会优先解析 IPv6，在对应域名没有 IPv6 的情况下，
            // 会等待 IPv6 dns解析失败 timeout 之后才按以前的正常流程去找 IPv4。
            // curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4)
            // 只有在php版本5.3及以上版本，curl版本7.10.8及以上版本时，以上设置才生效。
            //CURLOPT_HEADERFUNCTION => array($this, 'parse_response_header'),
            // CURLOPT_FAILONERROR == true 时，会导致出错时 parse_response_header() 不会调用
        );

    }

    public static function client()
    {
        if (!self::$_instance instanceof Http) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function debug($level)
    {
        $this->debug_level = $level;
        if ($this->debug_level == 1) {
            $this->options[CURLINFO_HEADER_OUT] = true;
            $this->options[CURLOPT_HEADER] = true;
        } elseif ($this->debug_level == 2) {
            $this->options[CURLINFO_HEADER_OUT] = 0;
            $this->options[CURLOPT_HEADER] = 0;
            $this->options[CURLOPT_VERBOSE] = true;
            $this->fp = fopen('php://temp', 'rw+');
            $this->options[CURLOPT_STDERR] = $this->fp;
        }
        return $this;
    }

    public function set_option($name, $value)
    {
        $this->options[$name] = $value;

        return $this;
    }

    public function set_options($options)
    {
        foreach ($options as $name => $value) {
            $this->options[$name] = $value;
        }

        return $this;
    }

    public function set_header($name, $value)
    {
        $name = $this->normalize_header($name);
        $this->request_headers[$name] = $value;

        return $this;
    }

    public function set_headers(array $headers)
    {
        foreach ($headers as $name => $value) {
            $name = $this->normalize_header($name);
            $this->request_headers[$name] = $value;
        }

        return $this;
    }

    protected function normalize_header($name)
    {
        $string = ucwords(str_replace('-', ' ', $name));

        return str_replace(' ', '-', $string);
    }

    public function auth_basic($username, $password)
    {
        $credentials = "$username:$password";
        $this->options[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
        $this->options[CURLOPT_USERPWD] = $credentials;

        return $this;
    }

    public function auth_bearer($access_token)
    {
        $this->set_header('Authorization', 'Bearer ' . $access_token);

        return $this;
    }

    protected function set_request_method($ch, $http_method)
    {
        switch (strtoupper($http_method)) {
            case 'HEAD':
                curl_setopt($ch, CURLOPT_NOBODY, true);
                break;
            case 'GET':
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method);
        }
    }

    public function ssl()
    {
        $this->options[CURLOPT_SSL_VERIFYPEER] = false; // 对认证证书来源的检查
        return $this;
    }

    public function log()
    {
        error_log(var_export(self::$last_log, true) . PHP_EOL, 3, self::CURL_LOG_PATH);
        return $this;
    }

    public function cookie()
    {
        $this->options[CURLOPT_COOKIEFILE] = self::COOKIE_FILE;
        $this->options[CURLOPT_COOKIE] = session_name() . '=' . session_id();
        return $this;
    }

    public function json()
    {
        $data = json_decode(self::$last_log['response'], true);
        return $data;
    }

    public function html()
    {
        return self::$last_log['response'];
    }

    public function reset_options()
    {
        $this->options = array();
    }

    public function request($http_method, $url, $params = null)
    {
        $ch = curl_init();
        if ($http_method == 'GET') {
            if (is_array($params)) {
                $url = $url . '?' . http_build_query($params);
            }
            self::$last_log['request'] = $url;
        } else {
            $post_param = array();
            if (is_array($params)) {
                $post_param = http_build_query($params);
            } else if (is_string($params)) { //json字符串
                $post_param = $params;
            }
            self::$last_log['request'] = $url . $post_param;
            $curl_options[CURLOPT_POST] = 1;
            $curl_options[CURLOPT_POSTFIELDS] = $post_param; //post数据
        }

        $this->set_request_method($ch, $http_method);
        $curl_options[CURLOPT_URL] = $url;
        if (!empty($this->request_headers)) {
            $curl_options[CURLOPT_HTTPHEADER] = $this->request_headers; //头部信息
        }
        $curl_options = $curl_options + $this->options + $this->default_options();
        curl_setopt_array($ch, $curl_options);
        $data = curl_exec($ch);
        self::$last_log['response'] = $data;
        self::$last_log['info'] = $this->debug_level == 2 ? (rewind($this->fp) ? stream_get_contents($this->fp) : '') : curl_getinfo($ch);
        $this->status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->reset_options();
        return $this;
    }

    public function get($url, $params = array())
    {
        return $this->request('GET', $url, $params);
    }

    public function post($url, $params = array())
    {
        return $this->request('POST', $url, $params);
    }

    public function put($url, $params = array())
    {
        return $this->request('PUT', $url, $params);
    }

    public function delete($url)
    {
        return $this->request('DELETE', $url);
    }

    public function patch($url, $params = array())
    {
        return $this->request('PATCH', $url, $params);
    }

    public function login($url, $params)
    {
        $login_options = array(
            CURLOPT_COOKIESESSION => 1, //启用时curl会仅仅传递一个session cookie，忽略其他的cookie，
            CURLOPT_COOKIEJAR => self::COOKIE_FILE, //连接结束后保存cookie信息的文件。
        );
        $this->set_options($login_options);
        return $this->request('POST', $url, $params);
    }

    public function download($remoteUrl, $localFile, $login = false)
    {
        $ch = curl_init($remoteUrl);
        $fp = fopen($localFile, 'w');
        curl_setopt($ch, CURLOPT_FILE, $fp); //这个文件将是你放置传送的输出文件，默认是STDOUT.
        curl_setopt($ch, CURLOPT_HEADER, 0); //启用时会将头文件的信息作为数据流输出
        if ($login) {
            curl_setopt($ch, CURLOPT_COOKIE, self::COOKIE_FILE);
        }
        curl_exec($ch);
        self::$last_log = array(
            'request' => '',
            'response' => '',
            'info' => curl_getinfo($ch),
        );
        curl_close($ch);
        fclose($fp);
    }

    public function status_code()
    {
        return $this->status_code;
    }

    public function print_last_log()
    {
        echo "<pre>";
        print_r(self::$last_log);
    }
}

// get post login download put delete patch 等操作
$result = \Tools\Http::client()
                    ->debug(2)
                    ->login('http://www.imooc.com/user/login', 'username=yangyun4814@gmail.com&password=yang995224814&remember=1')
                    ->cookie()
                    ->get('http://www.imooc.com/space/index');
echo $result->html();
var_dump($result->status_code());

