<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Profile extends MY_Controller {
	
	function __construct()
	{
	    parent::__construct();		
	  
			
		$this->load->helper(array('form', 'url'));
		$this->load->model('profile_model','profile');
	    $this->load->library(array('Auth','Menu','form_validation'));	
	} 
	
	public function home()
	{
		redirect('home');
	}	
	
	public function index()
	{
		
		
		$data['msg1']     	 = '';
		$data['msg2']     	 = '';
		$data['msg3']     	 = '';
		$data['name']        =  $this->auth->getName();
        $data['jabatan']     =  $this->auth->getJabatan();
		$data['member']	     =  $this->auth->getCreated();
		$data['avatar']	     =  $this->auth->getAvatar();
		$data['profile']     =  $this->_getProfile();
		$data['unit_kerja']  =  $this->_getUnitKerja();
		$data['tab_setting']     	 = 'active';
		$data['tab_change_password'] = '';
		$data['tab_activity']        = '';
		$data['tab_spesimen']        = '';
		$data['menu']         = $this->menu->build_menu();
		$data['instansi']     = $this->profile->getInstansi();
		$data['spesimen']     = $this->profile->getSpesimen();
		
		$this->load->view('profile/vprofile',$data);
	}
	
	function _getProfile()
	{
	    $row    =  $this->profile->getProfile()->row();
		return $row;
	}
	
	function _getUnitKerja()
    {
        return $this->profile->getUnitKerja();
    }

    public function setting()
    {    
		
		$this->form_validation->set_rules('first_name', 'first_name', 'required');
		$this->form_validation->set_rules('last_name', 'last_name', 'required');
		$this->form_validation->set_rules('email', 'email', 'required');
		$this->form_validation->set_rules('gender', 'gender', 'required');
		$this->form_validation->set_rules('jabatan', 'jabatan', 'required');
		$this->form_validation->set_rules('unit_kerja', 'unit_kerja', 'required');
		$this->form_validation->set_rules('instansi', 'instansi', 'required');
		$this->form_validation->set_rules('area', 'area', 'required');

		
		$this->form_validation->set_error_delimiters('<span class="text-red">', '</span>');
		
		
		if($this->form_validation->run() == FALSE)
		{
			$data['msg1']  ='<p><h4 class="text-red">Lengkapai Form</h4><p>';
		}
		else
		{
			$first_name    = $this->input->post('first_name');
			$last_name     = $this->input->post('last_name');
			$email         = $this->input->post('email');
			$gender        = $this->input->post('gender');
			$jabatan       = $this->input->post('jabatan');
			$unit_kerja    = $this->input->post('unit_kerja');
			$id_instansi   = $this->input->post('instansi');
			$area   	   = $this->input->post('area');
			
		
			$data = array(
			   'first_name'       => $first_name ,
			   'last_name'        => $last_name ,
			   'email'            => $email,
			   'gender'           => $gender,
			   'jabatan'          => $jabatan,
			   'area'             => $area,
			   'id_bidang'        => $unit_kerja,	
			   'id_instansi'      => $this->session->userdata('session_instansi'),
			);
			
			$this->profile->setProfile($data); 
			$data['msg1']     	 = ' <p><h4 class="text-green">Setting Profile save successfully...</h4><p>';
		}			
				
		$data['name']        =  $this->auth->getName();
		$data['jabatan']     =  $this->auth->getJabatan();
		$data['member']	     =  $this->auth->getCreated();
		$data['avatar']	     =  $this->auth->getAvatar();
		$data['profile']     =  $this->_getProfile();
		$data['unit_kerja']  =  $this->_getUnitKerja();
		$data['tab_setting']     	 = 'active';
		$data['tab_change_password'] = '';
		$data['tab_activity']        = '';
		$data['tab_spesimen']        = '';
		
		$data['msg2']     	 = '';
		$data['msg3']     	 = '';
		$data['menu']     	  =  $this->menu->build_menu();
		$data['instansi']     = $this->profile->getInstansi();
		$data['spesimen']     = $this->profile->getSpesimen();
		$this->load->view('profile/vprofile',$data); 
    }

    public function changePassword()
    {
       
		
		$this->form_validation->set_rules('currentPassword', 'Current Password', 'callback_currentPassword_check');
		$this->form_validation->set_rules('newPassword', 'new Password', 'required|matches[retypePassword]');
		$this->form_validation->set_rules('retypePassword', 'Password Confirmation', 'required');
		
		$this->form_validation->set_error_delimiters('<span class="text-red">', '</span>');
		
		
		$currentPassword    = $this->input->post('currentPassword');
		$newPassword        = $this->input->post('newPassword');
		$retypePassword     = $this->input->post('retypePassword');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['msg2']     = '<p><h4 class="text-red">Lengkapai Form</h4><p>';
		}
		else
		{
		    $this->profile->setPassword($newPassword);
			$data['msg2']     	 = ' <p><h4 class="text-green">Change Password save successfully...</h4><p>';
		}

        
		$data['name']                =  $this->auth->getName();
		$data['jabatan']             =  $this->auth->getJabatan();
		$data['member']	             =  $this->auth->getCreated();
		$data['avatar']	             =  $this->auth->getAvatar();
		$data['profile']             =  $this->_getProfile();
		$data['unit_kerja']  		 =  $this->_getUnitKerja();
		$data['tab_setting']     	 = '';
		$data['tab_change_password'] = 'active';
		$data['tab_activity']        = '';
		$data['tab_spesimen']        = '';
		$data['msg1']     	 = '';		
		$data['msg3']     	 = '';
		$data['menu']         =  $this->menu->build_menu();
		$data['instansi']     = $this->profile->getInstansi();
		$data['spesimen']     = $this->profile->getSpesimen();
		$this->load->view('profile/vprofile',$data);  		
    }

    public function currentPassword_check($str)
	{
		$row               = $this->profile->getCurrentPassword()->row();
		$currentPassword   = $row->password;
		
		if (SHA1($str) != $currentPassword)
		{
			$this->form_validation->set_message('currentPassword_check', 'The %s field not match');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}	
	
	public function setSpesimen()
    {
		$this->form_validation->set_rules('lokasiSpesimen', 'lokasiSpesimen', 'required');
		$this->form_validation->set_rules('jabatanSpesimen', 'jabatanSpesimen', 'required');
		$this->form_validation->set_rules('namaSpesimen', 'namaSpesimen', 'required');
		$this->form_validation->set_rules('pangkatSpesimen', 'pangkatSpesimen', 'required');
		$this->form_validation->set_rules('nipSpesimen', 'nipSpesimen', 'required');
		$this->form_validation->set_rules('instansiSpesimen', 'instansiSpesimen', 'required');
		$this->form_validation->set_rules('areaSpesimen', 'areaSpesimen', 'required');
		
		$this->form_validation->set_error_delimiters('<span class="text-red">', '</span>');
		
		
		
		
		if($this->form_validation->run() == FALSE)
		{
			
			$data['msg3']     	 = ' <p><h4 class="text-red">Lengkapai Form</h4><p>';
		}
		else
		{
		    $this->profile->setSpesimen();
			$data['msg3']     	 = ' <p><h4 class="text-green">Spesimen berhasil tersimpan</h4><p>';
		}
		
		$data['name']                =  $this->auth->getName();
		$data['jabatan']             =  $this->auth->getJabatan();
		$data['member']	             =  $this->auth->getCreated();
		$data['avatar']	             =  $this->auth->getAvatar();
		$data['profile']             =  $this->_getProfile();
		$data['unit_kerja']  		 =  $this->_getUnitKerja();
		$data['tab_setting']     	 = '';
		$data['tab_change_password'] = '';
		$data['tab_activity']        = '';
		$data['tab_spesimen']        = 'active';
		$data['msg1']     	 		 = '';		
		$data['msg2']     	 		 = '';
		$data['menu']     			 = $this->menu->build_menu();
		$data['instansi']  			 = $this->profile->getInstansi();
		$data['spesimen']     = $this->profile->getSpesimen();
		$this->load->view('profile/vprofile',$data);	
		
	}	
}
