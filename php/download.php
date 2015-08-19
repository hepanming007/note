<?php
/*
* 参考：
*  https://github.com/krakjoe/pthreads/blob/master/examples/SimpleWebRequest.php
*  http://zyan.cc/pthreads/
*
*/
class AsyncWebRequest extends Thread
{
    public $url;
    public $data;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function run()
    {
        if (($url = $this->url)) {
            $this->data = model_http_curl_get($url);
        }
    }
}

/**
 * 多线程请求
 * @param $urls_array
 * @return mixed
 */
function model_thread_result_get($urls_array)
{
    foreach ($urls_array as $key => $value) {
        $thread_array[$value['id']] = new AsyncWebRequest($value['url']);
        $thread_array[$value['id']]->start();
    }
    foreach ($thread_array as $thread_array_key => $thread_array_value) {
        while ($thread_array[$thread_array_key]->isRunning()) {//对象是否正在运行
            usleep(10);
        }
        if ($thread_array[$thread_array_key]->join()) {//同步
            $variable_data[$thread_array_key] = $thread_array[$thread_array_key]->data;
        }
    }
    unset($thread_array);
    return $variable_data;
}

/**
 * curl get封装
 * @param $url
 * @param string $userAgent
 * @return mixed
 */
function model_http_curl_get($url, $userAgent = "")
{
    $userAgent = $userAgent ? $userAgent : 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2)';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip, deflate'));
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


/**
 * 获取艺龙id对应的远程xml地址
 * @param $elong_id
 * @return string
 */
function get_elong_xml($elong_id)
{
    $url = 'http://api.test.lohoo.com/xml/v2.0/hotel/cn/' . substr($elong_id, -2) . '/' . $elong_id . '.xml';
    return $url;
}

/**
 * 保存艺龙xml到本地
 * @param $elong_id   艺龙id号
 * @param $elong_hotel_data
 */
function save_elong_xml($elong_id, $elong_hotel_data)
{
    $local_file_path = 'temp/' . substr($elong_id, -2) . '/' . $elong_id . '.xml';
    $xml_local_path = 'temp/' . substr($elong_id, -2);
    if (!file_exists($local_file_path)) {
        if (!is_dir($xml_local_path)) {
            mkdir($xml_local_path, 0777, true);
        }
        if (!empty($elong_hotel_data) && (substr($elong_hotel_data, 0, 5) === '<?xml') ) {//简单验证xml
            file_put_contents($local_file_path, $elong_hotel_data);
        }
    }
}

/**
 * 执行下载
 * @param string $fileNum
 */
function download_elong_xml($fileNum='')
{
    set_time_limit(0);
    if(!defined('DIR_DATA')){define('DIR_DATA','./data');} 
    if($fileNum){//单个文件
        $data_files = array(DIR_DATA."/hotellist{$fileNum}.php");
    }else{//目录下的所有文件
        $data_files = glob(DIR_DATA . '/*.php');
        sort($data_files, SORT_NATURAL);
    }
 
    foreach ($data_files as $filename) {
        if (file_exists($filename)) {
            echo $filename . PHP_EOL;
            $urls_array = array();
            $hotelArr = require $filename;
            foreach ($hotelArr as $hotel) {
                $hotel_attr = $hotel['@attributes'];
                if (empty($hotel_attr['HotelId'])) continue;
                $urls_array[] = array('id' => $hotel_attr['HotelId'], 'url' => get_elong_xml($hotel_attr['HotelId']));
            }
            
            $t = microtime(true);
            $totalArr = count($urls_array);
            $perNum = 10;
            for ($i = 0; $i < $totalArr; $i += $perNum) {
                 if($i%100==0){
                    echo '---'.$i.PHP_EOL;
                }
                $temp_urls_array = array_slice($urls_array, $i, $perNum);
                if(!empty($temp_urls_array ))
                {
                    $result = model_thread_result_get($temp_urls_array);
                    foreach ($result as $elong_id => $xml) {
                        echo $elong_id.' ';
                        save_elong_xml($elong_id, $xml);
                    }
                    unset($result);   
                }
            }
            $e = microtime(true);
            echo "多线程：" . ($e - $t) . "\n";
        }else{ 
            echo "文件不存在";
        }
    }
}
var_dump($argv);
if(isset($argv[1])&& $argv[1]=='all')
{
    download_elong_xml();
}else if(isset($argv[1])&& is_numeric($argv[1]))
{
    download_elong_xml($argv[1]);
}
