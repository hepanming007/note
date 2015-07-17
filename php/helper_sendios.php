<?php 
//下边的写法就是死写法了，一般不需要修改，唯一要修改的就是：
//ssl://gateway.sandbox.push.apple.com:2195这个是沙盒测试地址，
//ssl://gateway.push.apple.com:2195正式发布地址
class helper_sendios{
  const SSL_URL = 'ssl://gateway.sandbox.push.apple.com:2195';
	const CK_PASS = '111111';//ck.pem通关密码
	const CK_PEM = './ck.pem';
	const LOG_FILE = './sendlog/iosconnect.log';
	public static function send($info){
		//手机注册应用返回唯一的deviceToken
		$devicetoken = $info['devicetoken'];
		$pass = self::CK_PASS; 
		$message = $info['message'];//消息内容
		$badge = 4;
		$sound = 'Duck.wav';
		$body = array();
		// $body['id'] = "4f94d38e7d9704f15c000055";
		$body['aps'] = array('alert' => $message);
		if ($badge)
		  $body['aps']['badge'] = $badge;
		if ($sound)
		  $body['aps']['sound'] = $sound;
		//把数组数据转换为json数据
		$payload = json_encode($body);
		// echo strlen($payload),"\r\n";
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', self::CK_PEM);  
		stream_context_set_option($ctx, 'ssl', 'passphrase', $pass);
		$fp = stream_socket_client(self::SSL_URL, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
		if (!$fp) {
			date('Y-m-d H:i:s').$logmsg = "Failed to connect $err $errstr".PHP_EOL;
			error_log($logmsg,3,self::LOG_FILE);
			return;
		}else {
			$logmsg = date('Y-m-d H:i:s')."Connection OK".PHP_EOL;
			error_log($logmsg,3,self::LOG_FILE);
		}
		// send message
		$msg = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $devicetoken)) . pack("n",strlen($payload)) . $payload;
		
		$logmsg = date('Y-m-d H:i:s')."Sending message :{$payload};".PHP_EOL;
		error_log($logmsg,3,self::LOG_FILE);
		fwrite($fp, $msg);
		fclose($fp);
	}
	
}
	$info = array(
		'devicetoken'=>'0d80ac4356874dedf778302f749c65e299adeb35f6b4125d05594ce4726a0b56',
		'message'=>date('Y-m-d H:i:s')."xxxx赞了你的看法",
	);
	helper_sendios::send($info);
  
?>
