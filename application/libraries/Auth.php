<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Auth Class
 *
 * Authentication library for Code Igniter.
 * @author		Nur Muhamad Holik -2016
 * @version		1.0.0
 */
 
class Auth {
   var $_auth_message;
   
   
    function __construct()
    {
        $this->ci =& get_instance();
		$this->ci->load->library('Session');
		$this->ci->lang->load('auth');
    }
   
   public function loginUser()
   {
        $this->ci->load->model('Auth_model','users');
		
		$result = FALSE;
		
		if($query  = $this->ci->users->getUser() AND $query->num_rows()  == 1 )
        {
             $row 	      = $query->row();			 
			 $password 	  = $this->_encode();
			 $active      = $row->active;
			 $logged_in   = $row->session_id;
		     $stored_hash = $row->password;
			 
			if($password === $stored_hash)
			{
			   
				// Set message
				$this->_auth_message = $this->ci->lang->line('auth_login_correct_username_password');	
                if(!empty($active))
				{
					
					if(!empty($logged_in))
					{
						// remove other session
						$this->removeSessionId($logged_in);
						// Set message
						$this->_auth_message = $this->ci->lang->line('auth_login_current_logged_in');	
												
					}
					else
					{
						$this->_set_session($row);						
						// set logged_in flag
						//$this->ci->users->setLogin($row->user_id);
						// set session id app user
						$this->setSessionId();
						$result =  TRUE;
						
						
					}
				}
                else
                {
                    // Set message
				    $this->_auth_message = $this->ci->lang->line('auth_login_inactive_user');
                } 				
			}
			else
			{
			      // Set message
				  $this->_auth_message = $this->ci->lang->line('auth_login_incorrect_password');
			}
        }
        else
        {
            // Set message
			$this->_auth_message = $this->ci->lang->line('auth_login_incorrect_username');
        }

        return $result;		
   
   }
   
    public function logoutUser()
    {
       $this->ci->load->model('Auth_model','users');
	   
	   // set logout
	   $this->ci->users->setLogout($this->getUserId());
	   
	   $this->ci->session->sess_destroy();
    }
   
   public function getMessage()
   {
      return $this->_auth_message;
   }
   
    public function getRegMessage()
    {
      return $this->_register_message;
    }
   
    public function isLoggedin()
    {
		return $this->ci->session->userdata('logged_in');
    }
   
    public function getAuthMenu()
	{
	    $user_id		= $this->getUserId();
		$this->ci->load->model('Auth_model','users');		
		return $this->ci->users->getAuthMenu($user_id);
	}
	
	public function isAuthMenu($menu_id)
    {
		$r = FALSE;
		
		$allowmenu =   $this->getAuthMenu();
		if(in_array($menu_id,$allowmenu))
		{
           $r   = TRUE;
        } 	
		
		
		return $r;
		
    }
   
    function _encode()
    {
       return SHA1($this->ci->input->post('password'));
    }
   
   function _set_session($data)
	{
		// Set session data array
		$user = array(						
			'user_id'						=> $data->user_id,
			'user_name'						=> $data->username,
			'firts_name'		            => $data->first_name,
            'last_name'						=> $data->last_name,
            'jabatan'						=> $data->jabatan,	
            'created_date'					=> $data->created_date,		
            'session_instansi'				=> $data->id_instansi,	
			'session_bidang'				=> $data->id_bidang,
			'session_user_tipe'				=> $data->user_tipe,
			'gender'						=> $data->gender,
			'area'							=> $data->area,
			'logged_in'					    => TRUE
		);
		
		$this->ci->session->set_userdata($user);
	}
	
	public function setLastAccess()
    {
		$user_id		= $this->getUserId();
		$this->ci->load->model('Auth_model','users');		
		$this->ci->users->setLastAccess($user_id);
	}	
	
	public function setSessionId()
    {
		$user_id		= $this->getUserId();	
		$session_id		= $this->ci->session->userdata('session_id');
		$ip				= $this->ci->session->userdata('ip_address');
		$this->ci->load->model('Auth_model','users');
		$this->ci->users->setSessionId($user_id,$session_id,$ip);
	}	
	
	public function removeSessionId($id)
    {
		$session_id		= $id;
		
		$this->ci->load->model('Auth_model','users');
		$this->ci->users->removeSessionId($id);
	}	
	
	public function getAvatar()
    {
		if($this->ci->session->userdata('gender') == 'L')
		{
		    $avatar = 'avatar5.png';
		}
		else
		{
		     $avatar = 'avatar3.png';
		}
		
		return $avatar;
    }
	
	public function getUserId()
    {
		return $this->ci->session->userdata('user_id') ;
    }
	
	public function getLastName()
    {
		return $this->ci->session->userdata('last_name') ;
    }
	
	public function getName()
    {
		return $this->ci->session->userdata('firts_name').' '.$this->ci->session->userdata('last_name') ;
    }
	
	public function getJabatan()
    {
		return $this->ci->session->userdata('jabatan');
    }
	
	public function getBidang()
    {
		$r 			= NULL;
		$id  		=  $this->ci->session->userdata('session_bidang');
		$this->ci->load->model('Auth_model','users');
		$query 		= $this->ci->users->getBidang($id);
		if($query->num_rows() > 0)
		{
			$row  = $query->row();
			$r    = $row->nama_unit;
		}	
		return $r;
    }
	
	public function getCreated()
    {
		return $this->ci->session->userdata('created_date');
    }
	
	public function getRegReponse()
    {
      return $this->_register_Response;
    }
	

	
	public function register($data)
	{		
		// Load Models
		$this->ci->load->model('users_model', 'users');
		$this->ci->load->model('user_temp_model', 'user_temp');

		// Default return value
		$result = FALSE;		
		
		// Add activation key to user array
		//$new_user['activation_key'] = md5(rand().microtime());
		
		$check_user_temp      = $this->ci->user_temp->check_username($data['username']);
		$check_user 		  = $this->ci->users->check_username($data['username']);
		
		if($check_user->num_rows() ==  1 || $check_user_temp->num_rows() == 1 )
		{
					
			if($check_user->num_rows() == 1)
			{
				$this->_register_message 	= 'User telah terdaftar';
				$this->_register_Response	= FALSE;				
			}
			
			if($check_user_temp->num_rows() == 1)
			{
				$this->_register_message 	='User menunggu proses Approve, Hubungi Administrator';
				$this->_register_Response	= FALSE;
				
			}
		}
		else
		{
		
			// Create temporary user in database which means the user still unactivated.
			$res    	 = $this->ci->user_temp->create_temp($data);
			$response    = $res['response'];			
			
			if ($response)
			{
				$result 					= TRUE;
				$this->_register_message 	=' User berhasil ditambahkan, Hubungi Administrator untuk Approve';	
				$this->_register_Response	= TRUE;	
			}
			else
			{
				$this->_register_message 	= $res['pesan'];	
                $this->_register_Response	= FALSE;				
			}
		}
		
		
		
		return $result;
	}

}


	
	
 
