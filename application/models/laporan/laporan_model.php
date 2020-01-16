<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Laporan_model extends CI_Model {

	private     $rawName;
	private     $tablelayanan  		= 'layanan';
	private     $tableinstansi 		= 'mirror.instansi';
	private     $tablenom 			= 'nominatif';
	private     $tablepupns 		= 'mirror.pupns';
	private     $tableagenda 		= 'agenda';
	private     $tabledokumen		= 'dokumen';
	private     $tableuser			= 'app_user';
	private     $tablesyarat 		= 'syarat_layanan';
	
		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	
	public function getPelayanan()
	{
	    $bidang  = $this->session->userdata('session_bidang');
		
		$sql="SELECT * FROM $this->tablelayanan WHERE status='1' AND layanan_bidang='$bidang' ORDER BY layanan_nama ASC ";	
		return $this->db->query($sql);
		
	}	
	
	public function getInstansi()
	{
	    $sql="SELECT * FROM $this->tableinstansi";	
		return $this->db->query($sql);
		
	}	
	
	public function getLaporan($data)
	{		
	    $instansi  				= $data['instansi'];
		$layanan    			= $data['layanan'];
		$reportrange        	= $data['reportrange'];
		$status    				= $data['status'];
		$bydate    				= $data['bydate'];
		
		if(!empty($reportrange))
		{	
			$xreportrange       	= explode("-",$reportrange);
			$startdate				= $xreportrange[0];
			$enddate				= $xreportrange[1];
		}
		
		if(!empty($instansi))
		{
			$sql_instansi = " AND d.INS_KODINS = '$instansi' ";
        }
        else
		{
			$sql_instansi = " ";   
		}	

        if(!empty($layanan))
		{
		
			$sql_layanan = " AND b.layanan_id = '$layanan' ";
        }
		else
		{	
			$sql_layanan = " ";   
		}	

		if(!empty($startdate) AND !empty($enddate))
		{
		
		    if($bydate == 1)
			{	
				$sql_date = " AND DATE( a.verify_date ) BETWEEN STR_TO_DATE( '$startdate', '%d/%m/%Y ' )
				AND STR_TO_DATE( '$enddate', '%d/%m/%Y ' ) ";
			}
			else
			{
				$sql_date = " AND DATE( a.entry_date ) BETWEEN STR_TO_DATE( '$startdate', '%d/%m/%Y ' )
				AND STR_TO_DATE( '$enddate', '%d/%m/%Y ' ) ";
            }		
		}
		else
		{	
			$sql_date = " ";   
		}	

		if($status == "ALL")
		{
			$sql_status = " ";  
        }
        else
		{
			$sql_status = " AND a.nomi_status = '$status' ";
		}	
      
	    $bidang  = $this->session->userdata('session_bidang');
		 
		$q="select a.agenda_id, a.nip, a.nomi_status, a.nomi_alasan, a.verify_date,a.entry_date,
b.agenda_ins, b.agenda_nousul,b.layanan_id,b.agenda_timestamp,
c.layanan_nama,c.layanan_kode, 
d.INS_NAMINS instansi, 
e.PNS_PNSNAM nama,
f.first_name verif_name,
g.first_name entry_name
from $this->tablenom a
LEFT JOIN $this->tableagenda b ON a.agenda_id = b.agenda_id
LEFT JOIN $this->tablelayanan c ON b.layanan_id = c.layanan_id
LEFT JOIN $this->tableinstansi d ON d.INS_KODINS = b.agenda_ins
LEFT JOIN $this->tablepupns e ON e.PNS_NIPBARU = a.nip
LEFT JOIN $this->tableuser f ON a.nomi_verifby = f.user_id
LEFT JOIN $this->tableuser g ON a.entry_by = g.user_id
WHERE 1=1 AND c.layanan_bidang='$bidang' 
$sql_instansi  $sql_layanan   $sql_date  $sql_status order by a.update_date ASC";
	
		$query 		= $this->db->query($q);
		
        return      $query;		
    }	
}