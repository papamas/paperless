<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Asn extends MY_Controller {
	
	var $menu_id    = 7;
	var $allow 		= FALSE;
	
	function __construct()
	{
	    parent::__construct();		
	    $this->load->library(array('Auth','Menu','form_validation','Myencrypt'));
		$this->load->model('asn/asn_model', 'asn');
		$this->allow = $this->auth->isAuthMenu($this->menu_id);
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
		
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		
		$this->load->view('asn/index',$data);
		
	}
	
	public function find()
	{
		$search            = $this->input->post('nip');
		
		$data['menu']     =  $this->menu->build_menu();
		
		$data['message']  = '';
		$data['lname']    =  $this->auth->getLastName();        
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		
		// asn
		$data['pupns']      = $this->asn->_get_pupns($search);
		$data['pendidikan'] = $this->asn->_get_pendidikan($search);	
        $data['unor']		= $this->asn->_get_unorpns($search);	
		$data['kp']		    = $this->asn->_getkp_info($search);	
		$data['pengadaan']  = $this->asn->_get_pengadaan_info($search);
		
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		
		$this->load->view('asn/index',$data);
	}
	
	public function search()
	{
			
		$data['menu']     =  $this->menu->build_menu();
		
		$data['message']  = '';
		$data['lname']    =  $this->auth->getLastName();        
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		$data['show']   = FALSE;
		
		if(!$this->allow)
		{
			$this->load->view('403/search',$data);
			return;
		}
		
		$this->load->view('asn/search',$data);
		
	}
	
	public function dosearch()
	{
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		
		$data['menu']     =  $this->menu->build_menu();
    	$data['message']  = '';
		$data['lname']    =  $this->auth->getLastName();        
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		
		
		
		if(!$this->allow)
		{
			$this->load->view('403/search',$data);
			return;
		}
		
		if($this->form_validation->run() == FALSE)
		{
			$data['show']   = FALSE;
			$this->load->view('asn/search',$data);
		}
		else
		{
			$data['show']   = TRUE;
			$data['pns']    =  $this->asn->getSearch();
			$this->load->view('asn/search',$data);
		}
	}
}
