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
		p.sk_konv_nomor SK_KONV_NOMOR,TO_CHAR(p.sk_konv_tanggal,'YYYY-MM-DD')  SK_KONV_TANGGAL, p.sk_konv_urut SK_KONV_URUT, 
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
	
	
	function insertKP($data)
	{
		return $this->db->insert_batch('mirror.pupns_kp_info', $data);
	}
	
	function deleteKP($nip){
		
		$this->db->where('PKI_NIPBARU',$nip);
		return $this->db->delete('mirror.pupns_kp_info');
		
	}
	
	function getKp_Oracle($nip){
		$sql="
select p.nip_lama PKI_PNSNIP,
p.nip_baru 	PKI_NIPBARU,
TO_CHAR(u.tanggal_usul,'YYYY-MM-DD') PKI_TGL_USUL,
u.nomor_usul PKI_NOM_USUL ,o.jenis_jabatan_id 	JJB_JJBKOD,
o.jenis_kp_id JKP_JPNKOD,o.sk_nomor PKI_SK_NOMOR,
TO_CHAR(o.sk_tanggal,'YYYY-MM-DD') 	PKI_SK_TANGGAL ,o.golongan_lama_id PKI_GOLONGAN_LAMA_ID,
o.golongan_baru_id PKI_GOLONGAN_BARU_ID, 
TO_CHAR(o.tmt_golongan_baru,'YYYY-MM-DD') 	PKI_TMT_GOLONGAN_BARU,
o.nota_persetujuan_kp NOTA_PERSETUJUAN_KP,
TO_CHAR(o.tgl_nota_persetujuan_kp,'YYYY-MM-DD') TGL_NOTA_PERSETUJUAN_KP	
        from KANREG0.pns p, KANREG0.orang_usul_kp o , KANREG0.usul u ,KANREG0.satuan_kerja sk
                        where o.usul_kp_id = u.id and p.id = o.pns_orang_id and p.satuan_kerja_induk_id = sk.id
                        AND p.nip_baru='$nip'";
						
		return $this->oracle->query($sql);					
	}
	
	
	function insertPengadaan($data)
	{
		return $this->db->insert_batch('mirror.pupns_pengadaan_info', $data);
	}
	
	function deletePengadaan($nip){
		
		$this->db->where('NIP',$nip);
		return $this->db->delete('mirror.pupns_pengadaan_info');
		
	} 
	
	function getPengadaan_Oracle($nip){
		$sql="select o.nip NIP, o.jabatan_nama JABATAN_NAMA,
		o.unit_kerja_nama UNIT_KERJA_NAMA, o.ijasah_nama IJASAH_NAMA,
		o.tahun_ijazah TAHUN_IJAZAH, o.golongan_awal_id GOLONGAN_AWAL_ID,
		TO_CHAR(o.tmt_cpns,'YYYY-MM-DD') TMT_CPNS, 
		o.persetujuan_teknis_nomor PERSETUJUAN_TEKNIS_NOMOR,
		TO_CHAR(o.persetujuan_teknis_tanggal,'YYYY-MM-DD') PERSETUJUAN_TEKNIS_TANGGAL,
		TO_CHAR(o.ditetapkan_tanggal,'YYYY-MM-DD') DITETAPKAN_TANGGAL,
		TO_CHAR(u.tanggal_usul,'YYYY-MM-DD') TANGGAL_USUL
		from KANREG0.pns p, KANREG0.orang_usul_peng o , KANREG0.usul u ,KANREG0.satuan_kerja sk
		where o.usul_pengadaan_id = u.id and p.id = o.pns_orang_id 
		and p.satuan_kerja_induk_id = sk.id AND p.nip_baru='$nip'";
		
		return $this->oracle->query($sql);					
	}
	
	
	function insertPendidikan($data)
	{
		return $this->db->insert_batch('mirror.pupns_pendidikan', $data);
	}
	
	function deletePendidikan($nip){
		
		$this->db->where('PNS_NIPBARU',$nip);
		return $this->db->delete('mirror.pupns_pendidikan');
		
	} 
	
	function getPendidikan_Oracle($nip){
		$sql=" select p.NIP_LAMA PNS_PNSNIP,
		p.NIP_BARU PNS_NIPBARU,
		pk.CEPAT_KODE PEN_PENKOD ,op.TAHUN_LULUS PEN_TAHLUL,
		TO_CHAR(op.ncsistime,'YYYY-MM-DD') NCSISTIME, 
		TO_CHAR(op.TGL_TAHUN_LULUS,'YYYY-MM-DD') TGL_TAHUN_LULUS,
		op.NOMOR_IJAZAH  	
    from KANREG0.orang_pendidikan op, KANREG0.pns p, KANREG0.satuan_kerja sk, KANREG0.pendidikan pk
    where op.ORANG_ID = p.id and op.PENDIDIKAN_ID = pk.id 
    and p.satuan_kerja_kerja_id = sk.id AND p.nip_baru='$nip'";
		
		return $this->oracle->query($sql);					
	}
	
}
