<?php
/**
 * Class SoapHelper
 */
class SoapHelper{

    /**
     * xml请求地址
     * @var string
     */
    public $url = 'http://cnapi.cnbooking.net:8030/RESTServer.asmx?wsdl';
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
    public function __construct($request_url='http://cnapi.cnbooking.net:8030/RESTServer.asmx?wsdl')
    {
        $this->url = $request_url;
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
        $this->_soap();
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
           return $this->xml_to_array($xml_data);
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
}


class DingfangyiParam{
    
    public $xmlParam = '';
    
    public function __construct()
    {
        $this->setXmlHeader()->setIdentityInfo()->setScrollingInfo()->setXmlFooter();
        
    }
    public function setXmlHeader()
    {
        $this->xmlParam['header'] = '<?xml version="1.0" encoding="utf-8"?><CNRequest>';
        return $this;
    }
    
    public function setXmlFooter()
    {
        $this->xmlParam['footer'] = '</CNRequest>';
        return $this;
    }
  
    //2.1 单酒店精简信息查询接口
    public function SingleSearchHotelsInfo($HotelCode)
    {
          $this->xmlParam['action'] = "
            <ActionName>SingleSearchHotelsInfo</ActionName>
            <SearchConditions >
                <HotelCode>{$HotelCode}</HotelCode>
            </SearchConditions>";
            return $this;
    }
    //2.2.单酒店信息查询接口
    public function SearchHotelsInfo($HotelCode)
    {
         $this->xmlParam['action'] = "
            <ActionName>SearchHotelsInfo</ActionName>
            <SearchConditions >
                <HotelCode>{$HotelCode}</HotelCode>
            </SearchConditions>";
            return $this;
    }
    //2.3 单酒店简单房型信息查询接口
    public function SingleHotelRoomTypeSearch($HotelCode,$RoomTypeCode)
    {
         $this->xmlParam['action'] = "
            <ActionName>SingleHotelRoomTypeSearch</ActionName>
            <SearchConditions>
                <HotelCode>{$HotelCode}</HotelCode>
                <RoomTypeCode>{$RoomTypeCode}</RoomTypeCode>
            </SearchConditions>";
         
            return $this;
    }
    //2.4 多酒店简单信息查询接口
    public function SimpleHotelSearch($HotelCode='',$HotelName='',$Country='',$Province='',$City='',$District='',$RoomTypeCode='',$StarLevel='',$LandmarkID='')
    {
         $this->xmlParam['action'] = "
            <ActionName>SimpleHotelSearch</ActionName>
            <SearchConditions>
                 <HotelCode>{$HotelCode}</HotelCode>
                 <HotelName>{$HotelName}</HotelName>
                 <Country>{$Country}</Country>
                 <Province>{$Province}</Province>
                 <City>{$City}</City>
                 <District>{$District}</District>
                 <RoomTypeCode>{$RoomTypeCode}</RoomTypeCode>
                 <StarLevel>{$StarLevel}</StarLevel >
                 <LandmarkID>{$LandmarkID}</LandmarkID>
            </SearchConditions>";
            return $this;
    }
    //2.5 多酒店信息查询接口
    
    public function HotelSearch($HotelCode='',$HotelName='',$Country='',$Province='',$City='',$District='',
                                $RoomTypeCode='',$StarLevel='')
    {
        $this->xmlParam['action'] ="
        <ActionName>HotelSearch</ActionName>
        <SearchConditions>
            <HotelCode>{$HotelCode}</HotelCode>
            <HotelName>{$HotelName}</HotelName>
            <Country>{$Country}</Country>
            <Province>{$Province}</Province>
            <City>{$City}</City>
            <District>{$District}</District>
            <RoomTypeCode>{$RoomTypeCode}</RoomTypeCode>
            <StarLevel >{$StarLevel}</StarLevel >
        </SearchConditions>
        "; 
        return $this;
    }
    //2.6 单酒店房型价格信息查询接口
    
    public function SimpleRoomPriceInfo($CheckInDate,$CheckOutDate,$PayMode,$HotelCode,$RoomTypeCode='',
                                        $RateMin,$RateMax,$Currency='CNY')
    {
        $this->xmlParam['action'] = "
         <ActionName>SimpleRoomPriceInfo</ActionName>
         <SearchConditions>
         <CheckInDate>{$CheckInDate}</CheckInDate>
         <CheckOutDate>{$CheckOutDate}</CheckOutDate>
         <PayMode>{$PayMode}</PayMode>
         <HotelCode>{$HotelCode}</HotelCode>
         <RoomTypeCode>{$RoomTypeCode}</RoomTypeCode>
         <RateMin>{$RateMin}</RateMin>
         <RateMax>{$RateMax}</RateMax>
         <Currency>{$Currency}</Currency>
         <Obligatestr1 ></Obligatestr1 >
         <Obligatestr2></Obligatestr2>
         </SearchConditions>
        ";
        return $this;
    }
    
    
   // 4.1 获取酒店服务设施基础数据
   public function GetServerFacilities()
   {
         $this->xmlParam['action'] = "
         <ActionName>GetServerFacilities</ActionName>";
         return $this;
   }
   //4.2 国家/地区查询
    public function DistrictSearch()
   {
         $this->xmlParam['action'] = "
         <ActionName>DistrictSearch</ActionName>";
         return $this;
   }
   
