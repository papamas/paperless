<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Verifikator_model extends CI_Model {

	private     $rawName;
	private     $table          = 'upload_dokumen';
	private     $tablenom       = 'nominatif';
	private     $tablepupns     = 'mirror.pupns';
	private     $tableagenda    = 'agenda';
	private     $tabledokumen   = 'dokumen';
	private     $tablelayanan   = 'layanan';
	private     $tableinstansi  = 'mirror.instansi';
	private     $tableuser      = 'app_user';
	private     $tablesyarat    = 'syarat_layanan';
	private     $tabletahapan   = 'tahapan';
	private     $tablegolru     = 'mirror.golru';
	private     $usul			= 'usul_taspen';
		
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
		where a.nip ='$nip' AND b.flag IS NULL AND b.aktif='1'
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
		
		$level     = $this->input->post('level');
		
		$bidang  = $this->session->userdata('session_bidang');
		$user_id = $this->session->userdata('user_id');
		$tipe    = $this->session->userdata('session_user_tipe');
		
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

        if($bidang == 1)
		{
		    // tipe 2 = kabid , tipe 3 kanreg
			if($tipe  == 2 || $tipe == 3)
			{	
				$sql_work =" ";
			}
			else
			{
				$sql_work =" AND a.work_by = '$user_id'  "; 
			}
		}
        else
        {
            $sql_work =" ";
        }			
		
		if(!empty($level))
		{
		    switch($level){
				case 1:
					$sql_level = " AND a.verifby_level_satu IS NULL";
				break;
				case 2:
				   $sql_level = "  AND a.verifby_level_dua IS NULL ";
				break;
				case 3:
				    $sql_level = " AND a.verifby_level_tiga IS NULL";
				break;				
			}	
		}
		else
		{
		    $sql_level = " ";
		}		
      
		$q="SELECT a.* FROM ( SELECT a.*, b.user_id, b.id_instansi FROM (SELECT a.agenda_id,a.tahapan_id, a.nip,a.nomi_locked,a.nomi_status,
		a.verifby_level_satu,a.verifby_level_dua,a.verifby_level_tiga,a.locked_by,
b.layanan_id,b.agenda_ins,b.agenda_nousul,b.agenda_timestamp,b.agenda_dokumen,
c.layanan_nama, f.INS_NAMINS instansi,
g.PNS_PNSNAM nama,l.GOL_GOLNAM golongan,
group_concat(d.dokumen_id SEPARATOR ',') dokumen_id , 
group_concat(e.nama_dokumen SEPARATOR ',') nama_dokumen,
GROUP_CONCAT(IF(e.flag = 1,e.nama_dokumen, NULL) SEPARATOR ',')  main_dokumen,
GROUP_CONCAT(h.id_dokumen SEPARATOR ',')  upload_dokumen_id,
GROUP_CONCAT(i.nama_dokumen SEPARATOR ',')  upload_dokumen,
GROUP_CONCAT(IF(i.flag = 1,h.file_name, NULL) SEPARATOR ',')  main_upload_dokumen,
j.first_name lock_name,
k.tahapan_nama,
m.first_name work_name,
n.first_name verif_name_satu,
o.first_name verif_name_dua,
p.first_name verif_name_tiga,
q.first_name entry_proses_name,
r.first_name entry_name
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
LEFT JOIN $this->tabletahapan k ON a.tahapan_id = k.tahapan_id
LEFT JOIN $this->tablegolru l ON g.PNS_GOLRU = l.GOL_KODGOL
LEFT JOIN $this->tableuser m ON m.user_id = a.work_by
LEFT JOIN $this->tableuser n ON n.user_id = a.verifby_level_satu
LEFT JOIN $this->tableuser o ON o.user_id = a.verifby_level_dua
LEFT JOIN $this->tableuser p ON p.user_id = a.verifby_level_tiga
LEFT JOIN $this->tableuser q ON q.user_id = a.entry_proses_by
LEFT JOIN $this->tableuser r ON r.user_id = a.entry_by
where 1=1 $sql  
AND a.nomi_status='BELUM'
AND c.layanan_bidang='$bidang'
AND a.tahapan_id IN ('4','5','6','7','8','9','10','11','12')  $sql_work
GROUP BY a.nip,b.layanan_id,a.agenda_id ) a
LEFT JOIN user_layanan_role b ON (a.layanan_id=b.layanan_id AND a.agenda_ins = b.id_instansi AND b.user_id='$user_id')
) a
WHERE a.id_instansi IS NOT NULL $sql_level
ORDER BY a.nama ASC
";
		//var_dump($q); EXIT;
		$query 		= $this->db->query($q);
		
        return      $query;		
    }	
	
	public function getVerifyUsul($data)
	{	
		$bidang  		= $this->session->userdata('session_bidang');
		
		$this->setSedangKerja($data);		
		
		$nip  			= $data['nip'];
		$layanan_id		= $data['layanan_id'];
		$id_agenda      = $data['id_agenda'];
		
		
		$bidang  = $this->session->userdata('session_bidang');
		$user_id = $this->session->userdata('user_id');
		$tipe    = $this->session->userdata('session_user_tipe');
		
		if($bidang == 1)
		{
		    // tipe 2 = kabid , tipe 3 kanreg
			if($tipe  == 2 || $tipe == 3)
			{	
				$sql_work =" ";
			}
			else
			{
				$sql_work =" AND a.work_by = '$user_id'  "; 
			}
		}
        else
        {
            $sql_work =" ";
        }		
	    
		$q ="SELECT a.agenda_id,a.tahapan_id, a.nip,a.nomi_locked,a.nomi_status,a.nomi_alasan, a.locked_by,
b.layanan_id,b.agenda_ins,b.agenda_nousul,b.agenda_timestamp,b.agenda_dokumen,
c.layanan_nama, f.INS_NAMINS instansi,
g.PNS_PNSNAM nama,g.PNS_GOLRU,
group_concat(d.dokumen_id SEPARATOR ',') dokumen_id , 
group_concat(e.nama_dokumen SEPARATOR ',') nama_dokumen,
GROUP_CONCAT(IF(e.flag = 1,e.nama_dokumen, NULL) SEPARATOR ',')  main_dokumen,
GROUP_CONCAT(h.raw_name SEPARATOR ',')  upload_raw_name,
GROUP_CONCAT(i.nama_dokumen SEPARATOR ',')  upload_dokumen,
GROUP_CONCAT(IF(i.flag = 1,h.file_name, NULL) SEPARATOR ',')  main_upload_dokumen,
GROUP_CONCAT(IF(i.flag = 1,h.file_type, NULL) SEPARATOR ',')  file_type,
j.first_name lock_name,
k.tahapan_nama
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
LEFT JOIN $this->tabletahapan k ON a.tahapan_id = k.tahapan_id
where a.nip='$nip' 
AND a.nomi_status='BELUM'
AND a.tahapan_id IN ('4','5','6','7','8','9','10','11','12')
AND b.layanan_id='$layanan_id'  
AND a.agenda_id='$id_agenda' 
$sql_work
GROUP BY a.nip,b.layanan_id,a.agenda_id";  
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
	
	function getTahap($data)
	{
	    $nip  			= $data['nip'];
		$agenda         = $data['id_agenda'];
		
		// default tahapan sedang kerja
		$tahapan_id     = 5;
		
		$sql="SELECT tahapan_id FROM $this->tablenom WHERE agenda_id='$agenda' AND nip='$nip' "	;
		$query   =  $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
            $row  = $query->row();
			$tahapan_id   = $row->tahapan_id;
		}			
		
		return $tahapan_id;
	}	

	public function setSedangKerja($data)
	{
		$tahapan_id			  = $this->getTahap($data);
		$bidang  			  = $this->session->userdata('session_bidang');
		switch($tahapan_id){
			case 4:
			   $set['tahapan_id']    = 5;	
			break;
			case 5:
			   $set['tahapan_id']    = 6;	
			break;
			case 6:
			   $set['tahapan_id']    = 6;	
			break;
			case 7:
			   $set['tahapan_id']    = 7;	
			break;
			case 8:
			   $set['tahapan_id']    = 9;	
			break;
			case 9:
			   $set['tahapan_id']    = 9;	
			break;
			case 10:
			   $set['tahapan_id']    = 11;	
			break;
			default:
			    $set['tahapan_id']    = $tahapan_id;
		}	
		
		// jika bidang pensiun
		if($bidang == 2)
		{
		    $set['work_by']	      = $this->session->userdata('user_id');
        }
		
		
		$this->db->set($set);
		$this->db->where('agenda_id', $data['id_agenda']);		
		$this->db->where('nip', $data['nip']);
		return $this->db->update($this->tablenom);
	}	
	
	public function setKerja($data)
	{
		
		$r					  = FALSE;
		$set['nomi_locked']   = '1';		
		$set['locked_by']	  = $this->session->userdata('user_id');
		
		$tipe    = $this->session->userdata('session_user_tipe');
		switch($tipe){
		    case 1:
			    $set['tahapan_id']    = 6;	
			break;
			case 2:
			    $set['tahapan_id']    = 8;	
			break;
			case 3:
			    $set['tahapan_id']    = 10;	
			break;
		}
		
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
		$golongan                     = $data['golongan'];
		$finish					      = $data['finish'];
		$layanan_id                   = $data['layanan_id'];
		
		$tipe    				      = $this->session->userdata('session_user_tipe');
		
		// jika layanan pindah instansi hanya eeselon 2 spesimen
		// eselon 4 dan 3 hanya periksa , kalau PG eselon 4 periksa 
		// eselon 3 ttd surat
		if($layanan_id == 13 || $layanan_id == 14)
		{
	        $set['tahapan_id']   	  = 7;
	    }
		else
		{	
			switch($golongan){
				case 11:
					$set['nomi_status']   	  = $data['status'];
					$set['nomi_alasan']		  = $data['catatan'];
					$set['nomi_verifby']	  = $this->session->userdata('user_id');
					$set['tahapan_id']   	  = 7;
					$set['kpp_status']		  = $data['kpp_status'];
					$this->db->set('verify_date','NOW()',FALSE);
				break;
				case 12:
					$set['nomi_status']   	  = $data['status'];
					$set['nomi_alasan']		  = $data['catatan'];
					$set['nomi_verifby']	  = $this->session->userdata('user_id');
					$set['tahapan_id']   	  = 7;		
					$set['kpp_status']		  = $data['kpp_status'];
					$this->db->set('verify_date','NOW()',FALSE);				
				break;
				case 13:
					$set['nomi_status']   	  = $data['status'];
					$set['nomi_alasan']		  = $data['catatan'];
					$set['nomi_verifby']	  = $this->session->userdata('user_id');
					$set['tahapan_id']   	  = 7;	
					$set['kpp_status']		  = $data['kpp_status'];
					$this->db->set('verify_date','NOW()',FALSE);				
				break;
				case 14:
					$set['nomi_status']   	  = $data['status'];
					$set['nomi_alasan']		  = $data['catatan'];
					$set['nomi_verifby']	  = $this->session->userdata('user_id');
					$set['tahapan_id']   	  = 7;	
					$set['kpp_status']		  = $data['kpp_status'];
					$this->db->set('verify_date','NOW()',FALSE);				
				break;
				case 21:
					$set['nomi_status']   	  = $data['status'];
					$set['nomi_alasan']		  = $data['catatan'];
					$set['nomi_verifby']	  = $this->session->userdata('user_id');
					$set['tahapan_id']   	  = 7;	
					$set['kpp_status']		  = $data['kpp_status'];
					$this->db->set('verify_date','NOW()',FALSE);				
				break;
				case 22:
					$set['nomi_status']   	  = $data['status'];
					$set['nomi_alasan']		  = $data['catatan'];
					$set['nomi_verifby']	  = $this->session->userdata('user_id');
					$set['tahapan_id']   	  = 7;		
					$set['kpp_status']		  = $data['kpp_status'];
                    $this->db->set('verify_date','NOW()',FALSE);					
				break;
				case 23:
					$set['nomi_status']   	  = $data['status'];
					$set['nomi_alasan']		  = $data['catatan'];
					$set['nomi_verifby']	  = $this->session->userdata('user_id');
					$set['tahapan_id']   	  = 7;
					$set['kpp_status']		  = $data['kpp_status'];
					$this->db->set('verify_date','NOW()',FALSE);				
				break;
				case 24:
					$set['nomi_status']   	  = $data['status'];
					$set['nomi_alasan']		  = $data['catatan'];
					$set['nomi_verifby']	  = $this->session->userdata('user_id');
					$set['tahapan_id']   	  = 7;		
					$set['kpp_status']		  = $data['kpp_status'];
					$this->db->set('verify_date','NOW()',FALSE); 				
				break;
				case 31:
					$set['nomi_status']   	  = $data['status'];
					$set['nomi_alasan']		  = $data['catatan'];
					$set['nomi_verifby']	  = $this->session->userdata('user_id');
					$set['tahapan_id']   	  = 7;	
					$set['kpp_status']		  = $data['kpp_status'];
					$this->db->set('verify_date','NOW()',FALSE);				
				break;
				case 32:
					$set['nomi_status']   	  = $data['status'];
					$set['nomi_alasan']		  = $data['catatan'];
					$set['nomi_verifby']	  = $this->session->userdata('user_id');
					$set['tahapan_id']   	  = 7;	
					$set['kpp_status']		  = $data['kpp_status'];
					$this->db->set('verify_date','NOW()',FALSE);				
				break;
				case 33:
					$set['nomi_status']   	  = $data['status'];
					$set['nomi_alasan']		  = $data['catatan'];
					$set['nomi_verifby']	  = $this->session->userdata('user_id');
					$set['tahapan_id']   	  = 7;	// end verifikasi jika KP sampai III/D	
					$set['kpp_status']		  = $data['kpp_status'];
					$this->db->set('verify_date','NOW()',FALSE);				
				break;
				case 34:
					// mulai dari sini pengawas cuma paraf				
					$set['tahapan_id']   	  = 7;   
					$set['kpp_status']		  = $data['kpp_status'];
				break;
				case 41:
					$set['tahapan_id']   	  = 7;	
					$set['kpp_status']		  = $data['kpp_status'];
				break;
				case 42:
					$set['tahapan_id']   	  = 7;
					$set['kpp_status']		  = $data['kpp_status'];
				break;
				case 43:
					$set['tahapan_id']   	  = 7;
					$set['kpp_status']		  = $data['kpp_status'];	
				break;
				case 44:
					$set['tahapan_id']   	  = 7;
					$set['kpp_status']		  = $data['kpp_status'];	
				break;
				case 45:
					$set['tahapan_id']   	  = 7;
					$set['kpp_status']		  = $data['kpp_status'];	
				break;
			}	
		}
		
		switch($tipe){
			// eselon 4
		    case 1:
			    $set['status_level_satu'] = $data['status'];
				$set['alasan_level_satu'] = $data['catatan'];
				$set['verifby_level_satu']= $this->session->userdata('user_id');
				$this->db->set('verifdate_level_satu','NOW()',FALSE);
				$status					  = $data['status'];
				// jika status BTL atau TMS maka berkas berakhir di eselon 4
				if($status == 'BTL' || $status == 'TMS')
				{
					$set['nomi_status']   	  = $data['status'];
					$set['nomi_alasan']		  = $data['catatan'];
					$set['nomi_verifby']	  = $this->session->userdata('user_id');
					$this->db->set('verify_date','NOW()',FALSE);
				}
				// jika golongan 34 dan Pensiun KPP maka berkas berakhir di eselon 4
				if($golongan == 34 && $data['kpp_status'] == 2)
				{
					$set['nomi_status']   	  = $data['status'];
					$set['nomi_alasan']		  = $data['catatan'];
					$set['nomi_verifby']	  = $this->session->userdata('user_id');
					$this->db->set('verify_date','NOW()',FALSE);	
                }			
			break;
			// eselon 3
			case 2:
			    $set['status_level_dua'] = $data['status'];
				$set['alasan_level_dua'] = $data['catatan'];
				$set['verifby_level_dua']= $this->session->userdata('user_id');
				$set['tahapan_id']   	  = 9;
				$this->db->set('verifdate_level_dua','NOW()',FALSE);
				
				if($finish == 1)				{
					$set['nomi_status']   	  = $data['status'];
					$set['nomi_alasan']		  = $data['catatan'];
					$set['nomi_verifby']	  = $this->session->userdata('user_id');
					$this->db->set('verify_date','NOW()',FALSE);					
				}
				
			break;
			// eselon 2
			case 3:
			    $set['status_level_tiga'] = $data['status'];
				$set['alasan_level_tiga'] = $data['catatan'];
				$set['verifby_level_tiga']= $this->session->userdata('user_id');
				$set['tahapan_id']   	  = 11;
				$this->db->set('verifdate_level_tiga','NOW()',FALSE);
				$this->db->set('verify_date','NOW()',FALSE);
				
				// always finish
				$set['nomi_status']   	  = $data['status'];
				$set['nomi_alasan']		  = $data['catatan'];
				$set['nomi_verifby']	  = $this->session->userdata('user_id');
				$this->db->set('verify_date','NOW()',FALSE);
			break;
		}
		
		
		// jika layanan KARIS/KARSU/KARPEG LANGSUNG FINISH ACC HANYA SAMPAI LEVEL 1
		switch($layanan_id){
		    case 9:
			    $set['nomi_status']   	  = $data['status'];
				$set['nomi_alasan']		  = $data['catatan'];
				$set['nomi_verifby']	  = $this->session->userdata('user_id');
				$this->db->set('verify_date','NOW()',FALSE);
			break;
			case 10:
			    $set['nomi_status']   	  = $data['status'];
				$set['nomi_alasan']		  = $data['catatan'];
				$set['nomi_verifby']	  = $this->session->userdata('user_id');
				$this->db->set('verify_date','NOW()',FALSE);
			break;
			case 11:
			    $set['nomi_status']   	  = $data['status'];
				$set['nomi_alasan']		  = $data['catatan'];
				$set['nomi_verifby']	  = $this->session->userdata('user_id');
				$this->db->set('verify_date','NOW()',FALSE);
			break;
		}	
		
		// jika status BTL rekam BTL FROM
		if($status == 'BTL')
		{	
			// 4 BTL FROM Teknis
			$set['btl_from']	  	  = 4;
			$set['btl_teknis_alasan'] = $data['catatan'];			
			$this->db->set('btl_teknis_date','NOW()',FALSE);
			$this->db->set('btl_counter','btl_counter+1',FALSE);	
        }	
		
		$this->db->set($set);		
		$this->db->where('agenda_id', $data['id_agenda']);		
		$this->db->where('nip', $data['nip']);
		return $this->db->update($this->tablenom);
	}	
	
	
	public function getAgenda_byid($id_agenda,$nip)
	{    
		$sql="SELECT a.* , 
		b.layanan_grup, b.layanan_bidang, b.layanan_nama ,
		c.INS_NAMINS instansi,
		d.nip, d.nomi_status, d.nomi_alasan,d.status_level_satu, d.status_level_dua,d.status_level_tiga,
		e.PNS_PNSNAM,e.PNS_GLRDPN, e.PNS_GLRBLK,
		f.tahapan_nama
		FROM $this->tableagenda a
		LEFT JOIN $this->tablelayanan  b ON a.layanan_id = b.layanan_id
		LEFT JOIN $this->tableinstansi c ON a.agenda_ins = c.INS_KODINS
		LEFT JOIN $this->tablenom d ON a.agenda_id  = d.agenda_id
		LEFT JOIN $this->tablepupns e ON e.PNS_NIPBARU = d.nip
		LEFT JOIN $this->tabletahapan f ON f.tahapan_id = d.tahapan_id
		WHERE a.agenda_id='$id_agenda' AND d.nip='$nip' ";
    	return $this->db->query($sql);
	}
	
	function getTelegramAkun_byInstansi($instansi)
	{	
		$this->db->select('first_name,last_name,telegram_id');
		$this->db->where('id_instansi', $instansi);
		return $this->db->get($this->tableuser);		
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
CASE a.nomi_status
	WHEN 'ACC' THEN 'badge bg-green'
	WHEN 'TMS' THEN 'badge bg-red'
	WHEN 'BTL' THEN 'badge bg-yellow'
	ELSE 'badge bg-light-blue'
END AS bg,
b.agenda_ins, b.agenda_nousul,b.layanan_id,b.agenda_timestamp,
c.layanan_nama,c.layanan_kode, 
d.INS_NAMINS instansi, 
e.PNS_PNSNAM nama
from $this->tablenom a
LEFT JOIN $this->tableagenda b ON a.agenda_id = b.agenda_id
LEFT JOIN $this->tablelayanan c ON b.layanan_id = c.layanan_id
LEFT JOIN $this->tableinstansi d ON d.INS_KODINS = b.agenda_ins
LEFT JOIN $this->tablepupns e ON e.PNS_NIPBARU = a.nip
WHERE 1=1 $sql_instansi  $sql_layanan   $sql_date  $sql_status  $sql_verify  order by e.PNS_PNSNAM ASC";
       
	   //var_dump($sql);

		return $this->db->query($sql);

	}	
	
	/*TASPEN*/
	public function getUsulDokumenTaspen()
	{
		$searchby  = $this->input->post('searchby');
		$search    =  $this->input->post('search');		
		
		$bidang  = $this->session->userdata('session_bidang');
		$user_id = $this->session->userdata('user_id');
		$tipe    = $this->session->userdata('session_user_tipe');
		
		switch($searchby){
            case 1:
			    $sql = " AND f.nip = '$search' ";
            break;
            case 2:
			   $sql = " AND  f.nip = '999999999'  ";
            break;
			case 3:
			   $search = trim($search);			   
			   $sql = " AND  UPPER(f.nomor_usul)=UPPER('$search')";
            break;
			case 4:
			   $sql = " AND  UPPER(g.layanan_nama) LIKE UPPER('%$search%') ";
            break;
            default:
                $sql = " AND f.nip = '999999999' ";		
		}	
		
		$q  ="SELECT f.*,
		g.layanan_nama, 
		h.tahapan_nama,
		i.first_name usul_lock_name
		FROM (SELECT a.*, 
group_concat(b.dokumen_id SEPARATOR ',') syarat_dokumen_id , 
group_concat(c.nama_dokumen SEPARATOR ',') syarat_nama_dokumen,
GROUP_CONCAT(IF(c.flag = 1,c.nama_dokumen, NULL) SEPARATOR ',')  syarat_main_dokumen,
GROUP_CONCAT(d.id_dokumen SEPARATOR ',')  upload_dokumen_id,
GROUP_CONCAT(e.keterangan SEPARATOR ',')  upload_nama_dokumen,
GROUP_CONCAT(IF(c.flag = 1,d.file_name, NULL) SEPARATOR ',')  main_upload_dokumen
FROM usul_taspen a
LEFT JOIN syarat_layanan_taspen b ON a.layanan_id = b.layanan_id
LEFT JOIN dokumen_taspen c ON b.dokumen_id = c.id_dokumen
LEFT JOIN upload_dokumen_taspen d ON (a.nip = d.nip AND d.id_dokumen = b.dokumen_id)
LEFT JOIN dokumen_taspen e ON e.id_dokumen = d.id_dokumen
GROUP BY a.layanan_id,a.usul_id
) f
LEFT JOIN layanan g ON f.layanan_id = g.layanan_id
LEFT JOIN tahapan h ON f.usul_tahapan_id = h.tahapan_id
LEFT JOIN app_user i ON i.user_id = f.usul_lock_by    
WHERE f.usul_status='BELUM' $sql ";	
		$query 		= $this->db->query($q);
		return      $query;			
	}

    public function getAllTabTaspen($data)
	{		
	    $nip		= $data['nip'];
		$usul_id    = $data['usul_id'];
		
		$sql="SELECT a.*,b.*,
group_concat(minor_dok SEPARATOR ',') grup_dok ,
GROUP_CONCAT(raw_name SEPARATOR ',') upload_raw_name  
FROM upload_dokumen_taspen a 
LEFT JOIN dokumen_taspen b ON a.id_dokumen = b.id_dokumen 		
where a.nip ='$nip' 
AND b.flag IS NULL AND b.aktif='1'
group by b.id_dokumen order by created_date desc";
             	
		$query 		= $this->db->query($sql);
		
		
        return      $query;		
    }

	public function getAllDokumenTaspen($data)
	{		
	    $nip		= $data['nip'];
		$usul_id    = $data['usul_id'];
		
		$sql 		="SELECT a.*,b.* 
FROM upload_dokumen_taspen a 
LEFT JOIN dokumen_taspen b ON a.id_dokumen = b.id_dokumen 
where a.nip ='$nip'
AND b.flag IS NULL";
	    $query 		= $this->db->query($sql);
        return      $query;		
    }

	public function getVerifyUsulTaspen($data)
	{	
		$bidang  		= $this->session->userdata('session_bidang');
		
		//$this->setSedangKerja($data);		
		
		$nip  			= $data['nip'];
		$layanan_id		= $data['layanan_id'];
		$usul_id        = $data['usul_id'];
			    
		$q ="SELECT f.*,
		g.layanan_nama, 
		h.tahapan_nama,
		i.first_name usul_lock_name
		FROM (SELECT a.*, 
group_concat(b.dokumen_id SEPARATOR ',') syarat_dokumen_id , 
group_concat(c.nama_dokumen SEPARATOR ',') syarat_nama_dokumen,
GROUP_CONCAT(IF(c.flag = 1,c.nama_dokumen, NULL) SEPARATOR ',')  syarat_main_dokumen,
GROUP_CONCAT(d.id_dokumen SEPARATOR ',')  upload_dokumen_id,
GROUP_CONCAT(e.keterangan SEPARATOR ',')  upload_nama_dokumen,
GROUP_CONCAT(IF(c.flag = 1,d.file_name, NULL) SEPARATOR ',')  main_upload_dokumen
FROM usul_taspen a
LEFT JOIN syarat_layanan_taspen b ON a.layanan_id = b.layanan_id
LEFT JOIN dokumen_taspen c ON b.dokumen_id = c.id_dokumen
LEFT JOIN upload_dokumen_taspen d ON (a.nip = d.nip AND d.id_dokumen = b.dokumen_id)
LEFT JOIN dokumen_taspen e ON e.id_dokumen = d.id_dokumen
GROUP BY a.layanan_id,a.usul_id
) f
LEFT JOIN layanan g ON f.layanan_id = g.layanan_id
LEFT JOIN tahapan h ON f.usul_tahapan_id = h.tahapan_id
LEFT JOIN app_user i ON i.user_id = f.usul_lock_by
where f.usul_status='BELUM' AND  f.usul_id='$usul_id' ";  
        //var_dump($q);exit;
		$query 		= $this->db->query($q);
        return      $query;		
    }	
	
	public function setKerjaTaspen($data)
	{
		
		$r					  = FALSE;
		$set['usul_locked']   = 1;		
		$set['usul_lock_by']  = $this->session->userdata('user_id');
		$set['usul_tahapan_id']    = 6;	
		
		
        $this->db->trans_start();
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		$this->db->set($set);
		$this->db->set('usul_lock_date','NOW()',FALSE);
		$this->db->where('usul_id', $data['usul_id']);		
		$this->db->where('nip', $data['nip']);
		if (!$this->db->update($this->usul))
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
	
	public function setHasilVerifikatorTaspen($data)
	{
		
		$data['usul_verif_by']      = $this->session->userdata('user_id');
		$data['usul_tahapan_id']    = 7;	
						
		$this->db->set($data);		
		$this->db->set('usul_verif_date','NOW()',FALSE);
		$this->db->where('usul_id', $data['usul_id']);		
		$this->db->where('nip', $data['nip']);
		return $this->db->update($this->usul);
	}		
	
	public function getKinerjaTaspen($data)
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
		
		

        if(!empty($layanan))
		{
		
			$sql_layanan = " AND a.layanan_id = '$layanan' ";
        }
		else
		{	
			$sql_layanan = " ";   
		}	

		if(!empty($startdate) AND !empty($enddate))
		{
		
			$sql_date = " AND DATE( a.usul_verif_date ) BETWEEN STR_TO_DATE( '$startdate', '%d/%m/%Y ' )
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
			$sql_status = " AND a.usul_status = '$status' ";
		}			
		
		if(!empty($verify))
		{
			$sql_verify = "  AND a.usul_verif_by ='$verify'";
        }
        else
		{
			$sql_verify = " ";   
		}		
		
		$sql="SELECT a.*,DATE_FORMAT(a.tgl_usul,'%d-%m-%Y') tgl,
		CASE a.usul_status
			WHEN 'ACC' THEN 'badge bg-green'
			WHEN 'TMS' THEN 'badge bg-red'
			WHEN 'BTL' THEN 'badge bg-yellow'
			ELSE 'badge bg-light-blue'
		END AS bg,
		b.layanan_nama,
		c.tahapan_nama,
		d.PNS_NIPBARU nip_baru, d.PNS_PNSNIP nip_lama,
		e.first_name kirim_by,
		f.first_name usul_kirim_name,
		g.first_name usul_lock_name,
		h.first_name usul_verif_name,
		i.first_name usul_entry_name
		FROM $this->usul a
		LEFT JOIN $this->tablelayanan b ON a.layanan_id = b.layanan_id	
		LEFT JOIN $this->tabletahapan c ON c.tahapan_id = a.usul_tahapan_id
		LEFT JOIN $this->tablepupns d ON (a.nip = d.PNS_NIPBARU OR a.nip = d.PNS_PNSNIP)
		LEFT JOIN $this->tableuser e ON e.user_id = a.kirim_bkn_by
		LEFT JOIN $this->tableuser f ON f.user_id = a.usul_kirim_by
		LEFT JOIN $this->tableuser g ON g.user_id = a.usul_lock_by
		LEFT JOIN $this->tableuser h ON h.user_id = a.usul_verif_by
		LEFT JOIN $this->tableuser i ON i.user_id = a.usul_entry_by
        WHERE 1=1  $sql_verify  $sql_layanan $sql_status $sql_date";
       
	   //var_dump($sql);

		return $this->db->query($sql);

	}	
	
	public function setUnlockTaspen($data)
	{
		$set['usul_locked']   = NULL;
		$set['usul_lock_by']	  = NULL;
		
		$this->db->set($set);
		$this->db->where('usul_id', $data['usul_id']);		
		$this->db->where('nip', $data['usul_nip']);
		return $this->db->update($this->usul);
	}
	
	public function getUsul_byid($data)
	{
		$usul_id   	= $data['usul_id'];
		$nip		= $data['nip'];
		
		$sql ="SELECT a.* ,
		b.layanan_nama,
		c.tahapan_nama
		FROM $this->usul a
		LEFT JOIN $this->tablelayanan b ON a.layanan_id = b.layanan_id
		LEFT JOIN $this->tabletahapan c ON a.usul_tahapan_id = c.tahapan_id
		WHERE a.usul_id='$usul_id' AND a.nip=trim('$nip')  ";
		return $this->db->query($sql);
	}
	
	function getTelegramAkun_byUserid($user_id)
	{	
		$this->db->select('first_name,last_name,telegram_id');
		$this->db->where('user_id', $user_id);
		return $this->db->get($this->tableuser);		
	}	
}