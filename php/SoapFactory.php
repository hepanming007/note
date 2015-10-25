<?php
/*
 *     用法
 *      $this->load->model('remoting/soapHelper'); //包含soapHelper文件 类似include
        $soapHelper = SoapFactory::getInstance('TicketWebservice');
        $param = array(
            'DepartCity'       => 'XMN',
            'ArrivalCity'      => 'PEK',
            'DepartDate'       => '2015-10-30',
        );
        $response = $soapHelper->call('searchTicket_New',$param);
 *
 * */

require ROOTPATH.'/models/remoting/TicketWebservice.php';
//require ROOTPATH.'/models/Basemodel.php';


/**
soap简单工厂   由于$this->load->model不能传参 所以另外封装了一层
**/
class SoapFactory{

    /** 获取soap实例
     * @param $type       wsdl类型
     * @param $account    账号
     * @return SoapHelper soap实例
     */
    public static function getIntance($type,$account='b2b')
    {
        if(in_array($type,array('ticket_wsdl','account_wsdl') ) )
        {
            $wsdl = TicketWebservice::getWsdl($type);
            return new SoapHelper($wsdl,$account);
        }
    }
}


/*
 * soap请求方法封装
 * */
class SoapHelper extends Basemodel{

    CONST RESPONSE_DATA_TYPE_XML = 1;
    CONST RESPONSE_DATA_TYPE_JSON = 2;
    /**
     * xml请求地址
     * @var string
     */
    public $url = '';


    /**
     * @var string
     */
    public $account = '';
    /**
     * soap客户端
     * @var
     */
    public $soapClient;

    public $logPath = './soap.log';

    /**
     * 注入xml请求地址
     * @param string $request_url
     */
    public function __construct($request_url='',$account='b2b')
    {
        $this->url = $request_url;
        $this->account = $account;
    }

    /**
     * @param $func
     * @param array $request_data
     * @param bool $log
     * @param int $response_data_type
     * @return array|bool|Error
     */
    public function call($func,array $request_data,$log=false,$response_data_type=self::RESPONSE_DATA_TYPE_JSON){
        $this->_soap();

        if($this->soapClient==null){
            return false;
        }
        try{
            $request_data['ResponseDataType'] = $response_data_type;
            $request_data = $this->convert_encoding($request_data,'GBK','UTF-8');//转码

//            if($func=='bookTicket'){
//                return array('Status'=>0,'OrderId'=>'88888'.time(),'Pnrno'=>'TREWER');
//            }
            $ticket_param = TicketWebservice::$func($request_data,$this->account);
            $response = $this->soapClient->__soapCall($func, array($ticket_param));;
            if($log){
                $this->write_log(var_export($this->soapClient,true).var_export($this->logInfo(),true));//写日志
            }
            $result = $this->parse_response($response->out);
            return $result;
        }catch (Exception $e){
            return $this->set_error($e->getMessage());
        }
    }

    /**
     * 解析服务端响应
     *
     * @param  string $out 服务端的响应
     * @return array
     */
    protected function parse_response($out) {
        if ($out[0] === '{') {  // JSON
            $data = json_decode($out, true);
            $data = $data['Res'];
        } else {  // XML
            $xml  = simplexml_load_string($out);
            $json = json_encode($xml);
            $data = json_decode($json, true);
        }
        return $this->convert_encoding($data,'UTF-8','GBK');
    }

    /**
     * 发送xml请求 并解析响应
     * @param $xml                        xml请求数据 可以为数据或者字符串
     * @param string $action              方法
     * @param string $dataAttribute       返回的数据一维数据
     * @param string $seconddataAttribute 返回的数据二维数据
     * @return array|bool
     */
    public function request($xml,$action='GetXmlData',$dataAttribute='GetXmlDataResult',$seconddataAttribute='')
    {
        if($this->soapClient==null){
            return false;
        }
        try{
            $data = $this->soapClient->$action($xml);
            if(!empty($dataAttribute) && empty($seconddataAttribute))
            {
                $xml_data = simplexml_load_string($data->$dataAttribute);
            }elseif(!empty($dataAttribute) && !empty($seconddataAttribute)){
                $xml_data = simplexml_load_string($data->$dataAttribute->$seconddataAttribute);
            }else{
                $xml_data = simplexml_load_string(trim($data));
            }
            $xml_data_arr = $this->xml_to_array($xml_data);
            $xml_data_arr = $this->convert_encoding($xml_data_arr);
            return $xml_data_arr;
        }catch (Exception $e){
            return $this->_fault($e);
        }
    }

    //soap发起链接
    /**
     * @return array
     */
    private function _soap() {

        try{
            $soap_url = $this->url;
            $this->soapClient = @new SoapClient($soap_url, $this->getOptions());
        }catch (Exception $e) {

            return $this->_fault($e);
        }
    }

    //c请求失败返回数据
    /**
     * @param $e
     * @return array
     */
    private function _fault($e) {
        return array('error' => $e->getMessage());
    }

    //获取soap选项；配置
    /**
     * @return array
     */
    private function getOptions() {
        $options = array(
            'trace' => true,
            'encoding' =>'utf-8',
            'connection_timeout' =>30,
            'soap_version' => SOAP_1_1,
        );
        //if(SOAP_GZIP_ON) {
        //    $options['compression']=SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP;
        //}
        return $options;
    }

    /**
     * xml转数组
     * @param $xml
     * @return array
     */
    private function xml_to_array($xml)
    {
        $array = (array)($xml);
        foreach ($array as $key=>$item){
            $array[$key]  =  $this->struct_to_array((array)$item);
        }
        return $array;
    }

    /**
     * @param $item
     * @return array|string
     */
    private function struct_to_array($item) {
        if(!is_string($item)) {
            $item = (array)$item;
            foreach ($item as $key=>$val){
                $item[$key]  =  $this->struct_to_array($val);
            }
        }
        return $item;
    }

    /**
     * @param $data
     * @param string $from
     * @param string $to
     * @return string
     */
    public function convert_encoding($data, $from = 'UTF-8', $to = 'GBK') {
        $return = '';
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $k = mb_convert_encoding($k, $to, $from);
                if (is_array($v)) {
                    $v = $this->convert_encoding($v, $from, $to);
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

    /**
     * @return array
     */
    public function logInfo()
    {
        $logInfo = array(
            'lastRequestHeaders' =>$this->soapClient->__getLastRequestHeaders(),
            'lastResponseHeaders'=>$this->soapClient->__getLastResponseHeaders(),
            'lastRequest'        =>$this->soapClient->__getLastRequest(),
            'lastResponse'       =>$this->soapClient->__getLastResponse(),
        );
        return $logInfo;
    }

    public function get_log_name() {
        if (CURRENT_ENV == 'production') {
            return 'api_gss';
        }
        return 'api_gss'.date('d');
    }

    public function write_log($message) {
        write_log($this->get_log_name(), htmlspecialchars_decode($message), 'jipiao');
    }

}
