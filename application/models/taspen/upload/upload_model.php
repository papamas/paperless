<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Upload_model extends CI_Model {

	private     $rawName;
	private     $table  			= 'upload_dokumen_taspen';
	private     $dokumen  			= 'dokumen_taspen';
	private     $appuser  			= 'app_user';
	private     $usul  			    = 'usul_taspen';
	
		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	public function insertUpload($data)
	{
		$data['id_dokumen']		= $this->input->post('jenis');
		$data['nip']            = $this->input->post('nip');
		$data['upload_by']      = $this->session->userdata('user_id');
		$number 				= $this->_extract_numbers($data['raw_name']);
			
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
		$this->db->set('flag_update',1);
		$this->db->set('update_date','NOW()',FALSE);
		return $this->db->update($this->table);
		
	}	
			
	function _extract_numbers($string)
	{
	    preg_match_all('/([\d]+)/', $string, $match);
	    return $match[0];
	}
			
	
	public function getDaftar($data)
	{
	   	$searchby		= $data['searchby'];
		$search			= $data['search'];
			
		if($searchby   == 1)
		{
			$sql_nip  = " AND a.nip='$search'";
		}
		else
		{
			$sql_nip  = " ";
		}
		
		
		
		$sql=" SELECT a.*, 
		b.nama_dokumen,b.keterangan,
		c.first_name upload_name,
		d.nama_pns
		FROM $this->table a
		LEFT JOIN $this->dokumen b ON a.id_dokumen = b.id_dokumen		
		LEFT JOIN $this->appuser c ON a.upload_by = c.user_id
		LEFT JOIN $this->usul d ON a.nip = d.nip
		WHERE 1=1 $sql_nip  
		GROUP BY a.id_dokumen
		ORDER BY b.keterangan ASC";	
		
				
		return $this->db->query($sql);
		
	}	
	
	public function getDokumen()
	{
	    	
		$sql="SELECT * FROM $this->dokumen WHERE aktif IS NOT NULL ORDER BY keterangan ASC";	
		return $this->db->query($sql);
		
	}
	
}