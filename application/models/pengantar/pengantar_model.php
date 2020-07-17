<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pengantar_model extends CI_Model {

		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
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
		
		$sql="SELECT * FROM mirror.instansi  where 1=1 $sql_instansi ";	
		return $this->db->query($sql);
		
	}	
	
	public function getDaftar()
	{
	    $instansi 		= $this->input->post('instansi');
		$searchby		= $this->input->post('searchby');
		$search			= $this->input->post('search');
		
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
			$sql_nomor_usul  = "  AND trim(a.agenda_nousul)=trim('$search') GROUP BY  a.agenda_nousul";
		}
		else
		{
			$sql_nomor_usul  = " ";
		}
		
		$sql="SELECT a.*, b.PNS_PNSNAM nama_pns FROM (SELECT a.*, b.INS_NAMINS nama_instansi, c.nip
		FROM paperless.agenda a
		LEFT JOIN mirror.instansi b ON a.agenda_ins = b.INS_KODINS
		LEFT JOIN paperless.nominatif c ON c.agenda_id = a.agenda_id
		WHERE 1=1  $sql_nomor_usul ) a
		LEFT JOIN mirror.pupns b ON a.nip  = b.PNS_NIPBARU
		WHERE 1=1  $sql_nip ";		
		
		return $this->db->query($sql);
		
	}	
	
	
	
}