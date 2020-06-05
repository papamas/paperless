<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(! ini_get('date.timezone') )
{
   date_default_timezone_set('Asia/Jakarta');
}else{
   date_default_timezone_set('Asia/Jakarta');
}

class Agenda extends MY_Controller {

    var $menu_id    = 12;
	var $allow 		= FALSE;
	
	function __construct(){
		parent::__construct();
		$this->load->library(array('Auth','Menu','form_validation','Myencrypt','Telegram'));
		$this->load->model("agenda/magenda");
		$this->load->helper('form');
		$this->allow = $this->auth->isAuthMenu($this->menu_id);
	}

	//LIST AGENDA
	public function index(){		
		
		$instansi 				=  $this->session->userdata['session_instansi'];
		
		$data['menu']    		=  $this->menu->build_menu();		
		$data['lname']    		=  $this->auth->getLastName();        
		$data['name']     		=  $this->auth->getName();
        $data['jabatan']  		=  $this->auth->getJabatan();
		$data['member']	  		=  $this->auth->getCreated();
		$data['avatar']	  		=  $this->auth->getAvatar();
		
		
		
		$data['list_agenda'] 	= $this->magenda->mlist_agenda($instansi);
		$data['pesan']          = !empty($this->session->flashdata('berhasil')) ? $this->session->flashdata('berhasil') : $this->session->flashdata('gagal') ;
		$data['tipe']           = !empty($this->session->flashdata('berhasil')) ? 'success' : 'error' ;
		$data['title']          = !empty($this->session->flashdata('berhasil')) ? 'Berhasil !' : 'Gagal !' ;
		
		if($this->session->flashdata('berhasil') || $this->session->flashdata('gagal'))
		{
			$data['show'] = TRUE;
		}	
		else
		{		
			$data['show'] = FALSE;
		}
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('agenda/index', $data);
		
	}

	//FORM TAMBAH AGENDA
	public function tambah(){

		$data['menu']    		=  $this->menu->build_menu();
		$data['lname']    		=  $this->auth->getLastName();        
		$data['name']     		=  $this->auth->getName();
        $data['jabatan']  		=  $this->auth->getJabatan();
		$data['member']	  		=  $this->auth->getCreated();
		$data['avatar']	  		=  $this->auth->getAvatar();
		
		$data['list_layanan'] = $this->magenda->mlist_layanan();
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('agenda/agenda_tambah', $data);		

	}

	//FUNGSI TAMBAH AGENDA
	public function ftambah(){

		$instansi 				= $this->session->userdata['session_instansi'];
		$no_usul 				= $this->input->post('input_nousul');
		$layanan_id 			= $this->input->post('input_layanan');
		$layanan_grup 			= $this->magenda->mcek_layanangrup($layanan_id)->layanan_grup;
		$batas_kp 				= $this->magenda->mcek_bataskp()->periode_batas;
		$tanggal_sekarang 		= date('Y-m-d');
		$jumlah 				= $this->input->post('input_jumlah');

		//Tolak No Usul dan Layanan yang sama
		$cek_usul_layanan = $this->magenda->mcek_usul_layanan($no_usul, $layanan_id);
		
		if($cek_usul_layanan > 0){
		  $this->session->set_flashdata('gagal', "No Usul dan Layanan yang sama sudah pernah dibuat");
		  redirect('agenda');
		}
		
		if($jumlah > 50){
		  $this->session->set_flashdata('gagal', "Maximal 50 Jumlah Nominatif dalam satu Agenda");
		  redirect('agenda');
		}

		
		$data = array(
				   'agenda_nousul' 		=> trim($no_usul),
				   'layanan_id' 		=> $layanan_id,
                   'agenda_jumlah' 		=> $jumlah,
                   'agenda_ins' 		=> $this->session->userdata['session_instansi'],
                   'agenda_tgl' 		=> $tanggal_sekarang,
                   'agenda_thn' 		=> date('Y'),
                   'agenda_created_by' 	=> $this->session->userdata['user_id'],
				   
		);


		$target_dir  = './agenda/'.$instansi;
		//Data Dokumen
		if($_FILES['input_dokumen']['name'] != NULL){
			
			if (!is_dir($target_dir)) {
				mkdir($target_dir, 0777, TRUE);
			}
			 
			$config['upload_path'] 	    = $target_dir;
			$config['allowed_types']    = 'pdf';
			$config['max_size'] 		= '3024';
			$config['encrypt_name']		= TRUE;	
			
			$this->load->library('upload', $config);
			
			
			if($this->upload->do_upload('input_dokumen')){			
				$upload       					= $this->upload->data();	      	
				//Nama File
				$data['agenda_dokumen'] 		= $upload['file_name'];          
			} else {

			  $this->session->set_flashdata('gagal', "Gagal Menyimpan, File Max. 3 MB | File Di Ijinkan Hanya pdf");
			  redirect('agenda');

			}

		}

		$result		=  $this->magenda->mtambah_agenda($data);
		
		if($result['response']  === TRUE)
		{	
			$this->session->set_flashdata('berhasil', $result['pesan']);
		}
		else
		{
			$this->session->set_flashdata('gagal', $result['pesan']);
		}
		
		redirect('agenda');

	}

