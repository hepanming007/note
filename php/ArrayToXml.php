<?php
/**
 * @copyright Copyright (c) 2015 Xiamen Xinxin Information Technologies, Inc.
 */
namespace Cncn;

use DOMDocument;

/**
 * 转换数组为XML格式
 *     取自 https://github.com/spatie/array-to-xml，稍微进行了些调整
 * 
 * @author 黄景祥(Joel Huang) <joelhy@gmail.com> 
 * @since 2015-09-25 14:07:15
 */
class ArrayToXml {
    /**
     * 是否格式化 XML
     *
     * @var boolean
     */
    public static $format_xml = true;

     /**
     * 转换数组为 XML
     *
     * 使用示例：
     * 1. _不_带相同key的传参方式
     * ```php
     * $data = array(
     *      'DepartCity'    => 'WUH',
     *      'ArrivalCity'   => 'PEK',
     *      'DepartDate'    => '2015-12-24',
     * );
     * Cncn\ArrayToXml::convert($data)
     * ```
     * 
     * 生成 xml
     * ```xml
     * <Request>
     *   <DepartCity>WUH</DepartCity>
     *   <ArrivalCity>PEK</ArrivalCity>
     *   <DepartDate>2015-12-24</DepartDate>
     * </Request>
     * ```
     * 
     * 2. 带相同key的传参方式
     * ```php
     * $data = array(
     *     'passenger' => array(
     *         0 => array('name' => 'John Doe', 'gender' => 'male'),
     *         1 => array('name' => 'Jane Doe', 'gender' => 'female'),
     *     ),
     * );
     * Cncn\ArrayToXml::convert($data, 'passengers');
     * ```
     * 
     * 生成 xml
     * ```xml
     * <passengers>
     *   <passenger>
     *     <name>John Doe</name>
     *     <gender>male</gender>
     *   </passenger>
     *   <passenger>
     *     <name>Jane Doe</name>
     *     <gender>female</gender>
     *   </passenger>
     * </passengers>
     * ```
     *
     * @param  array  $arr       要转换的数组
     * @param  string $root_name 根节点的名称
     * @return string            转换后的 XML
     */
    public static function convert(array $arr, $root_name = 'Request') {
        $dom = new DOMDocument();

        if (self::$format_xml) {
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput       = true;
        }

        $root = $dom->createElement($root_name);
        foreach ($arr as $key => $value) {
            self::convert_node($value, $key, $root);
        }
        $dom->appendChild($root);

        /* $dom->documentElement 去掉 <?xml version="1.0"?> 声明 */
        return $dom->saveXML($dom->documentElement);
    }

    /**
     * 转换节点元素
     *
     * @param  string|string[] $node_value 节点元素的值
     * @param  string          $node_name  节点元素的名称
     * @param  \DOMNode        $parent     父节点
     */
    protected static function convert_node($node_value, $node_name, $parent) {
        if (is_array($node_value)) {
            foreach ($node_value as $key => $value) {
                $child = $parent->appendChild(new DOMElement($node_name));
                if (is_array($value)) {
                    if (!is_numeric($key)) {
                        self::convert_node($value, $key, $child);
                    } else {
                        foreach ($value as $k => $v) {
                            self::convert_node($v, $k, $child);
                        }
                    }
                } else {
                    $child->appendChild(new DOMElement($key, $value));
                }
            }
        } else {
            $parent->appendChild(new DOMElement($node_name, $node_value));
        }
    }
}