    //4.2 国家/地区查询
   public function GetBedType()
   {
         $this->xmlParam['action'] = "
         <ActionName>GetBedType</ActionName>";
         return $this;
   }
    
   //4.4 城市代码
    public  function SearchDistrictInfo($CountryCode='0001')
    {
        $this->xmlParam['action'] = "
        <ActionName>SearchDistrictInfo</ActionName>
        <SearchConditions>
            <CountryCode>{$CountryCode}</CountryCode>
        </SearchConditions>";
        return $this;
    }
    
    
    public function setIdentityInfo()
    {
        $this->xmlParam['identify'] = '
         <IdentityInfo>
             <SecurityKey></SecurityKey>
             <UserName></UserName>
             <PassWord>=</PassWord>
             <Signature></Signature>
             <AppID></AppID>
        </IdentityInfo>
        ';
        return $this;
    }
    
    public function setScrollingInfo($DisplayReq=50,$PageItems=10,$PageNo=1,$OrderField='ID',$OrderType=0)
    {
        $this->xmlParam['scrolling'] = "
        <ScrollingInfo>
            <DisplayReq>{$DisplayReq}</DisplayReq>
            <PageItems>{$PageItems}</PageItems>
            <PageNo>{$PageNo}</PageNo>
            <OrderField>{$OrderField}</OrderField>
            <OrderType>{$OrderType}</OrderType>
         </ScrollingInfo>";
         return $this;
    }
    
    public function getXmlParam()
    {
        return $this->xmlParam['header'].
                $this->xmlParam['identify'].
                $this->xmlParam['action'].
                $this->xmlParam['scrolling'].
                $this->xmlParam['footer'];
    }
    
}

class param8000yi{
     CONST URL = 'http://websvr.8000yi.com:8080/newPly/WebInterface/OrderService.asmx?wsdl';
     CONST NAME ='';
     CONST PWD  ='';
     public static function OrderGuidPayOutTicketAndPly($orderguid)
     {
         return array(
             'name'=>self::NAME,
             'pwd'=>self::PWD,
             'orderguid'=>$orderguid,
         );
     }
}

class Tripota{
    CONST URL ='http://www.tripota.com/gl/index.php?r=webservice/quote';
    public static function searchMessage($Operator=,$MessageId=,$Name='',$Identification='')
    {
        $request = <<<CONTENT
<?xml version="1.0" encoding="UTF-8"?>
<request>
    <Operator>{$Operator}</Operator>
    <MessageId>{$MessageId}</MessageId>
    <Name>{$Name}</Name>
    <Identification>{$Identification}</Identification>
</request>
CONTENT;
        return $request;
    }
}

/*

$soapHelper = new SoapHelper(param8000yi::URL);
$response = $soapHelper->request(param8000yi::OrderGuidPayOutTicketAndPly('I635767190469702022'),'OrderGuidPayOutTicketAndPly','OrderGuidPayOutTicketAndPlyResult','any');
//$soapHelper = new SoapHelper(Tripota::URL);
//$response   = $soapHelper->request(Tripota::searchMessage(),'searchMessage','');
echo "<pre>";
print_r($response);
print_r($soapHelper->logInfo());

$DingfangyiParam = new DingfangyiParam();
$SearchDistrictInfo = $DingfangyiParam->SearchDistrictInfo()->getXmlParam();
$xmlParam = array('xmlRequest'=>trim($SearchDistrictInfo));

$response = $soapHelper->request($xmlParam);
//echo "<pre>";
//print_r($response);
$distinctList = isset($response['MessageInfo']['Code'])&&$response['MessageInfo']['Code']=='30000'?
                $response['Data']['DistrictInfo']['DistrictList']:'';

$provice_city_ids = array();
foreach($distinctList as $distinct)
{
    $provice_id = $distinct['ProvinceId'];
    if(isset($distinct['Citys']['City']['CityCode']))
    {
        $city_code = $distinct['Citys']['City']['CityCode'];
        echo $provice_id."\t".$city_code.PHP_EOL;
        $provice_city_ids[] = array(
            'provice_id'=>$provice_id,
            'city_code' =>$city_code,
        );
    }else{
        foreach($distinct['Citys']['City'] as $city)
        {
            $city_code = $city['CityCode'];
            $provice_city_ids[] = array(
                'provice_id'=>$provice_id,
                'city_code' =>$city_code,
            );
            echo $provice_id."\t".$city_code.PHP_EOL;
        }
    }
}

foreach($provice_city_ids as $data)
{
    $HotelSearch = $DingfangyiParam->setScrollingInfo(50,1000,1)->HotelSearch('','','0001',$data['provice_id'],$data['city_code'])->getXmlParam();
    $xmlParam = array('xmlRequest'=>trim($HotelSearch));
    $response  = $soapHelper->request($xmlParam);

    if(isset($response['MessageInfo']['Code'])&&$response['MessageInfo']['Code']=='30000')
    {
//        $hotelNum = $response['Data']['HotelsInfo']['HotelNumber'];
//        $hotelList = $response['Data']['HotelsInfo']['HotelList'];
        echo "<pre>";
        print_r($soapHelper->request($xmlParam));

    }

}
echo "<pre>";
print_R($provice_city_ids);
//print_r($soapHelper->request($xmlParam));
*/
