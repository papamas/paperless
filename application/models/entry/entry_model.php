<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Entry_model extends CI_Model {

	
	private     $tablelayanan  = 'layanan';
	private     $tableinstansi = 'mirror.instansi';
	private     $table    = 'upload_dokumen';
	private     $tablenom = 'nominatif';
	private     $tablepupns = 'mirror.pupns';
	private     $tableagenda = 'agenda';
	private     $tabledokumen= 'dokumen';
	private     $tableuser= 'app_user';
	private     $tablesyarat = 'syarat_layanan';
	private     $tablephoto = 'upload_photo';
	
		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
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
	
	public function getUsulDokumen($data)
	{		
	    $instansi  				= $data['instansi'];
		$layanan    			= $data['layanan'];
		$reportrange        	= $data['reportrange'];
		$status    				= $data['status'];
		$nip    				= $data['nip'];
			
		switch($status)
		{
		    case 1:
			    $sql_status = " AND a.nomi_persetujuan IS NOT NULL";
            break;
            case 2 :
			    $sql_status = " AND a.nomi_persetujuan IS NULL";
            break;
            case 3:
			    $sql_status = " ";
            break;            			
		}	
		
		if(!empty($nip))
		{
			$sql_nip = " AND a.nip = '$nip' ";
        }
        else
		{
			$sql_nip = " ";   
		}	
		
		if(!empty($reportrange))
		{	
			
			$xreportrange       	= explode("-",$reportrange);
			$startdate				= $xreportrange[0];
			$enddate				= $xreportrange[1];
			
			
		}
		
		if(!empty($instansi))
		{
			$sql_instansi = " AND f.INS_KODINS = '$instansi' ";
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
		
			$sql_date = " AND DATE( a.verify_date ) BETWEEN STR_TO_DATE('$startdate', '%d/%m/%Y ' )
			AND STR_TO_DATE('$enddate', '%d/%m/%Y ' ) ";
		}
		else
		{	
			$sql_date = " ";   
		}		
      
		$q="SELECT a.agenda_id,a.nip,a.nomi_locked,a.nomi_status,
		a.nomi_persetujuan,DATE_FORMAT(a.tanggal_persetujuan, '%d-%m-%Y') tgl,
		a.verify_date, a.entry_by, a.entry_date,
b.layanan_id,b.agenda_ins,b.agenda_nousul,b.agenda_timestamp,b.agenda_dokumen,
c.layanan_nama, 
f.INS_NAMINS instansi,
g.PNS_PNSNAM nama,
group_concat(d.dokumen_id SEPARATOR ',') dokumen_id , 
group_concat(e.nama_dokumen SEPARATOR ',') nama_dokumen,
GROUP_CONCAT(IF(e.flag = 1,e.nama_dokumen, NULL) SEPARATOR ',')  main_dokumen,
GROUP_CONCAT(h.id_dokumen SEPARATOR ',')  upload_dokumen_id,
GROUP_CONCAT(i.nama_dokumen SEPARATOR ',')  upload_dokumen,
GROUP_CONCAT(IF(i.flag = 1,h.file_name, NULL) SEPARATOR ',')  main_upload_dokumen,
j.last_name,
k.orig_name, k.id_instansi
FROM nominatif a
LEFT JOIN $this->tableagenda b ON a.agenda_id = b.agenda_id
LEFT JOIN $this->tablelayanan c ON b.layanan_id = c.layanan_id
LEFT JOIN $this->tablesyarat d ON d.layanan_id = c.layanan_id
LEFT JOIN $this->tabledokumen e ON d.dokumen_id = e.id_dokumen
LEFT JOIN $this->tableinstansi f ON b.agenda_ins = f.INS_KODINS 
LEFT JOIN $this->tablepupns g ON g.PNS_NIPBARU = a.nip
LEFT JOIN $this->table h ON (a.nip = h.nip AND d.dokumen_id = h.id_dokumen)
LEFT JOIN $this->tabledokumen i ON  i.id_dokumen = h.id_dokumen
LEFT JOIN $this->tableuser j ON j.user_id = a.locked_by
LEFT JOIN $this->tablephoto k ON a.nip = k.nip
where 1=1 AND a.nomi_status='ACC'  $sql_instansi $sql_layanan  $sql_date  $sql_status  $sql_nip
GROUP BY b.layanan_id
";
	
		$query 		= $this->db->query($q);
		
        return      $query;		
    }	
	
	public function simpanPersetujuan($data)
	{
		$agenda	    		= $data['agenda'];
		$nip				= $data['nip'];
		$nomor				= $data['persetujuan'];
		$tanggal			= date('Y-m-d',strtotime($data['tanggal']));
		
		$set['nomi_persetujuan']    	=   strtoupper($nomor); 
		$set['tanggal_persetujuan']   	=   $tanggal; 
		$set['tahapan_id']   			=   7; 
		$set['work_by']   			    =   $this->session->userdata('user_id'); 
		$set['entry_by']   			    =   $this->session->userdata('user_id'); 
		
		$this->db->where('agenda_id',$agenda);
		$this->db->where('nip',$nip);
		$this->db->set($set);	
	    $this->db->set('entry_date','NOW()',FALSE);
		return $this->db->update($this->tablenom);
	}	
	
	public function simpanTahapan($data)
	{
		
		$set['tahapan_id']    = 6;	
		$set['work_by']	      = $this->session->userdata('user_id');
		
		$this->db->set($set);
		$this->db->where('agenda_id', $data['agenda']);		
		$this->db->where('nip', $data['nip']);
		return $this->db->update($this->tablenom);
	}	
}