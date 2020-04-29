<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Layanan_model extends CI_Model {

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
	private     $tableuserlayananrole = 'user_layanan_role';
	private     $tabletahapan         = 'tahapan';
	private     $tablegolru		      = 'mirror.golru';
	
		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	public function getAll()
	{
	    $bidang  = $this->session->userdata('session_bidang');
		$sql="SELECT a.jumlah_usul,b.agenda_jumlah,
b.agenda_nousul,b.agenda_timestamp,b.agenda_ins,b.agenda_dokumen,b.agenda_id,
c.layanan_nama,d.INS_NAMINS instansi
FROM  (select a.*, count(a.agenda_id) jumlah_usul 
FROM  $this->tablenominatif a
WHERE a.nomi_status='BELUM'
AND a.tahapan_id IN(2,3)
GROUP BY a.agenda_id
) a
LEFT JOIN $this->tableagenda b ON a.agenda_id = b.agenda_id
LEFT JOIN $this->tablelayanan c ON b.layanan_id = c.layanan_id
LEFT JOIN $this->tableinstansi d ON  b.agenda_ins = d.INS_KODINS
WHERE c.layanan_bidang='$bidang' 
ORDER BY b.agenda_timestamp DESC ";	

        $query = $this->db->query($sql);
        return $query;		
	}	
	
	
	public function getAllTaspen()
	{
		$sql="SELECT a.*,COUNT(a.usul_id) jumlah_usul,
		b.layanan_nama,		
        c.tahapan_nama
        FROM usul_taspen a 
		LEFT JOIN layanan b ON a.layanan_id = b.layanan_id
		LEFT JOIN tahapan c ON a.usul_tahapan_id = c.tahapan_id
		where 1=1 AND a.usul_tahapan_id IN(2,3)
		GROUP BY a.usul_id
		ORDER BY a.kirim_bkn_date DESC";
		$query = $this->db->query($sql);
        return $query;	
	}	
	
	public function getExcelTaspen($id)
	{
		$sql="SELECT a.*,COUNT(a.usul_id) jumlah_usul,
		b.layanan_nama,
		c.tahapan_nama,
        d.PNS_NIPBARU nip_baru, d.PNS_PNSNIP nip_lama		
		FROM usul_taspen a 
		LEFT JOIN layanan b ON a.layanan_id = b.layanan_id
		LEFT JOIN tahapan c ON a.usul_tahapan_id = c.tahapan_id
		LEFT JOIN mirror.pupns d ON (a.nip = d.PNS_NIPBARU OR a.nip = d.PNS_PNSNIP)
		WHERE usul_id='$id'
		GROUP BY a.usul_id";
		$query = $this->db->query($sql);
        return $query;	
	}	
	
	public function getExcel($id)
	{
	    $sql ="SELECT a.*, b.nip,b.nomi_status,
		c.layanan_nama,
		d.INS_NAMINS instansi,
		e.PNS_PNSNAM nama,
		f.tahapan_nama,f.tahapan_id,
		g.first_name work_name,
		h.first_name lock_name,
		i.first_name verif_name_satu,
		j.GOL_GOLNAM golongan,
		k.first_name verif_name_dua,
		l.first_name verif_name_tiga,
		m.first_name verif_name,
		n.first_name entry_proses_name,
		o.first_name entry_name
		FROM $this->tableagenda a 
LEFT JOIN $this->tablenominatif b ON a.agenda_id = b.agenda_id 
LEFT JOIN $this->tablelayanan c  ON a.layanan_id = c.layanan_id
LEFT JOIN $this->tableinstansi d ON a.agenda_ins = d.INS_KODINS
LEFT JOIN $this->tablepupns  e ON b.nip = e.PNS_NIPBARU
LEFT JOIN $this->tabletahapan f ON b.tahapan_id = f.tahapan_id
LEFT JOIN $this->tableuser g ON b.work_by  = g.user_id
LEFT JOIN $this->tableuser h ON h.user_id = b.locked_by
LEFT JOIN $this->tableuser i ON i.user_id = b.nomi_verifby
LEFT JOIN $this->tablegolru j ON e.PNS_GOLRU = j.GOL_KODGOL
LEFT JOIN $this->tableuser k ON k.user_id = b.verifby_level_dua
LEFT JOIN $this->tableuser l ON l.user_id = b.verifby_level_tiga
LEFT JOIN $this->tableuser m ON m.user_id = b.nomi_verifby
LEFT JOIN $this->tableuser n ON n.user_id = b.entry_proses_by
LEFT JOIN $this->tableuser o ON o.user_id = b.entry_by
where  a.agenda_id='$id'
AND b.tahapan_id IN(2,3)
";
	 
	 //var_dump($sql);
	 $query = $this->db->query($sql);
        return $query;		
	}	
}