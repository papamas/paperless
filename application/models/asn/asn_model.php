<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Asn_model extends CI_Model {

	
	private     $tablelayanan  = 'layanan';
	private     $tableinstansi = 'mirror.instansi';
	private     $table    = 'upload_dokumen';
	private     $tablenom = 'nominatif';
	private     $tablepupns = 'mirror.pupns';
	private     $tableagenda = 'agenda';
	private     $tabledokumen= 'dokumen';
	private     $tableuser= 'app_user';
	private     $tablesyarat = 'syarat_layanan';
	
		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	function _get_pendidikan($search)
	{
	
	   $sql="SELECT a.*, b.*  FROM mirror.`pupns_pendidikan` a
LEFT JOIN mirror.pendik b ON a.PEN_PENKOD = b.DIK_KODIK
WHERE a.`PNS_NIPBARU` LIKE '$search'
ORDER BY a.`PEN_TAHLUL` DESC";

       $r = $this->db->query($sql);
		
		return $r;
	}
	
	function _get_pupns($search)
	{
	
	    $sql="SELECT a.*,DATE_FORMAT(a.PNS_TGLLHRDT,'%d-%m-%Y') LAHIR,
		DATE_FORMAT(a.PNS_TMTCPN,'%d-%m-%Y') CPNS,
		DATE_FORMAT(a.PNS_TMTPNS,'%d-%m-%Y') PNS,
		DATE_FORMAT(a.PNS_TMTGOL,'%d-%m-%Y') TMTGOL,
		b.GOL_GOLNAM,b.GOL_PKTNAM, c.DIK_NAMDIK, d.LOK_LOKNAM,
		e.KED_KEDNAM, f.GOL_GOLNAM GOL_AWAL , g.JPG_JPGNAM,
		h.INS_NAMINS INSDUK , i.INS_NAMINS INSKER, j.JJB_JJBNAM
		FROM mirror.pupns a 
		LEFT JOIN mirror.golru b ON a.PNS_GOLRU =b.GOL_KODGOL
		LEFT JOIN mirror.tktpendik c ON a.PNS_TKTDIK = c.DIK_TKTDIK
		LEFT JOIN mirror.lokker  d ON  a.PNS_TEMKRJ = d.LOK_LOKKOD
		LEFT JOIN mirror.kedhuk e ON a.PNS_KEDHUK = e.KED_KEDKOD
		LEFT JOIN mirror.golru f ON a.PNS_GOLAWL = f.GOL_KODGOL
		LEFT JOIN mirror.jenpeg g ON a.PNS_JENPEG = g.JPG_JPGKOD
		LEFT JOIN mirror.instansi h ON a.PNS_INSDUK = h.INS_KODINS
		LEFT JOIN mirror.instansi i ON a.PNS_INSKER = i.INS_KODINS
		LEFT JOIN mirror.jenjab j ON a.PNS_JNSJAB = j.JJB_JJBKOD
		WHERE a.PNS_NIPBARU='$search'";
		$r = $this->db->query($sql);
		
		if($r->num_rows() > 0)
		{
			
			$r = $r->row();
		}
		else
		{
			
			$r = array();
		}	
		
		return $r;
	}
	
	function _get_unorpns($search)
	{
	
	    $sql="SELECT 
    a.`PNS_NIPBARU`,
    a.`PNS_PNSNAM`,
    h.INS_NAMINS INSDUK,
    j.JJB_JJBNAM,
    k.JBF_NAMJAB,
    l.NAMA_UNOR,
    l.NAMA_JABATAN,
    l.DIATASAN_ID,
    m.NAMA_UNOR UNO_INDUK
FROM
    mirror.pupns a
        LEFT JOIN
    mirror.instansi h ON a.PNS_INSDUK = h.INS_KODINS
        LEFT JOIN
    mirror.jenjab j ON a.PNS_JNSJAB = j.JJB_JJBKOD
        LEFT JOIN
    mirror.jabfun k ON a.PNS_JABFUN = k.JBF_KODJAB
		LEFT JOIN
    mirror.unor l ON (a.PNS_UNOR = l.UNOR_ID
      
    )
    LEFT join
    mirror.unor m ON (l.DIATASAN_ID = m.UNOR_ID) 
WHERE
    a.PNS_NIPBARU='$search'";
		$r = $this->db->query($sql);
		
		if($r->num_rows() > 0)
		{
			
			$r = $r->row();
		}
		else
		{
			
			$r = array();
		}	
		
		return $r;
	}	
	
	function _getkp_info($search)
	{
	   $sql="select * from (select a.*,b.PNS_TEMKRJ, c.GOL_GOLNAM GOL_BARU, d.GOL_GOLNAM GOL_LAMA , e.JKP_JPNNAMA FROM (
	   SELECT JKP_JPNKOD,PKI_NIPBARU,NOTA_PERSETUJUAN_KP ,PKI_SK_TANGGAL,
	   DATE_FORMAT(TGL_NOTA_PERSETUJUAN_KP,'%d-%m-%Y') TGL_NOTA_PERSETUJUAN_KP,
	   DATE(PKI_TMT_GOLONGAN_BARU) PKI_TMT_GOLONGAN_BARU ,
	   PKI_GOLONGAN_LAMA_ID,PKI_GOLONGAN_BARU_ID FROM mirror.pupns_kp_info 
	   WHERE PKI_NIPBARU='$search' AND NOTA_PERSETUJUAN_KP IS NOT NULL 
	   ) a 
	   INNER JOIN mirror.pupns b ON b.PNS_NIPBARU = a. PKI_NIPBARU
	   LEFT JOIN mirror.golru  c ON a.PKI_GOLONGAN_BARU_ID = c.GOL_KODGOL
	   LEFT JOIN mirror.golru  d ON a.PKI_GOLONGAN_LAMA_ID = d.GOL_KODGOL
	   LEFT JOIN mirror.jenis_kp e ON a.JKP_JPNKOD = e.JKP_JPNKOD
	   ) a ORDER BY PKI_SK_TANGGAL DESC";
	   
	    $r = $this->db->query($sql);
		
		return $r;
	}
	
	function _get_pengadaan_info($search)
	{
	    $sql ="SELECT a.*,DATE_FORMAT(a.TMT_CPNS,'%d-%m-%Y') CPNS,
		DATE_FORMAT(a.PERSETUJUAN_TEKNIS_TANGGAL,'%d-%m-%Y') TANGGAL_TEKNIS,
		DATE_FORMAT(a.DITETAPKAN_TANGGAL,'%d-%m-%Y') TANGGAL_PENETAPAN
		FROM mirror.pupns_pengadaan_info  a WHERE a.NIP LIKE '$search' ";
		$r = $this->db->query($sql);
		if($r->num_rows() > 0)
		{
			
			$r = $r->row();
		}
		else
		{
			
			$r = array();
		}	
		
		return $r;
		
		
	}
	
	function getSearch()
	{
		$name	= $this->input->post('nama');
	    
		$sql="SELECT a.PNS_NIPBARU,a.PNS_PNSNAM, b.INS_NAMINS
		FROM mirror.pupns a 
		LEFT JOIN mirror.instansi b ON a.PNS_INSKER = b.INS_KODINS
		WHERE a.PNS_PNSNAM LIKE '%$name%'";
		$r = $this->db->query($sql);
		return $r;
	}
	
}