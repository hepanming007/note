/**
 * 错误类
 *
 * @author huoyan
 * @package application.libraries
 */

class Error
{
    private $error;
    private $status;

    /**
     * 构造函数
     * @param string $error 
     */
    public function __construct($error = null, $status=-1) {
        $this->error = $error;
        $this->status = $status;
    }

    public function getError() {
        return $this->error;
    }

    public function getStatus() {
        return $this->status;
    }
    
    /**
     * 判断对象是否为错误对象
     * @param mixed $obj 要验证的对象或变量
     * @return boolean 返回$obj是否为CError对象
     */
    public static function isError($obj) {
        return ($obj instanceof self);
    }
}
