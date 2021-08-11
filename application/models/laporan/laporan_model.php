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
	private     $usul			    = 'usul_taspen';
	private     $tabletahapan 	    = 'tahapan';
	private     $tablejabatan		= 'jabatan';
	private     $tablesformatsurat  = 'format_surat';
	private     $tablespesimen      = 'spesimen_pengeluaran';
	private     $tablepengeluaran	= 'nomor_pengeluaran';
	private     $kantorTaspen		= 'kantor_taspen';
	private     $tablegolru			= 'mirror.golru';
	private     $spesimenTaspen     = 'spesimen_taspen';
	private     $tblpengeluaranTaspen	= 'nomor_pengeluaran_taspen';
	
    function __construct()
    {
        parent::__construct();
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
				
				$sql_order = " order by a.verify_date DESC ";
			}
			elseif($bydate == 2)
			{
				$sql_date = " AND DATE( a.entry_date ) BETWEEN STR_TO_DATE( '$startdate', '%d/%m/%Y ' )
				AND STR_TO_DATE( '$enddate', '%d/%m/%Y ' ) ";
				$sql_order = " order by a.entry_date DESC ";
            }
			else
			{
				$sql_date = " AND DATE( b.agenda_timestamp ) BETWEEN STR_TO_DATE( '$startdate', '%d/%m/%Y ' )
				AND STR_TO_DATE( '$enddate', '%d/%m/%Y ' ) ";
				$sql_order = " order by b.agenda_timestamp DESC ";
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
$sql_instansi  $sql_layanan   $sql_date  $sql_status 
$sql_order ";
	
		$query 		= $this->db->query($q);
		
        return      $query;		
    }	
	
	
	public function getLaporanTaspen($data)
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
		
		    if($bydate == 1)
			{	
				$sql_date = " AND DATE( a.usul_verif_date ) BETWEEN STR_TO_DATE( '$startdate', '%d/%m/%Y ' )
				AND STR_TO_DATE( '$enddate', '%d/%m/%Y ' ) ";
				
				$sql_order = " order by a.usul_verif_date DESC ";
			}
			elseif($bydate == 2)
			{
				$sql_date = " AND DATE( a.usul_entry_date ) BETWEEN STR_TO_DATE( '$startdate', '%d/%m/%Y ' )
				AND STR_TO_DATE( '$enddate', '%d/%m/%Y ' ) ";
				$sql_order = " order by a.usul_entry_date DESC ";
            }
			else
			{
				$sql_date = " AND DATE( a.kirim_bkn_date ) BETWEEN STR_TO_DATE( '$startdate', '%d/%m/%Y ' )
				AND STR_TO_DATE( '$enddate', '%d/%m/%Y ' ) ";
				$sql_order = " order by a.kirim_bkn_date DESC ";
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
			$sql_status = " AND a.usul_status = '$status' ";
		}	
	
	    $q ="SELECT a.*,DATE_FORMAT(a.tgl_usul,'%d-%m-%Y') tgl,
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
		i.first_name usul_entry_name,
		j.nama_taspen
		FROM $this->usul a
		LEFT JOIN $this->tablelayanan b ON a.layanan_id = b.layanan_id	
		LEFT JOIN $this->tabletahapan c ON c.tahapan_id = a.usul_tahapan_id
		LEFT JOIN $this->tablepupns d ON (a.nip = d.PNS_NIPBARU OR a.nip = d.PNS_PNSNIP)
		LEFT JOIN $this->tableuser e ON e.user_id = a.kirim_bkn_by
		LEFT JOIN $this->tableuser f ON f.user_id = a.usul_kirim_by
		LEFT JOIN $this->tableuser g ON g.user_id = a.usul_lock_by
		LEFT JOIN $this->tableuser h ON h.user_id = a.usul_verif_by
		LEFT JOIN $this->tableuser i ON i.user_id = a.usul_entry_by
		LEFT JOIN kantor_taspen j ON j.id_taspen = a.kantor_taspen
		WHERE 1=1 $sql_layanan   $sql_date  $sql_status $sql_order";
		
		
	    $query 		= $this->db->query($q);		
        return      $query;
	}	
	
	
	public function getPengeluaran()
	{
		$agenda_id		= trim($this->input->post('nomorUsul'));
		$spesimen		= trim($this->input->post('spesimenPengeluaran'));
		$pilihan		= trim($this->input->post('pilihanPengeluaran'));
		$last_number	= trim($this->input->post('nomorPengeluaran'));
		
		switch($pilihan){
		   case 1:
				$sql_pilihan  = " ";
		   break;
		   case 2:
				$sql_pilihan  = " AND b.out_status IS NOT NULL ";
		   break;
		   case 3:
				$sql_pilihan  = " AND b.out_status IS NULL";
		   break;
		}		
		$sql ="SELECT a.agenda_nousul , formatTanggal(a.agenda_tgl) tgl_usul,
		b.agenda_id,b.nip, b.nomi_persetujuan, formatTanggal(b.tanggal_persetujuan) tgl_acc,
		c.PNS_NIPBARU, c.PNS_PNSNAM,
		d.nama_jabatan, d.nama_daerah, d.lokasi_daerah,
		formatTanggal(NOW()) sekarang,
		REPLACE(REPLACE(REPLACE(REPLACE(e.format_surat,'bln',toRoman(MONTH(NOW()))),'tahun',YEAR(NOW())),'jenis_layanan',f.layanan_sk),'nomor_surat',g.last_number) nomor_surat,
		g.satker, g.lokasi
		FROM $this->tableagenda a
		LEFT JOIN $this->tablenom b ON a.agenda_id = b.agenda_id
		LEFT JOIN $this->tablepupns c ON b.nip = c.PNS_NIPBARU
		LEFT JOIN $this->tablejabatan d ON a.agenda_ins = d.id_instansi
		LEFT JOIN $this->tablesformatsurat e ON e.layanan_id = a.layanan_id
		LEFT JOIN $this->tablelayanan f ON f.layanan_id = a.layanan_id
		LEFT JOIN $this->tablepengeluaran g ON g.agenda_id = a.agenda_id		
		WHERE trim(a.agenda_id)= trim('$agenda_id') 
		AND b.nomi_status='ACC' 
		AND g.last_number='$last_number' 
		$sql_pilihan ";
		
		//var_dump($sql);exit;
		
		return $this->db->query($sql);
	
	}	
	
	public function update_out_status($agenda_id,$nip)
	{
	
		$this->db->set('out_status','1');
		$this->db->where('agenda_id',$agenda_id);
		$this->db->where('nip',$nip);
		return $this->db->update('nominatif');
	}	
	
	public function getAgenda($search)
	{
	    $sql="SELECT a.agenda_id id ,CONCAT(a.agenda_nousul,' - ', b.INS_NAMINS) as text		
		FROM $this->tableagenda a
		LEFT JOIN $this->tableinstansi b ON a.agenda_ins = b.INS_KODINS
		WHERE a.agenda_nousul LIKE '$search%' ";
	    return $this->db->query($sql);
	   
	}	
	
	public function getAgenda_byid($id)
	{
		$sql="SELECT a.agenda_id ,a.layanan_id		
		FROM $this->tableagenda a
		WHERE trim(a.agenda_id)= trim('$id') ";
	    return $this->db->query($sql);
	}	
	
	public function getPengeluaran_byid_and_last_number($id,$last_number)
	{
		$sql="SELECT a.agenda_id,a.last_number		
		FROM $this->tablepengeluaran a
		WHERE trim(a.agenda_id)= trim('$id') AND a.last_number = '$last_number' ";
	    return $this->db->query($sql);
	}	
	
	public function getPengeluaran_byid($id)
	{
		$sql="SELECT a.agenda_id,a.last_number		
		FROM $this->tablepengeluaran a
		WHERE trim(a.agenda_id)= trim('$id') ORDER BY a.last_number DESC limit 1";
	    return $this->db->query($sql);
	}	
	
	public function addPengeluaran()
	{
		$last_number			= trim($this->input->post('nomorPengeluaran'));	 
		$agenda_id				= trim($this->input->post('nomorUsul'));		
		$pengeluaran			= $this->getPengeluaran_byid_and_last_number($agenda_id,$last_number);
		$row					= $this->getAgenda_byid($agenda_id)->row();
		
		if($pengeluaran->num_rows() == 0)
		{	
			$data['agenda_id']		= $row->agenda_id;
			$data['layanan_id']     = $row->layanan_id;
			$data['last_number']    = $last_number;
			$data['satker']   		= $this->input->post('satker');
			$data['lokasi']	   		= $this->input->post('lokasiSatker');
			$this->db->insert($this->tablepengeluaran, $data);
		}
		else
		{
			$set['agenda_id']	    = $row->agenda_id;
			$set['layanan_id']      = $row->layanan_id;
			$set['last_number']     = $last_number;	
			$set['satker']   		= $this->input->post('satker');
			$set['lokasi']	   		= $this->input->post('lokasiSatker');
			
			$this->db->set($set);
			$this->db->where('agenda_id',$agenda_id);
			$this->db->where('last_number',$last_number);
			$this->db->update($this->tablepengeluaran);
		}
	}
	
	public function getSpesimen_pengeluaran()
	{
		$sql="SELECT a.nip,a.jabatan,
		b.PNS_PNSNAM nama, b.PNS_GLRDPN glrdpn, b.PNS_GLRBLK glrblk
		FROM $this->tablespesimen a
		LEFT JOIN $this->tablepupns b ON a.nip = b.PNS_NIPBARU
		WHERE a.aktif='1' ";
	    return $this->db->query($sql);
	}

	public function getSpesimen_pengeluaran_by_nip()
	{
		$nip 			= $this->input->post('spesimenPengeluaran');
		
		$sql="SELECT a.nip nip_spesimen,a.jabatan jabatan_spesimen,
		b.PNS_PNSNAM nama_spesimen, b.PNS_GLRDPN glrdpn, b.PNS_GLRBLK glrblk
		FROM $this->tablespesimen a
		LEFT JOIN $this->tablepupns b ON a.nip = b.PNS_NIPBARU
		WHERE a.nip='$nip' ";
	    return $this->db->query($sql)->row();
	}		
	
	public function getEntryOneTaspen()
	{
		$usul			= trim($this->input->post('usulTaspen'));
		
		$sql   = "SELECT a.*,
		formatTanggal(a.usul_tgl_persetujuan) persetujuan_tgl,
		formatTanggal(a.tgl_usul) atgl_usul,
		b.layanan_nama,
		c.tahapan_nama,
		d.PNS_NIPBARU nip_baru, d.PNS_PNSNIP nip_lama,
		e.nama_taspen,
		REPLACE(REPLACE(REPLACE(REPLACE(f.format_surat,'bln',toRoman(MONTH(NOW()))),'tahun',YEAR(NOW())),'jenis_layanan',b.layanan_sk),'nomor_surat',g.last_number) nomor_surat
		FROM $this->usul a
		LEFT JOIN $this->tablelayanan b ON a.layanan_id = b.layanan_id	
		LEFT JOIN $this->tabletahapan c ON c.tahapan_id = a.usul_tahapan_id
		LEFT JOIN $this->tablepupns d ON (a.nip = d.PNS_NIPBARU OR a.nip = d.PNS_PNSNIP)
		LEFT JOIN $this->kantorTaspen e ON e.id_taspen = a.kantor_taspen
		LEFT JOIN $this->tablesformatsurat f ON a.layanan_id = f.layanan_id
		LEFT JOIN $this->tblpengeluaranTaspen g ON a.layanan_id = g.layanan_id
		WHERE a.usul_id='$usul'  ";
		
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

	
	public function getUsulTaspen($search)
	{
	    $sql="SELECT a.usul_id id ,CONCAT(a.nomor_usul,'-', a.nama_pns,'-',IF(a.nama_janda_duda IS NULL,'',a.nama_janda_duda)) as text		
		FROM $this->usul a
		WHERE a.nomor_usul LIKE '$search%' ";
	    return $this->db->query($sql);
	   
	}	
	
	
	public function getUsulTaspen_byid($id)
	{
		$sql="SELECT a.usul_id ,a.layanan_id		
		FROM $this->usul a
		WHERE trim(a.usul_id)= trim('$id') ";
	    return $this->db->query($sql);
	}	
	
	public function getPengeluaranTaspen_byid($id)
	{
		$sql="SELECT a.usul_id,a.last_number		
		FROM $this->tblpengeluaranTaspen a
		WHERE trim(a.usul_id)= trim('$id') ";
	    return $this->db->query($sql);
	}	
	
	
	public function addPengeluaranTaspen()
	{
		$usul_id				= trim($this->input->post('usulTaspen'));		
		$pengeluaran			= $this->getPengeluaranTaspen_byid($usul_id);
		$row					= $this->getUsulTaspen_byid($usul_id)->row();
		
		if($pengeluaran->num_rows() == 0)
		{	
			$data['usul_id']		= $row->usul_id;
			$data['layanan_id']     = $row->layanan_id;
			$data['last_number']    = trim($this->input->post('nomorPengeluaranTaspen'));
			$this->db->insert($this->tblpengeluaranTaspen, $data);
		}
		else
		{
			$set['usul_id']	   	   = $row->usul_id;
			$set['layanan_id']     = $row->layanan_id;
			$set['last_number']    = trim($this->input->post('nomorPengeluaranTaspen'));	
			
			$this->db->set($set);
			$this->db->where('usul_id',$usul_id);
			$this->db->update($this->tblpengeluaranTaspen);
		}
	}
	
	public function getSpesimen_pengeluaranTaspen_by_nip()
	{
		$nip 			= $this->input->post('spesimenPengeluaranTaspen');
		
		$sql="SELECT a.nip ,a.jabatan ,
		b.PNS_PNSNAM nama_spesimen, b.PNS_GLRDPN glrdpn, b.PNS_GLRBLK glrblk
		FROM $this->tablespesimen a
		LEFT JOIN $this->tablepupns b ON a.nip = b.PNS_NIPBARU
		WHERE a.nip='$nip' ";
	    return $this->db->query($sql)->row();
	}		
	
	/* pengeluaran Mutasi */
	
	function getLastNumberPengeluaranMutasi()
	{
		$layanan_id		= $this->input->get('q');
		
		$sql ="SELECT (SELECT MAX(nomor_pengeluaran)+1 FROM pengeluaran_mutasi) nomor,
		REPLACE(REPLACE(REPLACE(
     REPLACE(format_surat,'bln',toRoman(MONTH(NOW()))),'tahun',YEAR(NOW())),
     'jenis_layanan',(SELECT layanan_nama FROM layanan WHERE layanan_id='$layanan_id')),'nomor_surat',(SELECT MAX(nomor_pengeluaran)+1 FROM pengeluaran_mutasi)) nomor_surat
FROM format_surat
WHERE layanan_id='$layanan_id'";
		return $this->db->query($sql);	
	}	
	
	public function getAgendaMutasi()
	{
		$id   		= $this->input->get('q');	 
		 
		$sql="SELECT a.agenda_id ,a.layanan_id,a.agenda_nousul		
		FROM $this->tableagenda a
		WHERE a.agenda_nousul LIKE '$id%' ";
	    return $this->db->query($sql);
	}	
	
	function getPengeluaranMutasi()
	{
		$id		= $this->input->post('agendaId');
		
		$sql="SELECT a.*,b.PNS_PNSNAM, DATE_FORMAT(b.PNS_TGLLHRDT,'%d-%m-%Y') PNS_TGLLHR
		FROM (SELECT a.*,b.nip, c.nama_jabatan, c.nama_daerah,lokasi_daerah,
		d.layanan_label,formatTanggal(NOW()) sekarang
		FROM agenda a
		LEFT JOIN nominatif b ON a.agenda_id = b.agenda_id
		LEFT JOIN jabatan c ON c.id_instansi = a.agenda_ins 
		LEFT JOIN layanan d ON d.layanan_id = a.layanan_id
		WHERE a.agenda_id='$id' AND b.nomi_status='ACC' ) a
		LEFT JOIN mirror.pupns b ON a.nip = b.PNS_NIPBARU
		";
		return $this->db->query($sql);	
	}	
	
	function insertPengeluaranMutasi()
	{
		$data['nomor_pengeluaran']			= $this->input->post('nomor');
		$this->db->insert('pengeluaran_mutasi',$data);
	}

    function getRealisasiAp3k($nip)
    {
       		 $this->ap3k  = $this->load->database('ap3k',TRUE);

		
		$id		= $this->input->post('layananId');
		$nomor  = NULL;
		
		switch($id){
			case '9':
			    $this->ap3k->where('nip',$nip);
				$query  = $this->ap3k->get('karis');
				if($query->num_rows() > 0)
				{
					$row   = $query->row();
					$nomor = $row->no_karis_baru;
				}		
			break;
			case '10':
				$this->ap3k->where('nip',$nip);
				$query  = $this->ap3k->get('karsu');
				if($query->num_rows() > 0)
				{
					$row   = $query->row();
					$nomor = $row->no_karsu_baru;
				}	
			break;
			default:
				$this->ap3k->where('nip',$nip);
				$query  = $this->ap3k->get('karpeg');
				if($query->num_rows() > 0)
				{
					$row   = $query->row();
					$nomor = $row->no_karpeg_baru;
				}	
		}	
		
		return $nomor;
    }	
}