<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Distribusi_model extends CI_Model {

	private     $rawName;
	private     $table    		= 'upload_dokumen';
	private     $tablenominatif = 'nominatif';
	private     $tablepupns 	= 'mirror.pupns';
	private     $tableagenda 	= 'agenda';
	private     $tabledokumen	= 'dokumen';
	private     $tablelayanan	= 'layanan';
	private     $tableinstansi	= 'mirror.instansi';
	private     $tablegolru		= 'mirror.golru';
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
	    $instansi     = $this->input->post('instansi');
		$layanan      = $this->input->post('layanan');
		$golongan     = $this->input->post('golongan');
		$nousul       = trim($this->input->post('nousul'));
		
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
		
		if(!empty($golongan))
		{
			$sql_golongan ="  AND  e.PNS_GOLRU='$golongan' ";			
		}
		else
		{
			$sql_golongan =" ";
		}	
		
		
		
		if(!empty($nousul))
		{
			$sql_nousul ="  AND  a.agenda_nousul='$nousul'";			
		}
		else
		{
			$sql_nousul =" ";
		}	
		
		
		$bidang  = $this->session->userdata('session_bidang');
		
		$q="SELECT a.*,b.nip,c.layanan_nama,
		d.INS_NAMINS instansi ,
		e.PNS_PNSNAM nama,
		f.GOL_GOLNAM golongan
		FROM $this->tableagenda a 
LEFT JOIN $this->tablenominatif b ON a.agenda_id = b.agenda_id 
LEFT JOIN $this->tablelayanan c  ON a.layanan_id = c.layanan_id
LEFT JOIN $this->tableinstansi d ON a.agenda_ins = d.INS_KODINS
LEFT JOIN $this->tablepupns e ON b.nip = e.PNS_NIPBARU
LEFT JOIN $this->tablegolru f ON e.PNS_GOLRU = f.GOL_KODGOL
WHERE c.layanan_bidang='$bidang' 
AND b.nomi_status='BELUM' 
AND a.agenda_status='dikirim'
AND b.tahapan_id='2' 
$sql_instansi  $sql_layanan  $sql_golongan   $sql_nousul ";
		
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
		$sql="SELECT * FROM $this->tablelayanan WHERE status='1' AND layanan_bidang='$bidang' ORDER BY layanan_nama ASC  ";	
		return $this->db->query($sql);
		
	}	
	
	public function getPenerima()
	{
		$bidang  = $this->session->userdata('session_bidang');
		$sql="SELECT * FROM $this->tableuser WHERE id_bidang='$bidang' AND id_instansi='4011' ";	
		return $this->db->query($sql);
		
	}	
	
	public function getGolongan()
	{
		$sql="SELECT * FROM $this->tablegolru ";	
		return $this->db->query($sql);
		
	}	
	
	public function setKirim()
	{
		// kirim dari TU ke Teknis		
		$r					  = FALSE;
		$agenda_id			  = $this->input->post('agenda');
		$nip                  = $this->input->post('nip');
        $penerima             = $this->input->post('penerima');		
		
		$set['tahapan_id']    = 4;	
		$set['kirim_by']      = $this->session->userdata('user_id');
		$set['work_by']       = $penerima;	
		
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
        $penerima             = $data['penerima'];		
		
		$set['tahapan_id']    = 4;	
		$set['kirim_by']      = $this->session->userdata('user_id');
		$set['work_by']       = $penerima;
		
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
	
	function getTelegramAkun_byPenerima($id)
	{	
		$this->db->select('first_name,last_name,telegram_id');
		$this->db->where('active', 1);
		$this->db->where('user_id', $id);
		return $this->db->get($this->tableuser);		
	}	
	
	function getAgendaData($id)
	{
		$sql="SELECT a.agenda_ins,a.layanan_id,
		a.agenda_nousul, a.agenda_jumlah,
		b.INS_NAMINS instansi,
		c.layanan_nama
		FROM $this->tableagenda a 
		LEFT JOIN $this->tableinstansi b ON a.agenda_ins  = b.INS_KODINS
		LEFT JOIN $this->tablelayanan c ON a.layanan_id = c.layanan_id
		WHERE a.agenda_id='$id' ";
		return $this->db->query($sql);
	}	
}