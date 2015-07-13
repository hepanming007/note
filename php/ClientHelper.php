<?php
 
class ClientHelper {
    /**
     * @return string
     */
    public static function getIp() {
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            return getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            return getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            return getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            return $_SERVER['REMOTE_ADDR'];
        }
        return 'unknown';
    }
    /**
     * @return string
     */
    public static function getBrowser() {
        if ( ! isset($_SERVER['HTTP_USER_AGENT']) ) 
            return 'unknown';
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/MSIE/i',$u_agent))
            return "Internet Explorer";
        elseif(preg_match('/Firefox/i',$u_agent))
            return "Mozilla Firefox";
        elseif(preg_match('/Chrome/i',$u_agent))
            return "Google Chrome";
        elseif(preg_match('/Safari/i',$u_agent))
            return "Apple Safari";
        elseif(preg_match('/Flock/i',$u_agent))
            return "Flock";
        elseif(preg_match('/Opera/i',$u_agent))
            return "Opera";
        elseif(preg_match('/Netscape/i',$u_agent))
            return "Netscape";
        return 'unknown';
    }
}
/*
echo ClientHelper::getIp();
echo ClientHelper::getBrowser();
*/
