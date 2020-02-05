<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth_model extends CI_Model {

    var $username;
	var $password;
	var $table_user = 'app_user' ;
	var $tableunit  = 'unit_kerja';
	var $table_appsession='app_sessions';
    
	function __construct()
    {
        parent::__construct();		
    }
		
	public function getUser()
	{
	     $this->username = $this->input->post('username');
		 $this->password = $this->input->post('password');	 
		
		 $this->db->select('*');
		 $this->db->select('date_format(created_date,"%d %M %Y") as member',FALSE);
		 $this->db->where('username',$this->username);		 
		 $this->db->or_where('email',$this->username);
		 $query = $this->db->get($this->table_user);
		 return $query;
		 
	}
	
	function setLogin($user_id)
	{
		$this->db->set('logged_in', 1);
		$this->db->where('user_id', $user_id);
		return $this->db->update($this->table_user);
	}
	
	function setLogout($user_id)
	{
		$this->db->set('logged_in', NULL);
		$this->db->where('user_id', $user_id);
		return $this->db->update($this->table_user);
	}
	
	public function setLastAccess($user_id)
    {
		$this->db->set('last_access','NOW()',FALSE);
		$this->db->where('user_id',$user_id );
		return $this->db->update($this->table_user);
	}	
	
	
	public function setSessionId($user_id,$session_id,$ip)
    {
		$this->db->set('session_id',$session_id);
		$this->db->set('ip_address',$ip);
		$this->db->where('user_id',$user_id );
		return $this->db->update($this->table_user);
	}
	
	public function removeSessionId($id)
    {
		$this->db->where('session_id',$id );
		return $this->db->delete($this->table_appsession);
	}

    public function getBidang($id)
    {
		$this->db->select('*');
		$this->db->where('id_bidang',$id);
        $query = $this->db->get($this->tableunit);
		return $query;		
	}	
	
	public function getAuthMenu($user_id)
	{
	    $r  = array();
			
		$sql="SELECT menu_id FROM `menu_role` WHERE user_id ='$user_id'";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0){
		    foreach($query->result() as $value)
			{
			    $r[] =$value->menu_id; 	
			}	
		}	
		return $r;		 
	}
	
	
}