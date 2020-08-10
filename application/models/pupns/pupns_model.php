<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pupns_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
		$this->oracle   = $this->load->database('oracle', TRUE);
	}
	
	function getInstansi()
	{
		$this->db->select('*');
		return $this->db->get('mirror.instansi');
	}	
	
	function getGolru()
	{
		$this->db->select('GOL_KODGOL kode, GOL_GOLNAM nama, GOL_PKTNAM pangkat');
		return $this->db->get('mirror.golru');
	}
	
	function cekPupns()
	{
		$nip   = $this->input->post('nip');
		$this->db->where('PNS_NIPBARU',$nip);
		return $this->db->get('mirror.pupns');
	}	
	
	function updatePupns()
	{
		$nip 		= $this->input->post('nip');	
		$psnOracle  = $this->getPnsDataOracle($nip)->row_array();
		
		$this->db->where('PNS_NIPBARU',$nip);
		return $this->db->update('mirror.pupns',$psnOracle);
	}	
	
	function insertPupns()
	{
		$nip 		= $this->input->post('nip');		
		$psnOracle  = $this->getPnsDataOracle($nip)->row_array();
	
		return $this->db->insert('mirror.pupns',$psnOracle);
		
	}
	
	function getPnsDataOracle($nip)
	{
		$sql="SELECT p.nip_lama PNS_PNSNIP, p.nip_baru PNS_NIPBARU, o.nama PNS_PNSNAM, 
		o.gelar_depan PNS_GLRDPN, o.gelar_blk PNS_GLRBLK, tptLahir.cepat_kode PNS_TEMLHR, 
		TO_CHAR(o.tgl_lhr,'DDMMYYYY') PNS_TGLLHR,TO_CHAR(o.tgl_lhr,'YYYY-MM-DD') PNS_TGLLHRDT,
		CASE O.JENIS_KELAMIN 
        WHEN 'M' THEN 1
        WHEN 'F' THEN 2 END PNS_PNSSEX ,
		O.TK_PENDIDIKAN_ID PNS_TKTDIK,P.GOLONGAN_AWAL_ID PNS_GOLAWL,TO_CHAR(P.tmt_cpns,'YYYY-MM-DD') PNS_TMTCPN, TO_CHAR(p.tmt_pns,'YYYY-MM-DD')  PNS_TMTPNS,
        P.JENIS_PEGAWAI_ID PNS_JENPEG, INSDUK.CEPAT_KODE PNS_INSDUK, INSKER.CEPAT_KODE 	PNS_INSKER,
		U.CEPAT_KODE PNS_UNITOR, p.jenis_jabatan_id PNS_JNSJAB, p.eselon_id PNS_KODESL, jabfun.cepat_kode PNS_JABFUN, 
		TO_CHAR(p.tmt_jabatan,'YYYY-MM-DD')  PNS_TMTFUN, p.golongan_id PNS_GOLRU, TO_CHAR(p.tmt_golongan ,'YYYY-MM-DD') PNS_TMTGOL, 
		p.mk_tahun PNS_THNKER, p.mk_bulan PNS_BLNKER, tptKerja.cepat_kode PNS_TEMKRJ, o.jenis_kawin_id 	PNS_STSWIN, 
		o.anak_tanggungan PNS_JMLANK, p.kedudukan_hukum_id PNS_KEDHUK, p.rumah PNS_RUMAH, p.tabrum PNS_TAPRUM,
		p.gunrum PNS_GUNRUM, o.alamat PNS_ALAMAT, O.KODE_POS PNS_KODPOS, p.unor_id PNS_UNOR, O.AGAMA_ID PNS_KODAGA,
		O.JENIS_ID_DOKUMEN_ID PNS_JENDOK, O.NOMOR_ID_DOCUMENT PNS_NOMDOK,P.STATUS_CPNS_PNS PNS_STCPNS, O.EMAIL PNS_EMAIL,
		O.NOMOR_HP PNS_NOMHP, O.NOMOR_TELPON PNS_NOMTEL, P.KARTU_PEGAWAI PNS_KARPEG,p.latihan_struktural_nama PNS_LATSTR,
		p.sk_konv_nomor SK_KONV_NOMOR, TO_CHAR(p.sk_konv_tanggal,'YYYY-MM-DD') SK_KONV_TANGGAL, p.sk_konv_urut SK_KONV_URUT, 
		sk.kanreg_id PNS_KANREG, o.id ORANG_ID  
        FROM KANREG0.PNS p
		inner join KANREG0.ORANG o on O.ID = P.ID
		inner join KANREG0.INSTANSI insduk on insduk.id = P.INSTANSI_INDUK_ID
		inner join KANREG0.INSTANSI insker on INSKER.ID = P.INSTANSI_KERJA_ID
		inner join KANREG0.SATUAN_KERJA sk on SK.ID = P.SATUAN_KERJA_KERJA_ID
		inner join KANREG0.SATUAN_KERJA sk1 on SK1.ID = P.satuan_kerja_induk_id
		inner join KANREG0.UNOR u on u.ID = P.unor_id
		left join KANREG0.LOKASI tptLahir on tptLahir.id = o.kabupaten_id
		left join KANREG0.lokasi tptKerja on tptKerja.id = p.lokasi_kerja_id
		left join KANREG0.JABATAN_FUNGSIONAL jabfun on jabfun.id = p.jabatan_fungsional_id
	    WHERE p.nip_baru='$nip' ";
		return $this->oracle->query($sql);	
	}	
	
	function getPns()
	{
		$nip   = $this->input->get('q');		 
		$sql="SELECT p.nip_lama, p.nip_baru, o.nama
		 FROM KANREG0.PNS p
		 inner join KANREG0.ORANG o on O.ID = P.ID
		 WHERE p.nip_baru='$nip'";
		return $this->oracle->query($sql);
		
	}	
	
	function getPnsdata()
	{		
		$nip   = $this->input->get('q');
		
		$sql="SELECT p.nip_lama, p.nip_baru, o.nama, 
		o.gelar_depan, o.gelar_blk, tptLahir.cepat_kode, 
		TO_CHAR(o.tgl_lhr,'DD-MM-YYYY') tgl_lhr, 
		o.tgl_lhr tgl_lhr_dt,CASE O.JENIS_KELAMIN 
        WHEN 'M' THEN 1
        WHEN 'F' THEN 2 END sex ,
		O.TK_PENDIDIKAN_ID, 
		P.GOLONGAN_AWAL_ID,
		P.tmt_cpns, p.tmt_pns,
        P.JENIS_PEGAWAI_ID, INSDUK.CEPAT_KODE instansi_induk, INSKER.CEPAT_KODE instansi_kerja,
		U.CEPAT_KODE, p.jenis_jabatan_id, 
        p.eselon_id, jabfun.cepat_kode, p.tmt_jabatan, p.golongan_id, p.tmt_golongan, p.mk_tahun, p.mk_bulan,
        tptKerja.cepat_kode, o.jenis_kawin_id, 0, o.anak_tanggungan, p.kedudukan_hukum_id,
        p.rumah, p.tabrum, p.gunrum, o.alamat, O.KODE_POS, p.unor_id, O.AGAMA_ID, O.JENIS_ID_DOKUMEN_ID, O.NOMOR_ID_DOCUMENT,
        P.STATUS_CPNS_PNS, O.EMAIL, O.NOMOR_HP, O.NOMOR_TELPON, P.KARTU_PEGAWAI,
        p.latihan_struktural_nama, p.sk_konv_nomor, p.sk_konv_tanggal, p.sk_konv_urut, sk.kanreg_id, o.id   
        FROM KANREG0.PNS p
		inner join KANREG0.ORANG o on O.ID = P.ID
		inner join KANREG0.INSTANSI insduk on insduk.id = P.INSTANSI_INDUK_ID
		inner join KANREG0.INSTANSI insker on INSKER.ID = P.INSTANSI_KERJA_ID
		inner join KANREG0.SATUAN_KERJA sk on SK.ID = P.SATUAN_KERJA_KERJA_ID
		inner join KANREG0.SATUAN_KERJA sk1 on SK1.ID = P.satuan_kerja_induk_id
		inner join KANREG0.UNOR u on u.ID = P.unor_id
		left join KANREG0.LOKASI tptLahir on tptLahir.id = o.kabupaten_id
		left join KANREG0.lokasi tptKerja on tptKerja.id = p.lokasi_kerja_id
		left join KANREG0.JABATAN_FUNGSIONAL jabfun on jabfun.id = p.jabatan_fungsional_id
	    WHERE p.nip_baru='$nip' ";
		return $this->oracle->query($sql);
	}	
	
	
}