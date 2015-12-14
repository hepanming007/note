class Encoding {
    /**
     * 字符串GBK转码为UTF-8，数字转换为数字。
     *
     * @param string|array $s
     * @return mixed
     */
    public static function g2u($s) {
        if (is_array($s)) {
            foreach ($s as $k => $v) {
                if (!empty($v)) {
                    $s[$k] = self::g2u($v);
                } else {
                    $s[$k] = $v;
                }
            }
        } else {
            if (!is_numeric($s)) {
                $s = mb_convert_encoding($s, 'UTF-8', 'GBK');
            }
        }

        return $s;
    }

    /**
     * 字符串UTF-8转码为GBK，数字转换为数字
     *
     * @param string|array $s
     * @return mixed
     */
    public static function u2g($s) {
        if (is_array($s)) {
            foreach ($s as $k => $v) {
                if (!empty ($v)) {
                    $s[$k] = self::u2g($v);
                } else {
                    $s[$k] = $v;
                }
            }
        } else {
            if (!is_numeric($s)) {
                $s = mb_convert_encoding($s, 'GBK', 'UTF-8');
            }
        }

        return $s;
    }
}
