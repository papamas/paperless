<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends MY_Controller {
	
	function __construct()
	{
	    parent::__construct();		
	    $this->load->library(array('Auth','Menu'));
	} 
	
	public function index()
	{
		//var_dump($this->session->all_userdata());
		
		$data['menu']     =  $this->menu->build_menu();
		
		$data['message']  = '';
		$data['lname']    =  $this->auth->getLastName();        
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		$this->load->view('home/index',$data);
	}
	
	
	
}
