<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Upload_model extends CI_Model {

	private     $rawName;
	private     $table  = 'upload_dokumen';
	private     $dokumen= 'dokumen';
	private     $tableinstansi= 'mirror.instansi';
	private     $tablepupns   ='mirror.pupns';
	private     $app_user   ='app_user';
		
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
                $data['nip']    = $value;
            }
            else
            {
			    $data['minor_dok']    = $value;
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
			$data['pesan']		= "Dokumen Kepegawaian Berhasil Tersimpan";
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
		
		$query = $this->db->query("SELECT * FROM (SELECT *,locate(nama_dokumen,'$find') result from dokumen ) a
 WHERE a.result = 1 AND a.aktif IS NOT NULL "); 	
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
		
	    $query = $this->db->query("SELECT * FROM (SELECT *,locate(nama_dokumen,'$find') result from dokumen ) a
 WHERE a.result = 1 AND a.aktif IS NOT NULL"); 
		if($query->num_rows() > 0){
		    $r 		= TRUE;
		}
		
		return $r;
	}
	
	function isMinorValid($file)
	{
		$raw_file  		= str_replace('.pdf', '', $file);
		$format_file 	= explode("_",$raw_file);
		$arr1			= array('KODE','TAHUN');
		
		$r = FALSE;
		
		$result = array_intersect($format_file,$arr1);
		if(count($result) == 0)
		{
			$r = TRUE;
		}
		
		return $r;
	}	
	
	
	
	function isSesuaiFormat($file)
	{
		$r = FALSE;
		$raw_file  		= str_replace('.pdf', '', $file);
		$format_file 	= explode("_",$raw_file);
		
		$sql="SELECT panjang FROM (SELECT *,locate(nama_dokumen,'$file') result from dokumen ) a
 WHERE a.result = 1 AND a.aktif IS NOT NULL";
		$row = $this->db->query($sql)->row();
		if(count($format_file) === intval($row->panjang))
		{
		   $r = TRUE;
		}
		
		return $r;
		
	}	
		
	
	function isAllowSize($file)
	{
		$file_name  = $file['name'];
		$file_size  = $file['size'];
		
		$query = $this->db->query("SELECT * FROM (SELECT *,locate(nama_dokumen,'$file_name') result from dokumen ) a
 WHERE a.result = 1 AND a.aktif IS NOT NULL"); 
		
		if($query->num_rows() > 0){
		    
			$row 			= $query->row();
			$file_size      = round($file_size/1024, 2);
			
			if ($file_size > $row->file_size)
			{
				$data['pesan']  		= " File Dokumen Jenis ".$row->nama_dokumen." Hanya diizinkan Maksimal ".round($row->file_size/1024)." MB";
				$data['response'] 		= FALSE;
			}
			else
			{
				$data ['pesan']     = " File diizinkan";
   				$data ['response']  = TRUE;
			}
		}
		else
		{
			$data ['pesan']     = " File bukan arsip kepegawaian yang disyaratkan";
   			$data ['response']  = FALSE;
		}
		
		return $data;
	}	
	
	function isAdaNIP($string)
	{
	    $number = $this->_extract_numbers($string);
		$cek  = 0;
		foreach($number as $value){
		    if (strlen($value) == 18){
                $cek |= TRUE;
            }
            else
            {
			    $cek |= FALSE;
            }		
	    }   
		
		return boolval($cek);
	}
	
	function _extract_numbers($string)
	{
	    preg_match_all('/([\d]+)/', $string, $match);
	   
	    

	   return $match[0];
	}
	
	public function getInstansi()
	{
	    $instansi  = $this->session->userdata('session_instansi');
		if($instansi  != 4011)
		{
           $sql_instansi= " AND INS_KODINS='$instansi' ";
        }
		else
		{
             $sql_instansi=" ";
		}
		
		$sql="SELECT * FROM $this->tableinstansi where 1=1 $sql_instansi ";	
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
        d.nama_dokumen,
        e.first_name name		
		FROM $this->table a  
		LEFT JOIN $this->tableinstansi b ON a.id_instansi = b.INS_KODINS
		LEFT JOIN $this->tablepupns c ON a.nip = c.PNS_NIPBARU
		LEFT JOIN $this->dokumen d ON a.id_dokumen = d.id_dokumen
		LEFT JOIN $this->app_user e ON a.upload_by  = e.user_id
		WHERE 1=1   $sql_instansi $sql_nip $sql_jenis 
		ORDER BY d.nama_dokumen ASC";	
		
		
		return $this->db->query($sql);
		
	}	
	
	function getFormatDokumen()
	{
		$sql=" SELECT * FROM $this->dokumen WHERE aktif='1' ORDER BY nama_dokumen ASC";
		return $this->db->query($sql);
	}	
	
	/*TASPEN*/
	public function getDaftarTaspen($data)
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
		FROM upload_dokumen_taspen a
		LEFT JOIN dokumen_taspen  b ON a.id_dokumen = b.id_dokumen		
		LEFT JOIN app_user c ON a.upload_by = c.user_id
		LEFT JOIN usul_taspen d ON a.nip = d.nip
		WHERE 1=1 $sql_nip  
		GROUP BY a.id_dokumen
		ORDER BY b.keterangan ASC";	
		
				
		return $this->db->query($sql);
		
	}	
	
}