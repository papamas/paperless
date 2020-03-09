<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Entry_model extends CI_Model {

	
	private     $tablelayanan  		= 'layanan';
	private     $tableinstansi 		= 'mirror.instansi';
	private     $table    			= 'upload_dokumen';
	private     $tablenom 			= 'nominatif';
	private     $tablepupns 		= 'mirror.pupns';
	private     $tableagenda 		= 'agenda';
	private     $tabledokumen		= 'dokumen';
	private     $tableuser			= 'app_user';
	private     $tablesyarat 		= 'syarat_layanan';
	private     $tablephoto 		= 'upload_photo';
	private     $tabletahapan 		= 'tahapan';
	private     $tablegolru			= 'mirror.golru';
	private     $tablejabatan		= 'jabatan';
	private     $tableijazah		= 'ijazah';
		
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
			    $sql_status = " AND b.nomi_persetujuan IS NOT NULL";
            break;
            case 2 :
			    $sql_status = " AND b.nomi_persetujuan IS NULL";
            break;
            case 3:
			    $sql_status = " ";
            break;            			
		}	
		
		if(!empty($nip))
		{
			$sql_nip = " AND b.nip = '$nip' ";
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
			$sql_instansi = " AND d.INS_KODINS = '$instansi' ";
        }
        else
		{
			$sql_instansi = " ";   
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
		
			$sql_date = " AND DATE( b.verify_date ) BETWEEN STR_TO_DATE('$startdate', '%d/%m/%Y ' )
			AND STR_TO_DATE('$enddate', '%d/%m/%Y ' ) ";
		}
		else
		{	
			$sql_date = " ";   
		}		
      
	    $bidang  = $this->session->userdata('session_bidang');
		
		$q="SELECT a.*,
		b.nip,b.tahapan_id,b.nomi_status,b.nomi_alasan,b.verify_date,b.entry_date,
		b.nomi_persetujuan,DATE_FORMAT(b.tanggal_persetujuan,'%d-%m-%Y') tgl,b.upload_persetujuan,b.upload_sk,		
		c.layanan_nama,
		d.INS_NAMINS instansi ,
		e.PNS_PNSNAM nama,
		f.tahapan_nama,
		g.first_name work_name,
		h.first_name lock_name,
		i.first_name verif_name_satu,
		j.GOL_GOLNAM golongan,
		k.first_name verif_name_dua,
		l.first_name verif_name_tiga,
		m.first_name verif_name,
		n.first_name entry_proses_name,
		o.first_name entry_name,
		p.id_instansi,p.orig_name
		FROM $this->tableagenda a 
