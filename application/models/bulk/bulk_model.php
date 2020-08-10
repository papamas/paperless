<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bulk_model extends CI_Model {

	private     $rawName;
	private     $table  		= 'upload_photo';
	private     $dokumen		= 'dokumen';
	private     $tableinstansi	= 'mirror.instansi';
	private     $pupns        	= 'mirror.pupns';
	private     $layanan      	= 'layanan';
	private     $tableagenda 	= 'agenda';
	private     $tablenom 		= 'nominatif';
		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	
	public function getPhoto()	{
	   
		$instansi		= $this->input->post('instansi');
		$layanan		= $this->input->post('layanan');
		$search 		= trim($this->input->post('search'));
		$searchby       = $this->input->post('searchby');
		
		if(!empty($instansi))
		{
			$sql_instansi ="  AND a.agenda_ins='$instansi' ";			
		}
		else
		{
			$sql_instansi =" ";
		}

		if(!empty($layanan))
		{
			$sql_layanan ="  AND  a.layanan_id='$layanan' ";			
		}
		else
		{
			$sql_layanan =" ";
		}	
		
		switch($searchby){
            case 1:
			   $search = trim($search);	
			   $sql = " AND  UPPER(trim(a.agenda_nousul))=UPPER('$search') ";
            break;
			default:
                $sql = " AND a.nip = '999999999' ";		
		}
		
		$sql="SELECT a.*, f.orig_name FROM $this->tableagenda a
		LEFT JOIN $this->tablenom b ON a.agenda_id = b.agenda_id  
		LEFT JOIN $this->layanan c  ON a.layanan_id = c.layanan_id
		LEFT JOIN $this->tableinstansi d ON a.agenda_ins = d.INS_KODINS
        LEFT JOIN $this->pupns e ON b.nip = e.PNS_NIPBARU
		LEFT JOIN $this->table f ON  (b.nip = f.nip AND f.layanan_id = a.layanan_id)
		WHERE 1=1
		-- b.nomi_status='ACC' 
		$sql_instansi $sql_layanan  $sql
    	ORDER BY e.PNS_PNSNAM ASC";
		//var_dump($sql);exit;
		
		return $this->db->query($sql);
		
	}	
	
	public function getInstansi()
	{
	   	
		$sql="SELECT * FROM $this->tableinstansi";	
		return $this->db->query($sql);
		
	}	
	
	public function getLayanan()
	{
		$bidang  = $this->session->userdata('session_bidang');
		$sql="SELECT * FROM $this->layanan WHERE status='1' 
		AND layanan_bidang='$bidang' AND layanan_id IN (9,10,11) 
		ORDER BY layanan_nama ASC  ";	
		return $this->db->query($sql);
		
	}	
	
	
}