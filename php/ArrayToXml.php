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
     * @param  array  $arr               要转换的数组
     * @param  string $root_element_name 根节点的名称
     * @return string                    转换后的 XML
     */
    public static function convert(array $arr, $root_element_name = 'Request') {
        $dom = new DOMDocument();

        if (self::$format_xml) {
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
        }

        $root = $dom->createElement($root_element_name);

        foreach ($arr as $key => $value) {
            $root->appendChild(self::convert_element($value, $key, $dom));
        }

        $dom->appendChild($root);

        /* $dom->documentElement 去掉 <?xml version="1.0"?> 声明 */
        return $dom->saveXML($dom->documentElement);
    }

    /**
     * 转换节点元素
     *
     * @param  string|string[]    
     * @param  string      $key
     * @param  \DOMDocument $dom   
     * @return \DOMElement
     */
    protected static function convert_element($value, $key, DOMDocument $dom) {
        $element = $dom->createElement($key);
        if (is_array($element)) {
            foreach ($element as $key => $value) {
                $element->appendChild(self::convert_element($value, $key, $dom));
            }
        } else {
            $element->nodeValue = $value;
        }

        return $element;
    }
}
