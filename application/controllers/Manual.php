<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manual extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function index()
	{
		$this->load->view('manual_api');
	}
	
	public function taspen()
	{
		$this->load->view('manualtaspen_api');
	}
	
	public function login()
	{
	    $headers = array(
		    "Content-Type: application/json",
        );
		
		$CurlConnect = curl_init();
		curl_setopt($CurlConnect, CURLOPT_URL, 'https://satupintu.my.id/index.php/api/login');
		curl_setopt($CurlConnect, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($CurlConnect, CURLOPT_POST, 1);
		curl_setopt($CurlConnect, CURLOPT_POSTFIELDS,  json_encode(array("username" => "198105122015031001" ,
                                                           		  "password" => "120581" )));
		curl_setopt($CurlConnect, CURLOPT_RETURNTRANSFER, 1 );
		$Result = curl_exec($CurlConnect);
		if(!$Result){die("Connection Failure");}
		curl_close($CurlConnect);
		var_dump($Result); exit;
		
	}	
	
	public function dokumen()
	{
	    $headers = array(
		    'Token:eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImFjdGl2ZSI6IjEiLCJ1c2VybmFtZSI6IjE5ODEwNTEyMjAxNTAzMTAwMSIsImluc3RhbnNpIjoiNDAxMSIsImlhdCI6MTYyNTU1NTE2NCwiZXhwIjoxNjI1NTczMTY0fQ.4dt5p4LBP8bYq4wo-P6VH7-gZjbBewNtOIM7E8B-izU'
        );
		
		$CurlConnect = curl_init();
		curl_setopt($CurlConnect, CURLOPT_URL, 'https://satupintu.my.id/index.php/api/dokumen?name=IJAZAH_30_198105122015031001.pdf');
		curl_setopt($CurlConnect, CURLOPT_HTTPHEADER,$headers);
		curl_setopt($CurlConnect, CURLOPT_RETURNTRANSFER, 1 );
		$Result = curl_exec($CurlConnect);
		if(!$Result){die("Connection Failure");}
		curl_close($CurlConnect);
		
		$json   = json_decode($Result,true);
		
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type: '.$json['file_mime']);
		header('Content-Disposition: inline; filename='.$json['file_name']);
		header('Expires:0'); 
		echo base64_decode($json['file_content']);	
	}	
}
