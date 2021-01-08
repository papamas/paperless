<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Turunstatus_model extends CI_Model {

		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	
	
	
	public function getDaftar()
	{
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
			$sql_nomor_usul  = "  AND trim(a.agenda_nousul)=trim('$search')";
		}
		else
		{
			$sql_nomor_usul  = " ";
		}
		
		$sql="SELECT a.*, b.PNS_PNSNAM nama_pns FROM (
		SELECT a.*, b.INS_NAMINS nama_instansi, c.nip , c.nomi_alasan,
		c.tahapan_id, e.tahapan_nama, c.nomi_status, 
		c.nomi_verifby,DATE_FORMAT(c.verify_date,'%d-%m-%Y %H:%i:%S') verify_date, 
		d.first_name
		FROM paperless.agenda a
		LEFT JOIN mirror.instansi b ON a.agenda_ins = b.INS_KODINS
		LEFT JOIN paperless.nominatif c ON c.agenda_id = a.agenda_id
		lEFT JOIN paperless.app_user d ON d.user_id = c.nomi_verifby
		LEFT JOIN paperless.tahapan e ON e.tahapan_id = c.tahapan_id
		WHERE 1=1  AND  a.agenda_status='dikirim'  
		$sql_nomor_usul ) a
		LEFT JOIN mirror.pupns b ON a.nip  = b.PNS_NIPBARU
		WHERE 1=1  $sql_nip ";		
		
		return $this->db->query($sql);
		
	}	
	
	
	function getTahapan()
	{
		return $this->db->get('tahapan');
	}	
	
	function updateNominatif()
	{
		$all		 = $this->input->post('all');		
			
		$agenda      = $this->input->post('agendaId');
		$nip   		 = $this->input->post('nip');
		$status		 = $this->input->post('status');
		$tahapan	 = $this->input->post('tahapan');
		$alasan	 	 = $this->input->post('nomiAlasan');
		
		if($all  == 1)
		{
			$this->db->where('agenda_id',$agenda);			
		}
		else
		{			
			$this->db->where('agenda_id',$agenda);
			$this->db->where('nip',$nip);			
		}		
		
		if($status == 'BTL')
		{
			if($this->session->userdata('session_user_tipe') == 'TU')
			{	
				$this->db->set('btl_from',3);
				$this->db->set('btl_tu_date','NOW()',FALSE);
			    $this->db->set('btl_tu_alasan',$alasan);
			}
			else
			{
				$this->db->set('btl_from',4);
				$this->db->set('btl_teknis_date','NOW()',FALSE);
			    $this->db->set('btl_teknis_alasan',$alasan);
			}		
			
			$this->db->set('btl_counter','btl_counter+1',FALSE);
		}		
		
		$this->db->set('nomi_status',$status);
		$this->db->set('tahapan_id',$tahapan);
		$this->db->set('nomi_alasan',$alasan);
		$this->db->set('turun_by',$this->session->userdata('user_id'));
		$this->db->set('turun_date','NOW()',FALSE);
		return $this->db->update('nominatif');
	}
	
	
}