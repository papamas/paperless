<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Menu_model extends CI_Model {

    
    function __construct()
    {
        parent::__construct();
		
    }
		
	public function getMenu()
	{
	    $user_id    = $this->session->userdata('user_id');
		
		$sql="SELECT a.menu_id , b.menu_name ,b.icon, b.link, b.parent, b.active FROM `menu_role` a
INNER JOIN menu b ON a.menu_id = b.menu_id WHERE user_id ='$user_id' 
";
		 $query = $this->db->query($sql);
		 return $query;
		 
	}
	
	public function getChildParent($id)
    {
        $sql ="SELECT * FROM menu where parent='$id' and active=1
		 AND parent IN (select menu_id where user_id='$id') ";
	    $query = $this->db->query($sql);
		return $query;
    }	
	
	
	
}