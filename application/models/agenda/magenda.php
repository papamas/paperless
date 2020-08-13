<?php

class Magenda extends CI_Model {

    function __construct()
    {
        parent::__construct();
		$this->load->database();
	}
	
    //LIST AGENDA
    public function mlist_agenda($instansi){

        $query = $this->db->query("SELECT * FROM agenda
                                LEFT JOIN layanan ON agenda.layanan_id = layanan.layanan_id
                                WHERE agenda_ins = '$instansi' AND agenda_status = 'dibuat'
                                ORDER BY agenda_id DESC");
		if(!$query){
		  return $this->db->error();
		}else{
		  return $query->result();
		}

	}

    //LIST LAYANAN
    public function mlist_layanan(){

		$query = $this->db->query("SELECT * FROM layanan WHERE status='1' ORDER BY layanan_nama ASC");
		if(!$query){
		  return $this->db->error();
		}else{
		  return $query->result();
		}

    }

	//CEK NO USUL DAN LAYANAN YANG SAMA
	public function mcek_usul_layanan($no_usul, $layanan_id){

		$query = $this->db->query("SELECT agenda_nousul, layanan_id FROM agenda WHERE agenda_nousul = '$no_usul' AND layanan_id = $layanan_id");
		if(!$query){
		  return $this->db->error();
		}else{
		  return $query->num_rows();
		}

	}

    //CEK GRUP LAYANAN
    public function mcek_layanangrup($layanan_id){

		$query = $this->db->query("SELECT layanan_grup FROM layanan WHERE layanan_id = $layanan_id");
		if(!$query){
		  return $this->db->error();
		}else{
		  return $query->row();
		}

	}

    
	//CEK BATAS KP
    public function mcek_bataskp(){

		$query = $this->db->query("SELECT periode_id, periode_batas FROM kp_periode ORDER BY periode_id DESC LIMIT 1");
		if(!$query){
		  return $this->db->error();
		}else{
		  return $query->row();
		}

	}

    //TAMBAH AGENDA
    public function mtambah_agenda($data){

      	$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
			
		if (!$this->db->insert('agenda', $data))
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
			$data['pesan']		= "Agenda berhasil dibuat";
			$data['response']	= TRUE;
		}	
        $this->db->db_debug = $db_debug; //restore setting	

        return $data;		

    }


    //DETAIL AGENDA
    public function mdetail_agenda($id){

    
		$sql="SELECT a.* , b.layanan_grup,
		b.layanan_bidang, b.layanan_nama , 
		c.INS_NAMINS instansi
		FROM agenda a
		LEFT JOIN layanan b ON a.layanan_id = b.layanan_id
		LEFT JOIN mirror.instansi c ON a.agenda_ins = c.INS_KODINS
		WHERE agenda_id='$id'";

		$query = $this->db->query($sql);
		if(!$query){
		  return $this->db->error();
		}else{
		  return $query->row();
		}

	}

	
 
    //UBAH AGENDA
    function mubah_agenda($data, $cond){

		if(isset($_POST)){
		  $this->db->set($data);
		  $this->db->where($cond);
		  $this->db->update('agenda');
		}

    }

	//HAPUS AGENDA
    public function mhapus_agenda($agenda_id){
		$this->db->delete('agenda', array('agenda_id' => $agenda_id));
		$this->db->delete('nominatif', array('agenda_id' => $agenda_id));
	}

	//LIST NOMINATIF
	public function mlist_nominatif($agenda_id){

		$sql="SELECT a.*, 
		datapns.pns_pnsnam, 
		golpns.gol_golnam,golpns.gol_pktnam,
		dikpns.dik_namdik,
        inspns.ins_namins ins_namins   		
		FROM nominatif a 
		LEFT JOIN mirror.pupns datapns ON a.nip = datapns.pns_nipbaru
		LEFT JOIN mirror.tktpendik dikpns ON datapns.pns_tktdik = dikpns.dik_tktdik
		LEFT JOIN mirror.golru golpns ON datapns.pns_golru = golpns.gol_kodgol
		LEFT JOIN mirror.instansi inspns ON datapns.pns_insduk = inspns.ins_kodins
		WHERE a.agenda_id = '$agenda_id' ORDER BY datapns.pns_pnsnam";
   
		$query = $this->db->query($sql);
		if(!$query){
		  return $this->db->error();
		}else{
		  return $query->result();
		}

	}

