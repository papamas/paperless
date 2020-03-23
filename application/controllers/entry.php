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
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->form_validation->set_rules('instansi', 'instansi', 'trim');
		$this->form_validation->set_rules('layanan', 'layanan', 'trim');
		$this->form_validation->set_rules('reportrange', 'Periode', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_rules('perintah', 'Perintah', 'required');
		$this->form_validation->set_rules('nip', 'NIP', 'trim');
		$this->form_validation->set_rules('spesimen', 'spesimen', 'trim');
		$this->form_validation->set_rules('searchby', 'searchby', 'trim');
		$this->form_validation->set_rules('search', 'search', 'trim');
		
		$search           = $this->input->post();
		$perintah         = $this->input->post('perintah');	
        $instansi         = $this->input->post('instansi');		
				
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
			$data['kantor']    	= $this->entry->getKantorTaspen();
			
			$this->load->view('entry/index',$data);
		
		}
		else
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
			$data['kantor']    	= $this->entry->getKantorTaspen();
			
			if($perintah == 1)
			{
				if($instansi != 9)
				{	
					$q	  			    = $this->entry->getUsulDokumen($search);
					$data['usul']	  	=  $q;
					$this->load->view('entry/index',$data);
				}
				else
				{
					$q	  			    = $this->entry->getUsulDokumenTaspen($search);
					$data['usul']	  	=  $q;
					$this->load->view('entry/indexTaspen',$data);
				}
			}
			else
			{	
				if($instansi != 9)
				{
					$q	  			    = $this->entry->getUsulDokumen($search);
					$this->_getExcel($q);
				}
				else
				{
					$q	  			    = $this->entry->getUsulDokumenTaspen($search);
					$this->_getExcelTaspen($q);
				}	
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
						<td>'.$value->verify_date.' Oleh :<b>'.$value->verif_name.'</b></span></td>';
			$html .='<td>';
						if(!empty($value->upload_persetujuan))
						{	
							$file = $value->file_persetujuan_raw_name.'.pdf';
							
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
							
							$file = $value->file_sk_raw_name.'.pdf';
							
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
		$golongan						= $this->input->post('agenda_golongan');
		$gol                            = intval($golongan) + 1;
		
		switch($layanan){
			case 1:
				$name  = 'NPKP_'.$nip.'_'.$gol;				
			break;
			case 2:
				$name  = 'NPKP_'.$nip.'_'.$gol;					
			break;
			case 3:
				$name  = 'NPKP_'.$nip.'_'.$gol;			
			break;			
			case 4:
				$name  = 'PERTEK_PENSIUN_'.$nip;				
			break;
			case 6:
				$name  = 'PERTEK_PENSIUN_'.$nip;			
			break;
			case 7:
				$name  = 'PERTEK_PENSIUN_'.$nip;				
			break;
			case 8:
				$name  = 'PERTEK_PENSIUN_'.$nip;			
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
				$name  = 'NPKP_'.$nip;			
			break;
			case 13:
				$name  = 'SK_MUTASI'.$nip;			
			break;
			case 14:
				$name  = 'SK_PG_'.$nip;;			
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
					$this->entry->updateNominatif($data);
					
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
	
	/*TASPEN*/
	public function simpanTahapanTaspen()
	{
		$data['usul_id'] 			= $this->myencrypt->decode($this->input->get('usul'));
		$data['nip']       			= $this->myencrypt->decode($this->input->get('nip'));
		
		$db_debug 			= $this->db->db_debug; 
		$this->db->db_debug = FALSE; 
		if (!$this->entry->simpanTahapanTaspen($data))
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
			$entry			= $this->entry->getEntryOneTaspen($data);
			
			$data['pesan']	= "update tahapan proses cetak";
			$data['entry']  = $entry->result();
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($data));
		}

		$this->db->db_debug = $db_debug; //restore setting
	
	}

	public function simpanTaspen()
	{
		$data['usul_id'] 			= $this->myencrypt->decode($this->input->post('usul'));
		$data['nip']       			= $this->myencrypt->decode($this->input->post('nip'));
		$data['persetujuan']		= $this->input->post('persetujuan');
		$data['tanggal']			= $this->input->post('tanggal');
		$data['pensiun_pokok']			= $this->input->post('pensiun_pokok');
		$data['pensiun_tmt']			= $this->input->post('pensiun_tmt');
		$data['kantor_taspen']			= $this->input->post('kantor');
		
		$this->form_validation->set_rules('persetujuan', 'Persetujuan', 'required');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
		$this->form_validation->set_rules('pensiun_pokok', 'Pensiun Pokok', 'required');
		$this->form_validation->set_rules('pensiun_tmt', 'Pensiun TMT', 'required');
		$this->form_validation->set_rules('kantor', 'Kantor Taspen', 'required');
		
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
			if (!$this->entry->simpanPersetujuanTaspen($data))
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
	
	public function getEntryAllTaspen()
	{
		$search           = $this->input->post();
		$entry			  = $this->entry->getUsulDokumenTaspen($search);
		
		$html = '';
		$html .='<table id="tb-entry" class="table table-striped table-condensed">
						<thead>
							<tr>
								<th style="width:125px;"></th>
								<th>NOMOR</th>								
								<th>NIP</th>
								<th>NAMA PNS</th>	
								<th>NAMA</th>
								<th>PELAYANAN</th>                               						
								<th style="width:125px;">ACC DATE</th>
								<th>FILE</th>
								<th>PERSETUJUAN</th>							
							</tr>
						</thead>  ';
		foreach($entry->result() as $value)
		{
			$layanan = $value->layanan_id;
			$html .='<tr>
						<td>';
						$html .='<a href="#dPhoto" class="btn btn-info btn-xs" data-tooltip="tooltip"  title="Unduh Photo" id="?id='.$this->myencrypt->encode($value->usul_id).'&n='.$this->myencrypt->encode($value->nip).'"><i class="fa fa-download"></i></a>';
						$html .= '&nbsp;<a href="#cetakSurat" class="btn btn-danger btn-xs cetak" data-tooltip="tooltip"  title="Cetak Surat Persetujuan" id="?a='.$this->myencrypt->encode($value->usul_id).'&n='.$this->myencrypt->encode($value->nip).'&l='.$this->myencrypt->encode($value->layanan_id).'"><i class="fa fa-print"></i></a>';
						$html .= '&nbsp;<a class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Input Persetujuan" data-toggle="modal" data-target="#skModal" data-usul="'.$this->myencrypt->encode($value->usul_id).'" data-nip="'.$this->myencrypt->encode($value->nip).'"><i class="fa fa-edit"></i></a>';
						$html .='&nbsp;<button class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="upload persetujuan" data-toggle="modal" data-target="#uploadModal" data-layanan="'.$value->layanan_id.'" data-agenda="'.$value->usul_id.'" data-nip="'.$value->nip.'"><i class="fa fa-upload"></i></button>';
						
			$html .='</td>
						<td>'.$value->nomor_usul.'</td>
						<td>'.$value->nip.'</td>
						<td>'.$value->nama_pns.'</td>	
						<td>'.$value->nama_janda_duda.'</td>	
						<td>'.$value->layanan_nama.'</td>
						<td>'.$value->usul_verif_date.' Oleh :<b>'.$value->usul_verif_name.'</b></span></td>';
			$html .='<td>';
						if(!empty($value->upload_persetujuan))
						{	
							$file = $value->file_persetujuan;							
							$html .= '<span data-toggle="tooltip" data-original-title="Ada File Persetujuan">
							<i class="fa fa-file-pdf-o" data-toggle="modal" data-target="#showFile" data-id="?id='.$this->myencrypt->encode($value->usul_id).'&f='.$this->myencrypt->encode($file).'" style="color:red;"></i></span>';
						}
						else
						{
							$html .= '<span data-toggle="tooltip" data-original-title="Tidak Ada File Persetujuan">
							<i class="fa fa-file-o" style="color:red;"></i></span>';
						}					
											
			$html .= '</td>';						
			$html .='<td>'.$value->usul_no_persetujuan.'<br/>'.$value->usul_tgl_persetujuan.'</td></tr>';	
		}
		$html .='</table>';		
        echo $html;		
		
	}
	
	public function uploadTaspen()
	{
		$nip							= $this->input->post('usul_nip');
		$usul							= $this->input->post('usul_id');
		$layanan						= $this->input->post('usul_layanan');
		
		switch($layanan){
			case 15:
				$name  = 'SK_PK_'.$nip;			
			break;
			case 16:
				$name  = 'SK_JD_'.$nip;;			
			break;
			case 17:
				$name  = 'SK_YP_'.$nip;;			
			break;
		}		
		$target_dir						='./uploads/taspen';		
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
				$data['nip']		  = $nip;
				$result		          = $this->entry->insertUploadTaspen($data);
				
			
				if($result['response'])
				{
				    $this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($result)); 
                }
				else
				{
					$this->entry->updateNominatifTaspen($data);
					
					$result['updated']  = $this->entry->updateFileTaspen($result);
					$result['error'] 	= 'File Persetujuan Teknis Taspen sudah ada, overwrite file';
					$this->output
						->set_status_header(406)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($result));

                }			
				
		}
		
	}	
	
	public function getInlineTaspen()
	{
		$instansi  = $this->myencrypt->decode($this->input->get('id'));
		$file      = $this->myencrypt->decode($this->input->get('f'));
						
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/pdf');
		header('Content-Disposition:inline; filename='.$file);                      
		header('Expires:0'); 
		readfile(base_url().'uploads/taspen/'.$file);
	}	
	
	public function cetakSuratTaspen()
	{
		$data['usul_id'] 			= $this->myencrypt->decode($this->input->get('a'));
		$data['nip']       			= $this->myencrypt->decode($this->input->get('n'));
		$layanan             		= $this->myencrypt->decode($this->input->get('l'));
		
		switch($layanan){
			case 15:
				$name  = '';
				$lname  = '';				
			break;
			case 16:
				$name   = 'Janda/Duda';
				$lname  = 'JD.ALM';
			break;
			case 17:
				$name  = 'Yatim';	
				$lname  = 'YT.ALM';
			break;
		}		
		
		$row						= $this->entry->getEntryOneTaspen($data)->row();
				
				
		$this->load->library('PDF', array());	
		$this->pdf->setPrintHeader(false);
		$this->pdf->setPrintFooter(false);		
		
		$this->pdf->SetAutoPageBreak(TRUE, 0);
		
		$this->pdf->SetFont('freeSerif', '', 8);
		$this->pdf->AddPage('L', 'FOLIO', false, false);
		
		
		$this->pdf->Text(65, 25, 'KEPUTUSAN KEPALA BADAN KEPEGAWAIAN NEGARA');
		$this->pdf->Text(80, 28, 'NOMOR: '.$row->usul_no_persetujuan);
		$this->pdf->Text(70, 33, 'KEPALA BADAN KEPEGAWAIAN NEGARA');
		
		
		$this->pdf->Text(10, 40, 'Menimbang');
		$this->pdf->Text(35, 40, ':');
		$this->pdf->Text(38, 40, '1. ');
		$text1='bahwa Pegawai Negeri Sipil/pensiunan Pegawai Negeri Sipil *) atas nama Saudara  '.$row->nama_pns.' NIP/NP '.$row->nip.'  telah meninggal dunia pada tanggal '.$row->meninggal;
		$this->pdf->writeHTMLCell(125,'',41,40,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(38, 48, '2. ');
		$text1='bahwa yang namanya tercantum dalam keputusan ini, memenuhi syarat untuk diberikan pensiun '.$name;
		$this->pdf->writeHTMLCell(125,'',41,48,$text1,0,0,false,false,'J',true);
		
		
		$this->pdf->Text(10, 55, 'Mengingat');
		$this->pdf->Text(35, 55, ':');
		$this->pdf->Text(38, 55, '1. Undang- Undang Nomor 11 Tahun 1969;');
		$this->pdf->Text(38, 59, '2. Undang-Undang Nomor 8 Tahun 1974 jo, Undang-Undang Nomor 43 Tahun 1999;');
		$this->pdf->Text(38, 63, '3. Peraturan Pemerintah Nomor 7 tahun 1977 jo. Peraturan Pemerintah Nomor 30 Tahun 2015;');
		$this->pdf->Text(38, 67, '4. Peraturan Pemerintah Nomor 32 Tahun 1979 jo. Peraturan Pemerintah Nomor 19 Tahun 2013;');
		$this->pdf->Text(38, 71, '5. Peraturan Pemerintah Nomor 99 Tahun 2000 jo. Peraturan Pemerintah Nomor 12 Tahun 2002;');
		$this->pdf->Text(38, 75, '6. Peraturan Pemerintah Nomor 9 Tahun 2003 jo Peraturan Pemerintah Nomor 63 Tahun 2009;');
		$this->pdf->Text(38, 79, '7. Peraturan Pemerintah Nomor 18 Tahun 2019;');
		$this->pdf->Text(38, 83, '8. Keputusan Kepala BKN Nomor 14 Tahun 2003; jo. Peraturan Kepala BKN Nomor 32 Tahun 2015;');
		$this->pdf->Text(38, 87, '9. Surat Kepala BKN Nomor WK-26-30/V33-5/99 Tanggal 30 Januari 2012;');
		
		
		$this->pdf->Text(90, 95, 'MEMUTUSKAN');
		$this->pdf->Text(10, 100, 'Menetapkan');
		$this->pdf->Text(35, 100, ':');
		$this->pdf->Text(10, 103, 'PERTAMA');
		$this->pdf->Text(35, 103, ':');
		
		$text1='Kepada yang namanya tercantum dalam lajur 1 terhitung mulai tanggal tersebut dalam lajur 9, diberikan pensiun pokok sebulan sebesar tersebut dalam lajur 11 keputusan ini.';
		$this->pdf->writeHTMLCell(125,'',41,103,$text1,0,0,false,false,'J',true);
		
		$tbl = <<<EOD
<table width="50%" cellspacing="0" cellpadding="1" border="1">
    <tr>
        <td width="25px;" align="center">1</td>
        <td width="200px;"> NAMA</td>
        <td> $row->nama_janda_duda</td>
		<td> $lname</td>
    </tr>
    <tr>
        <td width="25px;" align="center">2</td>
        <td> NAMA PNS/PENSIUN PNS *)</td>
        <td colspan="2"> $row->nama_pns</td>		
    </tr>
	<tr>
        <td width="25px;" align="center">3</td>
        <td> NIP/NRP</td>
        <td colspan="2"> $row->nip</td>		
    </tr>
	<tr>
        <td width="25px;" align="center">4</td>
        <td> PANGKAT/GOL. RUANG</td>
        <td colspan="2"> $row->GOl_PKTNAM / $row->GOL_GOLNAM</td>		
    </tr>
	
	<tr>
        <td width="25px;" align="center">5</td>
        <td> JABATAN </td>
        <td colspan="2"> $row->jabatan</td>		
    </tr>
	
	<tr>
        <td width="25px;" align="center">6</td>
        <td> UNIT KERJA TERAKHIR </td>
        <td colspan="2"> $row->unit_kerja</td>		
    </tr>
	
	<tr>
        <td width="25px;" align="center">7</td>
        <td> TANGGAL PERKAWINAN </td>
        <td colspan="2"> $row->perkawinan</td>		
    </tr>
	
	<tr>
        <td width="25px;" align="center">8</td>
        <td> MENINGGAL DUNIA </td>
        <td colspan="2"> $row->meninggal</td>		
    </tr>
		
	<tr>
        <td width="25px;" align="center">9</td>
        <td> PENSIUN TMT </td>
        <td colspan="2"> $row->pensiun</td>		
    </tr>
	
	<tr>
        <td width="25px;" align="center">10</td>
        <td> GAJI POKOK TERAKHIR </td>
        <td colspan="2"> Rp. $row->gapok,-</td>		
    </tr>
	
	<tr>
        <td width="25px;" align="center">11</td>
        <td> PENSIUN POKOK </td>
        <td> Rp. $row->penpok,- </td>
		<td> PP. 18/2019</td>		
    </tr>

</table>
EOD;

        $this->pdf->SetXY(10, 112);
		$this->pdf->writeHTML($tbl, true, false, false, false, '');
		
		$this->pdf->Text(10, 165, 'KEDUA');
		$this->pdf->Text(35, 165, ':');
		$text1='Mencatat bahwa anak penerima pensiun tersebut di atas pada akhir bulan terdiri dari:';
		$this->pdf->writeHTMLCell(165,'',41,165,$text1,0,0,false,false,'J',true);
		
		$tbl = <<<EOD
<table width="42%" cellspacing="0" cellpadding="1" border="1">
    <tr>
        <th width="25px;" align="center">NO</th>
        <th width="200px;" align="center"> NAMA</th>
        <th align="center"> TGL LAHIR</th>
		<th align="center"> NAMA<br/> AYAH/IBU</th>
		<th align="center"> KETERANGAN</th>
    </tr> 
	<tr>
        <td></td>
        <td></td>
        <td></td>
		<td></td>
		<td></td>
    </tr>	
	<tr>
        <td></td>
        <td></td>
        <td></td>
		<td></td>
		<td></td>
    </tr>
	<tr>
        <td></td>
        <td></td>
        <td></td>
		<td></td>
		<td></td>
    </tr>
	<tr>
        <td></td>
        <td></td>
        <td></td>
		<td></td>
		<td></td>
    </tr>

	<tr>
        <td></td>
        <td></td>
        <td></td>
		<td></td>
		<td></td>
    </tr>
</table>
EOD;
		$this->pdf->SetXY(10, 170);
		$this->pdf->writeHTML($tbl, true, false, false, false, '');
		
		$this->pdf->Text(175, 32, 'KETIGA');
		$this->pdf->Text(195, 32, ':');
		$this->pdf->Text(198, 32, 'Pembayaran pensiun janda/duda tersebut dilakukan dengan ketentuan:');
		$this->pdf->Text(203, 36, 'a.');
		$text1='Pemberian dan pembayaran pensiun janda/duda dihentikan akhir bulan janda/duda yang bersangkutan menikah lagi atau berakhir apabila meninggal dunia dan tidak terdapat lagi anak yang memenuhi syarat untuk menerima pensiun.';
		$this->pdf->writeHTMLCell(125,'',206,36,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(203, 48, 'b.');
		$text2='Jika janda/duda menikah lagi atau meninggal dunia, selama masih terdapat anak/anak-anak
		yang berusia di bawah 25 tahun tidak berpenghasilan sendiri belum pernah menikah, pensiun janda/duda 
		itu dibayarkan kepada dan atas nama anak pertama tersebut di atas untuk kepentingan anak-anak lainnya 
		terhitung mulai bulan berikutnya terjadinya pernikahan/kematian';
		$this->pdf->writeHTMLCell(125,'',206,48,$text2,0,0,false,true,'J',true);
		
		$text2='Khusus untuk janda apabila janda yang bersangkutan kemudian bercerai lagi, maka pensiun janda yang pembayarannya telah dihentikan, dibayarkan kembali mulai bulan berikutnya perceraian itu berlaku sah.';
		$this->pdf->writeHTMLCell(125,'',206,64,$text2,0,0,false,true,'J',true);
		 
		$this->pdf->Text(175, 80, 'KEEMPAT');
		$this->pdf->Text(195, 80, ':');
		$text2='Di atas pensiun pokok tersebut diberikan tunjangan keluarga dan tunjangan pangan yang berlaku bagi Pegawai Negeri Sipil dan tunjangan-tunjangan lain yang berlaku bagi penerima pensiun.';
		$this->pdf->writeHTMLCell(125,'',198,80,$text2,0,0,false,true,'J',true);
		
		$this->pdf->Text(175, 90, 'KELIMA');
		$this->pdf->Text(195, 90, ':');
		$text2='Apabila dikemudian hari ternyata terdapat kekeliruan dalam keputusan ini, akan diadakan perbaikan dan perhitungan kembali sebagaimana mestinya.';
		$this->pdf->writeHTMLCell(125,'',198,90,$text2,0,0,false,true,'J',true);
		
		$text2='Asli Keputusan ini diberikan kepada yang bersangkutan dengan alamat : '.$row->alamat;
		$this->pdf->writeHTMLCell(125,'',198,100,$text2,0,0,false,true,'J',true);
		
		$this->pdf->Text(175, 115, 'Sebagai bukti sah untuk dipergunakan sebagaimana mestinya.');
		
		$this->pdf->Text(265, 125, 'Ditetapkan di');
		$this->pdf->Text(285, 125, ':');
		$this->pdf->Text(290, 125, 'MANADO');
		
		$this->pdf->Text(265, 130, 'Pada Tanggal');
		$this->pdf->Text(285, 130, ':');
		$this->pdf->Text(290, 130, $row->persetujuan_tgl);
		
		$this->pdf->Text(265, 135, 'a.n. KEPALA BADAN KEPEGAWAIAN NEGARA');
		$this->pdf->Text(275, 140, 'KEPALA SEKSI PENSIUN PEGAWAI');
		$this->pdf->Text(275, 145, 'NEGERI SIPIL INSTANSI VERTIKAL');
		$this->pdf->Text(275, 150, 'DAN PROPINSI ');
		$this->pdf->Text(275, 170, 'WAISUL QORNI, S.Sos, M.Si ');
		$this->pdf->Text(275, 175, 'NIP. 197512311995031001 ');
		
	    $this->pdf->Text(175, 185, 'Tembusan, Keputusan ini disampaikan kepada :');
		$this->pdf->Text(175, 190, '1. Kepala Kantor Cabang PT.TASPEN (PERSERO)/PT.ASABRI (PERSERO) di Gorontalo');
		$this->pdf->Text(175, 195, '2. Direktur Pensiun BKN di Jakarta;');
		$this->pdf->Text(175, 200, '3. Pertinggal ');
		
		// set style for barcode
		$style = array(
			'border' => false,
			'padding' => 0,
			'fgcolor' => array(0, 0, 0),
			'bgcolor' => false, //array(255,255,255)
			'module_width' => 1, // width of a single module in points
			'module_height' => 1 // height of a single module in points
		);
		
		$code  = ' SK '.$name.' PNS '.$row->nama_pns.'  atas nama '.$row->nama_janda_duda ;
				
		$this->pdf->write2DBarcode($code, 'QRCODE,Q', 177, 155, 25, 25, $style, 'N');
		
		$this->pdf->Output('cetakSuratTaspen.pdf', 'D');
    }

	public function getPhotoTaspen()
	{
		$instansi  = $this->myencrypt->decode($this->input->get('id'));
		$file      = $this->myencrypt->decode($this->input->get('f'));
		$nip       = $this->myencrypt->decode($this->input->get('n'));
		
		ob_clean();		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:image/jpeg');
		header('Content-Disposition:attachment; filename='.$nip.'.jpeg');  
		readfile(base_url().'uploads/taspen/'.'PHOTO_'.$nip.'.jpg');
	}	
	
	private function _getExcelTaspen($q)
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
					<th>NOMOR USUL</th>
					<th>TANGGAL</th>
					<th>NIP</th>					
					<th>NAMA PNS</th>	
					<th>NAMA JANDA/DUDA</th>
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
				$html .= "<td>{$r->nomor_usul}</td>";	
				$html .= "<td>{$r->tgl_usul}</td>";
				$html .= "<td class=str>{$r->nip}</td>";				
                $html .= "<td>{$r->nama_pns}</td>";
                $html .= "<td>{$r->nama_janda_duda}</td>";					
				$html .= "<td>{$r->layanan_nama}</td>";	
				$html .= "<td>{$r->usul_status}".'<br/>'."{$r->usul_verif_name}</td>";
				$html .= "<td>{$r->usul_alasan}</td>";
				$html .= "<td>{$r->usul_verif_date}</td>";
				$html .= "<td>{$r->usul_no_persetujuan}</td>";
				$html .= "<td>{$r->usul_tgl_persetujuan}</td>";
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
}
