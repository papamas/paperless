<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends MY_Controller {
	
	function __construct()
	{
	    parent::__construct();		
	    $this->load->library(array('Auth','Menu','form_validation'));
		$this->load->model('users_model', 'user');
		$this->load->model('user_temp_model', 'temp_user');

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
		
		$data['unit_kerja'] = $this->user->getBidang();
		$data['instansi']   = $this->user->getInstansi();
		$data['user']       = $this->user->getAlluser();
		$data['temp_user']  = $this->temp_user->getAlluser();
		
		$this->load->view('user/index',$data);
	}
	
	public function setUser()
	{
		$this->form_validation->set_rules('fname', 'Firtsname', 'trim|required');
		$this->form_validation->set_rules('lname', 'Lastname', 'trim');
		$this->form_validation->set_rules('sex', 'Sex', 'required');
		$this->form_validation->set_rules('instansi', 'Instansi', 'required');
		$this->form_validation->set_rules('bidang', 'Bidang', 'required');
		$this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('nip', 'NIP', 'required|max_length[18]');
		$this->form_validation->set_rules('active', 'Active', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('usertipe', 'User Tipe', 'required');
		$this->form_validation->set_rules('area', 'Area', 'required');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['message']    = '';
		}
		else
		{	
			$user_id      = $this->input->post('user_id');
				
			if(empty($user_id))
			{
				$result    			= $this->user->insert_user();
				$response			= $result['response'];
				if(!$response)
				{	
					$data['message']    = '<p class="register-box-msg text-red">'.$result['pesan'].'</p>';
				}
				else
				{
					$data['message']    = '<p class="register-box-msg text-green">'.$result['pesan'].'</p>';

				}		
			}
			else
			{
				$result    			=  $this->user->update_user();
				$response			= $result['response'];
				if(!$response)
				{	
					$data['message']    = '<p class="register-box-msg text-red">'.$result['pesan'].'</p>';
				}
				else
				{
					$data['message']    = '<p class="register-box-msg text-green">'.$result['pesan'].'</p>';

				}	
			}	
	    }
		
		$data['menu']     =  $this->menu->build_menu();
		
		
		$data['lname']    =  $this->auth->getLastName();        
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		
		$data['unit_kerja'] = $this->user->getBidang();
		$data['instansi']   = $this->user->getInstansi();
		$data['user']       = $this->user->getAlluser();
		$data['temp_user']  = $this->temp_user->getAlluser();
		
		$this->load->view('user/index',$data);
	}	
	
	public function getUser()
	{
			
		$data['menu']     =  $this->menu->build_menu();
		
		$data['message']  = '';
		$data['lname']    =  $this->auth->getLastName();        
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		
		$data['unit_kerja'] = $this->user->getBidang();
		$data['instansi']   = $this->user->getInstansi();
		$data['user']       = $this->user->getAlluser();
		$data['temp_user']  = $this->temp_user->getAlluser();
		
		$this->load->view('user/index',$data);
	}
	
	public function approveUser()
	{
		$result 			= $this->user->approveUser();
		$response			= $result['response'];
		if(!$response)
		{	
			$data['pesan']    		= $result['pesan'];
			$data['response']		= $result['response'];
			
			$this->output
						->set_status_header(406)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
					return FALSE;
		}
		else
		{
			$data['pesan']    		= $result['pesan'];
			$data['response']		= $result['response'];
			$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
		}	
		
	}	
	
	
	public function Drop()
	{
		$result 			= $this->user->drop();
		$response			= $result['response'];
		if(!$response)
		{	
			$data['pesan']    		= $result['pesan'];
			$data['response']		= $result['response'];
			
			$this->output
						->set_status_header(406)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
					return FALSE;
		}
		else
		{
			$data['pesan']    		= $result['pesan'];
			$data['response']		= $result['response'];
			$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
		}	
		
	}	
	
	public function resetUser()
	{
		$result 			= $this->user->resetUser();
		$response			= $result['response'];
		if(!$response)
		{	
			$data['pesan']    		= $result['pesan'];
			$data['response']		= $result['response'];
			
			$this->output
						->set_status_header(406)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
					return FALSE;
		}
		else
		{
			$data['pesan']    		= $result['pesan'];
			$data['response']		= $result['response'];
			$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
		}	
		
	}	
	
	public function getPns()
	{
	    $search   = $this->input->get('q');
	    $sql="SELECT PNS_NIPBARU as id,CONCAT( PNS_NIPBARU ,' - ', PNS_PNSNAM)  as text 
		FROM mirror.pupns 
		WHERE TRIM(PNS_NIPBARU)=TRIM('$search') 
		OR TRIM(PNS_PNSNIP)=TRIM('$search') 
		ORDER BY PNS_PNSNAM ASC";
	  
    	$query= $this->db->query($sql);
	    $ret['results'] = $query->result_array();
	    echo json_encode($ret);
	}	
	
	public function getPnsdata()
	{
	    $nip   = $this->input->get('q');
	    $sql="SELECT a.*, b.* FROM (
		SELECT * FROM mirror.pupns WHERE PNS_NIPBARU='$nip') a 
		LEFT JOIN paperless.app_user b ON a.PNS_NIPBARU = b.nip";
	  
    	$query		        = $this->db->query($sql);
	    if($query->num_rows()  > 0)
		{
		    $row		= $query->row();
			
			$xnama      = explode(" ",$row->PNS_PNSNAM,2);
			
			if(count($xnama) == 2)
			{
				$fn 	= $xnama[0];
				$ln		= $xnama[1];
			}
			else
			{
				$fn		= $xnama[0];
				$ln		= '';
			}        			
		
            $data[]		= array('PNS_NIPBARU'     		   => $row->PNS_NIPBARU,
								'FIRSTNAME'  			   => $fn,
								'LASTNAME'				   => $ln,
								'PNS_INSKER'    		   => $row->PNS_INSKER,
								'PNS_PNSSEX'    		   => ($row->PNS_PNSSEX == 2 ? 'P' : 'L'),
								'PNS_EMAIL'    		       => (!empty($row->PNS_EMAIL) ? $row->PNS_EMAIL : $row->email),	
			);			
		}
		else
		{
		    $data[]		= array('PNS_NIPBARU'     		   => '',
								'PNS_PNSNAM'  			   => '',
								'PNS_INSKER'    		   => '',	
								'PNS_EMAIL'    		       => '',		
			);	
		}
		
		echo json_encode($data);	
	}	
	
	
}
