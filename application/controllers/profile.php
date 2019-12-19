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
		
		
		$data['message']     = '';
		$data['name']        =  $this->auth->getName();
        $data['jabatan']     =  $this->auth->getJabatan();
		$data['member']	     =  $this->auth->getCreated();
		$data['avatar']	     =  $this->auth->getAvatar();
		$data['profile']     =  $this->_getProfile();
		$data['unit_kerja']  =  $this->_getUnitKerja();
		$data['tab_setting']     	 = 'active';
		$data['tab_change_password'] = '';
		$data['tab_activity']        = '';
		$data['menu']     =  $this->menu->build_menu();
		$data['instansi']  = $this->profile->getInstansi();
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
		
		$first_name    = $this->input->post('first_name');
		$last_name     = $this->input->post('last_name');
		$email         = $this->input->post('email');
		$gender        = $this->input->post('gender');
		$jabatan       = $this->input->post('jabatan');
		$unit_kerja    = $this->input->post('unit_kerja');
		$id_instansi   = $this->input->post('instansi');
		
		
		
		$data = array(
		   'first_name'       => $first_name ,
		   'last_name'        => $last_name ,
		   'email'            => $email,
		   'gender'           => $gender,
		   'jabatan'          => $jabatan,
		   'id_bidang'        => $this->session->userdata('session_bidang'),	
		   'id_instansi'      => $this->session->userdata('session_instansi'),
		);
		
		
		
		if($this->input->post())
		{
		   $this->profile->setProfile($data); 
		   $data['message']     = ' <p><h4 class="text-green">Setting Profile save successfully...</h4><p>';
		}
		else
		{
			 $data['message']     = '';
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
		$data['menu']     =  $this->menu->build_menu();
		$data['instansi']  = $this->profile->getInstansi();
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
			$data['message']     = '';
		}
		else
		{
		    $this->profile->setPassword($newPassword);
			$data['message']     = ' <p><h4 class="text-green">Change Password save successfully...</h4><p>';
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
		$data['menu']     =  $this->menu->build_menu();
		$data['instansi']  = $this->profile->getInstansi();
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
}
