<?php 
class UrlHelper{
    
    public static function returnUrl($param,$selectParam)
    {  
       return  http_build_query(array_filter(array_diff($param,$selectParam)));
    }
    
}
$arr = ['a'=>1,'b'=>2,'c'=>3,'d'=>4];
echo UrlHelper::returnUrl($arr,array('a'=>3));
