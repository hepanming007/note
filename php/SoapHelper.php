<?php
class SoapHelper{

    public $url = 'http://cnapi.cnbooking.net:8030/RESTServer.asmx?wsdl';
    public $soapClient;
    
    public function request($xml)
    {
        $this->_soap();
        if($this->soapClient==null){
            return false;
        }
        try{
           $data = $this->soapClient->GetXmlData($xml);
           $xml_data = simplexml_load_string($data->GetXmlDataResult);

           return $this->xml_to_array($xml_data);
        }catch (Exception $e){
            return $this->_fault($e);
        }
    }

    //soap发起链接
    private function _soap() {
        try{
            $soap_url = $this->url;
            $this->soapClient = @new SoapClient($soap_url, $this->getOptions());
        }catch (Exception $e) {
            return $this->_fault($e);
        }
    }

    //c请求失败返回数据
    private function _fault($e) {
        return array('error' => $e->getMessage());
    }

    //获取soap选项；配置
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

    private function xml_to_array($xml)
    {
        $array = (array)($xml);
        foreach ($array as $key=>$item){
            $array[$key]  =  $this->struct_to_array((array)$item);
        }
        return $array;
    }

    private function struct_to_array($item) {
        if(!is_string($item)) {
            $item = (array)$item;
            foreach ($item as $key=>$val){
                $item[$key]  =  $this->struct_to_array($val);
            }
        }
        return $item;
    }
}


$xmlRequest = <<<EOT
<?xml version="1.0" encoding="utf-8"?>
 <CNRequest>
 <ActionName>SearchDistrictInfo</ActionName>
 <IdentityInfo>
     <SecurityKey>ffed0a5f-c9f7-4cb2-b1cc-2ce94a27433f</SecurityKey>
     <UserName>xinlvtong</UserName>
     <PassWord>7Z0NAEf0cBncKuKn5V5RMeiGWyA=</PassWord>
     <Signature>fmy4+phZP5dMjwsM1MfGbQO35jtyvZ2K/MNGb0DLP5hlsZcdn1HW/vW0117KQCckYui6320MiUppfv2rkarqakPVGqe7aRWCiLSGhZwuXwfA26dnhrcNFgBIrbxqozFR/vGgW3yJLHbE5/NvhA0bxcjaQDqDE5udjMZkKuEvZUA=</Signature>
     <AppID>1</AppID>
 </IdentityInfo>
 <ScrollingInfo>
    <DisplayReq>30</DisplayReq>
    <PageItems>10</PageItems>
    <PageNo>1</PageNo>
    <OrderField>ID</OrderField>
    <OrderType>0</OrderType>
 </ScrollingInfo>
 <SearchConditions>
    <CountryCode>0001</CountryCode>
 </SearchConditions>
 </CNRequest>
EOT;

$xmlParam = array('xmlRequest'=>$xmlRequest);

$soapHelper = new SoapHelper();
echo "<pre>";
print_r($soapHelper->request($xmlParam));


