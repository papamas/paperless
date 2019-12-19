<?php

class User_temp_model extends CI_Model 
{
	var $username;
	var $password;
	var $db1;
	var $_table='user_temp';
	
	function __construct()
	{
		parent::__construct();		
		$this->db1 = $this->load->database('default', TRUE);
	}
	
	function get_all($offset = 0, $row_count = 0)
	{
		if ($offset >= 0 AND $row_count > 0)
		{
			$query = $this->db1->get($this->_table, $row_count, $offset); 
		}
		else
		{
			$query = $this->db1->get($this->_table);
		}
		
		return $query;
	}		
	
	function get_user_by_username($username)
	{
		$this->db1->where('username', $username);
		return $this->db1->get($this->_table);
	}
	
	function get_user_by_email($email)
	{
		$this->db1->where('email', $email);
		return $this->db1->get($this->_table);
	}

	function get_login($login)
	{
		$this->db1->where('username', $login);
		$this->db1->or_where('email', $login);
		return $this->db1->get($this->_table);
	}

	function check_username($username)
	{
		$this->db1->select('1', FALSE);
		$this->db1->where('username', $username);
		return $this->db1->get($this->_table);
	}

	function check_email($email)
	{
		$this->db1->select('1', FALSE);
		$this->db1->where('email', $email);
		return $this->db1->get($this->_table);
	}

	function activate_user($username, $key)
	{
		$this->db1->where(array('username' => $username, 'activation_key' => $key));
		return $this->db1->get($this->_table);
	}

	function delete_user($id)
	{
		$this->db1->where('id', $id);
		return $this->db1->delete($this->_table);
	}

	function prune_temp()
	{
		$this->db1->where('UNIX_TIMESTAMP(created) <', time() - $this->config->item('DX_email_activation_expire'));
		return $this->db1->delete($this->_table);
	}

	function create_temp($data)
	{
		return $this->db1->insert($this->_table,$data);
	}
}

?>