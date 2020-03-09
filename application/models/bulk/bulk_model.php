<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bulk_model extends CI_Model {

	private     $rawName;
	private     $table  = 'upload_photo';
	private     $dokumen= 'dokumen';
	private     $tableinstansi= 'mirror.instansi';
	private     $pupns        = 'mirror.pupns';
	private     $layanan      = 'layanan';
	private     $tableagenda 	= 'agenda';
		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	
	public function getPhoto()	{
	   
		$instansi		= $this->input->post('instansi');
		$layanan		= $this->input->post('layanan');
		$search 		= $this->input->post('search');
		
		if(!empty($instansi))
		{
			$sql_instansi ="  AND b.agenda_ins='$instansi' ";			
		}
		else
		{
			$sql_instansi =" ";
		}

		if(!empty($layanan))
		{
			$sql_layanan ="  AND  a.layanan_id='$layanan' AND b.layanan_id='$layanan' ";			
		}
		else
		{
			$sql_layanan =" ";
		}	
		
		if(!empty($search))
		{
			$sql_usul ="  AND  b.agenda_nousul=trim('$search') ";			
		}
		else
		{
			$sql_usul =" ";
		}	
		
		$sql="SELECT a.* FROM upload_photo  a 
		LEFT JOIN $this->tableagenda b ON a.layanan_id = b.layanan_id
		LEFT JOIN $this->pupns c ON a.nip = c.PNS_NIPBARU
		WHERE 1=1  $sql_instansi $sql_layanan $sql_usul
		ORDER BY c.PNS_PNSNAM ASC";	
		
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