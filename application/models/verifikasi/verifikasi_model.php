<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Verifikasi_model extends CI_Model {

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
		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	
	
	public function getUsulDokumen()
	{		
	    $instansi    = $this->input->post('instansi');
		$layanan     = $this->input->post('layanan');
		
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
		
		
		$bidang  = $this->session->userdata('session_bidang');
		
		$q="SELECT a.*,b.nip,c.layanan_nama,
		d.INS_NAMINS instansi ,
		e.PNS_PNSNAM nama
		FROM $this->tableagenda a 
LEFT JOIN $this->tablenominatif b ON a.agenda_id = b.agenda_id 
LEFT JOIN $this->tablelayanan c  ON a.layanan_id = c.layanan_id
LEFT JOIN $this->tableinstansi d ON a.agenda_ins = d.INS_KODINS
LEFT JOIN $this->tablepupns e ON b.nip = e.PNS_NIPBARU
WHERE c.layanan_bidang='$bidang' 
AND b.nomi_status='BELUM' 
AND a.agenda_status='dikirim'
AND b.tahapan_id='2' 
$sql_instansi  $sql_layanan";
		
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
		$sql="SELECT * FROM $this->tablelayanan WHERE status='1' AND layanan_bidang='$bidang' ORDER BY layanan_nama ASC ";	
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