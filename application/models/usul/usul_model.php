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
	   /*$sql     = "SELECT a.*,c.layanan_nama,
		d.INS_NAMINS instansi, count(b.agenda_id) jumlah_usul FROM $this->tableagenda a 
LEFT JOIN $this->tablenominatif b ON a.agenda_id = b.agenda_id 
LEFT JOIN $this->tablelayanan c  ON a.layanan_id = c.layanan_id
LEFT JOIN $this->tableinstansi d ON a.agenda_ins = d.INS_KODINS
WHERE c.layanan_bidang='$bidang' AND b.nomi_status='BELUM' AND a.agenda_status='dikirim'
GROUP BY a.agenda_id ";*/
       
	    $bidang  			 = $this->session->userdata('session_bidang');
		$tipe    			 = $this->session->userdata('session_user_tipe');
		$user_id             = $this->session->userdata('user_id');
        
		// id 1 = bidang mutasi
		
		if($bidang == 1)
		{
		   	// tipe 2 = kabid , tipe 3 kanreg
			if($tipe  == 2 || $tipe == 3)
			{	
				$sql_work =" ";
			}
			else
			{
				$sql_work =" AND b.work_by = '$user_id'  "; 
			}
		}
        else
        {
            $sql_work =" ";
        }	
		
        $sql="SELECT * FROM (SELECT a.*, b.user_id, b.id_instansi FROM (SELECT 
    a.*,
    c.layanan_nama,
    d.INS_NAMINS instansi,
    COUNT(b.agenda_id) jumlah_usul
FROM
    agenda a
        LEFT JOIN
    nominatif b ON a.agenda_id = b.agenda_id
        LEFT JOIN
    layanan c ON a.layanan_id = c.layanan_id
        LEFT JOIN
    mirror.instansi d ON a.agenda_ins = d.INS_KODINS
WHERE
    c.layanan_bidang = '$bidang'
        AND b.nomi_status = 'BELUM'
        AND a.agenda_status = 'dikirim'
		AND b.tahapan_id IN ('4','5','6','7','8','9','10','11','12')
	    $sql_work
GROUP BY a.agenda_id
) a
LEFT JOIN $this->tableuserlayananrole  b ON (a.layanan_id=b.layanan_id AND a.agenda_ins = b.id_instansi AND b.user_id='$user_id')
) a 
WHERE id_instansi IS NOT NULL";

     // var_dump($sql);

        $query = $this->db->query($sql);
        return $query;		
	}	
	
	public function getExcel($id)
	{
	    /* $sql ="SELECT a.* FROM (SELECT a.*,c.PNS_PNSNAM nama,
d.nomi_status, d.nomi_locked,d.locked_by,d.nomi_verifby,
e.*, f.INS_NAMINS instansi, g.first_name,h.layanan_nama,
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
) a"; */
	 $sql ="SELECT a.*, 
	    b.nip,b.nomi_status,
		b.status_level_satu,b.alasan_level_satu,b.verifdate_level_satu,
		b.status_level_dua,b.alasan_level_dua,b.verifdate_level_dua,
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
LEFT JOIN $this->tableuser i ON i.user_id = b.verifby_level_satu
LEFT JOIN $this->tablegolru j ON e.PNS_GOLRU = j.GOL_KODGOL
LEFT JOIN $this->tableuser k ON k.user_id = b.verifby_level_dua
LEFT JOIN $this->tableuser l ON l.user_id = b.verifby_level_tiga
LEFT JOIN $this->tableuser m ON m.user_id = b.nomi_verifby
LEFT JOIN $this->tableuser n ON n.user_id = b.entry_proses_by
LEFT JOIN $this->tableuser o ON o.user_id = b.entry_by
where  a.agenda_id='$id'
AND b.nomi_status='BELUM' AND a.agenda_status='dikirim'
";
	 //var_dump($sql);
	 $query = $this->db->query($sql);
        return $query;		
	}	
}