	//FORM UBAH AGENDA
	public function ubah($id){
	   
		$data['menu']    		=  $this->menu->build_menu();
		$data['lname']    		=  $this->auth->getLastName();        
		$data['name']     		=  $this->auth->getName();
        $data['jabatan']  		=  $this->auth->getJabatan();
		$data['member']	  		=  $this->auth->getCreated();
		$data['avatar']	  		=  $this->auth->getAvatar();
		
		$data['detail_agenda']  = $this->magenda->mdetail_agenda($id);
        $data['list_layanan']   = $this->magenda->mlist_layanan();	
		$this->load->view('agenda/agenda_ubah', $data);		

	}

	//FUNGSI UBAH AGENDA
	public function fubah(){

		$instansi 			= $this->session->userdata['session_instansi'];
		
		$id 				= $this->input->post('input_id');
		$no_usul 			= $this->input->post('input_nousul');
		$layanan_id 		= $this->input->post('input_layanan');
		$layanan_grup 		= $this->magenda->mcek_layanangrup($layanan_id)->layanan_grup;
		
		$batas_kp 			= $this->magenda->mcek_bataskp()->periode_batas;
		$tanggal_sekarang 	= date('Y-m-d');
		
		$target_dir  		= './agenda/'.$instansi;

		
		//Cek Batas KP
		if($layanan_grup == 'KP'){
		  if($tanggal_sekarang <= $batas_kp){
			$kp_periode = $this->magenda->mcek_bataskp()->periode_id;
		  }else{
			$this->session->set_flashdata('gagal', "Periode KP sudah berakhir pada tanggal $batas_kp ");
			redirect('agenda');
		  }
		}else{
		  $kp_periode = NULL;
		}

		$data = array(
					   'agenda_nousul' 			=> trim($no_usul),
					   'layanan_id' 			=> $layanan_id,
					   'agenda_jumlah' 			=> $this->input->post('input_jumlah'),
					   'kp_periode' 			=> $kp_periode
					 );

		//Data Dokumen
		if($_FILES['input_dokumen']['name'] != NULL){

			  //Hapus Gambar Sebelumnya
			  if($this->input->post('input_dokumen_sblm') != NULL ){
				unlink($target_dir.'/'.$this->input->post('input_dokumen_sblm'));
			  }

			 
			  $config['upload_path'] 	= $target_dir;
			  $config['allowed_types'] 	= 'pdf';
			  $config['max_size'] 		= '3024';
			  $config['encrypt_name']	= TRUE;	
			 
			  $this->load->library('upload', $config);
			 

			  if($this->upload->do_upload('input_dokumen')){
				 
				  $upload 		= $this->upload->data();	
				  //Nama File
			      $data['agenda_dokumen'] 		= $upload['file_name']; 

			   } else {

				  $this->session->set_flashdata('gagal', "Gagal Menyimpan, File Max. 3 MB | File Di Ijinkan Hanya pdf");
				  redirect('agenda');

			   }

		}

		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		
		$cond = array('agenda_id' => $id);
		
		if (!$this->magenda->mubah_agenda($data, $cond)) {
			$error = $this->db->_error_message(); 
			
			if(!empty($error))
			{
				$this->session->set_flashdata('gagal', $error);
			}
			else
			{
				$this->session->set_flashdata('berhasil', 'Agenda Berhasil Diubah');
			}
			
		}
		
		$this->db->db_debug = $db_debug; //restore setting		
		redirect('agenda');

	}

