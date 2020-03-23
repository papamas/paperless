<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Berkas_model extends CI_Model {

	private     $layanan    		= 'layanan';
	private     $dokumen    		= 'dokumen_taspen';
	private     $usul    		    = 'usul_taspen';
	private     $upload             = 'upload_dokumen_taspen';
	private     $tahapan    		= 'tahapan';
    private     $tablepupns 	    = 'mirror.pupns';

	
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
		
	public function getBerkas()
	{		
	    $searchby  = $this->input->post('searchby');
		$search    = $this->input->post('search');
		
		switch($searchby){
            case 1:
			    $sql = " AND a.nip = trim('$search') ";
            break;
			case 2:
			    $sql = " AND a.nomor_usul = trim('$search') ";
            break;
			case 3:
			    $sql = " AND b.layanan_nama LIKE '$search%' ";
            break;
			default:
			   $sql       = '';
		}		
      
		$q="SELECT a.*,DATE_FORMAT(a.tgl_usul,'%d-%m-%Y') tgl,
		CASE a.usul_status
			WHEN 'ACC' THEN 'badge bg-green'
			WHEN 'TMS' THEN 'badge bg-red'
			WHEN 'BTL' THEN 'badge bg-yellow'
			ELSE 'badge bg-light-blue'
		END AS bg,
		b.layanan_nama,
		c.tahapan_nama,
		d.PNS_NIPBARU nip_baru, d.PNS_PNSNIP nip_lama
		FROM $this->usul a
		LEFT JOIN $this->layanan b ON a.layanan_id = b.layanan_id	
		LEFT JOIN $this->tahapan c ON c.tahapan_id = a.usul_tahapan_id
		LEFT JOIN $this->tablepupns d ON (a.nip = d.PNS_NIPBARU OR a.nip = d.PNS_PNSNIP)
        WHERE 1=1 $sql ";
		//var_dump($q);
		$query 		= $this->db->query($q);		
        return      $query;		
    }	
	
	
}