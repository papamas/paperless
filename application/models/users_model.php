<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users_model extends CI_Model 
  
{
	var $app_user		='app_user';
	var $instansi	    ='mirror.instansi';
	var $bidang		    ='unit_kerja';
	var $user_temp	    ='user_temp';
	var $menu_role      ='menu_role';
	
	
	function __construct()
	{
		parent::__construct();		
	}
	
	function check_usertemp($username)
	{
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(username)=', strtolower($username));
		return $this->db->get($this->user_temp);
	}
	
	function check_username($username)
	{
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(username)=', strtolower($username));
		return $this->db->get($this->app_user);
	}
	
	function getInstansi()
	{
		$this->db->select('*');
		return $this->db->get($this->instansi);
	}	
	
	function getBidang()
	{
		$this->db->select('*');
		return $this->db->get($this->bidang);
	}
	
	function getAlluser()
	{
		$nip 		= trim($this->input->post('find'));
		
		if(!empty($nip))
		{
			$sql_find = " AND a.nip='$nip' ";
		}
		else
		{
			$sql_find = " AND a.nip='99999999999' ";
		}		
		
		$sql ="SELECT a.*,b.*,c.*
		FROM $this->app_user a
		LEFT JOIN $this->instansi b ON a.id_instansi = b.INS_KODINS
		LEFT JOIN $this->bidang c ON a.id_bidang = c.id_bidang
		WHERE 1=1 $sql_find";
		return $this->db->query($sql);
	}
	
	function insert_user()
	{
		$data['username']	 	 = $this->input->post('username');
		$data['password']	 	 = SHA1($this->input->post('username'));
		$data['id_instansi']	 = $this->input->post('instansi');
		$data['first_name']		 = $this->input->post('fname');
		$data['last_name']		 = $this->input->post('lname');
		$data['jabatan']		 = $this->input->post('jabatan');
		$data['id_bidang']	 	 = $this->input->post('bidang');
		$data['nip']		     = $this->input->post('nip');
		$data['email']	 		 = $this->input->post('email');
		$data['user_tipe']		 = $this->input->post('usertipe');
		$data['gender']	 		 = $this->input->post('sex');
		$data['active']	 		 = ($this->input->post('active') == 1 ? 1 : NULL);
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		
		$check_user_temp      = $this->check_usertemp($data['username']);
		$check_user           = $this->check_username($data['username']);
		
		if($check_user->num_rows() ==  1 || $check_user_temp->num_rows() == 1)
		{
			if($check_user->num_rows() == 1)
			{
				$data['pesan'] 		= " Username telah terdaftar ";
				$data['response'] 	= FALSE;			
			}
			
			if($check_user_temp->num_rows() == 1)
			{
				$data['pesan']			 	='User menunggu proses Approve, Hubungi Administrator';
				$data['response']			= FALSE;				
			}
		}
		else
        {			
			if (!$this->db->insert($this->app_user, $data))
			{
				$error = $this->db->_error_message();
				if(!empty($error))
				{
					$data['pesan']		= $error;   
					$data['response'] 	= FALSE;
				}					
			}
			else
			{
				$data['pesan']		= "User Berhasil Tersimpan";
				$data['response']	= TRUE;				
			}	
        }
		
		$this->db->db_debug = $db_debug; //restore setting	
        return $data;
	}
	
	function update_user()
	{
		$user_id     			 = $this->input->post('user_id');
		
		$data['username']	 	 = $this->input->post('username');		
		$data['id_instansi']	 = $this->input->post('instansi');
		$data['first_name']		 = $this->input->post('fname');
		$data['last_name']		 = $this->input->post('lname');
		$data['jabatan']		 = $this->input->post('jabatan');
		$data['id_bidang']	 	 = $this->input->post('bidang');
		$data['nip']		     = $this->input->post('nip');
		$data['email']	 		 = $this->input->post('email');
		$data['user_tipe']		 = $this->input->post('usertipe');
		$data['gender']	 		 = $this->input->post('sex');
		$data['active']	 		 = ($this->input->post('active') == 1 ? 1 : NULL);
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		
		$this->db->where('user_id', $user_id);
		if (!$this->db->update($this->app_user, $data))
		{
			$error = $this->db->_error_message();
			if(!empty($error))
			{
				$data['pesan']		= $error;   
				$data['response'] 	= FALSE;
			}				
		}
		else
		{
			$data['pesan']		= "User Berhasil Terupdate";
			$data['response']	= TRUE;
			
		}	
		$this->db->db_debug = $db_debug; //restore setting	
		return $data;
	}
	
	function get_usertemp_by_id($id)
	{
		$this->db->select('username,password,email,first_name,last_name,nip,id_bidang,jabatan,id_instansi,gender,user_tipe');
		$this->db->where('user_temp_id', $id);
		return $this->db->get($this->user_temp);
	}
	
	function delete_usertemp_by_id($id)
	{
		$this->db->where('user_temp_id', $id);
		return $this->db->delete($this->user_temp);
	}
	
	function approveUser()
	{
		$user_id		    = $this->input->post('approve_user_id');
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 			
		$this->db->trans_begin();

		$user_temp  		= $this->get_usertemp_by_id($user_id)->result_array();	
		$this->db->insert_batch($this->app_user, $user_temp);
		$last_id 			= $this->db->insert_id();
		$this->insert_menuInstansi($last_id);
		$this->delete_usertemp_by_id($user_id);

		if ($this->db->trans_status() === FALSE)
		{
			$data['pesan']		= "Failed Approve User";
			$data['response'] 	= FALSE;					
			$this->db->trans_rollback();			
		}
		else
		{
			$data['response']	= TRUE;
			$data['pesan']		= "Approve User Berhasil";		
			$this->db->trans_commit();
		}
		$this->db->db_debug = $db_debug; //restore setting	
		return $data;
	}
	
	function insert_menuInstansi($id)
	{
		$data = array(
			array(
					'menu_id' => 3,
					'user_id' => $id,             
			),
			array(
					'menu_id' => 8,
					'user_id' => $id,				   
			),
			array(
					'menu_id' => 9,
					'user_id' => $id,				   
			),
			array(
					'menu_id' => 10,
					'user_id' => $id, 				   
			),
			array(
					'menu_id' => 11,
					'user_id' => $id,				   
			),
			array(
					'menu_id' => 12,
					'user_id' => $id,				   
			),
			array(
					'menu_id' => 13,
					'user_id' => $id,				   
			),			
		);

        return $this->db->insert_batch($this->menu_role, $data);
	}	
	
	function drop()
	{
		$user_id		= $this->input->post('drop_user_id');
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		
		$this->db->where('user_id', $user_id);
		if (!$this->db->delete($this->app_user))
		{
			$error = $this->db->_error_message();
			if(!empty($error))
			{
				$data['pesan']		= $error;   
				$data['response'] 	= FALSE;
			}
				
		}
		else
		{
			$data['pesan']		= "User Berhasil di DROP";
			$data['response']	= TRUE;
			
		}	
		$this->db->db_debug = $db_debug; //restore setting	
		return $data;
	}
	
	function resetUser()
	{
		$user_id		= $this->input->post('reset_user_id');
		$nip		    = $this->input->post('reset_nip');
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		
		$this->db->set('password',"SHA1($nip)",FALSE);
		$this->db->where('user_id', $user_id);
		if (!$this->db->update($this->app_user))
		{
			$error = $this->db->_error_message();
			if(!empty($error))
			{
				$data['pesan']		= $error;   
				$data['response'] 	= FALSE;
			}
				
		}
		else
		{
			$data['pesan']		= "Berhasil RESET Password";
			$data['response']	= TRUE;
			
		}	
		$this->db->db_debug = $db_debug; //restore setting	
		return $data;
	}
	// General function
	public function setLastAccess($user_id)
    {
		$this->db->set('last_access','NOW()',FALSE);
		$this->db->where('user_id',$user_id );
		return $this->db->update($this->_table);
	}	
	
	
	public function setSessionId($user_id,$session_id)
    {
		$this->db->set('session_id',$session_id);
		$this->db->where('user_id',$user_id );
		return $this->db->update($this->_table);
	}	
	
	function get_all($offset = 0, $row_count = 0)
	{
		$users_table = $this->_table;
		$roles_table = $this->_roles_table;
		
		if ($offset >= 0 AND $row_count > 0)
		{
			$this->db->select("$users_table.*", FALSE);
			$this->db->select("$roles_table.name AS role_name", FALSE);
			$this->db->join($roles_table, "$roles_table.id = $users_table.role_id");
			$this->db->order_by("$users_table.id", "ASC");
			
			$query = $this->db->get($this->_table, $row_count, $offset); 
		}
		else
		{
			$query = $this->db->get($this->_table);
		}
		
		return $query;
	}

	function get_user_by_id($user_id)
	{
		$this->db->where('id', $user_id);
		return $this->db->get($this->_table);
	}

	function get_user_by_username($username)
	{
		$this->db->where('username', $username);
		return $this->db->get($this->_table);
	}
	
	function get_user_by_email($email)
	{
		$this->db->where('email', $email);
		return $this->db->get($this->_table);
	}
	
	function get_login($login)
	{
		$this->db->where('username', $login);
		$this->db->or_where('email', $login);
		return $this->db->get($this->_table);
	}
	
	function check_ban($user_id)
	{
		$this->db->select('1', FALSE);
		$this->db->where('id', $user_id);
		$this->db->where('banned', '1');
		return $this->db->get($this->_table);
	}
	
	

	function check_email($email)
	{
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(email)=', strtolower($email));
		return $this->db->get($this->_table);
	}
		
	function ban_user($user_id, $reason = NULL)
	{
		$data = array(
			'banned' 			=> 1,
			'ban_reason' 	=> $reason
		);
		return $this->set_user($user_id, $data);
	}
	
	function unban_user($user_id)
	{
		$data = array(
			'banned' 			=> 0,
			'ban_reason' 	=> NULL
		);
		return $this->set_user($user_id, $data);
	}
		
	function set_role($user_id, $role_id)
	{
		$data = array(
			'role_id' => $role_id
		);
		return $this->set_user($user_id, $data);
	}

	// User table function

	function create_user($data)
	{
		return $this->db->insert($this->_table, $data);
	}

	function get_user_field($user_id, $fields)
	{
		$this->db->select($fields);
		$this->db->where('id', $user_id);
		return $this->db->get($this->_table);
	}

	
	
	
	
	// Forgot password function

	function newpass($user_id, $pass, $key)
	{
		$data = array(
			'newpass' 			=> $pass,
			'newpass_key' 	=> $key,
			'newpass_time' 	=> date('Y-m-d h:i:s', time() + $this->config->item('DX_forgot_password_expire'))
		);
		return $this->set_user($user_id, $data);
	}

	function activate_newpass($user_id, $key)
	{
		$this->db->set('password', 'newpass', FALSE);
		$this->db->set('newpass', NULL);
		$this->db->set('newpass_key', NULL);
		$this->db->set('newpass_time', NULL);
		$this->db->where('id', $user_id);
		$this->db->where('newpass_key', $key);
		
		return $this->db->update($this->_table);
	}

	function clear_newpass($user_id)
	{
		$data = array(
			'newpass' 			=> NULL,
			'newpass_key' 	=> NULL,
			'newpass_time' 	=> NULL
		);
		return $this->set_user($user_id, $data);
	}
	
	// Change password function

	function change_password($user_id, $new_pass)
	{
		$this->db->set('password', $new_pass);
		$this->db->where('id', $user_id);
		return $this->db->update($this->_table);
	}
	
	
}

?>