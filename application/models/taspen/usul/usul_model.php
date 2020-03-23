<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Usul_model extends CI_Model {

	private     $layanan    		= 'layanan';
	private     $dokumen    		= 'dokumen_taspen';
	private     $usul    		    = 'usul_taspen';
	private     $upload             = 'upload_dokumen_taspen';
	private     $tablepupns 	    = 'mirror.pupns';
	private     $golongan			= 'mirror.golru';

		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	public function getLayanan()
	{
	    	
		$sql="SELECT * FROM $this->layanan where layanan_bidang='2' and layanan_id IN (15,16,17) ";	
		return $this->db->query($sql);
		
	}	
	
	public function getDokumen()
	{
	    	
		$sql="SELECT * FROM $this->dokumen ORDER BY keterangan ASC";	
		return $this->db->query($sql);
		
	}

    public function getUpload()
	{
	    	
		$sql="SELECT nip FROM $this->upload GROUP by nip";	
		return $this->db->query($sql);
		
	}	
	
	public function getGolongan()
	{
	    	
		$sql="SELECT * FROM $this->golongan";	
		return $this->db->query($sql);
		
	}	
	
	public function saveUsul($data)
	{
		$data['usul_tahapan_id']   = 1;	
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
			
		if (!$this->db->insert($this->usul, $data))
		{
			$error = $this->db->_error_message();
			if(!empty($error))
			{
                $data['pesan']		= $error;   
				$data['response'] 	= FALSE;
			}
            	
        }
		else
		{
			$data['pesan']		= "Usul Berhasil Tersimpan";
			$data['response']	= TRUE;
		}	
        $this->db->db_debug = $db_debug; //restore setting	

        return $data;		
	}	
	
	public function updateUsul($data)
	{
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 	
		
		$this->db->where('usul_id',$data['usul_id']);	
		if (!$this->db->update($this->usul, $data))
		{
			$error = $this->db->_error_message();
			if(!empty($error))
			{
                $data['pesan']		= $error;   
				$data['response'] 	= FALSE;
			}
            	
        }
		else
		{
			$data['pesan']		= "Usul Berhasil Terupdate";
			$data['response']	= TRUE;
		}	
        $this->db->db_debug = $db_debug; //restore setting	

        return $data;		
	}	
	
	public function getUsul()
	{
		$find			= trim($this->input->post('find'));
		
		if(!empty($find))
		{
			$sql_find  =" AND (a.nip='$find' OR a.nomor_usul='$find')  ";
		}
		else
		{
           $sql_find  =" ";
		}
		
		$sql="SELECT a.*,DATE_FORMAT(a.tgl_usul,'%d-%m-%Y') tgl, 
		DATE_FORMAT(a.tgl_perkawinan,'%d-%m-%Y') perkawinan,
		DATE_FORMAT(a.meninggal_dunia,'%d-%m-%Y') meninggal,
		DATE_FORMAT(a.pensiun_tmt,'%d-%m-%Y') pensiun,
		b.layanan_nama,
		c.PNS_NIPBARU nip_baru, c.PNS_PNSNIP nip_lama
		FROM $this->usul a
		LEFT JOIN $this->layanan b ON a.layanan_id = b.layanan_id	
		LEFT JOIN $this->tablepupns c ON (a.nip = c.PNS_NIPBARU OR a.nip = c.PNS_PNSNIP)
        WHERE 1=1 AND a.kirim_bkn IS NULL $sql_find		
		ORDER by created_date DESC
		LIMIT 50";	
		return $this->db->query($sql);
	}	
	
	public function setKirim()
	{
		// kirim dari TU ke Teknis		
		$r					  = FALSE;
		$usul_id			  = $this->input->post('usul_id');
		$nip                  = $this->input->post('usul_nip');		
		
		$set['usul_tahapan_id']   = 2;	
		$set['kirim_bkn_by']      = $this->session->userdata('user_id');
		$set['kirim_bkn']   	  = 1;
		
        $this->db->trans_start();
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		
		$this->db->set($set);		
		$this->db->set('kirim_bkn_date','NOW()',FALSE);
		$this->db->where('usul_id', $usul_id);		
		$this->db->where('nip', $nip);	
		
		if ($this->db->update($this->usul))
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
	
	public function getUploadDokumen($nip)
	{
		$sql=" SELECT a.*, b.nama_dokumen,b.keterangan FROM $this->upload a
		LEFT JOIN $this->dokumen b ON a.id_dokumen = b.id_dokumen
		WHERE  a.nip='$nip'	ORDER BY b.keterangan ASC";			
				
		return $this->db->query($sql);
	}	
}