<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Berkas_model extends CI_Model {

	private     $rawName;
	private     $table    		= 'upload_dokumen';
	private     $tablenom 		= 'nominatif';
	private     $tablepupns 	= 'mirror.pupns';
	private     $tableagenda 	= 'agenda';
	private     $tabledokumen	= 'dokumen';
	private     $tablelayanan	= 'layanan';
	private     $tableinstansi	= 'mirror.instansi';
	private     $tableuser		= 'app_user';
	private     $tablesyarat 	= 'syarat_layanan';
	private     $tabletahapan 	= 'tahapan';
		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	
	
	public function getUsulDokumen($search)
	{		
	    $searchby  = $search['searchby'];
		$search    = $search['search'];
		
	
		$instansi  = $this->input->post('instansi');
		
		switch($searchby){
            case 1:
			    $sql = " AND a.nip = trim('$search') ";
            break;
            case 2:
			   $sql = " AND  UPPER(f.INS_NAMINS) LIKE UPPER('%$search%') ";
            break;
			case 3:
			   $sql = " AND  UPPER(trim(b.agenda_nousul))=UPPER(trim('$search')) ";
            break;
			case 4:
			   $sql = " AND  UPPER(c.layanan_nama) LIKE UPPER('%$search%') ";
            break;
            default:
                $sql = " AND a.nip = '999999999' ";		
		}		
      
		$q="SELECT a.agenda_id,a.nip,a.nomi_locked,a.nomi_status,
CASE a.nomi_status
    WHEN 'ACC' THEN 'badge bg-green'
    WHEN 'TMS' THEN 'badge bg-red'
	WHEN 'BTL' THEN 'badge bg-yellow'
    ELSE 'badge bg-light-blue'
END AS bg,a.nomi_verifby,a.update_date,
b.layanan_id,b.agenda_ins,b.agenda_nousul,b.agenda_timestamp,b.agenda_dokumen,b.agenda_status,
c.layanan_nama, f.INS_NAMINS instansi, g.PNS_PNSNAM nama,
group_concat(d.dokumen_id SEPARATOR ',') dokumen_id , 
group_concat(e.nama_dokumen SEPARATOR ',') nama_dokumen,
GROUP_CONCAT(IF(e.flag = 1,e.nama_dokumen, NULL) SEPARATOR ',')  main_dokumen,
GROUP_CONCAT(h.id_dokumen SEPARATOR ',')  upload_dokumen_id,
GROUP_CONCAT(i.nama_dokumen SEPARATOR ',')  upload_dokumen,
GROUP_CONCAT(IF(i.flag = 1,h.file_name, NULL) SEPARATOR ',')  main_upload_dokumen,
j.last_name ln_work,
k.tahapan_nama,
l.last_name  ln_locked
FROM nominatif a
LEFT JOIN $this->tableagenda b ON a.agenda_id = b.agenda_id
LEFT JOIN $this->tablelayanan c ON b.layanan_id = c.layanan_id
LEFT JOIN $this->tablesyarat d ON d.layanan_id = c.layanan_id
LEFT JOIN $this->tabledokumen e ON d.dokumen_id = e.id_dokumen
LEFT JOIN $this->tableinstansi f ON b.agenda_ins = f.INS_KODINS 
LEFT JOIN $this->tablepupns g ON g.PNS_NIPBARU = a.nip
LEFT JOIN $this->table h ON (a.nip = h.nip AND d.dokumen_id = h.id_dokumen)
LEFT JOIN $this->tabledokumen i ON  i.id_dokumen = h.id_dokumen
LEFT JOIN $this->tableuser j ON j.user_id = a.work_by
LEFT JOIN $this->tabletahapan k ON k.tahapan_id = a.tahapan_id
LEFT JOIN $this->tableuser l ON l.user_id = a.locked_by
where 1=1 AND  f.INS_KODINS='$instansi'  $sql
GROUP BY a.nip,b.layanan_id
";
		//var_dump($q);exit;
		$query 		= $this->db->query($q);
		
        return      $query;		
    }	
	
	public function getInstansi()
	{
	    $instansi  = $this->session->userdata('session_instansi');
		
		$sql="SELECT * FROM $this->tableinstansi where INS_KODINS='$instansi' ";	
		return $this->db->query($sql);
		
	}	
}