	//HAPUS AGENDA
	public function hapus($agenda_id, $dokumen){

		$instansi			= $this->session->userdata['session_instansi'];
		$target_dir  		= './agenda/'.$instansi;
		
		unlink($target_dir."/".$dokumen);

		$this->magenda->mhapus_agenda($agenda_id);
		$this->session->set_flashdata('berhasil', "Agenda dihapus");
		redirect('agenda');

	}

	//LIST NOMINATIF
	public function nominatif($agenda_id){

		$data['menu']    		=  $this->menu->build_menu();
		$data['lname']    		=  $this->auth->getLastName();        
		$data['name']     		=  $this->auth->getName();
		$data['jabatan']  		=  $this->auth->getJabatan();
		$data['member']	  		=  $this->auth->getCreated();
		$data['avatar']	  		=  $this->auth->getAvatar();
		
		$data['detail_agenda']  = $this->magenda->mdetail_agenda($agenda_id);
		$data['list_nominatif'] = $this->magenda->mlist_nominatif($agenda_id);
		
		$this->session->set_userdata('layanan_id',$data['detail_agenda']->layanan_id);
		
		$data['pesan']          = !empty($this->session->flashdata('berhasil')) ? $this->session->flashdata('berhasil') : $this->session->flashdata('gagal') ;
		$data['tipe']           = !empty($this->session->flashdata('berhasil')) ? 'success' : 'error' ;
		$data['title']          = !empty($this->session->flashdata('berhasil')) ? 'Berhasil !' : 'Gagal !' ;
		if($this->session->flashdata('berhasil') || $this->session->flashdata('gagal'))
		{
			$data['show'] = TRUE;
		}		
		else
		{		
			$data['show'] = FALSE;
		}
		
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('agenda/nominatif', $data);		

	}

	//CARI NOMINATIF
	public function autocomplete(){

		$instansi    = $this->session->userdata['session_instansi'];
		$layanan_id  = $this->session->userdata('layanan_id');
	 		
		$nip         = $this->input->get('kirim');
		$result      = $this->magenda->mcari_nominatif($nip, $instansi);
		
		

		if ($result->num_rows() > 0)
		{
			$row			= $result->row();
			$golongan       = $row->pns_golru;
			
			printf('<div id="item" onClick="kirim(\'%s\',\'%s\',\'%s\',\'%s\',\'%s\');">%s - %s</div> ', $row->pns_nipbaru, $row->pns_pnsnam, $row->gol_golnam, $row->dik_namdik, $row->ins_namins,$row->pns_nipbaru, $row->pns_pnsnam);
			
						
	    }
		else
		{
			echo '<div id="item">NIP bukan dari instansi anda</div>';
		}


	}

