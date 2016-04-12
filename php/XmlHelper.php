<?php

/**
 * Class XmlHelper  xml转数组工具
 */
class XmlHelper
{

    /**
     * 数组
     * @var array
     */
    public static $data = array();
    /**
     * xml数据
     * @var string
     */
    public static $xml_data = '';

    /**
     *  设置xml数据源
     * @param $url
     */
    public static function setXmlDataFromUrl($url)
    {
        $content = file_get_contents($url);
        self::$xml_data = simplexml_load_string(trim($content));
        self::$data = self::xml_to_array(self::$xml_data);
    }

    /**
     * xml转数组
     * @param $xml
     * @return array
     */
    public static function xml_to_array($xml)
    {
        $array = (array)($xml);
        foreach ($array as $key => $item) {
            $array[$key] = self::struct_to_array((array)$item);
        }
        return $array;
    }

    /**
     *
     * @param $item
     * @return array|string
     */
    public static function struct_to_array($item)
    {
        if (!is_string($item)) {
            $item = (array)$item;
            foreach ($item as $key => $val) {
                $item[$key] = self::struct_to_array($val);
            }
        }
        return $item;
    }

    /**
     *  转码
     * @param $data
     * @param string $from
     * @param string $to
     * @return string
     */
    public static function convert_encoding($data, $from = 'GBK', $to = 'UTF-8')
    {
        $return = '';
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $k = mb_convert_encoding($k, $to, $from);
                if (is_array($v)) {
                    $v = self::convert_encoding($v, $from, $to);
                } else {
                    $v = mb_convert_encoding($v, $to, $from);
                }
                $return[$k] = $v;
            }
        } else {
            $return = mb_convert_encoding($data, $to, $from);
        }
        return $return;
    }
}


/**
 * 
 *  
 *  
 *  $xml  = simplexml_load_string($out);
    $json = json_encode($xml);
    $data = json_decode($json, true);
            
 * 
*/
XmlHelper::setXmlDataFromUrl('./20101103.xml');
print_r(XmlHelper::$data);
