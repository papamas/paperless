<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lacak_model extends CI_Model {

	private     $rawName;
	private     $table    		= 'upload_dokumen';
	private     $tablenominatif = 'nominatif';
	private     $tablepupns 	= 'mirror.pupns';
	private     $tableagenda 	= 'agenda';
	private     $tabledokumen	= 'dokumen';
	private     $tablelayanan	= 'layanan';
	private     $tableinstansi	= 'mirror.instansi';
	private     $tableuser		= 'app_user';
	private     $tablesyarat 	= 'syarat_layanan';
	private     $tabletahapan 	= 'tahapan';
	private     $tablegolru		= 'mirror.golru';
		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	
	
	public function getUsulDokumen()
	{		
	    $searchby    = $this->input->post('searchby');
	    $search      = $this->input->post('search');
		
		switch($searchby){
            case 1:
			    $sql = " AND b.nip = '$search' ";
            break;
            case 2:
			   $sql = " AND  UPPER(d.INS_NAMINS) LIKE UPPER('%$search%') ";
            break;
			case 3:
			   $search = trim($search);			   
			   $sql = " AND  UPPER(a.agenda_nousul)=UPPER('$search')";
            break;
			case 4:
			   $sql = " AND  UPPER(c.layanan_nama) LIKE UPPER('%$search%') ";
            break;
			case 5:
			   $sql = " AND  UPPER(e.PNS_PNSNAM) LIKE UPPER('%$search%') ";
            break;
            default:
                $sql = " AND a.nip = '999999999' ";		
		}		
		
		
		$bidang  = $this->session->userdata('session_bidang');
		
		$q="SELECT a.*,b.nip,b.tahapan_id,b.nomi_status,
		CASE b.nomi_status
			WHEN 'ACC' THEN 'badge bg-green'
			WHEN 'TMS' THEN 'badge bg-red'
			WHEN 'BTL' THEN 'badge bg-yellow'
			ELSE 'badge bg-light-blue'
		END AS bg,
		c.layanan_nama,
		d.INS_NAMINS instansi ,
		e.PNS_PNSNAM nama,
		f.tahapan_nama,
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
LEFT JOIN $this->tablepupns e ON b.nip = e.PNS_NIPBARU
LEFT JOIN $this->tabletahapan f ON b.tahapan_id = f.tahapan_id
LEFT JOIN $this->tableuser g ON g.user_id = b.work_by
LEFT JOIN $this->tableuser h ON h.user_id = b.locked_by
LEFT JOIN $this->tableuser i ON i.user_id = b.verifby_level_satu
LEFT JOIN $this->tablegolru j ON e.PNS_GOLRU = j.GOL_KODGOL
LEFT JOIN $this->tableuser k ON k.user_id = b.verifby_level_dua
LEFT JOIN $this->tableuser l ON l.user_id = b.verifby_level_tiga
LEFT JOIN $this->tableuser m ON m.user_id = b.nomi_verifby
LEFT JOIN $this->tableuser n ON n.user_id = b.entry_proses_by
LEFT JOIN $this->tableuser o ON o.user_id = b.entry_by
WHERE c.layanan_bidang='$bidang' 
$sql";
		
		$query 		= $this->db->query($q);
		
        return      $query;		
    }	
	
	public function getUploadDokumen($nip)
	{
		$sql="SELECT a.*, b.nama_dokumen 
		FROM $this->table a
		LEFT JOIN $this->tabledokumen b ON a.id_dokumen = b.id_dokumen
		WHERE a.nip='$nip' ";
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
		$sql="SELECT * FROM $this->tablelayanan WHERE layanan_bidang='$bidang' ";	
		return $this->db->query($sql);
		
	}	
	
	public function setKirim()
	{
		// kirim dari TU ke Teknis		
		$r					  = FALSE;
		$agenda_id			  = $this->input->post('agenda');
		$nip                  = $this->input->post('nip');		
		
		$set['tahapan_id']    = 4;	
		$set['kirim_by']      = $this->session->userdata('user_id');
		
        $this->db->trans_start();
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		
		$this->db->set($set);		
		$this->db->set('kirim_date','NOW()',FALSE);
		$this->db->where('agenda_id', $agenda_id);		
		$this->db->where('nip', $nip);	
		
		if ($this->db->update($this->tablenominatif))
		{
			$error = $this->db->_error_message(); 
			if(!empty($error))
			{
				$r = FALSE;
			}
			else
			{
				$r = TRUE;
			}     
        }
        $this->db->db_debug = $db_debug; //restore setting			
		$this->db->trans_complete();
		
		return $r;
	}
	
	public function setKirimAll($data)
	{
		// kirim dari TU ke Teknis		
		$r					  = FALSE;
		$agenda_id			  = $data['agenda'];
		$nip                  = $data['nip'];		
		
		$set['tahapan_id']    = 4;	
		$set['kirim_by']      = $this->session->userdata('user_id');
		
        $this->db->trans_start();
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		
		$this->db->set($set);		
		$this->db->set('kirim_date','NOW()',FALSE);
		$this->db->where('agenda_id', $agenda_id);		
		$this->db->where('nip', $nip);	
		
		if ($this->db->update($this->tablenominatif))
		{
			$error = $this->db->_error_message(); 
			if(!empty($error))
			{
				$r = FALSE;
			}
			else
			{
				$r = TRUE;
			}     
        }
        $this->db->db_debug = $db_debug; //restore setting			
		$this->db->trans_complete();
		
		return $r;
	}
}