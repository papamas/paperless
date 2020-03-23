<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Validasi_model extends CI_Model {

	private     $rawName;
	private     $table    		= 'upload_taspen';
	private     $tablenom 		= 'nominatif';
	private     $tablepupns 	= 'mirror.pupns';
	private     $tableagenda 	= 'agenda';
	private     $dokumen	    = 'dokumen';
	private     $tablelayanan	= 'layanan';
	private     $tableinstansi	= 'mirror.instansi';
	private     $app_user		    = 'app_user';
	private     $tablesyarat 	= 'syarat_layanan';
	private     $tabletahapan 	= 'tahapan';
	private     $tableupload	= 'upload_dokumen';
		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	
	public function getInstansi()
	{
	   	$sql="SELECT * FROM $this->tableinstansi";	
		return $this->db->query($sql);
		
	}	
	
	
	public function getValidasiSK()
	{
	    $instansi 		= $this->input->post('instansi');
		$searchby		= $this->input->post('searchby');
		$search			= $this->input->post('search');
		
		if(!empty($instansi))
		{
			$sql_instansi  = " AND a.id_instansi='$instansi' ";
		}
		else
		{
			$sql_instansi  = " ";
		}
		
		if($searchby   == 1)
		{
			$sql_nip  = " AND a.nip='$search' ";
		}
		else
		{
			$sql_nip  = " ";
		}
		
		
		
		$sql="SELECT a.*, 
		b.INS_NAMINS instansi, 
		c.PNS_PNSNAM nama,c.PNS_NIPBARU nip_baru, c.PNS_PNSNIP nip_lama,
        d.nama_dokumen,
        e.first_name name		
		FROM $this->tableupload a  
		LEFT JOIN $this->tableinstansi b ON a.id_instansi = b.INS_KODINS
		LEFT JOIN $this->tablepupns c ON a.nip = c.PNS_NIPBARU
		LEFT JOIN $this->dokumen d ON a.id_dokumen = d.id_dokumen
		LEFT JOIN $this->app_user e ON a.upload_by  = e.user_id
		WHERE 1=1   AND a.id_dokumen IN(55,56)  $sql_instansi $sql_nip 
		ORDER BY d.nama_dokumen ASC";	
		
		
		return $this->db->query($sql);
		
	}	
}