<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Telegram Class
 *
 * Telegram library for Code Igniter.
 * @author		Nur Muhamad Holik -2020
 * @version		1.0.0
 */
class Maleodua {
	
	// MaleoDuabot
	var $token  = '1844229064:AAFYBlUJ4GCQVXfvv2xbdhP-PoGOgezqFY8';
	var $method;
	var $url     = 'https://api.telegram.org/bot';
	var $offset  = 0;
	var $pesan;
	var $chat_id;
	
	public function __construct()
    {
        log_message('debug', "Telegram Class Initialized");	    
    }
	
	public function setMethod($method)
	{
	    $this->method  =  $method;
	}
	
	public function getMethod()
	{
	    return $this->method;	
	}	
	
	public function setToken($token)
	{
		$this->token   = $token;
	}	
	
	public function getToken()
	{
		return $this->token;
	}	
	
	public function setUrl($url)
	{
	    $this->url  = $url;	
	}	
	
	public function getUrl()
	{
	    return $this->url;	
	}		
	
	public function setOffset($offset)
	{
	    $this->offset = $offset;	
	}	
	
	public function getOffset()
	{
		return $this->offset;
	}	
	
	public function setPesan($pesan)
	{
		$this->pesan  = $pesan;		
	}	
	
	public function getPesan()
	{
		return $this->pesan;	
	}	
	
	public function setChatid($chat_id)
	{
		$this->chat_id = $chat_id;		
	}

	public function getChatid()
	{
		return $this->chat_id;
	}	
	
	public function apiRequest($data)
	{
		if (!is_string($this->method)) {
			error_log("Nama method harus bertipe string!\n");
			return false;
		}		

		if (!$data) {
			$data = [];
		} elseif (!is_array($data)) {
			error_log("Data harus bertipe array\n");
			return false;
		}
	
	    //var_dump(http_build_query($data));
		
    	$url = $this->getUrl().$this->getToken().'/'.$this->getMethod();
		$options = [
			'http' => [
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data),
			],
		];
		
		$context = stream_context_create($options);
		$result = @file_get_contents($url, false, $context);
		return $result;
	}
	
	public function getApiUpdate()
	{
		$this->setMethod('getUpdates');
		
		$data['offset'] 	= $this->getOffset();

		$result = $this->apiRequest($data);

		$result = json_decode($result, true);
		if ($result['ok'] == 1) {
			return $result['result'];
		}

		return [];
	}
		
	public function sendApiAction($chatid, $action = 'typing')
	{
		$this->setMethod('sendChatAction');
		
		$data = [
			'chat_id' => $chatid,
			'action'  => $action,
		];
		
		$result = $this->apiRequest($data);
	}

	public function sendApiMsg($chatid, $text, $msg_reply_id = false, $parse_mode = false, $disablepreview = false)
	{
		$this->setMethod('sendMessage');
		$this->setChatid($chatid);
		$this->setPesan($text);
				
		$data = ['chat_id' => $chatid, 'text'  => $text];

		if ($msg_reply_id) {
			$data['reply_to_message_id'] = $msg_reply_id;
		}
		if ($parse_mode) {
			$data['parse_mode'] = $parse_mode;
		}
		if ($disablepreview) {
			$data['disable_web_page_preview'] = $disablepreview;
		}

		$result = $this->apiRequest($data);
	}

    public function prosesCallBackQuery($message)
	{
		$message_id 		= $message['message']['message_id'];
		$chatid 			= $message['message']['chat']['id'];
		$data 				= $message['data'];

		$inkeyboard = [
					[
						['text' => 'Tentang Male_o 1.9', 'callback_data' => 'Tentang'],
						['text' => 'Layanan Kepegawaian', 'callback_data' => 'Layanan'],
					],
					[
						['text' => 'Daftar Notifikasi Male_o 1.9', 'callback_data' => 'Daftar'],
					],
					[
						['text' => 'keyboard off', 'callback_data' => '/hide'],
					],
				];

		//$text = '*'.date('H:i:s').'* data baru : '.$data;

		//$this->editMessageText($chatid, $message_id, $data, $inkeyboard, true);

		$messageupdate 			= $message['message'];
		$messageupdate['text']  = $data;

		return $messageupdate;
	}


	

	public function sendApiKeyboard($chatid, $text, $keyboard = [], $inline = false)
	{
		$this->setMethod('sendMessage');
		
		$replyMarkup = [
			'keyboard'        => $keyboard,
			'resize_keyboard' => true,
		];

		$data = [
			'chat_id'    => $chatid,
			'text'       => $text,
			'parse_mode' => 'HTML',

		];

		if($inline)
        {
			$data['reply_markup'] = json_encode(['inline_keyboard' => $keyboard]);
		}
		else
		{	
			$data['reply_markup'] = json_encode($replyMarkup);
		}
		$result = $this->apiRequest($data);
	}


	public function editMessageText($chatid, $message_id, $text, $keyboard = [], $inline = false)
	{
		$this->setMethod('editMessageText');
		
		$replyMarkup = [
			'keyboard'        => $keyboard,
			'resize_keyboard' => true,
		];

		$data = [
			'chat_id'    => $chatid,
			'message_id' => $message_id,
			'text'       => $text,
			'parse_mode' => 'HTML',

		];

		if($inline)
        {
			$data['reply_markup'] = json_encode(['inline_keyboard' => $keyboard]);
		}
		else
		{	
			$data['reply_markup'] = json_encode($replyMarkup);
		}
		
		$result = $this->apiRequest($data);
	}

	public function sendApiHideKeyboard($chatid, $text)
	{
		$this->setMethod('sendMessage');
		
		$data = [
			'chat_id'       => $chatid,
			'text'          => $text,
			'parse_mode'    => 'Markdown',
			'reply_markup'  => json_encode(['hide_keyboard' => true]),

		];

		$result = $this->apiRequest($data);
	}

	public function sendApiSticker($chatid, $sticker, $msg_reply_id = false)
	{
		$this->setMethod('sendSticker');
		
		$data = [
			'chat_id'  => $chatid,
			'sticker'  => $sticker,
		];

		if ($msg_reply_id) {
			$data['reply_to_message_id'] = $msg_reply_id;
		}

		$result = $this->apiRequest($data);
	}
	
	
		

	
	
}

/* End of file Someclass.php */