	//FUNGSI TAMBAH NOMINATIF
	public function ftambah_nominatif(){

    
		$this->form_validation->set_rules('input_nip', 'NIP', 'trim|required|max_length[18]');

		$agenda_id 				= $this->input->post('input_agendaid');
		$layanan_id 			= $this->input->post('input_layananid');
		$layanan_nama 			= $this->input->post('input_layanannama');
		$layanan_grup 			= $this->input->post('input_layanangrup');
		$kp_periode 			= $this->input->post('input_periodekp');
		$input_nip 				= $this->input->post('input_nip');
		$nip 					= preg_replace("/[^0-9]/", "", $input_nip);
		$instansi 				= $this->session->userdata['session_instansi'];

		$cek_nip = $this->magenda->mcek_nip($nip, $instansi);
		if($cek_nip == 0){
		  $this->session->set_flashdata('gagal', "NIP tidak terdaftar / bukan dari instansi anda");
		  redirect('agenda/nominatif/'.$agenda_id);
		}

		$belum_selesai = $this->magenda->belum_selesai($nip);
		
		if($belum_selesai->num_rows() > 0)
		{
		    $row =  $belum_selesai->row();
			// KARIS/KARSU/KARPEG boleh usul paralel yang lain tidak boleh
			if($layanan_id == 9 || $layanan_id == 10 || $layanan_id == 11 )
			{	
				$adaSm = $this->magenda->mcek_nominatif2($layanan_id, $nip);
				if($adaSm->num_rows() > 0)
				{	
					$rowadSm  = $adaSm->row();
					$this->session->set_flashdata('gagal', "NIP masih dalam proses pada Sistem Male_o 1.9 dengan Nomor Usul :".$rowadSm->agenda_nousul." pada layanan ".$rowadSm->layanan_nama);
					redirect('agenda/nominatif/'.$agenda_id);
				}	
			}
			else
			{
				$this->session->set_flashdata('gagal', "NIP masih dalam proses pada Sistem Male_o 1.9 dengan Nomor Usul :".$row->agenda_nousul." pada layanan ".$row->layanan_nama);
				redirect('agenda/nominatif/'.$agenda_id);
			}
		}			
		
		/* $cek_nominatif = $this->magenda->mcek_nominatif($agenda_id, $nip);
		if($cek_nominatif > 0){
		  $this->session->set_flashdata('gagal', "NIP telah terdaftar di agenda ini");
		  redirect('agenda/nominatif/'.$agenda_id);
		}else if($cek_nominatif == 0){
			$cek_nominatif2 = $this->magenda->mcek_nominatif2($layanan_id, $nip);
			if($cek_nominatif2 > 0){
			  $this->session->set_flashdata('gagal', "NIP telah terdaftar dilayanan yang sama yaitu $layanan_nama");
			  redirect('agenda/nominatif/'.$agenda_id);
			}else if($cek_nominatif2 == 0){
			  $cek_nominatif3 = $this->magenda->mcek_nominatif3($layanan_grup, $nip);
			  if($cek_nominatif3 > 0 && $kp_periode == NULL){
				$this->session->set_flashdata('gagal', "NIP telah terdaftar di grup layanan yang sama yaitu $layanan_grup");
				redirect('agenda/nominatif/'.$agenda_id);
			  }else if($cek_nominatif3 > 0 && $kp_periode != NULL){
				 $periodekp_terahir = $this->magenda->mperiodekp_terakhir($layanan_grup, $nip)->kp_periode;
				 if($kp_periode == $periodekp_terahir){
				   $this->session->set_flashdata('gagal', "NIP telah terdaftar di periode KP yang sama");
				   redirect('agenda/nominatif/'.$agenda_id);
				 }
			  }
		   }
		} */

		$data = array(
					   'agenda_id' => $agenda_id,
					   'nip' => $nip
					 );

		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		
		if (!$this->magenda->mtambah_nominatif($data)) {
			
			$error = $this->db->_error_message(); 
			
			if(!empty($error))
			{
				$this->session->set_flashdata('gagal', $error);
			}
			else
			{
				$this->session->set_flashdata('berhasil', "Nominatif berhasil ditambah");
			}
			
		}
		
		$this->db->db_debug = $db_debug; //restore setting	
		redirect('agenda/nominatif/'.$agenda_id);

	}

 
	//HAPUS NOMINATIF
	public function hapus_nominatif($nip, $agenda_id){
		
		$this->magenda->mhapus_nominatif($nip, $agenda_id);
		$this->session->set_flashdata('berhasil', "Nominatif dihapus");
		redirect('agenda/nominatif/'.$agenda_id);
	}

