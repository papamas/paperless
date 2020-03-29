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
	
	function getAlasan($data){
		
		$usul_id			  = $data['usul_id'];
		$nip                  = $data['nip'];
		
		$this->db->select('usul_alasan');
		$this->db->where('usul_id', $usul_id);		
		$this->db->where('nip', $nip);
		return $this->db->get($this->usul);
	
	}	
	
	public function KirimBTL($data)
	{
		// kirim ulang berkas BTL
		$r					  = FALSE;
		$usul_id			  = $data['usul_id'];
		$nip                  = $data['usul_nip'];
        
		$set['usul_tahapan_id']    = 4;	
		$set['kirim_bkn_by']       = $this->session->userdata('user_id');
		$set['usul_status']        = 'BELUM';	
		
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
	
}