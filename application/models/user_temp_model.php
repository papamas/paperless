<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_temp_model extends CI_Model 
{
	var $username;
	var $password;
	var $db1;
	var $_table		='user_temp';
	var $instansi	='mirror.instansi';
	var $bidang		='unit_kerja';
	
	function __construct()
	{
		parent::__construct();			
	}
	
	function create_temp($data)
	{
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
			
		if (!$this->db->insert($this->_table, $data))
		{
			$error = $this->db->error();
			if(!empty($error['message']))
			{
				$data['pesan']		= $error['message'];   
				$data['response'] 	= FALSE;
			}
				
		}
		else
		{
			$data['pesan']		= "User Berhasil Tersimpan";
			$data['response']	= TRUE;
			
		}	
        		
		$this->db->db_debug = $db_debug; //restore setting	
        return $data;
		
	}
	
	function check_username($username)
	{
		$this->db->select('1', FALSE);
		$this->db->where('username', $username);
		return $this->db->get($this->_table);
	}
	
	function getAlluser()
	{
		$sql ="SELECT a.*,b.* , c.*
		FROM $this->_table a
		LEFT JOIN $this->instansi b ON a.id_instansi = b.INS_KODINS
		LEFT JOIN $this->bidang c ON a.id_bidang = c.id_bidang";
		return $this->db->query($sql);
	}
	
	function get_user_by_id($id)
	{
		$this->db->select('username,password,email,first_name,last_name,nip,id_bidang,jabatan,id_instansi,sex');
		$this->db->where('user_temp_id', $username);
		return $this->db->get($this->_table);
	}
	
	function delete_user($id)
	{
		$this->db->where('user_temp_id', $id);
		return $this->db->delete($this->_table);
	}
	
	/* general */
	
	function get_all($offset = 0, $row_count = 0)
	{
		if ($offset >= 0 AND $row_count > 0)
		{
			$query = $this->db->get($this->_table, $row_count, $offset); 
		}
		else
		{
			$query = $this->db->get($this->_table);
		}
		
		return $query;
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

	

	function check_email($email)
	{
		$this->db->select('1', FALSE);
		$this->db->where('email', $email);
		return $this->db->get($this->_table);
	}

	function activate_user($username, $key)
	{
		$this->db->where(array('username' => $username, 'activation_key' => $key));
		return $this->db->get($this->_table);
	}	

	function prune_temp()
	{
		$this->db->where('UNIX_TIMESTAMP(created) <', time() - $this->config->item('DX_email_activation_expire'));
		return $this->db->delete($this->_table);
	}

	
}

?>