<?php
/**
 * 字符串助手类
 * Class StringHelper
 */
class StringHelper
{

    /**
     * @param $string      输入字符串
     * @param $length      截取长度
     * @param string $dot  不充的字符
     * @param string $charset 字符编码
     * @return string       返回字符串
     */
    public static function cutstr($string, $length, $dot = ' ...', $charset = 'utf-8')
    {
        if (strlen($string) <= $length) {
            return $string;
        }
        $pre = chr(1);
        $end = chr(1);
        $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), $string);
        $strcut = '';
        if ($charset == 'utf-8') {
            $n = $tn = $noc = 0;
            while ($n < strlen($string)) {
                $t = ord($string[$n]);
                if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1;
                    $n++;
                    $noc++;
                } elseif (194 <= $t && $t <= 223) {
                    $tn = 2;
                    $n += 2;
                    $noc += 2;
                } elseif (224 <= $t && $t <= 239) {
                    $tn = 3;
                    $n += 3;
                    $noc += 2;
                } elseif (240 <= $t && $t <= 247) {
                    $tn = 4;
                    $n += 4;
                    $noc += 2;
                } elseif (248 <= $t && $t <= 251) {
                    $tn = 5;
                    $n += 5;
                    $noc += 2;
                } elseif ($t == 252 || $t == 253) {
                    $tn = 6;
                    $n += 6;
                    $noc += 2;
                } else {
                    $n++;
                }
                if ($noc >= $length) {
                    break;
                }
            }
            if ($noc > $length) {
                $n -= $tn;
            }
            $strcut = substr($string, 0, $n);
        } else {
            for ($i = 0; $i < $length; $i++) {
                $strcut .= ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
            }
        }
        $strcut = str_replace(array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
        $pos = strrpos($strcut, chr(1));
        if ($pos !== false) {
            $strcut = substr($strcut, 0, $pos);
        }
        return $strcut . $dot;
    }


    /**
     * utf-8字符截取
     * @param $string  输入字符串
     * @param $width   长度
     * @param string $point 补充的字符
     * @return string  输出字符串
     */
    public static function utf8_str($string, $width, $point = '')
    {
        $string = trim(replace($string));
        $start = 0;
        $encoding = 'UTF-8';
        if ($point == '') {
            $trimmarker = '...';
        } else {
            $trimmarker = '';
        }
        if ($width == '') {
            $width = mb_strwidth($string, "UTF-8");
        }
        return mb_strimwidth($string, $start, $width, $trimmarker, $encoding);
    }
}

/*
$a = 'test123456789';
echo StringHelper::cutstr($a, 8);
*/
