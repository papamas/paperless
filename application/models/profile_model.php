<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Profile_model extends CI_Model {

    var $table_app_user   = 'app_user';
	var $table_unit_kerja = 'unit_kerja';
	var $tableinstansi = 'mirror.instansi';
	
	
    function __construct()
    {
        parent::__construct();
		$this->load->database();
    }
	
		public function getInstansi()
	{
	    $sql="SELECT * FROM $this->tableinstansi";	
		return $this->db->query($sql);
		
	}	
		
	public function getProfile()
	{
	   	$user_id    = $this->session->userdata('user_id');
		/*
		$this->db->select('user_id,username,email,first_name,last_name,gender,active,jabatan,id_bidang,id_instansi');
		$this->db->where('user_id',$user_id);	
		return $this->db->get($this->table_app_user); 
		*/
		$sql="SELECT a.user_id,a.username,a.email,a.first_name,a.last_name,
		a.gender,a.active,a.jabatan,a.id_bidang,a.id_instansi, b.INS_NAMINS instansi, c.nama_unit
		FROM $this->table_app_user a
		LEFT JOIN $this->tableinstansi b ON a.id_instansi = b.INS_KODINS
		LEFT JOIN $this->table_unit_kerja c ON a.id_bidang = c.id_bidang
		WHERE user_id ='$user_id' ";
		
		return $this->db->query($sql);
		
		
	}
	
	function getUnitKerja()
    {
        $this->db->select('id_bidang,nama_unit');
		$this->db->order_by('nama_unit', 'asc');
        return $this->db->get($this->table_unit_kerja);
    }	
	
	function setProfile($data)
	{
	    $user_id    = $this->session->userdata('user_id');
		$this->db->where('user_id',$user_id);
		return $this->db->update($this->table_app_user, $data); 
	}
	
	function setPassword($newPassword)
	{
	    $user_id     = $this->session->userdata('user_id');
		$newPassword = SHA1($newPassword);
		$this->db->where('user_id',$user_id);
		$this->db->set('password',$newPassword);
		return $this->db->update($this->table_app_user); 
	}
	
	function getCurrentPassword()
	{
	    $user_id    = $this->session->userdata('user_id');
		$this->db->where('user_id',$user_id);
		$this->db->select('password');
		return $this->db->get($this->table_app_user);
	
	}
	
	
	
}