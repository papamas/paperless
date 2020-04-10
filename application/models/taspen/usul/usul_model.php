<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Usul_model extends CI_Model {

	private     $layanan    		= 'layanan';
	private     $dokumen    		= 'dokumen_taspen';
	private     $usul    		    = 'usul_taspen';
	private     $upload             = 'upload_dokumen_taspen';
	private     $tablepupns 	    = 'mirror.pupns';
	private     $golongan			= 'mirror.golru';
	private     $tempistri			= 'mutasi_istri_suami';
	private     $tempanak			= 'mutasi_anak';
	private     $tahapan            = 'tahapan';
		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	public function getLayananSK()
	{
	    	
		$sql="SELECT * FROM $this->layanan where layanan_bidang='2' and layanan_id IN (16,17) ";	
		return $this->db->query($sql);
		
	}	
	
	public function getLayananMutasi()
	{
	    	
		$sql="SELECT * FROM $this->layanan where layanan_bidang='2' and layanan_id=15";	
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
	
	public function getUsul($layanan)
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
		
		switch($layanan){
			case 1:
			$sql_layanan  =" AND a.layanan_id  IN  (16,17)";
			break;
			case 2:
			$sql_layanan  =" AND a.layanan_id =15";
			break;
		}
		
		$sql="SELECT a.*,
		(select COUNT(*) from $this->tempistri WHERE usul_id=a.usul_id) jumlah_istri,
        (select COUNT(*) from $this->tempanak  WHERE usul_id=a.usul_id) jumlah_anak,
		DATE_FORMAT(a.tgl_usul,'%d-%m-%Y') tgl, 
		DATE_FORMAT(a.tgl_lahir,'%d-%m-%Y') atgl_lahir,
		DATE_FORMAT(a.tgl_skep,'%d-%m-%Y') atgl_skep,
		DATE_FORMAT(a.pensiun_tmt,'%d-%m-%Y') apensiun_tmt,
		DATE_FORMAT(a.tgl_perkawinan,'%d-%m-%Y') perkawinan,
		DATE_FORMAT(a.meninggal_dunia,'%d-%m-%Y') meninggal,
		DATE_FORMAT(a.pensiun_tmt,'%d-%m-%Y') pensiun,
		b.layanan_nama,
		c.PNS_NIPBARU nip_baru, c.PNS_PNSNIP nip_lama
		FROM $this->usul a
		LEFT JOIN $this->layanan b ON a.layanan_id = b.layanan_id	
		LEFT JOIN $this->tablepupns c ON (a.nip = c.PNS_NIPBARU OR a.nip = c.PNS_PNSNIP)
        WHERE 1=1 AND a.kirim_bkn IS NULL $sql_find	 $sql_layanan	
		ORDER by created_date DESC
		LIMIT 50 
		";	
		
		return $this->db->query($sql);
	}	
	
	public function setKirim()
	{
		// kirim dari TASPEN  ke TU		
		$r					  = FALSE;
		$usul_id			  = $this->input->post('usul_id');
		$nip                  = $this->input->post('usul_nip');		
		
		$set['usul_tahapan_id']   = 2;	
		$set['kirim_bkn_by']      = $this->session->userdata('user_id');
		$set['kirim_bkn']   	  = 1;
		
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
				return $r;
	}
	
	function getUsul_byid($usul_id,$nip)
	{
		$sql="SELECT a.usul_id, a.nomor_usul, a.tgl_usul, a.nama_pns, 
		a.nama_janda_duda,a.nip,a.nopen , a.usul_status,a.usul_alasan,
		b.tahapan_nama,
		c.layanan_id, c.layanan_nama, c.layanan_bidang
		FROM $this->usul a 
		LEFT JOIN $this->tahapan b ON a.usul_tahapan_id = b.tahapan_id
		LEFT JOIN $this->layanan c ON a.layanan_id = c.layanan_id
		WHERE a.usul_id ='$usul_id' AND a.nip='$nip' ";
		return $this->db->query($sql);
		
	}	
	
	function getTelegramAkun_bybidang()
	{	
		$this->db->select('first_name,last_name,telegram_id');
		$this->db->where('id_bidang',2);
		$this->db->where('id_instansi',4011);
		return $this->db->get('app_user');		
	}	
	
	public function getUploadDokumen($nip)
	{
		$sql=" SELECT a.*, 
		b.nama_dokumen,b.keterangan 
		FROM $this->upload a
		LEFT JOIN $this->dokumen b ON a.id_dokumen = b.id_dokumen
		WHERE  a.nip='$nip' AND aktif IS NOT NULL	ORDER BY b.keterangan ASC";			
				
		return $this->db->query($sql);
	}	
	
	
	public function simpanTempIstri()
	{
		$data['nama']				= $this->input->post('nama');
		$data['nama_kecil']			= $this->input->post('nama_kecil');
		$data['tempat_lahir']		= $this->input->post('tempat_lahir');
		$data['tgl_lahir']			= date('Y-m-d',strtotime($this->input->post('tgl_lahir')));
		$data['tgl_nikah']			= date('Y-m-d',strtotime($this->input->post('tgl_nikah')));
		$data['tgl_pendaftaran']	= date('Y-m-d',strtotime($this->input->post('tgl_pendaftaran')));
		$data['tgl_cerai']			= (!empty($this->input->post('tgl_cerai')) ? date('Y-m-d',strtotime($this->input->post('tgl_cerai'))) : NULL);
		$data['tgl_wafat']			= (!empty($this->input->post('tgl_wafat')) ? date('Y-m-d',strtotime($this->input->post('tgl_wafat'))) : NULL);
		$data['tempat_lahir']		= $this->input->post('tempat_lahir');
		$data['alamat']				= $this->input->post('alamat');
		$data['usul_id']			= $this->input->post('usul_id');
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 	
		if (!$this->db->insert($this->tempistri, $data))
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
			$data['pesan']		= "Data Istri Berhasil Tersimpan";
			$data['response']	= TRUE;
		}	
        $this->db->db_debug = $db_debug; //restore setting	

        return $data;		
	}	
	
	public function updateTempIstri()
	{
		$data['nama']				= $this->input->post('nama');
		$data['nama_kecil']			= $this->input->post('nama_kecil');
		$data['tempat_lahir']		= $this->input->post('tempat_lahir');
		$data['tgl_lahir']			= date('Y-m-d',strtotime($this->input->post('tgl_lahir')));
		$data['tgl_nikah']			= date('Y-m-d',strtotime($this->input->post('tgl_nikah')));
		$data['tgl_pendaftaran']	= date('Y-m-d',strtotime($this->input->post('tgl_pendaftaran')));
		$data['tgl_cerai']			= (!empty($this->input->post('tgl_cerai')) ? date('Y-m-d',strtotime($this->input->post('tgl_cerai'))) : NULL);
		$data['tgl_wafat']			= (!empty($this->input->post('tgl_wafat')) ? date('Y-m-d',strtotime($this->input->post('tgl_wafat'))) : NULL);
		$data['tempat_lahir']		= $this->input->post('tempat_lahir');
		$data['alamat']				= $this->input->post('alamat');
		$temp_id			 		= $this->input->post('temp_mutasi_id');
		$data['usul_id']			= $this->input->post('usul_id');
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 	
		
		$this->db->where('mutasi_id',$temp_id);
		if (!$this->db->update($this->tempistri, $data))
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
			$data['pesan']		= "Data Istri Berhasil Terupdate";
			$data['response']	= TRUE;
		}	
        $this->db->db_debug = $db_debug; //restore setting	

        return $data;		
	}	
	
	
	public function getTempIstri($id)
	{
		$sql=" SELECT mutasi_id, nama,nama_kecil,tempat_lahir,alamat,usul_id,
		DATE_FORMAT(tgl_lahir,'%d-%m-%Y') tgl_lahir,
		DATE_FORMAT(tgl_nikah,'%d-%m-%Y') tgl_nikah,
		DATE_FORMAT(tgl_pendaftaran,'%d-%m-%Y') tgl_pendaftaran,
		DATE_FORMAT(tgl_cerai,'%d-%m-%Y') tgl_cerai,
		DATE_FORMAT(tgl_wafat,'%d-%m-%Y') tgl_wafat
		from $this->tempistri WHERE usul_id='$id' ";			
		return $this->db->query($sql);
	}	
	
	public function hapusTempIStri()
	{
		$id			  = $this->input->post('temp_mutasi_id');
	   	$this->db->where('mutasi_id', $id);		
		return $this->db->delete($this->tempistri);
	
	}
	
	public function getTempAnak($id)
	{
		$sql=" SELECT mutasi_id, nama,sex,nama_ibu_ayah,usul_id,
		DATE_FORMAT(tgl_lahir,'%d-%m-%Y') tgl_lahir,		
		DATE_FORMAT(cerai_tgl,'%d-%m-%Y') cerai_tgl,
		DATE_FORMAT(meninggal_tgl,'%d-%m-%Y') meninggal_tgl
		from $this->tempanak  WHERE usul_id='$id' " ;			
		return $this->db->query($sql);
	}	
	
	public function simpanTempAnak()
	{
		$data['nama']				= $this->input->post('nama');
		$data['sex']				= $this->input->post('sex');		
		$data['tgl_lahir']			= date('Y-m-d',strtotime($this->input->post('tgl_lahir')));
		$data['cerai_tgl']			= (!empty($this->input->post('tgl_cerai')) ? date('Y-m-d',strtotime($this->input->post('tgl_cerai'))) : NULL);
		$data['meninggal_tgl']		= (!empty($this->input->post('tgl_wafat')) ? date('Y-m-d',strtotime($this->input->post('tgl_wafat'))) : NULL);
		$data['nama_ibu_ayah']		= $this->input->post('nama_ibu_ayah');
		$data['usul_id']			= $this->input->post('usul_id');
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 	
		if (!$this->db->insert($this->tempanak, $data))
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
			$data['pesan']		= "Data Anak Berhasil Tersimpan";
			$data['response']	= TRUE;
		}	
        $this->db->db_debug = $db_debug; //restore setting	

        return $data;		
	}	
	
	public function updateTempAnak()
	{
		$data['nama']				= $this->input->post('nama');
		$data['sex']				= $this->input->post('sex');		
		$data['tgl_lahir']			= date('Y-m-d',strtotime($this->input->post('tgl_lahir')));
		$data['cerai_tgl']			= (!empty($this->input->post('tgl_cerai')) ? date('Y-m-d',strtotime($this->input->post('tgl_cerai'))) : NULL);
		$data['meninggal_tgl']		= (!empty($this->input->post('tgl_wafat')) ? date('Y-m-d',strtotime($this->input->post('tgl_wafat'))) : NULL);
		$data['nama_ibu_ayah']		= $this->input->post('nama_ibu_ayah');
		$temp_id			 		= $this->input->post('temp_mutasi_id');
		$data['usul_id']			= $this->input->post('usul_id');
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 	
		
		$this->db->where('mutasi_id',$temp_id);
		if (!$this->db->update($this->tempanak, $data))
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
			$data['pesan']		= "Data Anak Berhasil Terupdate";
			$data['response']	= TRUE;
		}	
        $this->db->db_debug = $db_debug; //restore setting	

        return $data;		
	}

	public function hapusTempAnak()
	{
		$id			  = $this->input->post('temp_mutasi_id');
	   	$this->db->where('mutasi_id', $id);		
		return $this->db->delete($this->tempanak);
	
	}	
}