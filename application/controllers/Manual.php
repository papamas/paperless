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
		curl_setopt($CurlConnect, CURLOPT_POSTFIELDS,  json_encode(array("username" => "19810512201503XXXX" ,
                                                           		  "password" => "IniAdalahRahasiaAku" )));
		curl_setopt($CurlConnect, CURLOPT_RETURNTRANSFER, 1 );
		$Result = curl_exec($CurlConnect);
		if(!$Result){die("Connection Failure");}
		curl_close($CurlConnect);
		var_dump($Result); exit;
		
	}	
	
	
	public function dokumen()
	{
	    $headers = array(
		    "Content-Type: application/json",
		    "Token :eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTgzIiwiYWN0aXZlIjoiMSIsInVzZXJuYW1lIjoiMzM2MSIsImluc3RhbnNpIjoiOSIsImlhdCI6MTYyNjE4NDUwOCwiZXhwIjoxNjI2MjAyNTA4fQ.F3MZE9FbavS-G_Sj_FoqROE06zCPjOdXObIeaH9hDyM"
        );
		
		$CurlConnect = curl_init();
		curl_setopt($CurlConnect, CURLOPT_URL, 'http://127.0.0.1/paperless/index.php/api/validasiSKDokumen?instansi=7000&name=PERTEK_PENSIUN_196307091986031022.pdf');
		curl_setopt($CurlConnect, CURLOPT_HTTPHEADER, $headers);
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
