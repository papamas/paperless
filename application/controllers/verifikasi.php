<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Verifikasi extends MY_Controller {
	
	var $menu_id    = 23;
	var $allow 		= FALSE;
	
	function __construct()
	{
	    parent::__construct();		
	    $this->load->library(array('Auth','Menu','form_validation','Myencrypt','Telegram'));
		$this->load->model('verifikasi/verifikasi_model', 'verifikasi');
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
		
		$data['instansi']  		= $this->verifikasi->getInstansi();
		$data['layanan']  		= $this->verifikasi->getLayanan();
		$data['show']  			= FALSE;
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('verifikasi/index',$data);
	}
	
	
	public function getBerkas()
	{	
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		
		$this->form_validation->set_rules('instansi', 'instansi', 'trim|required');
		$this->form_validation->set_rules('layanan', 'layanan', 'trim|required');
		
		if($this->form_validation->run() == FALSE)
		{
		    $data['menu']      		=  $this->menu->build_menu();
			$data['lname']    		=  $this->auth->getLastName();        
			$data['name']      		=  $this->auth->getName();
			$data['jabatan']   		=  $this->auth->getJabatan();
			$data['member']	   		=  $this->auth->getCreated();
			$data['avatar']	   		=  $this->auth->getAvatar();
			
			$data['instansi']  		= $this->verifikasi->getInstansi();
			$data['layanan']  		= $this->verifikasi->getLayanan();
			$data['show']  			= FALSE;
			
			$this->load->view('verifikasi/index',$data);
		}
        else
		{			
			$instansi               = $this->input->post('instansi');
			$data['menu']     		=  $this->menu->build_menu();
			$data['lname']    		=  $this->auth->getLastName();        
			$data['name']     		=  $this->auth->getName();
			$data['jabatan']  		=  $this->auth->getJabatan();
			$data['member']	  		=  $this->auth->getCreated();
			$data['avatar']	  		=  $this->auth->getAvatar();
			$data['instansi']  		=  $this->verifikasi->getInstansi();
			$data['layanan']  		=  $this->verifikasi->getLayanan();
			$data['penerima']  		=  $this->verifikasi->getPenerima();
			$data['show']  			=  TRUE;
									
			if($instansi != 9)
			{	
				$q	  			        = $this->verifikasi->getUsulDokumen();			
				$data['usul']	  		=  $q;				
				$this->load->view('verifikasi/index',$data);
			}
            else
            {
				$q	  			        = $this->verifikasi->getUsulDokumenTaspen();			
				$data['usul']	  		=  $q;
				$this->load->view('verifikasi/indexTaspen',$data);
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
		
		$nip         = $this->myencrypt->decode($this->input->get('n'));
		$berkas      = $this->verifikasi->getUploadDokumen($nip);
		$layanan     = $this->myencrypt->decode($this->input->get('l'));
		
		$html = '';
		$html .='<table class="table table-bordered table-striped table-condensed">
						<thead>
						    <tr>
							<td colspan="4">LAYANAN USUL '.$layanan.'</td>
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
						case 15:
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
		readfile(base_url().'uploads/'.$instansi.'/'.$file);
	}	
	
	public function kirim()
	{
		$data['response']	= $this->verifikasi->setKirim();
		
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		
	}
	
	public function kirimAll()
	{
		$nip                = $this->input->post('nip');
		$agenda             = $this->input->post('agenda');
		for($i=0;$i < count($nip);$i++)
        {
			$data['agenda']     = $agenda[$i];
			$data['nip']        = $nip[$i];
			$data['response']	= $this->verifikasi->setKirimAll($data);
			$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
			
		}   
        	
	}

	public function setBtl()
	{
		$this->form_validation->set_rules('alasan','Alasan', 'required');
		
		
		if ($this->form_validation->run() == FALSE)
		{
			$data['error']	    = 'Lengkapi Form';
			$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
		}
		else
		{	
			$this->db->trans_begin();
			$data['response']	= $this->verifikasi->setBtl();
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				
				$data['error']	    = 'Something, Wrong';
				$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
			}
			else
			{			   
				$data['agenda_id']	  = $this->input->post('agenda');
				$data['nip']          = $this->input->post('nip');
				
				$this->db->trans_commit();				
				
				$this->send_to_Telegram($data);				
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
            }		
		}	
		
	}
	
	
    public function getVerifikasi()
	{
		$usul 		= $this->verifikasi->getUsulDokumen();
		
		$html = '';
		$html .='<table id="tb-layanan" class="table table-striped table-condensed">
						<thead>
						    <tr>
								<th></th>
								<th>NOUSUL</th>									
								<th>PELAYANAN</th>
								<th>INSTANSI</th>
								<th>NIP</th>
								<th>NAMA</th>
								<th></th>
								
						    </tr>
					</thead>';
		foreach($usul->result() as $value)
		{
			$html .='<tr>
						<td style="width:100px;">
						<a href="#"class="btn bg-orange btn-flat btn-xs" data-tooltip="tooltip"  title="Lihat Berkas" data-toggle="modal" data-target="#lihatModal" data-id="'.'?n='.$this->myencrypt->encode($value->nip).'&l='.$this->myencrypt->encode($value->layanan_nama).'"><i class="fa fa-search"></i></a>
     					<a href="#" class="btn btn-danger btn-flat btn-xs" data-tooltip="tooltip"  title="Set BTL" data-toggle="modal" data-target="#btlModal" data-nip="'.$value->nip.'" data-agenda="'.$value->agenda_id.'" ><i class="fa fa-mail-reply"></i></a>
						<a href="#" class="btn btn-danger btn-flat btn-xs" data-tooltip="tooltip"  title="Kirim Teknis" data-toggle="modal" data-target="#kirimModal" data-nip="'.$value->nip.'" data-agenda="'.$value->agenda_id.'" ><i class="fa fa-mail-forward"></i></a>

						</td>
						<td>'.$value->agenda_nousul.'</td>													
						<td>'.$value->layanan_nama.'</td>
						<td>'.$value->instansi.'</td>		
						<td>'.$value->nip.'</td>
						<td>'.$value->nama.'</td>
                        <td>
						   <input type="checkbox" value="'.$value->nip.'" class="checkbox" name="nip[]" /> 
						   <input type="checkbox" value="'.$value->agenda_id.'" class="checkbox" name="agenda[]"  style="opacity: 0.0; position: absolute; left: -9999px">
						</td>						
					</tr>';	
		}
		$html .='<tr><td colspan="7" class="full-right">
						<label class="form-label">Jumlah Berkas :'.$usul->num_rows().'</label>
						</td>
					</tr>';
		$html .='</table>';		
        echo $html;		
		
	}
	
	/*TASPEN*/
	public function getKelengkapanTaspen()	{
		
		$nip         = $this->myencrypt->decode($this->input->get('n'));
		$berkas      = $this->verifikasi->getUploadDokumenTaspen($nip);
		$layanan     = $this->myencrypt->decode($this->input->get('l'));
		
		$html = '';
		$html .='<table class="table table-bordered table-striped table-condensed">
						<thead>
						    <tr>
							<td colspan="4">LAYANAN USUL '.$layanan.'</td>
							</tr>
							<tr>
								<th>ADA</th>
								<th>NAMA BERKAS</th><th></th></tr></thead>';
		foreach($berkas->result() as $value)
		{
			$html .='<tr>
						<td><i class="fa fa-check" style="color:green;"></i></td>	
						<td>'.$value->keterangan.'</td>
						<td><button class="btn bg-navy btn-flat btn-xs" data-tooltip="tooltip"  title="Lihat File" data-toggle="modal" data-target="#showFile" data-id="?f='.$this->myencrypt->encode($value->file_name).'&t='.$this->myencrypt->encode($value->file_type).'"><i class="fa fa-search"></i></button></td>
						</tr>';	
		}
		$html .='</table>';
		
		echo $html;
	}
	
	public function getInlineTaspen()
	{
		$file      = $this->myencrypt->decode($this->input->get('f'));
		$type      = $this->myencrypt->decode($this->input->get('t'));
				
		ob_clean();			
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:'.$type.'');
		header('Content-Disposition:inline; filename='.$file);                      
		header('Expires:0'); 
		readfile(base_url().'uploads/taspen/'.$file);
	}	
	
	public function kirimTaspen()
	{
		$data['response']	= $this->verifikasi->setKirimTaspen();
		
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		
	}
	
	public function kirimAllTaspen()
	{
		$nip                = $this->input->post('nip');
		$usul_id            = $this->input->post('usul_id');
		$penerima			= $this->input->post('penerima');
		
		for($i=0;$i < count($nip);$i++)
        {
			$data['usul_id']    = $usul_id[$i];
			$data['nip']        = $nip[$i];
			$data['penerima']	= $penerima;
			$data['response']	= $this->verifikasi->setKirimAllTaspen($data);
			$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
			
		}   
        	
	}

	
	public function getVerifikasiTaspen()
	{
		$usul 		= $this->verifikasi->getUsulDokumenTaspen();		
		$html = '';
		$html .='<table id="tb-layanan" class="table table-striped table-condensed">
						<thead>
						    <tr>
								<th></th>
								<th>NOUSUL</th>									
								<th>PELAYANAN</th>
								<th>INSTANSI</th>
								<th>NIP</th>
								<th>NAMA</th>
								<th></th>
								
						    </tr>
					</thead>';
		foreach($usul->result() as $value)
		{
			$html .='<tr>
						<td style="width:65px;">
						<a href="#"class="btn bg-orange btn-flat btn-xs" data-tooltip="tooltip"  title="Lihat Berkas" data-toggle="modal" data-target="#lihatModal" data-id="'.'?n='.$this->myencrypt->encode($value->nip).'&l='.$this->myencrypt->encode($value->layanan_nama).'"><i class="fa fa-search"></i></a>
						<a href="#" class="btn btn-danger btn-flat btn-xs" data-tooltip="tooltip"  title="Kirim Teknis" data-toggle="modal" data-target="#kirimModal" data-nip="'.$value->nip.'" data-usul="'.$value->usul_id.'" ><i class="fa fa-mail-forward"></i></a>

						</td>
						<td>'.$value->nomor_usul.'</td>													
						<td>'.$value->layanan_nama.'</td>
						<td>TASPEN</td>		
						<td>'.$value->nip.'</td>
						<td>'.$value->nama_pns.'</td>
                        <td>
						   <input type="checkbox" value="'.$value->nip.'" class="checkbox" name="nip[]" /> 
						   <input type="checkbox" value="'.$value->usul_id.'" class="checkbox" name="usul_id[]"  style="opacity: 0.0; position: absolute; left: -9999px">
						   <input type="hidden" value="" name="penerima" />
						</td>						
					</tr>';	
		}
		$html .='<tr><td colspan="7" class="full-right">
						<label class="form-label">Jumlah Berkas :'.$usul->num_rows().'</label>
						</td>
					</tr>';
		$html .='</table>';		
        echo $html;				
	}
	
	/* Kirim Notifikasi Telegram ke Instansi*/
	
	function send_to_Telegram($data)
	{
		$agenda_id      = $data['agenda_id'];
		$nip			= $data['nip'];
		
		$row_agenda	    =  $this->verifikasi->getAgenda_byid($agenda_id,$nip)->row();
		$TelegramAkun   =  $this->verifikasi->getTelegramAkun_byInstansi($row_agenda->agenda_ins);
				
		if($TelegramAkun->num_rows() > 0)
		{	
			foreach($TelegramAkun->result() as $value)
			{	
				// send to telegram API
				if(!empty($value->telegram_id))
				{	
					$this->telegram->sendApiAction($value->telegram_id);
					$text  = "<pre>Hello, <strong>".$value->first_name ." ".$value->last_name. "</strong>  Berkas kamu sudah selesai verifikasi dengan hasil berikut ini :";
					$text .= "\n Tanggal :".date('d-m-Y H:i:s');
					$text .= "\n Nomor Usul :".$row_agenda->agenda_nousul;
					$text .= "\n Layanan :".$row_agenda->layanan_nama;
					$text .= "\n NIP :".$row_agenda->nip;
					$text .= "\n Nama PNS :".$row_agenda->PNS_GLRDPN.' '.$row_agenda->PNS_PNSNAM.' '.$row_agenda->PNS_GLRBLK;
					$text .= "\n Tahapan :".$row_agenda->tahapan_nama;
					(!empty($row_agenda->status_level_satu) ? $text .= "\n Status  Level 1 :".$row_agenda->status_level_satu : '');
					(!empty($row_agenda->status_level_dua)  ? $text .= "\n Status  Level 2 :".$row_agenda->status_level_dua : '');
					(!empty($row_agenda->status_level_tiga) ? $text .= "\n Status  Level 3 :".$row_agenda->status_level_tiga : '');
					$text .= "\n Status Berkas :".$row_agenda->nomi_status;
					$text .= "\n Keterangan :".$row_agenda->nomi_alasan;
					$text .= "\n Instansi :".$row_agenda->instansi.'</pre>';
					$this->telegram->sendApiMsg($value->telegram_id, $text , false, 'HTML');
										
				}	
			}
		}
	}	
}
