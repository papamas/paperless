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
	private     $kantorTaspen		= 'kantor_taspen';
	private     $spesimenTaspen     = 'spesimen_taspen';
		
    function __construct()
    {
        parent::__construct();
	}
	
	public function getAllTab($nip)
	{		
	    // jika layanan perbaikan pertek pensiun hilangkan status aktif
		$layanan_id = $this->session->userdata('layanan_id');
		
		if($layanan_id == 5)
		{
			$sql_aktif = " ";
		}	
		else
		{
			$sql_aktif = " AND b.aktif='1'";
		}
		
		$sql="SELECT a.*,b.*,
		group_concat(minor_dok SEPARATOR ',') grup_dok ,
		GROUP_CONCAT(raw_name SEPARATOR ',') upload_raw_name  
		FROM $this->table a 
		LEFT JOIN $this->tabledokumen b ON a.id_dokumen = b.id_dokumen 		
		where a.nip ='$nip' AND b.flag IS NULL 
		$sql_aktif
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
		$bidang  			          = $this->session->userdata('session_bidang');
		
		$tipe    				      = $this->session->userdata('session_user_tipe');
		
		// jika layanan pindah instansi hanya eselon 2 spesimen
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
				
				// jika bidang pensiun golongan 34 dan Pensiun NON KPP maka berkas berakhir di eselon 4
				if($bidang == 2 && $golongan == 34 && $data['kpp_status'] == NULL)
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
										
				//  Jika berakhir di eselon 3
				if($finish == 1)				{
					$set['nomi_status']   	  = $data['status'];
					$set['nomi_alasan']		  = $data['catatan'];
					$set['nomi_verifby']	  = $this->session->userdata('user_id');
					$this->db->set('verify_date','NOW()',FALSE);					
				}
				$status					  = $data['status'];
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
				$status					  = $data['status'];
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

        // jika status BTL atau TMS maka berkas berakhir
		if($status == 'BTL' || $status == 'TMS')
		{
			$set['nomi_status']   	  = $data['status'];
			$set['nomi_alasan']		  = $data['catatan'];
			$set['nomi_verifby']	  = $this->session->userdata('user_id');
			$this->db->set('verify_date','NOW()',FALSE);
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
		
			$sql_date = " AND (DATE( a.verify_date ) BETWEEN STR_TO_DATE( '$startdate', '%d/%m/%Y ' )
			AND STR_TO_DATE( '$enddate', '%d/%m/%Y')
			OR DATE( a.verifdate_level_satu ) BETWEEN STR_TO_DATE( '$startdate', '%d/%m/%Y ' )
			AND STR_TO_DATE( '$enddate', '%d/%m/%Y' )
			OR DATE( a.verifdate_level_dua ) BETWEEN STR_TO_DATE( '$startdate', '%d/%m/%Y ' )
			AND STR_TO_DATE( '$enddate', '%d/%m/%Y' )
			OR DATE( a.verifdate_level_tiga ) BETWEEN STR_TO_DATE( '$startdate', '%d/%m/%Y ' )
			AND STR_TO_DATE( '$enddate', '%d/%m/%Y' )
			) ";
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
			$sql_status = " AND (a.nomi_status = '$status'  
			OR a.status_level_satu = '$status'
			OR a.status_level_dua = '$status'
			OR a.status_level_tiga = '$status') ";
		}			
		
		if(!empty($verify))
		{
			$sql_verify = "  AND (a.nomi_verifby ='$verify'
			OR a.verifby_level_satu ='$verify' 
			OR a.verifby_level_dua ='$verify'
			OR a.verifby_level_tiga ='$verify')";
        }
        else
		{
			$sql_verify = " ";   
		}		
		
		$sql="select a.agenda_id, a.nip,
		a.nomi_status, a.nomi_alasan, a.verify_date,
		a.status_level_satu, a.verifdate_level_satu, CONCAT(f.first_name,' ',f.last_name) verif_name_satu,
		a.status_level_dua , a.verifdate_level_dua, CONCAT(g.first_name,' ',g.last_name) verif_name_dua,
		a.status_level_tiga, a.verifdate_level_tiga, CONCAT(h.first_name,' ',h.last_name) verif_name_tiga,
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
LEFT JOIN $this->tableuser f ON f.user_id = verifby_level_satu
LEFT JOIN $this->tableuser g ON g.user_id = verifby_level_dua
LEFT JOIN $this->tableuser h ON h.user_id = verifby_level_tiga
WHERE 1=1 
$sql_instansi 
$sql_layanan
$sql_date 
$sql_status
$sql_verify 
order by e.PNS_PNSNAM ASC";
       
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
		
		$q  ="SELECT a.* FROM 
