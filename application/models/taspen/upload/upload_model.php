<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Upload_model extends CI_Model {

	private     $rawName;
	private     $table  			= 'upload_taspen';
	private     $tabledokumen       = 'dokumen';
	private     $tableinstansi      = 'mirror.instansi';
	private     $tablepupns         = 'mirror.pupns';
		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	public function insertUpload($data)
	{
		$data['id_dokumen']		= $this->_getIdDokumen($data);
		$data['id_instansi']    = $this->session->userdata('session_instansi');
		$data['upload_by']      = $this->session->userdata('user_id');
		$number 				= $this->_extract_numbers($data['raw_name']);
		
		foreach($number as $value){
		    if (strlen($value) == 18){
                $data['nip_baru']    = $value;
            }
            else
            {
			    $data['nip_lama']    = $value;
            }		
	    }   
		
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
			
		if (!$this->db->insert($this->table, $data))
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
			$data['pesan']		= "Dokumen Berhasil Tersimpan";
			$data['response']	= TRUE;
		}	
        $this->db->db_debug = $db_debug; //restore setting	

        return $data;		
		
	}
	
	function  updateFile($data)
	{
				
		$this->db->where('raw_name',$data['raw_name']);
		$this->db->where('id_instansi',$data['id_instansi']);
		$this->db->set('flag_update',1);
		$this->db->set('update_date','NOW()',FALSE);
		return $this->db->update($this->table);
		
	}	
	
	function _is_exist($data)
	{  
	    $r  = FALSE;		
		$query 	= $this->db->where('raw_name', $data['raw_name'])->get($this->table);
		
		if($query->num_rows() > 0){
		    $r 	= TRUE;
		}

        return $r; 		
	}
	
	function _getIdDokumen($data)
	{
	    $r = NULL;
		$find    = $data['raw_name'];
		
		$query = $this->db->query("SELECT * FROM (SELECT *,locate(nama_dokumen,'$find') result from $this->tabledokumen ) a
 WHERE a.result = 1"); 	
		if($query->num_rows() > 0){
		    $row 	= $query->row();
			$r 		= $row->id_dokumen;
		}
		
		return $r;
	}
	
	function _is_arsip($data)
	{
	    $r = FALSE;
		$find    = $data;
		
	    $query = $this->db->query("SELECT * FROM (SELECT *,locate(nama_dokumen,'$find') result from $this->tabledokumen ) a
 WHERE a.result = 1"); 
		if($query->num_rows() > 0){
		    $r 		= TRUE;
		}
		
		return $r;
	}
	
	function _extract_numbers($string)
	{
	    preg_match_all('/([\d]+)/', $string, $match);
	   
	    

	   return $match[0];
	}
	
	public function getInstansi()
	{
	    $instansi  = $this->session->userdata('session_instansi');
		
		$sql="SELECT * FROM $this->tableinstansi where INS_KODINS='$instansi' ";	
		return $this->db->query($sql);
		
	}	
	
	public function getDaftar($data)
	{
	    $instansi 		= 99;
		$searchby		= $data['searchby'];
		$search			= $data['search'];
		
		if(!empty($instansi))
		{
			$sql_instansi  = " AND a.id_instansi='$instansi' ";
		}
		else
		{
			$sql_instansi  = " ";
		}
		
		if($searchby   == 1)
		{
			$sql_nip  = " AND (a.PNS_PNSNIP='$search' OR a.PNS_NIPBARU='$search') ";
		}
		else
		{
			$sql_nip  = " ";
		}
		
		
		
		$sql="SELECT a.PNS_PNSNAM nama,b.*,c.nama_dokumen from (SELECT a.* FROM mirror.pupns a
		where 1=1 $sql_nip  ) a
		LEFT JOIN upload_taspen b ON (b.nip_baru = a.PNS_NIPBARU OR b.nip_lama = a.PNS_PNSNIP)
		LEFT JOIN dokumen c ON b.id_dokumen = c.id_dokumen
		 ORDER BY c.nama_dokumen ASC";	
		
				
		return $this->db->query($sql);
		
	}	
	
}