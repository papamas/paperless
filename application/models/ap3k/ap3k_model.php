<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ap3k_model extends CI_Model {

		
    function __construct()
    {
        parent::__construct();
		$this->ap3k  = $this->load->database('ap3k',TRUE);
	}
	
	function getPengantarByName()
	{
	   $q    = $this->input->get('q');
	   $this->db->like('agenda_nousul',$q);
	   return $this->db->get('agenda');
	}

    function getPengantarById()
	{
	   $q    = $this->input->get('q');
	   $sql  = "SELECT a.*, DATE_FORMAT(a.agenda_tgl,'%d-%m-%Y') agenda_tgl 
	   from agenda a
	   WHERE agenda_id='$q' ";
	   return $this->db->query($sql);
	}	
		
	
	function getNomorAgenda()
	{
		$sql="select max(kd_pengantar) nomor_agenda from pengantar";
		$query  = $this->ap3k->query($sql);
		return $query;
	}	
	
	function getDaftarPengantar()
	{
		$sql="select a.*,DATE_FORMAT(a.tgl_agenda,'%d-%m-%Y') tgl_agenda ,
		DATE_FORMAT(a.tgl_pengantar,'%d-%m-%Y') tgl_pengantar, b.instansi 
		from pengantar a 
		LEFT JOIN instansi b ON a.kd_instansi = b.kd_instansi
		where a.publish = '1' AND a.agenda_maleo IS NOT NULL";
		$query  = $this->ap3k->query($sql);
		return $query;
	}	
	
	public function getInstansi()
	{
	    $instansi  = $this->session->userdata('session_instansi');
		if($instansi  != 4011)
		{
           $sql_instansi= " AND INS_KODINS='$instansi' ";
        }
		else
		{
             $sql_instansi=" ";
		}
		
		$sql="SELECT * FROM mirror.instansi  where 1=1 $sql_instansi ";	
		return $this->db->query($sql);
		
	}	
	
	function insertPengantar()
	{
		$data['kd_pengantar'] 		= $this->input->post('nomorAgenda');
		$data['no_pengantar'] 		= $this->input->post('agendaUsulmaleo');
		$data['tgl_pengantar'] 		= date('Y-m-d',strtotime($this->input->post('tanggalPengantar')));
		$data['permintaan'] 		= $this->input->post('permintaan');
		$data['no_agenda'] 			= $this->input->post('nomorAgenda');
		$data['tgl_agenda'] 		= date('Y-m-d',strtotime($this->input->post('tanggalAgenda')));
		$data['jenis_pengantar'] 	= $this->input->post('jenisUsul');
		$data['kd_instansi'] 		= $this->input->post('instansi');
		$data['publish']			= 1;
		$data['agenda_maleo']		= $this->input->post('agendaMaleo');
		
		$db_debug 			= $this->ap3k->db_debug; 
		$this->ap3k->db_debug = FALSE; 	
		
		if (!$this->ap3k->insert('pengantar',$data)) 
		{
			$error = $this->ap3k->_error_message(); 
			
			if(!empty($error))
			{
				$data['pesan']			= $error;
				$data['response']       = FALSE;				
			}
			else
			{
				$data['pesan']			=  NULL;
				$data['response']       = TRUE;				
			}
			
		}
		else
		{
			$data['pesan']			=  NULL;
			$data['response']       = TRUE;	
        }	
			
		$this->ap3k->db_debug = $db_debug; //restore setting	
		
		return $data;
	
	}	
	
	
	function updatePengantar()
	{		
		$data['no_pengantar'] 		= $this->input->post('agendaUsulmaleo');
		$data['tgl_pengantar'] 		= date('Y-m-d',strtotime($this->input->post('tanggalPengantar')));
		$data['permintaan'] 		= $this->input->post('permintaan');
		$data['no_agenda'] 			= $this->input->post('nomorAgenda');
		$data['tgl_agenda'] 		= date('Y-m-d',strtotime($this->input->post('tanggalAgenda')));
		$data['jenis_pengantar'] 	= $this->input->post('jenisUsul');
		$data['kd_instansi'] 		= $this->input->post('instansi');
		$data['publish']			= 1;
		$data['agenda_maleo']		= $this->input->post('agendaMaleo');
		
		$db_debug 			  = $this->ap3k->db_debug; 
		$this->ap3k->db_debug = FALSE; 	
		
		$kdPengantar		  = $this->input->post('kdPengantar');
		
		$this->ap3k->where('kd_pengantar',$kdPengantar);
		if (!$this->ap3k->update('pengantar',$data)) 
		{
			$error = $this->ap3k->_error_message(); 
			
			if(!empty($error))
			{
				$data['pesan']			= $error;
				$data['response']       = FALSE;				
			}
			else
			{
				$data['pesan']			=  NULL;
				$data['response']       = TRUE;				
			}
			
		}
		else
		{
			$data['pesan']			=  NULL;
			$data['response']       = TRUE;	
        }	
			
		$this->ap3k->db_debug = $db_debug; //restore setting	
		
		return $data;
	
	}	
	
	public function hapusPengantar()
	{
		$id			  = $this->input->post('kdPengantar');
	   	$this->ap3k->where('kd_pengantar', $id);		
		return $this->ap3k->delete('pengantar');
	
	}
	
	function getNominatif()
	{
		$id  = $this->myencrypt->decode($this->input->get('i'));
		
		if(empty($id))
		{
			$id   = $this->input->post('agendaId');
		}
		
		$sql ="SELECT a.*,b.nip, c.PNS_PNSNAM , d.layanan_nama ,e.INS_NAMINS instansi
		FROM agenda a
		LEFT JOIN nominatif b ON a.agenda_id = b.agenda_id
		LEFT JOIN mirror.pupns c ON b.nip = c.PNS_NIPBARU
		LEFT JOIN layanan d ON a.layanan_id = d.layanan_id	
		LEFT JOIn mirror.instansi e ON a.agenda_ins = e.INS_KODINS
		WHERE a.agenda_id='$id' ";
		return $this->db->query($sql);	
	}
	
	function saveNominatif()
	{	
		$id  			= $this->input->post('agendaId');
		$kdPengantar    = $this->input->post('kdPengantarAp3k');
		
		// get nominatif maleo
		$sql ="SELECT a.agenda_id,a.agenda_ins,a.layanan_id, b.nip 
		FROM agenda a 
		LEFT JOIN nominatif b ON a.agenda_id = b.agenda_id WHERE a.agenda_id='$id' ";
		$nominatif   = $this->db->query($sql);	
		
		// dapatkan data di KANREG 0 berdasarkan NIP
		foreach ($nominatif->result() as $value)
		{
			$dataKanreg0   		  = $this->getPnsOracleByNip($value->nip);					
			// tambahkan atau update setiap PNS pada data PNS Ap3K
			$ap3k['nip']          = $dataKanreg0->NIP_BARU;
			$ap3k['nama']         = $dataKanreg0->NAMA;
			$ap3k['gelar_blk']    = $dataKanreg0->GELAR_BLK;
			$ap3k['gelar_dpn']	  = $dataKanreg0->GELAR_DEPAN;
			$ap3k['jk']   		  = ($dataKanreg0->JENIS_KELAMIN == 'M'  ? 'L' : 'P');
			$ap3k['tgl_lahir']    = date('Y-m-d',strtotime($dataKanreg0->TGL_LHR));
			$ap3k['pejabat']      = $dataKanreg0->SPESIMEN_PEJABAT_CPNS;
			$ap3k['no_sk']        = $dataKanreg0->NOMOR_SK_PNS;
			$ap3k['tgl_sk']       = date('Y-m-d',strtotime($dataKanreg0->TGL_SK_PNS));
			$ap3k['kd_jabatan']   = 4;
			$ap3k['kd_instansi']  = $value->agenda_ins;
			
			// cek data PNS pada Ap3K jika ada update sesuai data KANREG0 jika tidak tambahkan
			$ada  = $this->cekPnsAp3k($dataKanreg0->NIP_BARU);
			
			if(!$ada)
			{	
				$result 		=  $this->updatePnsAp3k($ap3k);				
			}
			else
			{				
				$result 		=  $this->insertPnsAp3k($ap3k);				
			}
			
			//tambahkan nominatif pada usul di Ap3K
			$layanan_id  = $value->layanan_id;			
			switch($layanan_id)
			{	
				// Jika Layanan KARPEG
				case 11:
					$uap3k['kd_karpeg'] 	= NULL;
					$uap3k['nip']           = $dataKanreg0->NIP_BARU;
					$uap3k['kd_pengantar']  = $kdPengantar;
					
					// cek KARPEG LAMA
					$adaKarpeg 				= $this->cekKarpeg($dataKanreg0->NIP_BARU);
					
					if(!$adaKarpeg['response'])
					{	
						// sudah ada 
						$uap3k['status']		    = 'TMS';
						$uap3k['keterangan']		= $adaKarpeg['pesan'];	
					}
					else
					{				
						// belum pernah di buat 
						$uap3k['status']			= 'MS';
						$uap3k['keterangan']		= $adaKarpeg['pesan'];	
					}
					// cek apakah pernah ada usul 
					$adaUsul 				= $this->cekUsulKarpeg($dataKanreg0->NIP_BARU);
					if(!$adaUsul)
					{	
						$result 		=  $this->updateUsulKarpeg($ap3k);				
					}
					else
					{				
						$result 		=  $this->insertUsulKarpeg($uap3k);				
					}
				break;
				// Jika Layanan KARSU
				case 10:
					$uap3k['kd_karsu'] 		= NULL;
					$uap3k['no_karsu'] 		= NULL;
					$uap3k['no_karsu_baru'] = NULL;
					$uap3k['pemilik'] 		= NULL;
					$uap3k['gelar_blk']     = NULL;
					$uap3k['gelar_dpn']	    = NULL;
					$ap3k['tgl_lahir']    	= date('Y-m-d',strtotime($dataKanreg0->TGL_LHR));
					$ap3k['tgl_kawin']    	= date('Y-m-d',strtotime($dataKanreg0->TGL_LHR));
					$uap3k['nip']           = $dataKanreg0->NIP_BARU;
					$uap3k['kd_pengantar']  = $kdPengantar;
				break;
				// Jika Layanan KARIS
				default:
					$uap3k['kd_karis'] 		= NULL;
					$uap3k['no_karis'] 		= NULL;
					$uap3k['no_karis_baru'] = NULL;
					$uap3k['pemilik'] 		= NULL;
					$uap3k['gelar_blk']     = NULL;
					$uap3k['gelar_dpn']	    = NULL;
					$ap3k['tgl_lahir']    	= date('Y-m-d',strtotime($dataKanreg0->TGL_LHR));
					$ap3k['tgl_kawin']    	= date('Y-m-d',strtotime($dataKanreg0->TGL_LHR));
					$uap3k['nip']           = $dataKanreg0->NIP_BARU;
					$uap3k['kd_pengantar']  = $kdPengantar;
				
				
			}// end switch
		}	
	}
	
	function insertUsulKarpeg($data)
	{
		return $this->ap3k->insert('usul_karpeg',$data);
	}

	function  updateUsulKarpeg($data)
	{
		$this->ap3k->where('nip',$data['nip']);
		return $this->ap3k->update('usul_karpeg',$data);
	}		
	
	function getPnsOracleByNip($nip)
	{
		$this->oracle   = $this->load->database('oracle', TRUE);
		
		$sqlKanreg0   = "SELECT a.*, b.NAMA_UNOR, b.NAMA_JABATAN, c.NAMA NAMA_INSTANSI_KERJA, d.NAMA NAMA_INSTANSI_INDUK,
e.NAMA NAMA_SATUAN_KERJA_INDUK, f.NAMA NAMA_SATUAN_KERJA,g.NAMA NAMA_KEDUDUKAN_HUKUM
FROM (select a.ID,a.KABUPATEN_ID,a.NAMA,a.GELAR_DEPAN,a.GELAR_BLK,TO_CHAR(a.TGL_LHR,'DD-MM-YYYY') TGL_LHR,
		a.JENIS_KELAMIN,b.INSTANSI_KERJA_ID, b.INSTANSI_INDUK_ID,b.KEDUDUKAN_HUKUM_ID,b.LOKASI_KERJA_ID,b.NIP_LAMA,
b.NIP_BARU,b.STATUS_CPNS_PNS,b.NOMOR_SK_CPNS,TO_CHAR(b.TGL_SK_CPNS,'DD-MM-YYYY') TGL_SK_CPNS ,b.NOM_URUT_SK_CPNS,
b.NOMOR_SK_PNS,TO_CHAR(b.TGL_SK_PNS,'DD-MM-YYYY') TGL_SK_PNS ,b.NOM_URUT_SK_PNS,b.NOMOR_STTPL,
TO_CHAR(b.TGL_STTPL,'DD-MM-YYYY') TGL_STTPL, TO_CHAR(b.TGL_TUGAS,'DD-MM-YYYY') TGL_TUGAS,
b.SATUAN_KERJA_INDUK_ID, b.SATUAN_KERJA_KERJA_ID,b.UNOR_ID,  TO_CHAR(b.TMT_CPNS,'DD-MM-YYYY') TMT_CPNS,
TO_CHAR(b.TMT_PNS,'DD-MM-YYYY') TMT_PNS,
b.NOMOR_DOKTER_PNS,TO_CHAR(b.TANGGAL_DOKTER_PNS,'DD-MM-YYYY') TANGGAL_DOKTER_PNS,
b.NOMOR_SPMT,b.SPESIMEN_PEJABAT_CPNS
from KANREG0.orang a
LEFT JOIN KANREG0.PNS b ON a.ID = b.ID
WHERE b.NIP_BARU='$nip') a
LEFT JOIN KANREG0.UNOR b ON a.UNOR_ID = b.ID
LEFT JOIN KANREG0.INSTANSI c ON a.INSTANSI_KERJA_ID = c.ID
LEFT JOIN KANREG0.INSTANSI d ON a.INSTANSI_INDUK_ID = d.ID
LEFT JOIN KANREG0.SATUAN_KERJA e ON a.SATUAN_KERJA_INDUK_ID = e.ID
LEFT JOIN KANREG0.SATUAN_KERJA f ON a.SATUAN_KERJA_KERJA_ID = f.ID
LEFT JOIN KANREG0.KEDUDUKAN_HUKUM g ON a.KEDUDUKAN_HUKUM_ID = g.ID";

        return $this->oracle->query($sqlKanreg0)->row();			
	}	
	
	function cekUsulKarpeg($nip)
	{
		$this->ap3k->where('nip',$nip);
		$query   		= $this->ap3k->get('usul_karpeg');	
		$r  = FALSE;
		
		if($query->num_rows() == 0)
		{
            $r  = TRUE;
		}
		return $r;
	}	
	
	function cekKarpeg($nip)
	{
		$this->ap3k->where('nip',$nip);
		$query   		= $this->ap3k->get('karpeg');		
		
		if($query->num_rows() == 0)
		{
            $r['response']  = TRUE;
			$r['pesan']     = 'KARPEG Belum Ada';
		}
		else
		{	
			$row			= $query->row();
			$r['response']  = FALSE;
			$r['pesan']     = 'KARPEG Sudah Ada '.$row->no_karpeg;
		}
		
		return $r;
	}	
	
	function cekPnsAp3k($nip)
	{
		$this->ap3k->where('nip',$nip);
		$query   = $this->ap3k->get('pns');	
		$r  = FALSE;
		
		if($query->num_rows() == 0)
		{
            $r  = TRUE;
		}
		return $r;
	}	
	
	function insertPnsAp3k($data)
	{
		return $this->ap3k->insert('pns',$data);		
	}	
	
	function updatePnsAp3k($data)
	{
		$this->ap3k->where('nip',$data['nip']);	
		return $this->ap3k->update('pns',$data);
	}	
	
	
}