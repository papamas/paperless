<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Verifikator_model extends CI_Model {

	private     $rawName;
	private     $table    = 'upload_dokumen';
	private     $tablenom = 'nominatif';
	private     $tablepupns = 'mirror.pupns';
	private     $tableagenda = 'agenda';
	private     $tabledokumen= 'dokumen';
	private     $tablelayanan= 'layanan';
	private     $tableinstansi= 'mirror.instansi';
	private     $tableuser= 'app_user';
	private     $tablesyarat = 'syarat_layanan';
		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	public function getAllTab($nip)
	{		
	    $sql="SELECT a.*,b.*,
		group_concat(minor_dok SEPARATOR ',') grup_dok ,
		GROUP_CONCAT(raw_name SEPARATOR ',') upload_raw_name  
		FROM $this->table a 
		LEFT JOIN $this->tabledokumen b ON a.id_dokumen = b.id_dokumen 		
		where a.nip ='$nip' AND b.flag IS NULL 
		group by b.id_dokumen order by created_date desc";
		
		//var_dump($sql);exit;
		$query 		= $this->db->query($sql);
		
		
        return      $query;		
    }

	public function getAllDokumen($nip)
	{		
	    $sql 		="SELECT a.*,b.* FROM $this->table a 
		LEFT JOIN $this->tabledokumen b ON a.id_dokumen = b.id_dokumen 
		where a.nip ='$nip' AND b.flag IS NULL";
		//var_dump($sql);exit;
	    $query 		= $this->db->query($sql);
        return      $query;		
    }		
	
	public function getUsulDokumen($search)
	{		
	    $searchby  = $search['searchby'];
		$search    = $search['search'];		
		
		$bidang  = $this->session->userdata('session_bidang');
		
		switch($searchby){
            case 1:
			    $sql = " AND a.nip = '$search' ";
            break;
            case 2:
			   $sql = " AND  UPPER(f.INS_NAMINS) LIKE UPPER('%$search%') ";
            break;
			case 3:
			   $search = trim($search);			   
			   $sql = " AND  UPPER(b.agenda_nousul)=UPPER('$search')";
            break;
			case 4:
			   $sql = " AND  UPPER(c.layanan_nama) LIKE UPPER('%$search%') ";
            break;
            default:
                $sql = " AND a.nip = '999999999' ";		
		}		
      
		$q="SELECT a.agenda_id,a.nip,a.nomi_locked,a.nomi_status,a.locked_by,
b.layanan_id,b.agenda_ins,b.agenda_nousul,b.agenda_timestamp,b.agenda_dokumen,
c.layanan_nama, f.INS_NAMINS instansi, g.PNS_PNSNAM nama,
group_concat(d.dokumen_id SEPARATOR ',') dokumen_id , 
group_concat(e.nama_dokumen SEPARATOR ',') nama_dokumen,
GROUP_CONCAT(IF(e.flag = 1,e.nama_dokumen, NULL) SEPARATOR ',')  main_dokumen,
GROUP_CONCAT(h.id_dokumen SEPARATOR ',')  upload_dokumen_id,
GROUP_CONCAT(i.nama_dokumen SEPARATOR ',')  upload_dokumen,
GROUP_CONCAT(IF(i.flag = 1,h.file_name, NULL) SEPARATOR ',')  main_upload_dokumen,
j.last_name
FROM $this->tablenom a
LEFT JOIN $this->tableagenda b ON a.agenda_id = b.agenda_id
LEFT JOIN $this->tablelayanan c ON b.layanan_id = c.layanan_id
LEFT JOIN $this->tablesyarat d ON d.layanan_id = c.layanan_id
LEFT JOIN $this->tabledokumen e ON d.dokumen_id = e.id_dokumen
LEFT JOIN $this->tableinstansi f ON b.agenda_ins = f.INS_KODINS 
LEFT JOIN $this->tablepupns g ON g.PNS_NIPBARU = a.nip
LEFT JOIN $this->table h ON (a.nip = h.nip AND d.dokumen_id = h.id_dokumen)
LEFT JOIN $this->tabledokumen i ON  i.id_dokumen = h.id_dokumen
LEFT JOIN $this->tableuser j ON j.user_id = a.locked_by
where 1=1 $sql  
AND a.nomi_status='BELUM'
AND c.layanan_bidang='$bidang'
GROUP BY a.nip,b.layanan_id
";
		//var_dump($q);
		$query 		= $this->db->query($q);
		
        return      $query;		
    }	
	
	public function getVerifyUsul($data)
	{	
		// flag sedang dikerjakan
		$this->setSedangKerja($data);
		
		$nip  			= $data['nip'];
		$layanan_id		= $data['layanan_id'];
		$id_agenda      = $data['id_agenda'];
		
	    
		$q ="SELECT a.agenda_id,a.nip,a.nomi_locked,a.nomi_status,a.locked_by,
b.layanan_id,b.agenda_ins,b.agenda_nousul,b.agenda_timestamp,b.agenda_dokumen,
c.layanan_nama, f.INS_NAMINS instansi, g.PNS_PNSNAM nama,
group_concat(d.dokumen_id SEPARATOR ',') dokumen_id , 
group_concat(e.nama_dokumen SEPARATOR ',') nama_dokumen,
GROUP_CONCAT(IF(e.flag = 1,e.nama_dokumen, NULL) SEPARATOR ',')  main_dokumen,
GROUP_CONCAT(h.raw_name SEPARATOR ',')  upload_raw_name,
GROUP_CONCAT(i.nama_dokumen SEPARATOR ',')  upload_dokumen,
GROUP_CONCAT(IF(i.flag = 1,h.file_name, NULL) SEPARATOR ',')  main_upload_dokumen,
GROUP_CONCAT(IF(i.flag = 1,h.file_type, NULL) SEPARATOR ',')  file_type,
j.last_name
FROM $this->tablenom a
LEFT JOIN $this->tableagenda b ON a.agenda_id = b.agenda_id
LEFT JOIN $this->tablelayanan c ON b.layanan_id = c.layanan_id
LEFT JOIN $this->tablesyarat d ON d.layanan_id = c.layanan_id
LEFT JOIN $this->tabledokumen e ON d.dokumen_id = e.id_dokumen
LEFT JOIN $this->tableinstansi f ON b.agenda_ins = f.INS_KODINS 
LEFT JOIN $this->tablepupns g ON g.PNS_NIPBARU = a.nip
LEFT JOIN $this->table h ON (a.nip = h.nip AND d.dokumen_id = h.id_dokumen)
LEFT JOIN $this->tabledokumen i ON  i.id_dokumen = h.id_dokumen
LEFT JOIN $this->tableuser j ON j.user_id = a.locked_by
where a.nip='$nip' AND b.layanan_id='$layanan_id'  AND a.agenda_id='$id_agenda' 
GROUP BY a.nip,b.layanan_id";  
        //var_dump($q);exit;
		$query 		= $this->db->query($q);
        return      $query;		
    }	
	
	public function getIdagenda($nip)
    {
		$r  = NULL;
		$query 		= $this->db->query("SELECT * FROM $this->tablenom WHERE nip='$nip' AND nomi_status='belum' ");
		if($query->num_rows() > 0){
			$row   = $query->row();
			$r     = $row->agenda_id;
		}
		return $r;

    }	
	
	public function getDataPNS($nip)
	{		
		$query 		= $this->db->query("SELECT a.*,b.* FROM (SELECT * FROM mirror.$this->tablepupns WHERE PNS_NIPBARU='$nip') a  
		LEFT JOIN $this->tablenom b ON a.PNS_NIPBARU = b.nip ");
		
		return $query;
	}
	
	

	public function setSedangKerja($data)
	{
		$set['tahapan_id']    = 3;	
		$set['work_by']	      = $this->session->userdata('user_id');
		
		$this->db->set($set);
		$this->db->where('agenda_id', $data['id_agenda']);		
		$this->db->where('nip', $data['nip']);
		return $this->db->update($this->tablenom);
	}	
	
	public function setKerja($data)
	{
		$r					  = FALSE;
		$set['nomi_locked']   = '1';
		$set['tahapan_id']    = 4;	
		$set['locked_by']	  = $this->session->userdata('user_id');
		
        $this->db->trans_start();
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		$this->db->set($set);
		$this->db->set('locked_date','NOW()',FALSE);
		$this->db->where('agenda_id', $data['id_agenda']);		
		$this->db->where('nip', $data['nip']);
		if (!$this->db->update($this->tablenom))
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
	
	public function setUnlock($data)
	{
		$set['nomi_locked']   = NULL;
		$set['locked_by']	  = NULL;
		
		$this->db->set($set);
		$this->db->where('agenda_id', $data['agenda']);		
		$this->db->where('nip', $data['nip']);
		return $this->db->update($this->tablenom);
	}
	
	public function setVerifikator($data)
	{
		$set['nomi_status']   	  = $data['status'];
		$set['nomi_alasan']		  = $data['catatan'];
		$set['nomi_verifby']	  = $this->session->userdata('user_id');
		$set['tahapan_id']   	  = 5;
		
		$this->db->set($set);
		$this->db->set('verify_date','NOW()',FALSE);
		$this->db->where('agenda_id', $data['id_agenda']);		
		$this->db->where('nip', $data['nip']);
		return $this->db->update($this->tablenom);
	}	
	
	
	public function getPelayanan()
	{
	    $bidang  = $this->session->userdata('session_bidang');
		
		$sql="SELECT * FROM $this->tablelayanan WHERE layanan_bidang='$bidang' ";	
		return $this->db->query($sql);
		
	}	
	
	public function getInstansi()
	{
	    $sql="SELECT * FROM $this->tableinstansi";	
		return $this->db->query($sql);
		
	}	
	
	
	public function getVerifikator()
	{
	    $user_id 		= $this->session->userdata('user_id');
		$sql			= "SELECT * FROM $this->tableuser WHERE user_id='$user_id' ";	
		return $this->db->query($sql);
		
	}	
	
	
	public function getKinerja($data)
	{
		
		$instansi		    = $data['instansi'];
		$layanan		    = $data['layanan'];		
		$reportrange        = $data['reportrange'];
		$status       		= $data['status'];
		$verify 			= $data['verifikator'];
		
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
		
			$sql_layanan = " AND c.layanan_id = '$layanan' ";
        }
		else
		{	
			$sql_layanan = " ";   
		}	

		if(!empty($startdate) AND !empty($enddate))
		{
		
			$sql_date = " AND DATE( a.verify_date ) BETWEEN STR_TO_DATE( '$startdate', '%d/%m/%Y ' )
			AND STR_TO_DATE( '$enddate', '%d/%m/%Y ' ) ";
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
		
		if(!empty($verify))
		{
			$sql_verify = "  AND a.nomi_verifby ='$verify'";
        }
        else
		{
			$sql_verify = " ";   
		}		
		
		$sql="select a.agenda_id, a.nip, a.nomi_status, a.nomi_alasan, a.verify_date,
b.agenda_ins, b.agenda_nousul,b.layanan_id,b.agenda_timestamp,
c.layanan_nama,c.layanan_kode, d.INS_NAMINS instansi, e.PNS_PNSNAM nama
from $this->tablenom a
LEFT JOIN $this->tableagenda b ON a.agenda_id = b.agenda_id
LEFT JOIN $this->tablelayanan c ON b.layanan_id = c.layanan_id
LEFT JOIN $this->tableinstansi d ON d.INS_KODINS = b.agenda_ins
LEFT JOIN $this->tablepupns e ON e.PNS_NIPBARU = a.nip
WHERE 1=1 $sql_instansi  $sql_layanan   $sql_date  $sql_status  $sql_verify  order by a.update_date ASC";
       
	   //var_dump($sql);

		return $this->db->query($sql);

	}	
}