	//KIRIM USUL
	public function kirim_usul(){

		$agenda_id 				= $this->input->post('input_agendaid');
		$agenda_jumlah 			= $this->input->post('input_agendajumlah');
		$kp_periode 			= $this->input->post('input_periodekp');
		$tanggal_sekarang 		= date("Y-m-d");


		$jumlahnom = $this->magenda->mhitung_nominatif($agenda_id);
		if($agenda_jumlah != $jumlahnom){
		  $this->session->set_flashdata('gagal', "Gagal Kirim, Jumlah Nominatif belum sesuai");
		  redirect('agenda/nominatif/'.$agenda_id);
		}
		
		// cek by nip apakah ada dokumen usul	
		$cek  = TRUE;
		$nip  = array();
		
		$result    = $this->magenda->cekDokumen($agenda_id);
		for($i=0;$i < count($result);$i++)
		{
			// jika salah satu tidak ada dokumen batalkan seluruhnya
			$cek &= $result[$i]['response'];
			if($result[$i]['response'] == FALSE)
			{	
			    $nip[]  = $result[$i]['nip'];	
			}	
		}
		
		if(!boolval($cek))
		{	
			$p  = implode(",",$nip);
			$this->session->set_flashdata('gagal', "Gagal Kirim, NIP ".$p." tidak ada dokumen usul");
			redirect('agenda/nominatif/'.$agenda_id);
		}
		
		$this->db->trans_begin();

	    $this->magenda->mkirim_usul1($agenda_id);
		$this->magenda->mkirim_usul2($agenda_id);
				
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('gagal', "Usul Gagal dikirim");
		}
		else
		{
			$this->db->trans_commit();
			$this->send_to_Telegram($agenda_id,$agenda_jumlah);
			$this->session->set_flashdata('berhasil', "Usul Berhasil dikirim");
		}

		redirect('agenda');
	  
	}

    public function getPdf($instansi, $file)	{
				
		$instansi  = $this->myencrypt->decode($instansi);
		$file      = $this->myencrypt->decode($file);
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/pdf');
		header('Content-Disposition:attachment; filename=pengantar.pdf');                      
		header('Expires:0'); 
		readfile(base_url().'agenda/'.$instansi.'/'.$file);
	}

	public function getXls()	{
				
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/vnd.ms-excel');
		header('Content-Disposition:attachment; filename=FileContohImport.xls');                      
		header('Expires:0'); 
		readfile(base_url().'format/FormatNominatif.xls');
	}	
	
	/* Kirim Notifikasi Telegram ke BKN per bidang layanan*/
	
	function send_to_Telegram($agenda_id,$agenda_jumlah)
	{
		$row_agenda	    =  $this->magenda->mdetail_agenda($agenda_id);
		$TelegramAkun   =  $this->magenda->getTelegramAkun_bybidang($row_agenda->layanan_bidang);
				
		if($TelegramAkun->num_rows() > 0)
		{	
			foreach($TelegramAkun->result() as $value)
			{	
				// send to telegram API
				if(!empty($value->telegram_id))
				{	
					$this->telegram->sendApiAction($value->telegram_id);
					$text  = "<pre>Hello, <strong>".$value->first_name ." ".$value->last_name. "</strong>  Ada Usul berkas baru nih :";
					$text .= "\n Tanggal:".date('d-m-Y H:i:s');
					$text .= "\n Nomor Usul:".$row_agenda->agenda_nousul;
					$text .= "\n Layanan:".$row_agenda->layanan_nama;
					$text .= "\n Instansi:".$row_agenda->instansi;
					$text .= "\n Jumlah:".$agenda_jumlah.'</pre>';
					$this->telegram->sendApiMsg($value->telegram_id, $text , false, 'HTML');
					
				}	
			}
		}
	}	

}

?>
