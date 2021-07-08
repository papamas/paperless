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
	
	function getInstansi()
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
		a.gender,a.active,a.jabatan,a.id_bidang,a.id_instansi, b.INS_NAMINS instansi, c.nama_unit,
		area
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
	
	function setSpesimen()
	{
		$data['lokasi_spesimen']			= $this->input->post('lokasiSpesimen');
		$data['jabatan_spesimen']			= $this->input->post('jabatanSpesimen');
		$data['nama_spesimen']				= $this->input->post('namaSpesimen');
		$data['pangkat_spesimen']			= $this->input->post('pangkatSpesimen');
		$data['nip_spesimen']				= $this->input->post('nipSpesimen');
		$data['instansi_spesimen']			= $this->input->post('instansiSpesimen');
		$data['area_spesimen']				= $this->input->post('areaSpesimen');
		
		//$this->session->set_userdata('area',$this->input->post('areaSpesimen'));
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		if($this->input->post('aksi') == 1)
		{			
			if (!$this->db->insert('spesimen_instansi', $data))
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
				$data['pesan']		= "Data Spesimen Berhasil Tersimpan";
				$data['response']	= TRUE;
			}	
		}
		else
		{
			$instansi   = $this->session->userdata('session_instansi');
			$area       = substr($instansi,0,2);
			
			if($area  == '70' || $area  == '71' || $area  == '79')
			{
				$this->db->where('instansi_spesimen',$instansi);
			}	
			else
			{
				$this->db->where('instansi_spesimen',$instansi);
				$this->db->where('area_spesimen',$this->session->userdata('area'));
			}		
			
			if (!$this->db->update('spesimen_instansi', $data))
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
				$data['pesan']		= "Data Spesimen Berhasil Tersimpan";
				$data['response']	= TRUE;
			}	

		}		
		$this->db->db_debug = $db_debug; //restore setting			
		
		
		return $data;

	}

	function getSpesimen()
	{
		$instansi   = $this->session->userdata('session_instansi');
		$area       = substr($instansi,0,2);
		
		if($area  == '70' || $area  == '71' || $area  == '79')
		{
			$sql_area   = " AND instansi_spesimen='$instansi'";	
		}	
		else
		{
			$area 		= $this->session->userdata('area');
			$sql_area   = " AND instansi_spesimen='$instansi' AND area_spesimen='$area' ";	
		}			
		
		$sql="SELECT a.* FROM spesimen_instansi a
		WHERE 1=1 $sql_area ";
		return $this->db->query($sql);
	}		
	
	
	
}