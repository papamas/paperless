<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Entry extends MY_Controller {
	
	var $menu_id    = 17;
	var $allow 		= FALSE;
	
	function __construct()
	{
	    parent::__construct();		
	    $this->load->library(array('Auth','Menu','form_validation','Myencrypt'));
		$this->load->model('entry/entry_model', 'entry');
		$this->allow = $this->auth->isAuthMenu($this->menu_id);
	} 
	
	public function index()
	{
			
		$data['menu']     		=  $this->menu->build_menu();
		$data['lname']    		=  $this->auth->getLastName();        
		$data['name']     		=  $this->auth->getName();
        $data['jabatan']  		=  $this->auth->getJabatan();
		$data['member']	  		=  $this->auth->getCreated();
		$data['avatar']	  		=  $this->auth->getAvatar();
		
		$data['layanan']  		= $this->entry->getPelayanan();
		$data['instansi']  		= $this->entry->getInstansi();
		$data['ijazah']         = $this->entry->getIjazah();
		$data['spesimen']    	= $this->entry->getSpesimen();
		
		$data['show']  			= FALSE;	
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('entry/index',$data);
	}
	
	public function sapk()
	{			
		$data['menu']     =  $this->menu->build_menu();	
		$data['lname']    =  $this->auth->getLastName();        
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();	
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('entry/sapk',$data);
	}
	
	
	public function getEntry()
	{
		
		$this->form_validation->set_rules('instansi', 'instansi', 'trim');
		$this->form_validation->set_rules('layanan', 'layanan', 'trim');
		$this->form_validation->set_rules('reportrange', 'Periode', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_rules('perintah', 'Perintah', 'required');
		$this->form_validation->set_rules('nip', 'NIP', 'trim');
		$this->form_validation->set_rules('spesimen', 'spesimen', 'trim');
		
		$search           = $this->input->post();
		$perintah         = $this->input->post('perintah');		
				
		if($this->form_validation->run() == FALSE)
		{
			$data['menu']     		=  $this->menu->build_menu();	
			$data['lname']    		=  $this->auth->getLastName();        
			$data['name']     		=  $this->auth->getName();
			$data['jabatan']  		=  $this->auth->getJabatan();
			$data['member']	  		=  $this->auth->getCreated();
			$data['avatar']	  		=  $this->auth->getAvatar();
			$data['layanan']  		= $this->entry->getPelayanan();
			$data['instansi']  		= $this->entry->getInstansi();
			$data['ijazah']         = $this->entry->getIjazah();
			$data['show']  			= FALSE;
			$data['spesimen']    	= $this->entry->getSpesimen();
			
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('entry/index',$data);
		
		}
		else
		{	
			$q	  			  = $this->entry->getUsulDokumen($search);
			
			if($perintah == 1)
			{
				
				$data['menu']     	=  $this->menu->build_menu();		
				$data['lname']    	=  $this->auth->getLastName();        
				$data['name']     	=  $this->auth->getName();
				$data['jabatan']  	=  $this->auth->getJabatan();
				$data['member']	  	=  $this->auth->getCreated();
				$data['avatar']	  	=  $this->auth->getAvatar();
				
				$data['layanan']  	= $this->entry->getPelayanan();
				$data['instansi']   = $this->entry->getInstansi();
				$data['ijazah']     = $this->entry->getIjazah();
				$data['show']  		= TRUE;
				$data['spesimen']   = $this->entry->getSpesimen();
				$data['usul']	  	=  $q;
				if(!$this->allow)
				{
					$this->load->view('403/index',$data);
					return;
				}
				$this->load->view('entry/index',$data);
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
		$filename         = "ENTRY BERKAS ".$now.".xls";
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/vnd.ms-excel');
		header('Content-Disposition:attachment; filename='.$filename);                      
		header('Expires:0'); 
		
		$html  = 'ENTRY BERKAS STATUS ACC';
		if($q->num_rows() > 0){
			$row = $q->row();
		$html .= '<table>';		
		$html .= '<tr></tr>';		
		$html .= '</table><p></p>';
		}
		$html .= '<style> .str{mso-number-format:\@;}</style>';
		$html .= '<table border="1">';					
		$html .='<tr>
					<th>NO</th>
					<th>AGENDA</th>
					<th>TANGGAL</th>
					<th>NIP</th>					
					<th>NAMA</th>	
					<th>INSTANSI</th>
					<th>PELAYANAN</th>
					<th>STATUS</th>
					<th>ALASAN</th>
					<th>TANGGAL</th>
					<th>PERSETUJUAN</th>
					<th>TANGGAL PERSETUJUAN</th>
					'; 
		$html 	.= '</tr>';
		if($q->num_rows() > 0){
			$i = 1;		        
			foreach ($q->result() as $r) {
				$html .= "<tr><td>$i</td>";		
				$html .= "<td>{$r->agenda_nousul}</td>";	
				$html .= "<td>{$r->agenda_timestamp}</td>";
				$html .= "<td class=str>{$r->nip}</td>";				
                $html .= "<td>{$r->nama}</td>";
                $html .= "<td>{$r->instansi}</td>";					
				$html .= "<td>{$r->layanan_nama}</td>";	
				$html .= "<td>{$r->nomi_status}".'<br/>'."{$r->verif_name}</td>";
				$html .= "<td>{$r->nomi_alasan}</td>";
				$html .= "<td>{$r->verify_date}</td>";
				$html .= "<td>{$r->nomi_persetujuan}</td>";
				$html .= "<td>{$r->tgl}</td>";
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
	
	public function simpan()
	{
		$data['agenda'] 			= $this->myencrypt->decode($this->input->post('agenda'));
		$data['nip']       			= $this->myencrypt->decode($this->input->post('nip'));
		$data['persetujuan']		= $this->input->post('persetujuan');
		$data['tanggal']			= $this->input->post('tanggal');
		
		$this->form_validation->set_rules('persetujuan', 'Persetujuan', 'required');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['pesan']	= "Lengkapi Form";
			$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
			return FALSE;	
		}
		else
		{	
			$db_debug 			= $this->db->db_debug; 
			$this->db->db_debug = FALSE; 
			if (!$this->entry->simpanPersetujuan($data))
			{
				$error 				= $this->db->_error_message(); 
				$data['pesan']		= $error;
				if(!empty($error))
				{
					$this->output
						->set_status_header(406)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
					return FALSE;	
				}				
			}
			else
			{
				$data['pesan']	= "Sukses Entry Persetujuan";
				$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
			}

			$this->db->db_debug = $db_debug; //restore setting		
		}
		
    }

	public function simpanPG()
	{
		$data['agenda'] 			= $this->myencrypt->decode($this->input->post('agenda'));
		$data['nip']       			= $this->myencrypt->decode($this->input->post('nip'));
		
		$data['persetujuan']		= $this->input->post('persetujuan');
		$data['tanggal']			= $this->input->post('tanggal');
		$data['kode_ijazah']		= $this->input->post('kode_ijazah');
		$data['nomor_ijazah']		= $this->input->post('nomor_ijazah');		
		$data['tgl_ijazah']			= $this->input->post('tgl_ijazah');
		$data['kampus']				= $this->input->post('kampus');
		$data['prodi']				= $this->input->post('prodi');
		$data['lokasi_kampus']		= $this->input->post('lokasi_kampus');
		$data['nama_gelar']			= $this->input->post('nama_gelar');
		
		$this->form_validation->set_rules('persetujuan', 'Persetujuan', 'required');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
		$this->form_validation->set_rules('kode_ijazah', 'Kode Ijazah', 'required');
		$this->form_validation->set_rules('nomor_ijazah', 'Nomor Ijazah', 'required');
		$this->form_validation->set_rules('tgl_ijazah', 'Tgl Ijazah', 'required');
		$this->form_validation->set_rules('kampus', 'Kampus', 'required');
		$this->form_validation->set_rules('prodi', 'Program Studi', 'required');
		$this->form_validation->set_rules('lokasi_kampus', 'Lokasi Kampus', 'required');
		$this->form_validation->set_rules('nama_gelar', 'Nama Gelar', 'required');
		
		
		if($this->form_validation->run() == FALSE)
		{
			$data['pesan']	= "Lengkapi Form";
			$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
			return FALSE;	
		}
		else
		{	
			$db_debug 			= $this->db->db_debug; 
			$this->db->db_debug = FALSE; 
			if (!$this->entry->simpanPersetujuanPG($data))
			{
				$error 				= $this->db->_error_message(); 
				$data['pesan']		= $error;
				if(!empty($error))
				{
					$this->output
						->set_status_header(406)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
					return FALSE;	
				}				
			}
			else
			{
				$data['pesan']	= "Sukses Entry Persetujuan";
				$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
			}

			$this->db->db_debug = $db_debug; //restore setting		
		}
		
    }
	
	public function simpanTahapan()
	{
		$data['agenda'] 			= $this->myencrypt->decode($this->input->get('agenda'));
		$data['nip']       			= $this->myencrypt->decode($this->input->get('nip'));
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		if (!$this->entry->simpanTahapan($data))
		{
			$error 				= $this->db->_error_message(); 
			$data['pesan']		= $error;
			if(!empty($error))
			{
				$this->output
					->set_status_header(406)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($data));
				return FALSE;	
			}				
		}
		else
		{
			$entry			= $this->entry->getEntryOne($data);
			
			$data['pesan']	= "update tahapan proses cetak";
			$data['entry']  = $entry->result();
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($data));
		}

		$this->db->db_debug = $db_debug; //restore setting
	
	}	
	
	 public function getEntryAll()
	{
		$search           = $this->input->post();
		$entry			  = $this->entry->getUsulDokumen($search);
		
		$html = '';
		$html .='<table id="tb-entry" class="table table-striped table-condensed">
						<thead>
							<tr>
								<th style="width:100px;"></th>
								<th>NOMOR</th>
								<th>INSTANSI</th>
								<th>NIP</th>
								<th>NAMA</th>								
								<th>PELAYANAN</th>                               						
								<th style="width:150px;">ACC DATE</th>
								<th style="width:55px;">FILE</th>
								<th>PERSETUJUAN</th>							
							</tr>
						</thead>  ';
		foreach($entry->result() as $value)
		{
			$layanan = $value->layanan_id;
			$html .='<tr>
						<td>';
						$layanan = $value->layanan_id;
						if($layanan === "9" || $layanan === "10" || $layanan === "11")
						{  
							$html .='<a href="#dPhoto" class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Unduh Photo" id="?id='.$this->myencrypt->encode($value->id_instansi).'&f='.$this->myencrypt->encode($value->orig_name).'&n='.$this->myencrypt->encode($value->nip).'"><i class="fa fa-search"></i></a>';
						}
						if($layanan === "14")
						{  
							$html .= '<a class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Input Persetujuan" data-toggle="modal" data-target="#skModalPG" data-agenda="'.$this->myencrypt->encode($value->agenda_id).'" data-nip="'.$this->myencrypt->encode($value->nip).'"><i class="fa fa-edit"></i></a>';
							$html .= '&nbsp;<a href="#cetakSurat" class="btn btn-danger btn-xs cetak" data-tooltip="tooltip"  title="Cetak Surat Peningkatan Pendidikan" id="?a='.$this->myencrypt->encode($value->agenda_id).'&n='.$this->myencrypt->encode($value->nip).'"><i class="fa fa-print"></i></a>';
						}
						else
						{
							$html .= '&nbsp;<a class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Input Persetujuan" data-toggle="modal" data-target="#skModal" data-agenda="'.$this->myencrypt->encode($value->agenda_id).'" data-nip="'.$this->myencrypt->encode($value->nip).'"><i class="fa fa-edit"></i></a>';
					
						}	
						
						$html .='&nbsp;<button class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="upload persetujuan" data-toggle="modal" data-target="#uploadModal" data-layanan="'.$value->layanan_id.'" data-agenda="'.$value->agenda_id.'" data-instansi="'.$value->agenda_ins.'" data-nip="'.$value->nip.'"><i class="fa fa-upload"></i></button>';
						
			$html .='</td>
						<td>'.$value->agenda_nousul.'</td>
						<td>'.$value->instansi.'</td>
						<td>'.$value->nip.'</td>
						<td>'.$value->nama.'</td>																					
						<td>'.$value->layanan_nama.'</td>
						<td><span class="badge bg-green">'.$value->verify_date.' Oleh :<b>'.$value->verif_name.'</b></span></td>';
			$html .='<td>';
						if(!empty($value->upload_persetujuan))
						{
							switch($value->layanan_id){
								case 1:
									$name  = 'NPKP_';				
								break;
								case 2:
									$name  = 'NPKP_';				
								break;
								case 3:
									$name  = 'NPKP_';			
								break;			
								case 4:
									$name  = 'PERTEK_PENSIUN_';				
								break;
								case 6:
									$name  = 'PERTEK_PENSIUN_';				
								break;
								case 7:
									$name  = 'PERTEK_PENSIUN_';				
								break;
								case 8:
									$name  = 'PERTEK_PENSIUN_';				
								break;
								case 9:
									$name  = 'KARIS_';				
								break;
								case 10:
									$name  = 'KARSU_';				
								break;
								case 11:
									$name  = 'KARPEG_';				
								break;
								case 12:
									$name  = 'NPKP_';			
								break;
								case 13:
									$name  = 'SK_MUTASI';			
								break;
								case 14:
									$name  = 'SK_PG_';			
								break;
							}	
							
							$file = $name.$value->nip.'.pdf';
							
							$html .= '<span data-toggle="tooltip" data-original-title="Ada File Persetujuan">
							<i class="fa fa-file-pdf-o" data-toggle="modal" data-target="#showFile" data-id="?id='.$this->myencrypt->encode($value->agenda_ins).'&f='.$this->myencrypt->encode($file).'" style="color:red;"></i></span>';
						}
						else
						{
							$html .= '<span data-toggle="tooltip" data-original-title="Tidak Ada File Persetujuan">
							<i class="fa fa-file-o" style="color:red;"></i></span>';
						}
						
						
						if(!empty($value->upload_sk))
						{
							switch($value->layanan_id){
								case 1:
									$name  = 'SK_KP_';				
								break;
								case 2:
									$name  = 'SK_KP_';				
								break;
								case 3:
									$name  = 'SK_KP_';			
								break;			
								case 4:
									$name  = 'SK_PENSIUN_';				
								break;
								case 6:
									$name  = 'SK_PENSIUN_';				
								break;
								case 7:
									$name  = 'SK_PENSIUN_';				
								break;
								case 8:
									$name  = 'SK_PENSIUN_';				
								break;
								case 9:
									$name  = 'KARIS_';				
								break;
								case 10:
									$name  = 'KARSU_';				
								break;
								case 11:
									$name  = 'KARPEG_';				
								break;
								case 12:
									$name  = 'SK_KP_';			
								break;
								case 13:
									$name  = 'SK_MUTASI';			
								break;
								case 14:
									$name  = 'SK_PG_';			
								break;
							}	
							
							$file = $name.$value->nip.'.pdf';
							
							$html.= '<span data-toggle="tooltip" data-original-title="Ada File Surat Keputusan">
							<i class="fa fa-file-pdf-o" data-toggle="modal" data-target="#showFile" data-id="?id='.$this->myencrypt->encode($value->agenda_ins).'&f='.$this->myencrypt->encode($file).'" style="color:red;"></i></span>';
						}
						else
						{
							$html .= '<span data-toggle="tooltip" data-original-title="Tidak Ada File Surat Keputusan">
							<i class="fa fa-file-o" style="color:red;"></i></span>';
						}							
			$html .= '</td>';						
			$html .='<td>'.$value->nomi_persetujuan.'<br/>'.$value->tgl.'</td></tr>';	
		}
		$html .='</table>';		
        echo $html;		
		
	}
	
	public function cetakSurat()
	{
		$data['agenda'] 			= $this->myencrypt->decode($this->input->get('a'));
		$data['nip']       			= $this->myencrypt->decode($this->input->get('n'));
		
		
		$row						= $this->entry->getEntryOne($data)->row();
			
		$this->load->library('PDF', array());
		
		
		$this->pdf->setPrintHeader(true);
		$this->pdf->setPrintFooter(true);	
		
		$this->pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);
		$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		$this->pdf->SetFont('freeSerif', '', 12);
		
		$this->pdf->AddPage('P', 'A4');
		$this->pdf->Text(10, 50, 'Nomor');
		$this->pdf->Text(40, 50, ':');
		$this->pdf->Text(45, 50, $row->nomi_persetujuan);
		
		$this->pdf->Text(150, 50, 'Manado, '.$row->tanggal_acc);
		
		
		$this->pdf->Text(10, 54, 'Lampiran');
		$this->pdf->Text(40, 54, ':');
		$this->pdf->Text(45, 54, '-');
		
		$this->pdf->Text(10, 58, 'Perihal');
		$this->pdf->Text(40, 58, ':');
		$this->pdf->Text(45, 58, 'Pencantuman Gelar Akademik');
		
		$this->pdf->Text(45, 62, $row->nama.', '.$row->gelar);
		$this->pdf->Text(45, 66, 'NIP. '.$row->nip);
		
		$this->pdf->Text(20, 75, 'Kepada');
		$this->pdf->Text(10, 79, 'Yth.');
		$this->pdf->Text(20, 79, $row->nama_jabatan.' '.$row->nama_daerah);
		$this->pdf->Text(20, 83, 'di');	
		$this->pdf->Text(20, 87, $row->lokasi_daerah);
		
		
		$text='Berkenaan dengan surat Saudara Nomor '.$row->agenda_nousul.' tanggal '.$row->tanggal_agenda.' perihal sebagaimana pada pokok surat, diberitahukan dengan hormat bahwa  '.$row->nama_ijazah.' Program Studi '.$row->prodi.' '.$row->kampus.' yang dikeluarkan  di  '.$row->lokasi_kampus.' atas nama : ';
		
		$this->pdf->Text(20, 100, '1.');		
		$this->pdf->writeHTMLCell(160,125,25,100,$text,0,0,false,true,'J',true);
		
		$this->pdf->Text(25, 125, 'Nama');
		$this->pdf->Text(70, 125, ':');
		$this->pdf->Text(75, 125, $row->nama.', '.$row->gelar);
		
		$this->pdf->Text(25, 130, 'NIP');
		$this->pdf->Text(70, 130, ':');
		$this->pdf->Text(75, 130, $row->nip);
		
		$this->pdf->Text(25, 135, 'Pangkat/Gol.Ruang/TMT');
		$this->pdf->Text(70, 135, ':');
		$this->pdf->Text(75, 135, $row->pangkat.' / '. $row->nama_golongan.' / '. $row->tmt_golongan);
		
		$this->pdf->Text(25, 140, 'Nomor/Tgl.Ijazah');
		$this->pdf->Text(70, 140, ':');
		$this->pdf->Text(75, 140, $row->nomor_ijazah.' / '. $row->tgl_ijazah);
		
		
		
		$text1='Memenuhi syarat dan telah kami cantumkan dalam data induk Pegawai Negeri  Sipil,kepada  yang  bersangkutan berhak mencantumkan gelar '.$row->nama_gelar.' pada Mutasi Kepegawaiannya';
		$this->pdf->writeHTMLCell(160,125,25,150,$text1,0,0,false,true,'J',true);
		
		
		$this->pdf->Text(20, 170, '2.');		
		$this->pdf->writeHTMLCell(160,125,25,170,'Demikian, agar digunakan sebagaimana mestinya',0,0,false,true,'J',true);
		
		// set style for barcode
		$style = array(
			'border' => false,
			'padding' => 0,
			'fgcolor' => array(0, 0, 0),
			'bgcolor' => false, //array(255,255,255)
			'module_width' => 1, // width of a single module in points
			'module_height' => 1 // height of a single module in points
		);
		
		$code  = 'Pencantuman Gelar PNS dengan NIP '.$row->nip.' atas nama '.$row->nama;
		$code .= ' telah disetujui dengan nomor surat '.$row->nomi_persetujuan.' pada tanggal '.$row->tanggal_acc;
		$code .= ' dengan gelar '.$row->nama_gelar.' Program Studi '.$row->prodi.' dan Nomor Ijazah '.$row->nomor_ijazah;
		
		
		$this->pdf->write2DBarcode($code, 'QRCODE,Q', 30, 185, 35, 35, $style, 'N');
       
		
		$text2='an.Kepala Kantor Regional XI Badan Kepegawaian Negara '.$row->jabatan;
		$this->pdf->writeHTMLCell(60,125,130,180,$text2,0,0,false,true,'L',true);
		
		$this->pdf->Text(130, 215, $row->nama_spesimen.', '.$row->gelar_spesimen);
		$this->pdf->Text(130, 220, 'NIP.'.$row->nip_spesimen);
		
		$this->pdf->Text(20, 225, 'Tembusan, Yth :');
		$this->pdf->Text(20, 230, '1. Kepala Kantor Regional XI sebagai laporan;');
		$this->pdf->Text(20, 235, '2. Kepala Bidang Informasi Kepegawaian  Kanreg  XI BKN;');
		$this->pdf->Text(20, 240, '3. Saudara '.$row->nama.' pada Pemerintah Daerah '.$row->nama_daerah.'.');
		
		$this->pdf->Output('cetakSurat.pdf', 'D');
		
	}	
	
	public function upload()
	{
		
		$instansi						= $this->input->post('agenda_ins');
		$nip							= $this->input->post('agenda_nip');
		$agenda							= $this->input->post('agenda_id');
		$layanan						= $this->input->post('agenda_layanan');
		
		switch($layanan){
			case 1:
				$name  = 'NPKP_';				
			break;
			case 2:
				$name  = 'NPKP_';				
			break;
			case 3:
				$name  = 'NPKP_';			
			break;			
			case 4:
				$name  = 'PERTEK_PENSIUN_';				
			break;
			case 6:
				$name  = 'PERTEK_PENSIUN_';				
			break;
			case 7:
				$name  = 'PERTEK_PENSIUN_';				
			break;
			case 8:
				$name  = 'PERTEK_PENSIUN_';				
			break;
			case 9:
				$name  = 'KARIS_';				
			break;
			case 10:
				$name  = 'KARSU_';				
			break;
			case 11:
				$name  = 'KARPEG_';				
			break;
			case 12:
				$name  = 'NPKP_';			
			break;
			case 13:
				$name  = 'SK_MUTASI';			
			break;
			case 14:
				$name  = 'SK_PG_';			
			break;
		}	
									
		
		
		$target_dir						='./uploads/'.$instansi;		
		$config['upload_path']          = $target_dir;
		$config['allowed_types']        = 'pdf';
		$config['max_size']             = 3024;
		$config['encrypt_name']			= FALSE;	
		$config['overwrite']			= TRUE;	
		$config['detect_mime']			= TRUE;
		$config['file_name']            = $name.$nip;
		
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
				//$data['agenda']		  = $agenda;
				$result		          = $this->entry->insertUpload($data);
				
			
				if($result['response'])
				{
				    $this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($result)); 
                }
				else
				{
					$result['updated']  = $this->entry->updateFile($result);
					$result['error'] 	= 'File Persetujuan Teknis sudah ada, overwrite file';
					$this->output
						->set_status_header(406)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($result));

                }			
				
		}
		
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
}