( SELECT f.*,
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
WHERE f.usul_status='BELUM'
AND f.usul_tahapan_id IN ('4','5','6','7','8','9','10','11')
$sql ) a
INNER JOIN user_layanan_role  b ON (a.layanan_id=b.layanan_id AND  b.id_instansi =9 AND b.user_id='$user_id')";	
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
			    
		$q ="SELECT 
		f.*,
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
where f.usul_status='BELUM' 
AND f.usul_tahapan_id IN ('4','5','6','7','8','9','10','11')
AND  f.usul_id='$usul_id' ";  
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
		e.first_name kirim_by,
		f.first_name usul_kirim_name,
		g.first_name usul_lock_name,
		h.first_name usul_verif_name,
		i.first_name usul_entry_name
		FROM $this->usul a
		LEFT JOIN $this->tablelayanan b ON a.layanan_id = b.layanan_id	
		LEFT JOIN $this->tabletahapan c ON c.tahapan_id = a.usul_tahapan_id
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
		$this->db->where('active', 1);
		$this->db->where('user_id', $user_id);
		return $this->db->get($this->tableuser);		
	}	
	
	/* Draft SK TASPEN*/
	public function getEntryOneTaspen($data)
	{
		$usul			= $data['usul_id'] ;
		$nip			= $data['nip'];
		
		$sql   = "SELECT a.*,
		b.PNS_PNSNAM nama_spesimen, b.PNS_GLRBLK glrblk, b.PNS_GLRDPN glrdpn
		FROM (SELECT a.*,DATE_FORMAT(a.tgl_usul,'%d-%m-%Y') tgl,
		DATE_FORMAT(a.usul_tgl_persetujuan,'%d-%m-%Y') tgl_persetujuan,
		DATE_FORMAT(a.pensiun_tmt,'%d-%m-%Y') tmt_pensiun,
		DATE_FORMAT(a.meninggal_dunia,'%d-%m-%Y') tgl_meninggal,
		DATE_FORMAT(a.tgl_perkawinan,'%d-%m-%Y') atgl_perkawinan,
		formatTanggal(a.tgl_lahir) atgl_lahir,
		formatTanggal(a.tgl_skep) atgl_skep,
		formatTanggal(a.meninggal_dunia) meninggal,
		formatTanggal(a.tgl_perkawinan) perkawinan,
		formatTanggal(a.pensiun_tmt) pensiun,
		formatTanggal(a.usul_tgl_persetujuan) persetujuan_tgl,
		formatTanggal(a.tgl_usul) atgl_usul,
		replace(format(a.gaji_pokok_terakhir,0),',','.') gapok,
		replace(format(a.pensiun_pokok,0),',','.') penpok,
		b.layanan_nama,
		c.tahapan_nama,
		e.first_name kirim_by,
		f.first_name usul_kirim_name,
		g.first_name usul_lock_name,
		h.first_name usul_verif_name,
		i.first_name usul_entry_name,
		j.GOl_PKTNAM,j.GOL_GOLNAM,
		k.nama_taspen,
		l.nip nip_spesimen, l.jabatan jabatan_spesimen,
		m.nama nama_anak,
		m.nama_ayah,
		m.nama_ibu,
		formatTanggal(m.tgl_lahir) tgl_lahir_anak,
		m.keterangan
		FROM $this->usul a
		LEFT JOIN $this->tablelayanan b ON a.layanan_id = b.layanan_id	
		LEFT JOIN $this->tabletahapan c ON c.tahapan_id = a.usul_tahapan_id
		LEFT JOIN $this->tableuser e ON e.user_id = a.kirim_bkn_by
		LEFT JOIN $this->tableuser f ON f.user_id = a.usul_kirim_by
		LEFT JOIN $this->tableuser g ON g.user_id = a.usul_lock_by
		LEFT JOIN $this->tableuser h ON h.user_id = a.usul_verif_by
		LEFT JOIN $this->tableuser i ON i.user_id = a.usul_entry_by
		LEFT JOIN $this->tablegolru j ON j.GOL_KODGOL = a.golongan
		LEFT JOIN $this->kantorTaspen k ON k.id_taspen = a.kantor_taspen
		LEFT JOIN $this->spesimenTaspen l ON l.nip = a.usul_spesimen
		LEFT JOIN jd_dd_anak m ON m.usul_id = a.usul_id
		WHERE a.nip='$nip' AND a.usul_id='$usul' ) a
		LEFT JOIN $this->tablepupns b ON a.nip_spesimen = b.PNS_NIPBARU";
		
		$query 	=   $this->db->query($sql);
		return      $query;	
	}	
	
	public function getMutasiIstri($usul_id)
	{
		$sql   = "SELECT a.*,
		DATE_FORMAT(a.tgl_lahir,'%d-%m-%Y') atgl_lahir,
		DATE_FORMAT(a.tgl_nikah,'%d-%m-%Y') atgl_nikah,
		DATE_FORMAT(a.tgl_pendaftaran,'%d-%m-%Y') atgl_pendaftaran,
		DATE_FORMAT(a.tgl_cerai,'%d-%m-%Y') atgl_cerai,
		DATE_FORMAT(a.tgl_wafat,'%d-%m-%Y') atgl_wafat
		FROM mutasi_istri_suami a
		WHERE a.usul_id='$usul_id'";
		$query 	=   $this->db->query($sql);
		return      $query;	
	}	
	
	public function getMutasiAnak($usul_id)
	{
		$sql   = "SELECT a.*,
		DATE_FORMAT(a.tgl_lahir,'%d-%m-%Y') atgl_lahir,
		DATE_FORMAT(a.cerai_tgl,'%d-%m-%Y') acerai_tgl,
		DATE_FORMAT(a.meninggal_tgl,'%d-%m-%Y') ameninggal_tgl
		FROM mutasi_anak a 
		WHERE a.usul_id='$usul_id'";
		$query 	=   $this->db->query($sql);
		return      $query;	
	}

	public function getSpesimenTaspen()
	{
		$sql="SELECT a.* ,
		b.PNS_PNSNAM nama_spesimen, b.PNS_GLRBLK glrblk, b.PNS_GLRDPN glrdpn
		FROM $this->spesimenTaspen a
		LEFT JOIN $this->tablepupns b ON a.nip = b.PNS_NIPBARU
		WHERE a.aktif='1' ";	
		return $this->db->query($sql);
		
	}

    public function getAnakJd($id)
	{
		$sql=" SELECT jd_dd_anak_id, nama,nama_ibu,nama_ayah,keterangan,usul_id,
		DATE_FORMAT(tgl_lahir,'%d-%m-%Y') tgl_lahir	
		from jd_dd_anak  WHERE usul_id='$id'" ;
		return $this->db->query($sql);
	}	
	
	public function getIstri($id)
	{
		$sql=" SELECT mutasi_id, nama,nama_kecil,tempat_lahir,alamat,usul_id,
		DATE_FORMAT(tgl_lahir,'%d-%m-%Y') tgl_lahir,
		DATE_FORMAT(tgl_nikah,'%d-%m-%Y') tgl_nikah,
		DATE_FORMAT(tgl_pendaftaran,'%d-%m-%Y') tgl_pendaftaran,
		DATE_FORMAT(tgl_cerai,'%d-%m-%Y') tgl_cerai,
		DATE_FORMAT(tgl_wafat,'%d-%m-%Y') tgl_wafat
		from mutasi_istri_suami WHERE usul_id='$id' ";			
		return $this->db->query($sql);
	}	
	
	
	public function getAnak($id)
	{
		$sql=" SELECT mutasi_id, nama,sex,nama_ibu_ayah,usul_id,
		DATE_FORMAT(tgl_lahir,'%d-%m-%Y') tgl_lahir,		
		DATE_FORMAT(cerai_tgl,'%d-%m-%Y') cerai_tgl,
		DATE_FORMAT(meninggal_tgl,'%d-%m-%Y') meninggal_tgl
		from mutasi_anak  WHERE usul_id='$id' " ;			
		return $this->db->query($sql);
	}	
	
	public function simpanAnakJd()
	{
		$data['nama']				= $this->input->post('nama');
		$data['tgl_lahir']			= date('Y-m-d',strtotime($this->input->post('tgl_lahir')));
		$data['nama_ayah']		    = $this->input->post('nama_ayah');
		$data['nama_ibu']		    = $this->input->post('nama_ibu');
		$data['usul_id']			= $this->input->post('usul_id');
		$data['keterangan']			= $this->input->post('keterangan');
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 	
		if (!$this->db->insert('jd_dd_anak', $data))
		{
			$error = $this->db->_error_message();
			if(!empty($error))
			{
                $data['pesan']		= $error;   
				$data['response'] 	= FALSE;
			}            	
        }
		else
		{
			$data['pesan']		= "Data Anak Berhasil Tersimpan";
			$data['response']	= TRUE;
		}	
        $this->db->db_debug = $db_debug; //restore setting	

        return $data;		
	}
	
	public function updateAnakJd()
	{
		$data['nama']				= $this->input->post('nama');
		$data['tgl_lahir']			= date('Y-m-d',strtotime($this->input->post('tgl_lahir')));
		$data['nama_ayah']		    = $this->input->post('nama_ayah');
		$data['nama_ibu']		    = $this->input->post('nama_ibu');
		$data['usul_id']			= $this->input->post('usul_id');
		$data['keterangan']			= $this->input->post('keterangan');
		
		$temp_id			 		= $this->input->post('jd_dd_anak_id');
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 	
		
		$this->db->where('jd_dd_anak_id',$temp_id);
		if (!$this->db->update('jd_dd_anak', $data))
		{
			$error = $this->db->_error_message();
			if(!empty($error))
			{
                $data['pesan']		= $error;   
				$data['response'] 	= FALSE;
			}
            	
        }
		else
		{
			$data['pesan']		= "Data Anak Berhasil Terupdate";
			$data['response']	= TRUE;
		}	
        $this->db->db_debug = $db_debug; //restore setting	

        return $data;		
	}
	
	public function hapusAnakJd()
	{
		$id			  = $this->input->post('jd_dd_anak_id');
	   	$this->db->where('jd_dd_anak_id', $id);		
		return $this->db->delete('jd_dd_anak');
	
	}
	
	public function simpanAnak()
	{
		$data['nama']				= $this->input->post('nama');
		$data['sex']				= $this->input->post('sex');		
		$data['tgl_lahir']			= date('Y-m-d',strtotime($this->input->post('tgl_lahir')));
		$data['cerai_tgl']			= (!empty($this->input->post('tgl_cerai')) ? date('Y-m-d',strtotime($this->input->post('tgl_cerai'))) : NULL);
		$data['meninggal_tgl']		= (!empty($this->input->post('tgl_wafat')) ? date('Y-m-d',strtotime($this->input->post('tgl_wafat'))) : NULL);
		$data['nama_ibu_ayah']		= $this->input->post('nama_ibu_ayah');
		$data['usul_id']			= $this->input->post('usul_id');
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 	
		if (!$this->db->insert('mutasi_anak', $data))
		{
			$error = $this->db->_error_message();
			if(!empty($error))
			{
                $data['pesan']		= $error;   
				$data['response'] 	= FALSE;
			}
            	
        }
		else
		{
			$data['pesan']		= "Data Anak Berhasil Tersimpan";
			$data['response']	= TRUE;
		}	
        $this->db->db_debug = $db_debug; //restore setting	

        return $data;		
	}	
	

    public function updateAnak()
	{
		$data['nama']				= $this->input->post('nama');
		$data['sex']				= $this->input->post('sex');		
		$data['tgl_lahir']			= date('Y-m-d',strtotime($this->input->post('tgl_lahir')));
		$data['cerai_tgl']			= (!empty($this->input->post('tgl_cerai')) ? date('Y-m-d',strtotime($this->input->post('tgl_cerai'))) : NULL);
		$data['meninggal_tgl']		= (!empty($this->input->post('tgl_wafat')) ? date('Y-m-d',strtotime($this->input->post('tgl_wafat'))) : NULL);
		$data['nama_ibu_ayah']		= $this->input->post('nama_ibu_ayah');
		$temp_id			 		= $this->input->post('temp_mutasi_id');
		$data['usul_id']			= $this->input->post('usul_id');
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 	
		
		$this->db->where('mutasi_id',$temp_id);
		if (!$this->db->update('mutasi_anak', $data))
		{
			$error = $this->db->_error_message();
			if(!empty($error))
			{
                $data['pesan']		= $error;   
				$data['response'] 	= FALSE;
			}
            	
        }
		else
		{
			$data['pesan']		= "Data Anak Berhasil Terupdate";
			$data['response']	= TRUE;
		}	
        $this->db->db_debug = $db_debug; //restore setting	

        return $data;		
	}

	public function hapusAnak()
	{
		$id			  = $this->input->post('temp_mutasi_id');
	   	$this->db->where('mutasi_id', $id);		
		return $this->db->delete('mutasi_anak');
	
	}	
		
	public function simpanIstri()
	{
		$data['nama']				= $this->input->post('nama');
		$data['nama_kecil']			= $this->input->post('nama_kecil');
		$data['tempat_lahir']		= $this->input->post('tempat_lahir');
		$data['tgl_lahir']			= date('Y-m-d',strtotime($this->input->post('tgl_lahir')));
		$data['tgl_nikah']			= date('Y-m-d',strtotime($this->input->post('tgl_nikah')));
		$data['tgl_pendaftaran']	= date('Y-m-d',strtotime($this->input->post('tgl_pendaftaran')));
		$data['tgl_cerai']			= (!empty($this->input->post('tgl_cerai')) ? date('Y-m-d',strtotime($this->input->post('tgl_cerai'))) : NULL);
		$data['tgl_wafat']			= (!empty($this->input->post('tgl_wafat')) ? date('Y-m-d',strtotime($this->input->post('tgl_wafat'))) : NULL);
		$data['tempat_lahir']		= $this->input->post('tempat_lahir');
		$data['alamat']				= $this->input->post('alamat');
		$data['usul_id']			= $this->input->post('usul_id');
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 	
		if (!$this->db->insert('mutasi_istri_suami', $data))
		{
			$error = $this->db->_error_message();
			if(!empty($error))
			{
                $data['pesan']		= $error;   
				$data['response'] 	= FALSE;
			}
            	
        }
		else
		{
			$data['pesan']		= "Data Istri Berhasil Tersimpan";
			$data['response']	= TRUE;
		}	
        $this->db->db_debug = $db_debug; //restore setting	

        return $data;		
	}		
	
	public function updateIstri()
	{
		$data['nama']				= $this->input->post('nama');
		$data['nama_kecil']			= $this->input->post('nama_kecil');
		$data['tempat_lahir']		= $this->input->post('tempat_lahir');
		$data['tgl_lahir']			= date('Y-m-d',strtotime($this->input->post('tgl_lahir')));
		$data['tgl_nikah']			= date('Y-m-d',strtotime($this->input->post('tgl_nikah')));
		$data['tgl_pendaftaran']	= date('Y-m-d',strtotime($this->input->post('tgl_pendaftaran')));
		$data['tgl_cerai']			= (!empty($this->input->post('tgl_cerai')) ? date('Y-m-d',strtotime($this->input->post('tgl_cerai'))) : NULL);
		$data['tgl_wafat']			= (!empty($this->input->post('tgl_wafat')) ? date('Y-m-d',strtotime($this->input->post('tgl_wafat'))) : NULL);
		$data['tempat_lahir']		= $this->input->post('tempat_lahir');
		$data['alamat']				= $this->input->post('alamat');
		$temp_id			 		= $this->input->post('temp_mutasi_id');
		$data['usul_id']			= $this->input->post('usul_id');
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 	
		
		$this->db->where('mutasi_id',$temp_id);
		if (!$this->db->update('mutasi_istri_suami', $data))
		{
			$error = $this->db->_error_message();
			if(!empty($error))
			{
                $data['pesan']		= $error;   
				$data['response'] 	= FALSE;
			}
            	
        }
		else
		{
			$data['pesan']		= "Data Istri Berhasil Terupdate";
			$data['response']	= TRUE;
		}	
        $this->db->db_debug = $db_debug; //restore setting	

        return $data;		
	}
	
	public function hapusIStri()
	{
		$id			  = $this->input->post('temp_mutasi_id');
	   	$this->db->where('mutasi_id', $id);		
		return $this->db->delete('mutasi_istri_suami');
	
	}
	
	
	function getPnsdataOracle($nip)
	{
		$this->oracle   = $this->load->database('oracle', TRUE);
				
		$sql="SELECT a.*, b.NAMA_UNOR, b.NAMA_JABATAN, c.NAMA NAMA_INSTANSI_KERJA, d.NAMA NAMA_INSTANSI_INDUK,
e.NAMA NAMA_SATUAN_KERJA_INDUK, f.NAMA NAMA_SATUAN_KERJA,g.NAMA NAMA_KEDUDUKAN_HUKUM
FROM (select a.ID,a.KABUPATEN_ID,a.NAMA,a.GELAR_DEPAN,a.GELAR_BLK,TO_CHAR(a.TGL_LHR,'DD-MM-YYYY') TGL_LHR,
		a.JENIS_KELAMIN,b.INSTANSI_KERJA_ID, b.INSTANSI_INDUK_ID,b.KEDUDUKAN_HUKUM_ID,b.LOKASI_KERJA_ID,b.NIP_LAMA,
b.NIP_BARU,b.STATUS_CPNS_PNS,b.NOMOR_SK_CPNS,TO_CHAR(b.TGL_SK_CPNS,'DD-MM-YYYY') TGL_SK_CPNS ,b.NOM_URUT_SK_CPNS,
b.NOMOR_SK_PNS,TO_CHAR(b.TGL_SK_PNS,'DD-MM-YYYY') TGL_SK_PNS ,b.NOM_URUT_SK_PNS,b.NOMOR_STTPL,
TO_CHAR(b.TGL_STTPL,'DD-MM-YYYY') TGL_STTPL, TO_CHAR(b.TGL_TUGAS,'DD-MM-YYYY') TGL_TUGAS,
b.SATUAN_KERJA_INDUK_ID, b.SATUAN_KERJA_KERJA_ID,b.UNOR_ID,  TO_CHAR(b.TMT_CPNS,'DD-MM-YYYY') TMT_CPNS,
TO_CHAR(b.TMT_PNS,'DD-MM-YYYY') TMT_PNS,
b.NOMOR_DOKTER_PNS,TO_CHAR(b.TANGGAL_DOKTER_PNS,'DD-MM-YYYY') TANGGAL_DOKTER_PNS,
b.NOMOR_SPMT,b.SPESIMEN_PEJABAT_CPNS
from kanreg0.orang a
LEFT JOIN KANREG0.PNS b ON a.ID = b.ID
WHERE b.NIP_BARU='$nip') a
LEFT JOIN KANREG0.UNOR b ON a.UNOR_ID = b.ID
LEFT JOIN KANREG0.INSTANSI c ON a.INSTANSI_KERJA_ID = c.ID
LEFT JOIN KANREG0.INSTANSI d ON a.INSTANSI_INDUK_ID = d.ID
LEFT JOIN KANREG0.SATUAN_KERJA e ON a.SATUAN_KERJA_INDUK_ID = e.ID
LEFT JOIN KANREG0.SATUAN_KERJA f ON a.SATUAN_KERJA_KERJA_ID = f.ID
LEFT JOIN KANREG0.KEDUDUKAN_HUKUM g ON a.KEDUDUKAN_HUKUM_ID = g.ID";
        return $this->oracle->query($sql);
		
	}	
	
	function getUsul($agenda,$nip)
	{
		$sql=" SELECT a.*,
		DATE_FORMAT(old_tmt_gaji,'%d-%m-%Y') old_tmt_gaji,	
		DATE_FORMAT(tanggal_persetujuan,'%d-%m-%Y') tanggal_persetujuan,
		DATE_FORMAT(baru_tmt_gaji,'%d-%m-%Y') baru_tmt_gaji,
		DATE_FORMAT(mulai_pegawai,'%d-%m-%Y') mulai_pegawai,
		DATE_FORMAT(sampai_pegawai,'%d-%m-%Y') sampai_pegawai,
		DATE_FORMAT(mulai_honor,'%d-%m-%Y') mulai_honor,
		DATE_FORMAT(sampai_honor,'%d-%m-%Y') sampai_honor,
		DATE_FORMAT(tanggal_ijazah1,'%d-%m-%Y') tanggal_ijazah1,
		DATE_FORMAT(tanggal_ijazah2,'%d-%m-%Y') tanggal_ijazah2,
		DATE_FORMAT(tanggal_ijazah3,'%d-%m-%Y') tanggal_ijazah3,
		DATE_FORMAT(tanggal_ijazah4,'%d-%m-%Y') tanggal_ijazah4,
		DATE_FORMAT(tanggal_ijazah5,'%d-%m-%Y') tanggal_ijazah5,
		DATE_FORMAT(acc_tmt_gaji,'%d-%m-%Y') acc_tmt_gaji
		FROM  usul_pmk a 
		WHERE a.agenda_id='$agenda' AND a.nip='$nip'  " ;			
		return $this->db->query($sql);
		
	}	
	
	function saveAccPmk()
	{
		$data['agenda_id']				= $this->input->post('agendaId');
		$data['nip']				    = $this->input->post('nip');
		$data['old_masa_kerja_tahun']	= $this->input->post('oldTahun');
		$data['old_masa_kerja_bulan']	= $this->input->post('oldBulan');
		$data['old_gaji_pokok']			= $this->input->post('oldGaji');
		$data['old_tmt_gaji']	        = date('Y-m-d',strtotime($this->input->post('oldTmtGaji')));
		$data['nomor_persetujuan']		= $this->input->post('nomorPersetujuan');
		$data['tanggal_persetujuan']	= date('Y-m-d',strtotime($this->input->post('tanggalPersetujuan')));
		$data['baru_masa_kerja_tahun']	= $this->input->post('baruTahun');
		$data['baru_masa_kerja_bulan']	= $this->input->post('baruBulan');
		$data['baru_gaji_pokok']		= $this->input->post('baruGaji');
		$data['baru_tmt_gaji']			= date('Y-m-d',strtotime($this->input->post('baruTmtGaji')));
		$data['mulai_honor']			= date('Y-m-d',strtotime($this->input->post('mulaiHonor')));
		$data['sampai_honor']			= date('Y-m-d',strtotime($this->input->post('sampaiHonor')));
		$data['tahun_honor']			= $this->input->post('tahunHonor');
		$data['bulan_honor']			= $this->input->post('bulanHonor');
		$data['mulai_pegawai']			= date('Y-m-d',strtotime($this->input->post('mulaiPegawai')));
		$data['sampai_pegawai']			= date('Y-m-d',strtotime($this->input->post('sampaiPegawai')));
		$data['tahun_pegawai']			= $this->input->post('tahunPegawai');
		$data['bulan_pegawai']			= $this->input->post('bulanPegawai');
		$data['salinan_sah']			= $this->input->post('salinanSah');
		$data['sk_pangkat']				= $this->input->post('skPangkat');
		$data['tempat_lahir']			= $this->input->post('tempatLahir');
		
		$data['tingkat1']			    = $this->input->post('tingkat1');
		$data['nomor_ijazah1']			= $this->input->post('nomorIjazah1');
		$data['tanggal_ijazah1']	    = date('Y-m-d',strtotime($this->input->post('tanggalIjazah1')));
		
		$data['tingkat2']			    = $this->input->post('tingkat2');
		$data['nomor_ijazah2']			= $this->input->post('nomorIjazah2');
		$data['tanggal_ijazah2']	    = date('Y-m-d',strtotime($this->input->post('tanggalIjazah2')));
		
		$data['tingkat3']			    = $this->input->post('tingkat3');
		$data['nomor_ijazah3']			= $this->input->post('nomorIjazah3');
		$data['tanggal_ijazah3']	    = date('Y-m-d',strtotime($this->input->post('tanggalIjazah3')));
		
		$data['tingkat4']			    = $this->input->post('tingkat4');
		$data['nomor_ijazah4']			= $this->input->post('nomorIjazah4');
		$data['tanggal_ijazah4']	    = date('Y-m-d',strtotime($this->input->post('tanggalIjazah4')));
		
		$data['tingkat5']			    = ($this->input->post('tingkat5') ? $this->input->post('tingkat5') : NULL) ;
		$data['nomor_ijazah5']			= $this->input->post('nomorIjazah5');
		$data['tanggal_ijazah5']	    = ($this->input->post('tanggalIjazah5') ? date('Y-m-d',strtotime($this->input->post('tanggalIjazah5'))) : NULL );
		
		$data['acc_masa_kerja_tahun']	= $this->input->post('baruTahunAcc');
		$data['acc_masa_kerja_bulan']	= $this->input->post('baruBulanAcc');
		$data['acc_gaji_pokok']			= $this->input->post('baruGajiAcc');
		$data['acc_tmt_gaji']			= date('Y-m-d',strtotime($this->input->post('baruTmtGajiAcc')));
		
		$data['dinilai_tahun_honor']			= $this->input->post('dinilaiTahunHonor');
		$data['dinilai_bulan_honor']			= $this->input->post('dinilaiBulanHonor');
		$data['dinilai_tahun_pegawai']			= $this->input->post('dinilaiTahunPegawai');
		$data['dinilai_bulan_pegawai']			= $this->input->post('dinilaiBulanPegawai');
		
		$data['keterangan']						= $this->input->post('keterangan');
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		
		$this->db->where('nip',$this->input->post('nip'));
		$this->db->where('agenda_id',$this->input->post('agendaId'));		
		if (!$this->db->update('usul_pmk', $data))
		{
			$error = $this->db->_error_message();
			if(!empty($error))
			{
                $data['pesan']		= $error;   
				$data['response'] 	= FALSE;
			}
            	
        }
		else
		{
			$data['pesan']		= "Data Berhasil Tersimpan";
			$data['response']	= TRUE;
		}	
		
		$this->db->db_debug = $db_debug; //restore setting			
		
		return $data;
	}		
	
}