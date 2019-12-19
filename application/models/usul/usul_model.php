<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Usul_model extends CI_Model {

	private     $rawName;
	private     $table  	  		= 'upload_dokumen';
	private     $tableagenda  		= 'agenda';
	private     $tablenominatif 	= 'nominatif';
	private     $tableinstansi   	= 'mirror.instansi';
	private     $tablepupns   		= 'mirror.pupns';
	private     $tabledokumen      	= 'dokumen';
	private     $tableuser      	= 'app_user';
	private     $tablelayanan      	= 'layanan';
	private     $tablesyarat      	= 'syarat_layanan';
	
		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	public function getAll()
	{
	    $bidang  = $this->session->userdata('session_bidang');
		
		$query = $this->db->query("SELECT a.*,b.*,c.layanan_nama,
		d.INS_NAMINS instansi, count(b.agenda_id) jumlah_usul FROM $this->tableagenda a 
LEFT JOIN $this->tablenominatif b ON a.agenda_id = b.agenda_id 
LEFT JOIN $this->tablelayanan c  ON a.layanan_id = c.layanan_id
LEFT JOIN $this->tableinstansi d ON a.agenda_ins = d.INS_KODINS
WHERE c.layanan_bidang='$bidang' AND b.nomi_status='BELUM' AND a.agenda_status='dikirim'
GROUP BY a.agenda_id ");
        return $query;		
	}	
	
	public function getExcel($id)
	{
	    $sql ="SELECT a.* FROM (SELECT a.*,c.PNS_PNSNAM nama,
d.nomi_status, d.nomi_locked,d.locked_by,d.nomi_verifby,
e.*, f.INS_NAMINS instansi, g.last_name,h.layanan_nama,
group_concat(j.nama_dokumen SEPARATOR ',') syarat_terpenuhi,
group_concat(i.dokumen_id SEPARATOR ',') syarat,
group_concat(b.nama_dokumen SEPARATOR ',') berkas FROM upload_dokumen a 
LEFT JOIN $this->tabledokumen b ON a.id_dokumen = b.id_dokumen 
LEFT JOIN $this->tablepupns c ON c.PNS_NIPBARU = a.nip 
LEFT JOIN $this->tablenominatif d ON d.nip = a.nip 
LEFT JOIN $this->tableagenda e ON d.agenda_id = e.agenda_id 
LEFT JOIN $this->tableinstansi  f ON f.INS_KODINS = e.agenda_ins 
LEFT JOIN $this->tableuser g ON d.locked_by = g.user_id
LEFT JOIN $this->tablelayanan h ON e.layanan_id = h.layanan_id
LEFT JOIN $this->tablesyarat i ON (i.layanan_id = e.layanan_id and a.id_dokumen = i.dokumen_id) 
LEFT JOIN $this->tabledokumen j ON j.id_dokumen = i.dokumen_id
where  d.agenda_id='$id'
GROUP BY a.nip 
) a";
	 
	 //var_dump($sql);
	 $query = $this->db->query($sql);
        return $query;		
	}	
}