LEFT JOIN $this->tablenom b ON a.agenda_id = b.agenda_id 
LEFT JOIN $this->tablelayanan c  ON a.layanan_id = c.layanan_id
LEFT JOIN $this->tableinstansi d ON a.agenda_ins = d.INS_KODINS
LEFT JOIN $this->tablepupns e ON b.nip = e.PNS_NIPBARU
LEFT JOIN $this->tabletahapan f ON b.tahapan_id = f.tahapan_id
LEFT JOIN $this->tableuser g ON g.user_id = b.work_by
LEFT JOIN $this->tableuser h ON h.user_id = b.locked_by
LEFT JOIN $this->tableuser i ON i.user_id = b.verifby_level_satu
LEFT JOIN $this->tablegolru j ON e.PNS_GOLRU = j.GOL_KODGOL
LEFT JOIN $this->tableuser k ON k.user_id = b.verifby_level_dua
LEFT JOIN $this->tableuser l ON l.user_id = b.verifby_level_tiga
LEFT JOIN $this->tableuser m ON m.user_id = b.nomi_verifby
LEFT JOIN $this->tableuser n ON n.user_id = b.entry_proses_by
LEFT JOIN $this->tableuser o ON o.user_id = b.entry_by
LEFT JOIN $this->tablephoto p ON  (b.nip = p.nip AND p.layanan_id = a.layanan_id)
WHERE b.nomi_status='ACC' 
AND c.layanan_bidang='$bidang' 
$sql_status  $sql_nip  $sql_instansi  $sql_layanan  $sql_date
";
	
		$query 		= $this->db->query($q);
		
        return      $query;		
    }	
	
	public function simpanPersetujuan($data)
	{
		// selesai proses cetak
		$agenda	    		= $data['agenda'];
		$nip				= $data['nip'];
		$nomor				= $data['persetujuan'];
		$tanggal			= date('Y-m-d',strtotime($data['tanggal']));
		
		$set['nomi_persetujuan']    	=   strtoupper($nomor); 
		$set['tanggal_persetujuan']   	=   $tanggal; 
		$set['tahapan_id']   			=   13; 
		$set['entry_by']   			    =   $this->session->userdata('user_id');		
		
		$this->db->where('agenda_id',$agenda);
		$this->db->where('nip',$nip);
		$this->db->set($set);	
	    $this->db->set('entry_date','NOW()',FALSE);
		return $this->db->update($this->tablenom);
	}	
	
	public function simpanPersetujuanPG($data)
	{
		// selesai proses cetak
		$agenda	    		= $data['agenda'];
		$nip				= $data['nip'];
		$nomor				= $data['persetujuan'];
		$tanggal			= date('Y-m-d',strtotime($data['tanggal']));
		
		$set['nomi_persetujuan']    	=   strtoupper($nomor); 
		$set['tanggal_persetujuan']   	=   $tanggal; 
		$set['tahapan_id']   			=   13; 
		$set['entry_by']   			    =   $this->session->userdata('user_id'); 
		
		
		$set['kode_ijazah']				= $data['kode_ijazah'];
		$set['nomor_ijazah']            = $data['nomor_ijazah'];		
		$set['tgl_ijazah']              = date('Y-m-d',strtotime($data['tgl_ijazah']));
		$set['kampus']                	= $data['kampus'];
		$set['prodi']                	= $data['prodi'];
		$set['lokasi_kampus']           = $data['lokasi_kampus'];
		$set['nama_gelar']              = $data['nama_gelar'];
		
		$this->db->where('agenda_id',$agenda);
		$this->db->where('nip',$nip);
		$this->db->set($set);	
	    $this->db->set('entry_date','NOW()',FALSE);
		return $this->db->update($this->tablenom);
	}	
	
	public function simpanTahapan($data)
	{
		// tahapan  proses cetak
		$set['tahapan_id']    		  = 12;	
		$set['entry_proses_by']	      = $this->session->userdata('user_id');
		
		$this->db->set($set);
		$this->db->where('agenda_id', $data['agenda']);		
		$this->db->where('nip', $data['nip']);
		return $this->db->update($this->tablenom);
	}	
	
	public function getEntryOne($data)
	{
		$agenda			= $data['agenda'] ;
		$nip			= $data['nip'];
		
		$sql   = "SELECT 
		a.nip, a.nomi_persetujuan,formatTanggal(a.tanggal_persetujuan) tanggal_acc,date_format(a.tanggal_persetujuan, '%d-%m-%Y') date_format,
		a.nomor_ijazah,formatTanggal(a.tgl_ijazah) tgl_ijazah,date_format(a.tgl_ijazah,'%d-%m-%Y') format_tgl_ijazah, a.lokasi_kampus,a.kampus,a.nama_gelar,a.prodi,a.kode_ijazah,
		b.PNS_PNSNAM nama,b.PNS_GLRBLK gelar, formatTanggal(b.PNS_TMTGOL) tmt_golongan,
		c.agenda_nousul,formatTanggal(c.agenda_timestamp) tanggal_agenda,
		d.nama_jabatan , d.nama_daerah ,d.lokasi_daerah,
		e.nama_ijazah,		
		f.GOl_PKTNAM pangkat, f.GOL_GOLNAM nama_golongan,
		g.jabatan,
		h.PNS_PNSNAM nama_spesimen,h.PNS_GLRBLK gelar_spesimen,h.PNS_NIPBARU nip_spesimen
		FROM $this->tablenom  a 
		LEFT JOIN $this->tablepupns b ON a.nip = b.PNS_NIPBARU
		LEFT JOIN $this->tableagenda c ON c.agenda_id = a.agenda_id
		LEFT JOIN $this->tablejabatan d ON c.agenda_ins = d.id_instansi
		LEFT JOIN $this->tableijazah e ON e.kode_ijazah = a.kode_ijazah
		LEFT JOIN $this->tablegolru f ON b.PNS_GOLRU = f.GOL_KODGOL
		LEFT JOIN $this->tableuser g ON g.user_id = a.nomi_verifby
		LEFT JOIN $this->tablepupns h ON g.nip = h.PNS_NIPBARU
		WHERE a.agenda_id='$agenda' AND a.nip='$nip' ";
		
		$query 	=   $this->db->query($sql);
		return      $query;	
	}	
	
	public function getIjazah()
	{
		$this->db->select('*');
		return $this->db->get($this->tableijazah);
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
			$data['pesan']		= "File Persetujuan Teknis Berhasil Tersimpan";
			$data['response']	= TRUE;
			
			$this->updateNominatif();
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
	
	function updateNominatif()
	{
		
		$instansi						= $this->input->post('agenda_ins');
		$nip							= $this->input->post('agenda_nip');
		$agenda							= $this->input->post('agenda_id');
		
		$this->db->where('nip',$nip);
		$this->db->where('agenda_id',$agenda);
		$this->db->set('upload_persetujuan',1);
		$this->db->set('date_upload_persetujuan','NOW()',FALSE);
		return $this->db->update($this->tablenom);

	}
	
}