	//CARI NOMINATIF
	public function mcari_nominatif($nip, $instansi){

		$layanan_id     = $this->session->userdata('layanan_id');
		
		// jika pindah instansi 
		// pencarian nominatif berdasar nip dan instansi mati
		if($layanan_id == 13 || $layanan_id == 2 || $layanan_id == 3)
		{
			$sql  =" ";
		}
		else
		{
			$sql  =" AND pns_insduk = '$instansi' ";
		}
		$query = $this->db->query("SELECT pns_nipbaru, pns_pnsnam,pns_golru, dik_namdik, gol_golnam, ins_namins FROM mirror.pupns datapns
                                LEFT JOIN mirror.tktpendik dikpns ON datapns.pns_tktdik = dikpns.dik_tktdik
                                LEFT JOIN mirror.golru golpns ON datapns.pns_golru = golpns.gol_kodgol
                                LEFT JOIN mirror.instansi inspns ON datapns.pns_insduk = inspns.ins_kodins
				                        WHERE pns_nipbaru = '$nip' $sql");
      
		return $query;
		

	}

	//CEK EKSIS NIP
	public function mcek_nip($nip, $instansi){

		$layanan_id     = $this->session->userdata('layanan_id');
		 
		if($layanan_id == 13 || $layanan_id == 2 || $layanan_id == 3)
		{
			$sql  =" ";
		}
		else
		{
			$sql  =" AND pns_insduk = '$instansi' ";
		}
		
		$query = $this->db->query("SELECT * FROM mirror.pupns datapns
									WHERE pns_nipbaru = '$nip'  $sql ");
		if(!$query){
		  return $this->db->error();
		}else{
		  return $query->num_rows();
		}

	}
    
	/* Cek Nominatif berdasarkan NIP yang usulnya belum kelar*/
	public function belum_selesai($nip){
		$sql="SELECT a.agenda_id,a.nip,a.nomi_status,
		b.layanan_id,b.agenda_nousul,
		c.layanan_nama
		FROM nominatif a 
		LEFT JOIN agenda b ON a.agenda_id = b.agenda_id
		LEFT JOIN layanan c ON b.layanan_id = c.layanan_id
		WHERE a.nomi_status IN ('BELUM')
		AND a.nip='$nip' ";
		return $this->db->query($sql);
	}	

	//CEK NOMINATIF
	public function mcek_nominatif($agenda_id, $nip){

		$query = $this->db->query("SELECT * FROM nominatif
									 LEFT JOIN agenda ON nominatif.agenda_id = agenda.agenda_id
									 WHERE nominatif.agenda_id = '$agenda_id' AND nominatif.nip = '$nip'");
		if(!$query){
		  return $this->db->error();
		}else{
		  return $query->num_rows();
		}

	}

	//CEK NOMINATIF 2
	public function mcek_nominatif2($layanan_id, $nip){

		$query = $this->db->query("SELECT a.nip,
								   b.layanan_id,b.agenda_nousul, 
								   c.layanan_nama
								   FROM nominatif a
 								   LEFT JOIN agenda b ON a.agenda_id = b.agenda_id
								   LEFT JOIN layanan c ON b.layanan_id = c.layanan_id
								   WHERE a.nip = '$nip' 
								   AND b.layanan_id = '$layanan_id'");
		return $query;

	}

	//CEK NOMINATIF 3
	public function mcek_nominatif3($layanan_grup, $nip){

		$query = $this->db->query("SELECT * FROM nominatif
                                 LEFT JOIN agenda ON nominatif.agenda_id = agenda.agenda_id
                                 LEFT JOIN layanan ON agenda.layanan_id = layanan.layanan_id
                                 WHERE nominatif.nip = '$nip' AND layanan.layanan_grup = '$layanan_grup'");
		if(!$query){
		  return $this->db->error();
		}else{
		  return $query->num_rows();
		}

	}

	//PERIODE KP TERAKHIR
	public function mperiodekp_terakhir($layanan_grup, $nip){

		$query = $this->db->query("SELECT * FROM nominatif
									 LEFT JOIN agenda ON nominatif.agenda_id = agenda.agenda_id
									 LEFT JOIN layanan ON agenda.layanan_id = layanan.layanan_id
									 WHERE nominatif.nip = '$nip' AND layanan.layanan_grup = '$layanan_grup'
									 ORDER BY agenda.kp_periode DESC LIMIT 1");
		if(!$query){
		  return $this->db->error();
		}else{
		  return $query->row();
		}

	}

	//TAMBAH NOMINATIF
	public function mtambah_nominatif($data){

      $this->db->insert('nominatif', $data);

	}


	//HAPUS NOMINATIF
	public function mhapus_nominatif($nip, $agenda_id){

		$this->db->delete('nominatif', array('agenda_id' => $agenda_id, 'nip' => $nip ));

	}

	//INPUT NOMINATIF EXCEL
	public function minput_nominatif($data_excel){

		$this->db->insert_batch('nominatif', $data_excel);

	}

	//HITUNG JUMLAH NOMINATIF
	public function mhitung_nominatif($agenda_id){

		$query = $this->db->query("SELECT * FROM nominatif
									 WHERE agenda_id = '$agenda_id'");
		if(!$query){
		  return $this->db->error();
		}else{
		  return $query->num_rows();
		}
	}

    //AMBIL TANGGAL BATAS KP
    public function mtanggal_bataskp($kp_periode){

		$query = $this->db->query("SELECT * FROM kp_periode
									 WHERE periode_id = '$kp_periode'");
		if(!$query){
		  return $this->db->error();
		}else{
		  return $query->row();
		}
	}

  
    //KIRIM USUL (UPDATE DATA AGENDA)
    public function mkirim_usul1($agenda_id){
       
	    $this->db->set('agenda_status','dikirim');
		$this->db->where('agenda_id',$agenda_id);
	    $this->db->update('agenda');
    }

    //KIRIM USUL (UPDATE DATA NOMINATIF)
	public function mkirim_usul2($agenda_id){

		$this->db->set('tahapan_id',2);
		$this->db->where('agenda_id',$agenda_id);
	    $this->db->update('nominatif');
		
	}
	
	public function cekDokumen($agenda_id)
	{
		$sql ="SELECT a.nip ,a.agenda_id, b.layanan_id
		FROM nominatif a  
		LEFT JOIN agenda b ON a.agenda_id = b.agenda_id
		WHERE a.agenda_id='$agenda_id' ";
		$query = $this->db->query($sql);
		
		$cek   = array();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $value)
			{
				$cek []  = $this->cekMain_dokumen($value->agenda_id,$value->layanan_id,$value->nip);		

			}			
		}
			
		
		return $cek;
	}	
	
	function cekMain_dokumen($agenda_id,$layanan_id,$nip)
	{
	    // cek dokumen usul sebelum kirim usul per nip
		$sql ="SELECT a.nip,c.dokumen_id, d.layanan_nama ,
		e.id_dokumen, f.nama_dokumen, f.flag 
		FROM nominatif a 
		LEFT JOIN agenda b ON a.agenda_id = b.agenda_id
		LEFT JOIN syarat_layanan c ON b.layanan_id = c.layanan_id
	    LEFT JOIN layanan d ON d.layanan_id = c.layanan_id
		LEFT JOIN upload_dokumen e ON (a.nip = e.nip AND e.id_dokumen = c.dokumen_id)
		LEFT JOIN dokumen f ON e.id_dokumen = f.id_dokumen
		WHERE a.agenda_id='$agenda_id' 
		AND b.layanan_id='$layanan_id'
		AND e.nip ='$nip'
		AND f.flag='1' ";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{	
		    $r['response'] = TRUE;
			$r['nip']      = $nip;
        }
		else
		{
			$r['response'] = FALSE;
			$r['nip']      = $nip;
			
		}
		
		return $r;
	}
	// kirim notifikasi ke TU
	function getTelegramAkun_bybidang($id_bidang)
	{	
		$this->db->select('first_name,last_name,telegram_id');
		$this->db->where('id_bidang', $id_bidang);
		$this->db->where('id_instansi', 4011);
		$this->db->where('active', 1);
		$this->db->where('user_tipe', 'TU');
		return $this->db->get('app_user');		
	}	

}

?>
