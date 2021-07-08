<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pmk_model extends CI_Model {

		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	function saveUsul()
	{
		$data['agenda_id']				= $this->input->post('agendaId');
		$data['nip']				    = $this->input->post('nip');
		$data['old_masa_kerja_tahun']	= $this->input->post('oldTahun');
		$data['old_masa_kerja_bulan']	= $this->input->post('oldBulan');
		$data['old_gaji_pokok']			= $this->input->post('oldGaji');
		$data['old_tmt_gaji']	        = date('Y-m-d',strtotime($this->input->post('oldTmtGaji')));
		$data['nomor_persetujuan']		= $this->input->post('nomorPersetujuan');
		$data['tanggal_persetujuan']	= date('Y-m-d',strtotime($this->input->post('tanggalPersetujuan')));
		$data['baru_masa_kerja_tahun']	= $this->input->post('baruTahun');
		$data['baru_masa_kerja_bulan']	= $this->input->post('baruBulan');
		$data['baru_gaji_pokok']		= $this->input->post('baruGaji');
		$data['baru_tmt_gaji']			= date('Y-m-d',strtotime($this->input->post('baruTmtGaji')));
		$data['mulai_honor']			= date('Y-m-d',strtotime($this->input->post('mulaiHonor')));
		$data['sampai_honor']			= date('Y-m-d',strtotime($this->input->post('sampaiHonor')));
		$data['tahun_honor']			= $this->input->post('tahunHonor');
		$data['bulan_honor']			= $this->input->post('bulanHonor');
		$data['mulai_pegawai']			= date('Y-m-d',strtotime($this->input->post('mulaiPegawai')));
		$data['sampai_pegawai']			= date('Y-m-d',strtotime($this->input->post('sampaiPegawai')));
		$data['tahun_pegawai']			= $this->input->post('tahunPegawai');
		$data['bulan_pegawai']			= $this->input->post('bulanPegawai');
		$data['salinan_sah']			= $this->input->post('salinanSah');
		$data['sk_pangkat']				= $this->input->post('skPangkat');
		$data['tempat_lahir']			= $this->input->post('tempatLahir');
		
		$data['tingkat1']			    = $this->input->post('tingkat1');
		$data['nomor_ijazah1']			= $this->input->post('nomorIjazah1');
		$data['tanggal_ijazah1']	    = date('Y-m-d',strtotime($this->input->post('tanggalIjazah1')));
		
		$data['tingkat2']			    = $this->input->post('tingkat2');
		$data['nomor_ijazah2']			= $this->input->post('nomorIjazah2');
		$data['tanggal_ijazah2']	    = date('Y-m-d',strtotime($this->input->post('tanggalIjazah2')));
		
		$data['tingkat3']			    = $this->input->post('tingkat3');
		$data['nomor_ijazah3']			= $this->input->post('nomorIjazah3');
		$data['tanggal_ijazah3']	    = date('Y-m-d',strtotime($this->input->post('tanggalIjazah3')));
		
		$data['tingkat4']			    = $this->input->post('tingkat4');
		$data['nomor_ijazah4']			= $this->input->post('nomorIjazah4');
		$data['tanggal_ijazah4']	    = date('Y-m-d',strtotime($this->input->post('tanggalIjazah4')));
		
		$data['tingkat5']			    = ($this->input->post('tingkat5') ? $this->input->post('tingkat5') : NULL) ;
		$data['nomor_ijazah5']			= $this->input->post('nomorIjazah5');
		$data['tanggal_ijazah5']	    = ($this->input->post('tanggalIjazah5') ? date('Y-m-d',strtotime($this->input->post('tanggalIjazah5'))) : NULL );


		$data['lokasi_ttd']			    = $this->input->post('lokasiTtd');
		$data['tanggal_ttd']			= date('Y-m-d',strtotime($this->input->post('tanggalTtd')));
		$data['jabatan_ttd']			= $this->input->post('jabatanTtd');
		$data['nama_ttd']				= $this->input->post('namaTtd');
		$data['pangkat_ttd']			= $this->input->post('pangkatTtd');
		$data['nip_ttd']				= $this->input->post('nipTtd');
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		
		$this->db->where('nip',$this->input->post('nip'));
		$this->db->where('agenda_id',$this->input->post('agendaId'));		
		if (!$this->db->update('usul_pmk', $data))
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
			$data['pesan']		= "Data Berhasil Tersimpan";
			$data['response']	= TRUE;
		}	
		
		$this->db->db_debug = $db_debug; //restore setting			
		
		return $data;
	}		
	
	
	function getUsul($agenda,$nip)
	{
		$sql=" SELECT a.*,
		DATE_FORMAT(old_tmt_gaji,'%d-%m-%Y') old_tmt_gaji,	
		DATE_FORMAT(tanggal_persetujuan,'%d-%m-%Y') tanggal_persetujuan,
		DATE_FORMAT(baru_tmt_gaji,'%d-%m-%Y') baru_tmt_gaji,
		DATE_FORMAT(mulai_pegawai,'%d-%m-%Y') mulai_pegawai,
		DATE_FORMAT(sampai_pegawai,'%d-%m-%Y') sampai_pegawai,
		DATE_FORMAT(mulai_honor,'%d-%m-%Y') mulai_honor,
		DATE_FORMAT(sampai_honor,'%d-%m-%Y') sampai_honor,
		DATE_FORMAT(tanggal_ijazah1,'%d-%m-%Y') tanggal_ijazah1,
		DATE_FORMAT(tanggal_ijazah2,'%d-%m-%Y') tanggal_ijazah2,
		DATE_FORMAT(tanggal_ijazah3,'%d-%m-%Y') tanggal_ijazah3,
		DATE_FORMAT(tanggal_ijazah4,'%d-%m-%Y') tanggal_ijazah4,
		DATE_FORMAT(tanggal_ijazah5,'%d-%m-%Y') tanggal_ijazah5,
		DATE_FORMAT(tanggal_ttd,'%d-%m-%Y') tanggal_ttd
		FROM  usul_pmk a 
		WHERE a.agenda_id='$agenda' AND a.nip='$nip'  " ;			
		return $this->db->query($sql);
		
	}	
	
	function getCetakUsul($agenda,$nip)
	{		
		$sql="select a.*,DATE_FORMAT(tanggal_lahir,'%d-%m-%Y') tanggal_lahir,
		DATE_FORMAT(tanggal_ijazah1,'%d-%m-%Y') tanggal_ijazah1,
		DATE_FORMAT(tanggal_ijazah2,'%d-%m-%Y') tanggal_ijazah2,
		DATE_FORMAT(tanggal_ijazah3,'%d-%m-%Y') tanggal_ijazah3,
		DATE_FORMAT(tanggal_ijazah4,'%d-%m-%Y') tanggal_ijazah4,
		DATE_FORMAT(tanggal_ijazah5,'%d-%m-%Y') tanggal_ijazah5,
formatTanggal(a.old_tmt_gaji) old_tmt_gaji,
formatTanggal(a.tanggal_persetujuan) tanggal_persetujuan,
formatTanggal(a.baru_tmt_gaji) baru_tmt_gaji,
b.PNS_PNSNAM, b.PNS_GLRDPN,b.PNS_GLRBLK,
c.agenda_nousul, d.INS_NAMINS,
formatTanggal(a.tanggal_ttd) tanggal_ttd,
formatTanggal(baru_tmt_gaji) baru_tmt_gaji,
DATE_FORMAT(mulai_pegawai,'%d-%m-%Y') mulai_pegawai,
DATE_FORMAT(sampai_pegawai,'%d-%m-%Y') sampai_pegawai,
DATE_FORMAT(mulai_honor,'%d-%m-%Y') mulai_honor,
DATE_FORMAT(sampai_honor,'%d-%m-%Y') sampai_honor
from usul_pmk a
LEFT JOIN mirror.pupns b ON a.nip = b.PNS_NIPBARU
LEFT JOIN paperless.agenda c ON c.agenda_id = a.agenda_id
LEFT JOIN mirror.instansi d ON d.INS_KODINS = c.agenda_ins
where a.nip='$nip' AND a.agenda_id='$agenda'";
		return $this->db->query($sql);
	}	
	
	function getCetakAccPmk($agenda,$nip)
	{		
		$sql="SELECT a.*, b.PNS_PNSNAM nama_acc,b.PNS_GLRDPN  glrdpn_acc, b.PNS_GLRBLK glrblk_acc, c.GOL_GOLNAM, c.GOL_PKTNAM FROM 
(select a.tempat_lahir,a.nip,a.tingkat1,a.nomor_ijazah1,
		a.status,a.tingkat2,a.nomor_ijazah2,
		a.pangkat,a.tingkat3,a.nomor_ijazah3,
		a.golongan,a.tingkat4,a.nomor_ijazah4,
		a.old_masa_kerja_tahun,a.tingkat5,a.nomor_ijazah5,
		a.old_masa_kerja_bulan,a.old_gaji_pokok,a.nomor_persetujuan,a.salinan_sah,
		a.dinilai_tahun_honor,a.dinilai_tahun_pegawai,a.dinilai_bulan_honor,
		a.dinilai_bulan_pegawai,a.baru_gaji_pokok,a.sk_pangkat,a.tahun_honor,
		a.bulan_honor,a.tahun_pegawai,a.bulan_pegawai,a.keterangan,
		a.acc_gaji_pokok,formatTanggal(a.acc_tmt_gaji) acc_tmt_gaji,
		DATE_FORMAT(tanggal_lahir,'%d-%m-%Y') tanggal_lahir,
		DATE_FORMAT(tanggal_ijazah1,'%d-%m-%Y') tanggal_ijazah1,
		DATE_FORMAT(tanggal_ijazah2,'%d-%m-%Y') tanggal_ijazah2,
		DATE_FORMAT(tanggal_ijazah3,'%d-%m-%Y') tanggal_ijazah3,
		DATE_FORMAT(tanggal_ijazah4,'%d-%m-%Y') tanggal_ijazah4,
		DATE_FORMAT(tanggal_ijazah5,'%d-%m-%Y') tanggal_ijazah5,
formatTanggal(a.old_tmt_gaji) old_tmt_gaji,
formatTanggal(a.tanggal_persetujuan) tanggal_persetujuan,
formatTanggal(a.baru_tmt_gaji) baru_tmt_gaji,
b.PNS_PNSNAM, b.PNS_GLRDPN,b.PNS_GLRBLK,
c.agenda_nousul, formatTanggal(c.agenda_tgl) agenda_tgl,
d.INS_NAMINS,
formatTanggal(a.tanggal_ttd) tanggal_ttd,
DATE_FORMAT(mulai_pegawai,'%d-%m-%Y') mulai_pegawai,
DATE_FORMAT(sampai_pegawai,'%d-%m-%Y') sampai_pegawai,
DATE_FORMAT(mulai_honor,'%d-%m-%Y') mulai_honor,
DATE_FORMAT(sampai_honor,'%d-%m-%Y') sampai_honor,
e.nomi_persetujuan,formatTanggal(e.tanggal_persetujuan) tanggal_persetujuan_nota,formatTanggal(e.kirim_date) diterima,
f.nip nip_acc, f.jabatan
from usul_pmk a
LEFT JOIN mirror.pupns b ON a.nip = b.PNS_NIPBARU
LEFT JOIN paperless.agenda c ON c.agenda_id = a.agenda_id
LEFT JOIN mirror.instansi d ON d.INS_KODINS = c.agenda_ins
LEFT JOIN paperless.nominatif e ON (a.agenda_id = e.agenda_id AND e.nip = a.nip) 
LEFT JOIN paperless.app_user f ON e.nomi_verifby = f.user_id
where a.nip='$nip' AND a.agenda_id='$agenda'
)a
LEFT JOIN mirror.pupns b ON b.PNS_NIPBARU = a.nip_acc
LEFT JOIN mirror.golru c ON b.PNS_GOLRU = c.GOL_KODGOL";
		return $this->db->query($sql);
	}	
	
	
}