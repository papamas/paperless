<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Syarat extends MY_Controller {
	
	function __construct()
	{
	    parent::__construct();		
	    $this->load->library(array('Auth','Menu'));
	} 
	
	public function index()
	{
			
		$data['menu']     =  $this->menu->build_menu();
		
		$data['message']  = '';
		$data['lname']    =  $this->auth->getLastName();        
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		$this->load->view('syarat/index',$data);
	}
	
	
	
}
