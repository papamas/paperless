<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Berkas extends MY_Controller {
	
	var $menu_id    = 13;
	var $allow 		= FALSE;
	
	function __construct()
	{
	    parent::__construct();		
	    $this->load->library(array('Auth','Menu','form_validation','Myencrypt','Telegram'));
		$this->load->model('berkas/berkas_model', 'berkas');
		$this->allow = $this->auth->isAuthMenu($this->menu_id);
	} 
	
	public function index()
	{
	   
		$data['menu']      		=  $this->menu->build_menu();
		$data['lname']    		=  $this->auth->getLastName();        
		$data['name']      		=  $this->auth->getName();
        $data['jabatan']   		=  $this->auth->getJabatan();
		$data['member']	   		=  $this->auth->getCreated();
		$data['avatar']	   		=  $this->auth->getAvatar();
		
		$data['instansi']  		= $this->berkas->getInstansi();
		$data['show']  			= FALSE;
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('berkas/index',$data);
	}
	
	
	public function getBerkas()
	{
		$this->form_validation->set_rules('instansi', 'instansi', 'required');
		$this->form_validation->set_rules('searchby', 'Filter', 'required');
		$this->form_validation->set_rules('search', 'Data', 'required');
		$this->form_validation->set_rules('status', 'status', 'trim');
		
		$search['search']              = $this->input->post('search');
		$search['searchby']            = $this->input->post('searchby');
		$search['status']              = $this->input->post('status');
		$perintah           		   = $this->input->post('perintah');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['menu']      =  $this->menu->build_menu();
			$data['lname']     =  $this->auth->getLastName();        
			$data['name']      =  $this->auth->getName();
			$data['jabatan']   =  $this->auth->getJabatan();
			$data['member']	   =  $this->auth->getCreated();
			$data['avatar']	   =  $this->auth->getAvatar();
			
			$data['instansi']  = $this->berkas->getInstansi();
			$data['show']  	   = FALSE;
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('berkas/index',$data);
		}
		else
		{	
			$q	  						   = $this->berkas->getUsulDokumen($search);
				
			if($perintah == 1)			{
				
				$data['menu']     		=  $this->menu->build_menu();
				$data['lname']    		=  $this->auth->getLastName();        
				$data['name']     		=  $this->auth->getName();
				$data['jabatan']  		=  $this->auth->getJabatan();
				$data['member']	  		=  $this->auth->getCreated();
				$data['avatar']	  		=  $this->auth->getAvatar();
				$data['usul']	  		=  $q;
				$data['instansi']  		=  $this->berkas->getInstansi();
				$data['show']  			=  TRUE;
				if(!$this->allow)
				{
					$this->load->view('403/index',$data);
					return;
				}
				$this->load->view('berkas/index',$data);
			}
			else
			{	
				$this->_getExcel($q);
			}
		}	
	}
	
	private function _getExcel($q)
	{
		// creating xls file
		$now              = date('dmYHis');
		$filename         = "USUL BERKAS INSTANSI".$now.".xls";
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/vnd.ms-excel');
		header('Content-Disposition:attachment; filename='.$filename);                      
		header('Expires:0'); 
		
		$html  = 'CEK BERKAS';
		if($q->num_rows() > 0){
			$row = $q->row();
		$html .= '<table>';	
		$html .= '<tr><td  colspan=2>TANGGAL</td><td>'.date('d-M-Y H:i:s').'</td></tr>';
		$html .= '<tr><td  colspan=2>INSTANSI</td><td>'.$row->instansi.'</td></tr>';		
		$html .= '</table><p></p>';
		}
		$html .= '<style> .str{mso-number-format:\@;}</style>';
		$html .= '<table border="1">';					
		$html .='<tr>
					<th>NO</th>
					<th>NIP</th>
					<th>NAMA</th>
					<th>AGENDA</th>
					<th>TERIMA</th>
					<th>LAYANAN</th>
					<th>STATUS</th>
					<th>ALASAN</th>
					<th>BERKAS YANG DI UPLOAD</th>
					'; 
		$html 	.= '</tr>';
		if($q->num_rows() > 0){
			$i = 1;		        
			foreach ($q->result() as $r) {
				$html .= "<tr><td>$i</td>";				
				$html .= "<td class=str>{$r->nip}</td>";	
                $html .= "<td>{$r->nama}</td>";	
				$html .= "<td>{$r->agenda_nousul}</td>";
				$html .= "<td>{$r->agenda_timestamp}</td>";				
				$html .= "<td>{$r->layanan_nama}</td>";
				$html .= "<td>{$r->nomi_status}</td>";		
                $html .= "<td>{$r->nomi_alasan}</td>";					
				$html .= "<td>{$r->upload_dokumen}</td>";					
				$html .= "</tr>";
				$i++;
			}
			$html .="</table>";
			echo $html;
		}else{
			$html .="<tr><td  colspan=6 >There is no data found</td></tr></table>";
			echo $html;
		} 	
	}	
	
	public function getKelengkapan()	{
		
		$nip         		 = $this->myencrypt->decode($this->input->get('n'));
		$berkas      		 = $this->berkas->getUploadDokumen($nip);
		$layanan    		 = $this->myencrypt->decode($this->input->get('l'));
		$agenda_dokumen      = $this->myencrypt->decode($this->input->get('d'));
		$agenda_instansi     = $this->myencrypt->decode($this->input->get('i'));
		
		$html = '';
		$html .='<table class="table table-bordered table-striped table-condensed">
						<thead>
						    <tr>
							<td colspan="2">LAYANAN USUL '.$layanan.'</td>
							<td colspan="2"><button class="btn bg-maroon btn-flat btn-xs" data-tooltip="tooltip"  title="Lihat Surat Pengantar" data-toggle="modal" data-target="#lihatSuratPengantarModal" data-id="?id='.$this->myencrypt->encode($agenda_instansi).'&f='.$this->myencrypt->encode($agenda_dokumen).'"><i class="fa fa-search"></i></button></td>
							</tr>
							<tr>
								<th>ADA</th>
								<th>NAMA BERKAS</th><th></th><th>TAHUN/GOL/TINGKAT</th></tr></thead>';
		foreach($berkas->result() as $value)
		{
		        $jenis_sk     = $value->nama_dokumen;
														
				if($jenis_sk != "IJAZAH" && $jenis_sk != "TRANSKRIP" && $jenis_sk != "IBEL" && $jenis_sk != "MOU") 
				{
					switch($value->minor_dok){
						case 45:
							$n = "IV/e";
						break;
						case 44:
							$n = "IV/d";
						break;
						case 43:
							$n = "IV/c";
						break;
						case 42:
							$n = "IV/b";
						break;
						case 41:
							$n = "IV/a";
						break;
						case 34:
							$n = "III/d";
						break;
						case 33:
							$n = "III/c";
						break;
						case 32:
							$n = "III/b";
						break;
						case 31:
							$n = "III/a";
						break;
						case 24:
							$n = "II/d";
						break;
						case 23:
							$n = "II/c";
						break;
						case 22:
							$n = "II/b";
						break;
						case 21:
							$n = "II/a";
						break;
						case 14:
							$n = "I/d";
						break;
						case 13:
							$n = "I/c";
						break;
						case 12:
							$n = "I/b";
						break;
						case 11:
							$n = "I/a";
						break;
						default:
							$n = $value->minor_dok;									
																	
					}	
				}
				else
				{
					
					switch($value->minor_dok){
						case 50:
							$n = "S-3/Doktor";
						break;
						case 45:
							$n = "S-2";
						break;
						case 40:
							$n = "S-1/Sarjana";
						break;
						case 35:
							$n = "Diploma IV";
						break;
						case 30:
							$n = "Diploma III/Sarjana Muda";
						break;
						case 25:
							$n = "Diploma II";
						break;
						case 20:
							$n = "Diploma I";
						break;
						case 18:
							$n = "SLTA Keguruan";
						break;
						case 17:
							$n = "SLTA Kejuruan";
						break;
						case 15:
							$n = "SLTA";
						break;
						case 12:
							$n = "SLTP Kejuruan";
						break;
						case 10:
							$n = "SLTP";
						break;
						case 05:
							$n = "Sekolah Dasar";
						break;														
						default:
							$n = $value->minor_dok;									
																	
					}								
						
				}
			$html .='<tr>
						<td><i class="fa fa-check" style="color:green;"></i></td>	
						<td>'.$value->nama_dokumen.'</td>
						<td><button class="btn bg-navy btn-flat btn-xs" data-tooltip="tooltip"  title="Lihat File" data-toggle="modal" data-target="#lihatFileModal" data-id="?id='.$this->myencrypt->encode($value->id_instansi).'&f='.$this->myencrypt->encode($value->orig_name).'"><i class="fa fa-search"></i></button></td>
						<td>'.$n.'</td></tr>';	
		}
		$html .='</table>';
		
		echo $html;
	}
	
	public function getInline()
	{
		$instansi  = $this->myencrypt->decode($this->input->get('id'));
		$file      = $this->myencrypt->decode($this->input->get('f'));
		
						
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/pdf');
		header('Content-Disposition:inline; filename='.$file);                      
		header('Expires:0');
        ob_end_clean();				
		readfile(base_url().'uploads/'.$instansi.'/'.$file);
	}	
	
	public function getInlinePengantar()
	{
		$instansi  = $this->myencrypt->decode($this->input->get('id'));
		$file      = $this->myencrypt->decode($this->input->get('f'));
						
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/pdf');
		header('Content-Disposition:inline; filename='.$file);                      
		header('Expires:0'); 
		 ob_end_clean();		
		readfile(base_url().'agenda/'.$instansi.'/'.$file);
	}	
	
	
	
	
	public function kirim()
	{
		$data['nip']     = $this->myencrypt->decode($this->input->post('nip'));
		$data['agenda']  = $this->myencrypt->decode($this->input->post('agenda'));
		$data['btlFrom'] = $this->myencrypt->decode($this->input->post('btlFrom'));
		
		$this->db->trans_begin();
		
        $agenda_id          = $this->myencrypt->decode($this->input->post('agenda'));
		$data['response']	= $this->berkas->KirimUlang($data);		
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			
			$data['pesan']		= 'Berkas Gagal dikirim kembali ke BKN';
			$this->output
			->set_status_header(406)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		}
		else
		{
			
			$data['pesan']		= 'Berkas berhasil dikirim kembali ke BKN';			
			
			// send notifikasi to  telegram			
			$this->send_to_Telegram($data);			
			
			$this->db->trans_commit();			
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		}
		
		
		
		
	}
	
	public function getAlasan(){
	
	    $data['nip']         = $this->myencrypt->decode($this->input->get('n'));
		$data['agenda']      = $this->myencrypt->decode($this->input->get('a'));
		$alasan              = $this->berkas->getAlasan($data)->row();
		
		$html = '';
		$html .='<table class="table table-bordered table-striped table-condensed">
						<thead>
						    <tr><td colspan="4"><b>KETERANGAN ALASAN BERKAS BTL</b></td></tr>
						</thead>';
		$html .='<tr>
					<td>'.$alasan->nomi_alasan.'</td>
				</tr>';							
		$html .='</table>';
		
		echo $html;
	}	
	
	public function upload()
	{
		
		$instansi						= $this->input->post('agenda_ins');
		$nip							= $this->input->post('agenda_nip');
		$agenda							= $this->input->post('agenda_id');
		$layanan						= $this->input->post('agenda_layanan');
		$golongan						= $this->input->post('agenda_golongan');
		$gol                            = intval($golongan) + 1;
		switch($layanan){
			case 1:
			    $name  = 'SK_KP_'.$nip.'_'.$gol;				
			break;
			case 2:
			    $name  = 'SK_KP_'.$nip.'_'.$gol;				
			break;
			case 3:
			    $name  = 'SK_KP_'.$nip.'_'.$gol;			
			break;			
		    case 4:
			    $name  = 'SK_PENSIUN_'.$nip;				
			break;
			case 6:
			    $name  = 'SK_PENSIUN_'.$nip;				
			break;
			case 7:
			    $name  = 'SK_PENSIUN_'.$nip;					
			break;
			case 8:
			    $name  = 'SK_PENSIUN_'.$nip;				
			break;
			case 9:
				$name  = 'KARIS_'.$nip;				
			break;
			case 10:
				$name  = 'KARSU_'.$nip;				
			break;
			case 11:
				$name  = 'KARPEG_'.$nip;				
			break;
			case 12:
				$name  = 'SK_KP_'.$nip.'_'.$gol;			
			break;
			case 13:
				$name  = 'SK_MUTASI_'.$nip;			
			break;
			case 14:
				$name  = 'SK_PG_'.$nip;			
			break;
		}	
		
		
		$target_dir						='./uploads/'.$instansi;		
		$config['upload_path']          = $target_dir;
		$config['allowed_types']        = 'pdf';
		$config['max_size']             = 3024;
		$config['encrypt_name']			= FALSE;	
		$config['overwrite']			= TRUE;	
		$config['detect_mime']			= TRUE;
		$config['file_name']            = $name;
		
		$this->load->library('upload', $config);
		
		if (! $this->upload->do_upload('file'))
		{
				$error = array('error' => strip_tags($this->upload->display_errors()));

				$this->output
						->set_status_header(406)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($error));
				
		}
		else
		{
				$data 		          = $this->upload->data();
				$data['id_instansi']  = $instansi;
				$data['nip']		  = $nip;				
				$result		          = $this->berkas->insertUpload($data);
				
			
				if($result['response'])
				{
				    $this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($result)); 
                }
				else
				{
					// update nominatif
					$this->berkas->updateNominatif($data);
					
					$result['updated']  = $this->berkas->updateFile($result);
					$result['error'] 	= 'File Surat Keputusan sudah ada, overwrite file';
					$this->output
						->set_status_header(406)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($result));

                }			
				
		}
		
	}	
	
	public function getBerkasAll()
	{
		$search           = $this->input->post();
		$entry			  = $this->berkas->getUsulDokumen($search);
		
		$html = '';
		$html .='<table id="tb-entry" class="table table-striped table-condensed">
						<thead>
							<tr>
								<th style="width:125px;"></th>	
								<th>NOUSUL</th>									
								<th style="width:16%">NIP</th>
								<th>NAMA</th>
								<th>UPDATE</th>
								<th>PELAYANAN</th>
								<th>STATUS</th>
								<th style="width:50px;">FILE</th>
								<th>TAHAPAN</th>
							</tr>
						</thead>  ';
		foreach($entry->result() as $value)
		{
			$layanan = $value->layanan_id;
			$link  ='';
			$link2 ='';
			if($value->nomi_status == 'BTL')
			{
				$link='&nbsp;<a href="#" class="btn bg-maroon btn-flat btn-xs" data-tooltip="tooltip"  title="Kirim Ulang Berkas BTL ini" data-toggle="modal" data-target="#kirimModal" data-nip="'.$this->myencrypt->encode($value->nip).'" data-agenda="'.$this->myencrypt->encode($value->agenda_id).'" data-btl="'.$this->myencrypt->encode($value->btl_from).'" ><i class="fa fa-mail-forward"></i></a>';	
				$link.='&nbsp;<button class="btn btn-success btn-xs" data-tooltip="tooltip"  title="Update Surat Pengatar" data-toggle="modal" data-target="#updatePengantarModal" data-layanan="'.$value->layanan_id.'" data-agenda="'.$value->agenda_id.'" data-instansi="'.$value->agenda_ins.'" data-nip="'.$value->nip.'" data-gol="'.$value->golongan.'"><i class="fa fa-upload"></i></button>';
				$link2='<a href="#" class="btn bg-orange btn-xs" data-tooltip="tooltip"  title="Cek Keterangan Alasan BTL" data-toggle="modal" data-target="#cekModal" data-id="?n='.$this->myencrypt->encode($value->nip).'&a='.$this->myencrypt->encode($value->agenda_id).'">'.$value->nomi_status.'</a>';
			}
			else
			{
				$link2='<span class="'.$value->bg.'">'.$value->nomi_status.'</span>';
				
			}
			
			$html .='<tr><td>';
			$html .='<a href="#" class="btn bg-orange btn-flat btn-xs" data-tooltip="tooltip"  title="Lihat Kelengkapan Berkas" data-toggle="modal" data-target="#lihatModal" data-id="?n='.$this->myencrypt->encode($value->nip).'&l='.$this->myencrypt->encode($value->layanan_nama).'&d='.$this->myencrypt->encode($value->agenda_dokumen).'&i='.$this->myencrypt->encode($value->agenda_ins).'"><i class="fa fa-search"></i></a>';			
			$html.= '&nbsp;<button class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="upload Surat Keputusan" data-toggle="modal" data-target="#uploadModal" data-layanan="'.$value->layanan_id.'" data-agenda="'.$value->agenda_id.'" data-instansi="'.$value->agenda_ins.'" data-nip="'.$value->nip.'"><i class="fa fa-upload"></i></button>';
			$html .= $link;
			$html .='</td>';						
			$html .='	<td>'.$value->agenda_nousul.'</td>
						<td>'.($value->nomi_locked == "1" ?  '<i class="fa fa-lock"></i>'.$value->nip : $value->nip).'</td>
						<td>'.$value->nama.'</td>
						<td>'.$value->update_date.'</td>																					
						<td>'.$value->layanan_nama.'</td>
						<td>'.$link2.'</td>';
			$html .='<td>';
						if(!empty($value->upload_persetujuan))
						{
							$file = $value->file_persetujuan_raw_name.'.pdf';
							
							$html .= '<span data-toggle="tooltip" data-original-title="Ada File Persetujuan">
							<i class="fa fa-file-pdf-o" data-toggle="modal" data-target="#lihatFileModal" data-id="?id='.$this->myencrypt->encode($value->agenda_ins).'&f='.$this->myencrypt->encode($file).'" style="color:red;"></i></span>';
						}
						else
						{
							$html .= '<span data-toggle="tooltip" data-original-title="Tidak Ada File Persetujuan">
							<i class="fa fa-file-o" style="color:red;"></i></span>';
						}
						
						
						if(!empty($value->upload_sk))
						{
							$file = $value->file_sk_raw_name.'.pdf';
							
							$html .= '<span data-toggle="tooltip" data-original-title="Ada File Surat Keputusan">
							<i class="fa fa-file-pdf-o" data-toggle="modal" data-target="#lihatFileModal" data-id="?id='.$this->myencrypt->encode($value->agenda_ins).'&f='.$this->myencrypt->encode($file).'" style="color:red;"></i></span>';
						}
						else
						{
							$html .= '<span data-toggle="tooltip" data-original-title="Tidak Ada File Surat Keputusan">
							<i class="fa fa-file-o" style="color:red;"></i></span>';
						}									
							
			$html .= '</td>';						
			$html .='<td>'.$value->tahapan_nama.(!empty($value->ln_work) ? ' Oleh '.$value->ln_work : '').'</td>';
			$html .='</tr>';	
		}
		$html .='</table>';		
        echo $html;		
		
	}
	/* Kirim Notifikasi Telegram kirim ulang  Berkas BTL BKN per bidang layanan*/
	
	function send_to_Telegram($data)
	{
		$agenda_id      = $data['agenda'];
		$nip			= $data['nip'];
		$btlFrom        = $data['btlFrom'];
		
		$row_agenda	    =  $this->berkas->getAgenda_byid($agenda_id,$nip)->row();
		$TelegramAkun   =  $this->berkas->getTelegramAkun_bybidang($row_agenda->layanan_bidang,$btlFrom);
		
		if($btlFrom  == 3)
		{
			$txt 	= 'TU';
		}
		else
		{
			$txt    = 'TEKNIS'; 
		}	
				
		if($TelegramAkun->num_rows() > 0)
		{	
			foreach($TelegramAkun->result() as $value)
			{	
				// send to telegram API
				if(!empty($value->telegram_id))
				{	
					$this->telegram->sendApiAction($value->telegram_id);
					$text  = "<pre>Hello, <strong>".$value->first_name ." ".$value->last_name. "</strong>  Ada berkas yang telah di BTL kan oleh ".$txt."  sudah dikirim ulang nih :";
					$text .= "\n Tanggal:".date('d-m-Y H:i:s');
					$text .= "\n Nomor Usul:".$row_agenda->agenda_nousul;
					$text .= "\n Layanan:".$row_agenda->layanan_nama;
					$text .= "\n NIP :".$row_agenda->nip;
					$text .= "\n Nama PNS:".$row_agenda->PNS_GLRDPN.''.$row_agenda->PNS_PNSNAM.''.$row_agenda->PNS_GLRBLK;
					$text .= "\n Instansi:".$row_agenda->instansi.'</pre>';
					$this->telegram->sendApiMsg($value->telegram_id, $text , false, 'HTML');
				    
				}	
			}
		}
	}	
	
	
	public function updatePengantar()
	{
		
		$instansi						= $this->input->post('agenda_ins');
				
		$target_dir						='./agenda/'.$instansi;	

        if (!is_dir($target_dir)) {
			mkdir($target_dir, 0777, TRUE);
		}
			
		$config['upload_path']          = $target_dir;
		$config['allowed_types']        = 'pdf';
		$config['max_size']             = 3024;
		$config['encrypt_name']			= TRUE;	
				
		$this->load->library('upload', $config);
		
		if (! $this->upload->do_upload('file'))
		{
			$error = array('error' => strip_tags($this->upload->display_errors()));

			$this->output
					->set_status_header(406)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($error));
			
		}
		else
		{
			$upload 		          		= $this->upload->data();	
           	//Nama File
			$dataUpdate['agenda_dokumen'] 	= $upload['file_name'];			
			
			$db_debug 			= $this->db->db_debug; 
		    $this->db->db_debug = FALSE; 
	       
            // cek file old
			$filePengantar		= $this->berkas->getFilePengantar()->row();

            // remove old file
            @unlink($_SERVER['DOCUMENT_ROOT']."/agenda/".$instansi."/".$filePengantar->agenda_dokumen);			
			$result		          			= $this->berkas->updatePengantar($dataUpdate);
				
			
			if(!$result['response'])
			{
				$data['response'] 	= $result['response'];
				$data['pesan']		= 'Sukses Update File Surat Pengantar';
				$error = $this->db->_error_message();
				if(!empty($error))
				{
					$data['pesan']		= $error;   
				}
				
				$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($data)); 
			}
			else
			{
				
				$data['pesan']		= 'Gagal Update File Surat Pengantar';
				$data['response']	= TRUE;
				
				$this->output
					->set_status_header(406)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($data));

			}			
			
			 $this->db->db_debug = $db_debug; //restore setting				
                
		}
		
	}	
	
}
