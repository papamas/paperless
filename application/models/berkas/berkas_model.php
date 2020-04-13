<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Berkas_model extends CI_Model {

	private     $rawName;
	private     $table    		= 'upload_dokumen';
	private     $tablenom 		= 'nominatif';
	private     $tablepupns 	= 'mirror.pupns';
	private     $tableagenda 	= 'agenda';
	private     $tabledokumen	= 'dokumen';
	private     $tablelayanan	= 'layanan';
	private     $tableinstansi	= 'mirror.instansi';
	private     $tableuser		= 'app_user';
	private     $tablesyarat 	= 'syarat_layanan';
	private     $tabletahapan 	= 'tahapan';
	private     $tableupload    = 'upload_dokumen';
		
    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
	
	
	public function getUsulDokumen($data)
	{		
	    		
		$searchby  = $data['searchby'];
		$search    = $data['search'];
		$status    = $data['status'];
		
		
		$instansi  = $this->input->post('instansi');
		
		switch($searchby){
            case 1:
			    $sql = " AND a.nip = trim('$search') ";
            break;
            case 2:
			   $sql = " AND  UPPER(f.INS_NAMINS) LIKE UPPER('%$search%') ";
            break;
			case 3:
			   $search = trim($search);	
			   $sql = " AND  UPPER(trim(b.agenda_nousul))=UPPER('$search') ";
            break;
			case 4:
			   $sql = " AND  UPPER(c.layanan_nama) LIKE UPPER('%$search%') ";
            break;
            default:
                $sql = " AND a.nip = '999999999' ";		
		}

		if($status == "ALL")
		{
			$sql_status = " ";  
        }
        else
		{
			$sql_status = " AND a.nomi_status = '$status' ";
		}			
		
		
		$q="SELECT a.agenda_id,a.nip,a.nomi_locked,a.nomi_status,a.nomi_alasan,a.btl_from,
CASE a.nomi_status
    WHEN 'ACC' THEN 'badge bg-green'
    WHEN 'TMS' THEN 'badge bg-red'
	WHEN 'BTL' THEN 'badge bg-yellow'
    ELSE 'badge bg-light-blue'
END AS bg,
a.nomi_verifby,a.update_date,a.upload_persetujuan,a.upload_sk,a.file_persetujuan_raw_name,a.file_sk_raw_name,
b.layanan_id,b.agenda_ins,b.agenda_nousul,b.agenda_timestamp,b.agenda_dokumen,b.agenda_status,
c.layanan_nama, f.INS_NAMINS instansi, g.PNS_PNSNAM nama,g.PNS_GOLRU golongan,
group_concat(d.dokumen_id SEPARATOR ',') dokumen_id , 
group_concat(e.nama_dokumen SEPARATOR ',') nama_dokumen,
GROUP_CONCAT(IF(e.flag = 1,e.nama_dokumen, NULL) SEPARATOR ',')  main_dokumen,
GROUP_CONCAT(h.id_dokumen SEPARATOR ',')  upload_dokumen_id,
GROUP_CONCAT(i.nama_dokumen SEPARATOR ',')  upload_dokumen,
GROUP_CONCAT(IF(i.flag = 1,h.file_name, NULL) SEPARATOR ',')  main_upload_dokumen,
j.first_name ln_work,
k.tahapan_nama,
l.first_name  ln_locked
FROM nominatif a
LEFT JOIN $this->tableagenda b ON a.agenda_id = b.agenda_id
LEFT JOIN $this->tablelayanan c ON b.layanan_id = c.layanan_id
LEFT JOIN $this->tablesyarat d ON d.layanan_id = c.layanan_id
LEFT JOIN $this->tabledokumen e ON d.dokumen_id = e.id_dokumen
LEFT JOIN $this->tableinstansi f ON b.agenda_ins = f.INS_KODINS 
LEFT JOIN $this->tablepupns g ON g.PNS_NIPBARU = a.nip
LEFT JOIN $this->table h ON (a.nip = h.nip AND d.dokumen_id = h.id_dokumen)
LEFT JOIN $this->tabledokumen i ON  i.id_dokumen = h.id_dokumen
LEFT JOIN $this->tableuser j ON j.user_id = a.work_by
LEFT JOIN $this->tabletahapan k ON k.tahapan_id = a.tahapan_id
LEFT JOIN $this->tableuser l ON l.user_id = a.locked_by
where 1=1 AND  f.INS_KODINS='$instansi'  $sql $sql_status 
GROUP BY a.nip,b.layanan_id,a.agenda_id
";
		//var_dump($q);exit;
		$query 		= $this->db->query($q);
		
        return      $query;		
    }	
	
	public function getInstansi()
	{
	    $instansi  = $this->session->userdata('session_instansi');
		
		$sql="SELECT * FROM $this->tableinstansi where INS_KODINS='$instansi' ";	
		return $this->db->query($sql);
		
	}	
	
	public function getUploadDokumen($nip)
	{
		$sql="SELECT a.*, b.nama_dokumen 
		FROM $this->tableupload a
		LEFT JOIN $this->tabledokumen b ON a.id_dokumen = b.id_dokumen
		WHERE a.nip='$nip' and b.aktif='1' ";
		return $this->db->query($sql);
	}
	
	public function KirimUlang($data)
	{
		// kirim ulang berkas BTL ke TEKNIS
		$r					  = FALSE;
		$agenda_id			  = $data['agenda'];
		$nip                  = $data['nip'];
		$btlFrom			  = $data['btlFrom'];
		        
		$set['tahapan_id']    = $btlFrom;	
		$set['kirim_by']      = $this->session->userdata('user_id');
		$set['nomi_status']   = 'BELUM';	
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		
		$this->db->set($set);		
		$this->db->set('kirim_date','NOW()',FALSE);
		$this->db->where('agenda_id', $agenda_id);		
		$this->db->where('nip', $nip);	
		
		if ($this->db->update($this->tablenom))
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
		
		return $r;
	}
	
	function getAlasan($data){
		
		$agenda_id			  = $data['agenda'];
		$nip                  = $data['nip'];
		
		$this->db->select('nomi_alasan');
		$this->db->where('agenda_id', $agenda_id);		
		$this->db->where('nip', $nip);
		return $this->db->get($this->tablenom);
	
	}	
	
	public function insertUpload($data)
	{
		$data['id_dokumen']		= $this->_getIdDokumen($data);
		$data['upload_by']      = $this->session->userdata('user_id');
		$number 				= $this->_extract_numbers($data['raw_name']);
		
		foreach($number as $value){
		    if (strlen($value) == 18){
                $data['nip']    = $value;
            }
            else
            {
			    $data['minor_dok']    = $value;
            }		
	    }   
		
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
			
		if (!$this->db->insert($this->table, $data))
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
			$data['pesan']		= "File Surat Keputusan Berhasil Tersimpan";
			$data['response']	= TRUE;
			
			$this->updateNominatif($data);
		}	
        $this->db->db_debug = $db_debug; //restore setting	

        return $data;		
		
	}
	
	function _getIdDokumen($data)
	{
	    $r = NULL;
		$find    = $data['raw_name'];
		
		$query = $this->db->query("SELECT * FROM (SELECT *,locate(nama_dokumen,'$find') result from dokumen ) a
 WHERE a.result = 1"); 	
		if($query->num_rows() > 0){
		    $row 	= $query->row();
			$r 		= $row->id_dokumen;
		}
		
		return $r;
	}
	
	function _extract_numbers($string)
	{
	    preg_match_all('/([\d]+)/', $string, $match);
	   
	    

	   return $match[0];
	}
	
	function  updateFile($data)
	{
				
		$this->db->where('raw_name',$data['raw_name']);
		$this->db->where('id_instansi',$data['id_instansi']);
		$this->db->set('flag_update',1);
		$this->db->set('update_date','NOW()',FALSE);
		return $this->db->update($this->table);
		
	}	
	
	function updateNominatif($data)
	{
		
		$instansi						= $this->input->post('agenda_ins');
		$nip							= $this->input->post('agenda_nip');
		$agenda							= $this->input->post('agenda_id');
		
		$this->db->where('nip',$nip);
		$this->db->where('agenda_id',$agenda);
		$this->db->set('upload_sk',1);
		$this->db->set('file_sk_raw_name',$data['raw_name']);
		$this->db->set('date_upload_sk','NOW()',FALSE);
		return $this->db->update($this->tablenom);

	}
	
	public function getAgenda_byid($agenda_id,$nip)
	{    
		$sql="SELECT a.* , 
		b.layanan_grup, b.layanan_bidang, b.layanan_nama ,
		c.INS_NAMINS instansi,
		d.nip, d.nomi_status, d.nomi_alasan,d.status_level_satu, d.status_level_dua,d.status_level_tiga,
		e.PNS_PNSNAM,e.PNS_GLRDPN, e.PNS_GLRBLK,
		f.tahapan_nama
		FROM agenda a
		LEFT JOIN $this->tablelayanan  b ON a.layanan_id = b.layanan_id
		LEFT JOIN $this->tableinstansi c ON a.agenda_ins = c.INS_KODINS
		LEFT JOIN $this->tablenom d ON a.agenda_id  = d.agenda_id
		LEFT JOIN $this->tablepupns e ON e.PNS_NIPBARU = d.nip
		LEFT JOIN $this->tabletahapan f ON f.tahapan_id = d.tahapan_id
		WHERE a.agenda_id='$agenda_id' AND d.nip='$nip' ";
    	return $this->db->query($sql);
	}
	
	function getTelegramAkun_bybidang($id_bidang)
	{	
		$this->db->select('first_name,last_name,telegram_id');
		$this->db->where('id_bidang',$id_bidang);
		$this->db->where('id_instansi',4011);
		return $this->db->get('app_user');		
	}	
}