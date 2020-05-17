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
AND b.tahapan_id IN('2','3') 
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
	
	public function setBtl()
	{
		// kirim BTL dari TU ke Instansi	
		$r					  = FALSE;
		$agenda_id			  = $this->input->post('agenda');
		$nip                  = $this->input->post('nip');		
		
		$set['nomi_status']   = 'BTL';
		$set['nomi_alasan']   = $this->input->post('alasan');
		$set['nomi_verifby']  = $this->session->userdata('user_id');
		$set['btl_from']	  = 3;
		$set['btl_tu_alasan'] = $this->input->post('alasan');
		$set['tahapan_id']    = 3;
		
		$this->db->set($set);	
        $this->db->set('verify_date','NOW()',FALSE);
		$this->db->set('btl_tu_date','NOW()',FALSE);
		$this->db->set('btl_counter','btl_counter+1',FALSE);		
		$this->db->where('agenda_id', $agenda_id);		
		$this->db->where('nip', $nip);	
		return $this->db->update($this->tablenominatif);	 
	}
	
	/*TASPEN*/
	public function getUsulDokumenTaspen()
	{		
		$layanan     = $this->input->post('layanan');		
		
		if(!empty($layanan))
		{
			$sql_layanan ="  AND  a.layanan_id='$layanan' ";			
		}
		else
		{
			$sql_layanan =" ";
		}	
		
		
				
		$q="SELECT a.*,
		b.layanan_nama,		
        c.tahapan_nama
       	FROM usul_taspen a 
		LEFT JOIN layanan b ON a.layanan_id = b.layanan_id
		LEFT JOIN tahapan c ON a.usul_tahapan_id = c.tahapan_id
		WHERE 1=1 AND a.usul_tahapan_id IN (2,3) $sql_layanan";
		
		$query 		= $this->db->query($q);
		
        return      $query;		
    }	
	
	public function getUploadDokumenTaspen($nip)
	{
		$sql=" SELECT a.*, b.nama_dokumen,b.keterangan FROM upload_dokumen_taspen a
		LEFT JOIN  dokumen_taspen b ON a.id_dokumen = b.id_dokumen
		WHERE  a.nip='$nip'	ORDER BY b.keterangan ASC";			
				
		return $this->db->query($sql);
	}	
	
	public function setKirimTaspen()
	{
		// kirim dari TU ke Teknis		
		$r					  = FALSE;
		$usul_id			  = $this->input->post('usul_id');
		$nip                  = $this->input->post('nip');
		$penerima			  = $this->input->post('penerima');
		
		$set['usul_tahapan_id']    = 4;	
		$set['usul_kirim_by']      = $this->session->userdata('user_id');
		$set['usul_work_by']       = $penerima;
		
        $this->db->trans_start();
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		
		$this->db->set($set);		
		$this->db->set('usul_kirim_date','NOW()',FALSE);
		$this->db->where('usul_id', $usul_id);		
		$this->db->where('nip', $nip);	
		
		if ($this->db->update('usul_taspen'))
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
	
	public function setKirimAllTaspen($data)
	{
		// kirim dari TU ke Teknis		
		$r					  = FALSE;
		$usul_id			  = $data['usul_id'];
		$nip                  = $data['nip'];
        $penerima			  = $data['penerima'];
 		
		
		$set['usul_tahapan_id']    = 4;	
		$set['usul_kirim_by']      = $this->session->userdata('user_id');
		$set['usul_work_by']       = $penerima;
		
        $this->db->trans_start();
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		
		$this->db->set($set);		
		$this->db->set('usul_kirim_date','NOW()',FALSE);
		$this->db->where('usul_id', $usul_id);		
		$this->db->where('nip', $nip);	
		
		if ($this->db->update('usul_taspen'))
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
	
	public function getPenerima()
	{
		$bidang  = $this->session->userdata('session_bidang');
		$sql="SELECT * FROM $this->tableuser WHERE id_bidang='$bidang' AND id_instansi='4011' ";	
		return $this->db->query($sql);
		
	}	
	
	public function getAgenda_byid($id_agenda,$nip)
	{    
		$sql="SELECT a.* , 
		b.layanan_grup, b.layanan_bidang, b.layanan_nama ,
		c.INS_NAMINS instansi,
		d.nip, d.nomi_status, d.nomi_alasan,d.status_level_satu, d.status_level_dua,d.status_level_tiga,
		e.PNS_PNSNAM,e.PNS_GLRDPN, e.PNS_GLRBLK,
		f.tahapan_nama
		FROM $this->tableagenda a
		LEFT JOIN $this->tablelayanan  b ON a.layanan_id = b.layanan_id
		LEFT JOIN $this->tableinstansi c ON a.agenda_ins = c.INS_KODINS
		LEFT JOIN $this->tablenominatif d ON a.agenda_id  = d.agenda_id
		LEFT JOIN $this->tablepupns e ON e.PNS_NIPBARU = d.nip
		LEFT JOIN $this->tabletahapan f ON f.tahapan_id = d.tahapan_id
		WHERE a.agenda_id='$id_agenda' AND d.nip='$nip' ";
    	return $this->db->query($sql);
	}
	
	function getTelegramAkun_byInstansi($instansi)
	{	
		$this->db->select('first_name,last_name,telegram_id');
		$this->db->where('id_instansi', $instansi);
		return $this->db->get($this->tableuser);		
	}	
}