<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Photo_model extends CI_Model {

	private     $rawName;
	private     $table  = 'upload_photo';
	private     $dokumen= 'dokumen';
	private     $tableinstansi= 'mirror.instansi';
	private     $pupns        = 'mirror.pupns';
	private     $layanan      = 'layanan';
		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	public function insertUpload($data)
	{
		$data['id_instansi']    = $this->session->userdata('session_instansi');
		$data['upload_by']      = $this->session->userdata('user_id');
		$number 				= $this->_extract_numbers($data['raw_name']);
		
		foreach($number as $value){
		    if (strlen($value) == 18){
                $data['nip']    = $value;
            }            	
	    }  


		$haystack = $data['raw_name'];
		

        if( stripos( $haystack, "KARIS" ) !== false) {
			$data['layanan_id']    = 9;
		}		
		
		if( stripos( $haystack, "KARSU" ) !== false) {
			$data['layanan_id']    = 10;
		}	
		
		if( stripos( $haystack, "KARPEG" ) !== false) {
			$data['layanan_id']    = 11;
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
			$data['pesan']		= "Photo Berhasil Tersimpan";
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
	
	function isAllowFormat($haystack)
	{
		$r  = FALSE;
		
		if( stripos( $haystack, "KARIS" ) !== false) {
			$r = TRUE;
		}	
		
		if( stripos( $haystack, "KARSU" ) !== false) {
			$r = TRUE;
		}

		if( stripos( $haystack, "KARPEG" ) !== false) {
			$r = TRUE;
		}

		return $r;	
		
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
	
	function _is_photo($data)
	{
	    $r = FALSE;
		$number  = $this->_extract_numbers($data);
		foreach($number as $value){
		    if (strlen($value) == 18){
                $r = TRUE;
            }
            
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
	    $instansi 		= $data['instansi'];
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
			$sql_nip  = " AND a.nip='$search' ";
		}
		else
		{
			$sql_nip  = " ";
		}
		
		if($searchby   == 2)
		{
			$sql_jenis  = " AND UPPER(a.raw_name) LIKE UPPER('$search%') ";
		}
		else
		{
			$sql_jenis  = " ";
		}
		
		$sql="SELECT a.*, 
		b.INS_NAMINS instansi, 
		c.PNS_PNSNAM nama,
        d.layanan_nama       	
		FROM $this->table a  
		LEFT JOIN $this->tableinstansi b ON a.id_instansi = b.INS_KODINS
		LEFT JOIN $this->pupns c ON a.nip = c.PNS_NIPBARU
		LEFT JOIN $this->layanan d ON a.layanan_id = d.layanan_id
		WHERE 1=1   $sql_instansi $sql_nip $sql_jenis ";	
		
		
		return $this->db->query($sql);
		
	}	
	
}