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
		$data['spesimenTaspen'] = $this->entry->getSpesimenTaspen();
		$data['kantor']    	    = $this->entry->getKantorTaspen();
		
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
			$data['spesimenTaspen'] = $this->entry->getSpesimenTaspen();
			$data['kantor']    	    = $this->entry->getKantorTaspen();
			
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
			$data['spesimenTaspen'] = $this->entry->getSpesimenTaspen();
			$data['kantor']    	    = $this->entry->getKantorTaspen();
			
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
		$instansi					= $this->myencrypt->decode($this->input->get('instansi'));
		$layanan					= $this->myencrypt->decode($this->input->get('layanan'));
		
		
		$this->db->trans_start();
		
		if($layanan == 19)
		{	
		
			$last	                    = $this->entry->getLastNomorPmk($instansi);
			$instansi2					= $this->entry->getInstansi2($instansi);
			$cek 					    = $this->entry->cekNomorPmk($data['agenda'],$data['nip']);
			
			$d['instansi1']        = $instansi;
			$d['instansi2']		   = $instansi2;
			$d['agenda']           = $data['agenda'];
			$d['nip']              = $data['nip'];
			$d['nomor']            = $last;
			$d['tahun']            = date('Y');
			$d['nomor_pmk']        = $instansi2.substr("0000000{$last}",-7);
			
			if($cek->num_rows()  == 0)
			{			
				$insert                		= $this->entry->insertNomorPmk($d);
				$data['insert']        		= $insert;
				$data['persetujuan']   		= 'LH-'.$instansi2.substr("0000000{$last}",-7);
				$data['insert_nominatif']   = $this->entry->simpanNomorPmk($data);
				
			}
			else
			{
				$rowcek                     = $cek->row();
				$data['persetujuan']   		= 'LH-'.$instansi2.substr("0000000{$rowcek->nomor}",-7);
				$data['update_nominatif']   = $this->entry->simpanNomorPmk($data);
			}		
			
		}	
		
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
			
			$data['pesan']		= "update tahapan proses cetak";
			$data['layanan']	= $layanan;
			$data['entry']      = $entry->result();
			$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($data));
		}

		$this->db->db_debug = $db_debug; //restore setting
		$this->db->trans_complete();
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
						
						if($layanan === "19" )
						{  
							$html .='&nbsp;<a href="#cetakNotaPMK" class="btn btn-success btn-xs cetak" data-tooltip="tooltip"  title="Cetak Nota Persetujuan PMK" id="?a='.$this->myencrypt->encode($value->agenda_id).'&n='.$this->myencrypt->encode($value->nip).'"><i class="fa fa-print"></i></a>';
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
		
		$this->pdf->SetFont('arial', '', 12);
		
		$this->pdf->AddPage('P', array(210,297));
		$this->pdf->Text(25, 65, 'Nomor');
		$this->pdf->Text(40, 65, ':');
		$this->pdf->Text(42, 65, trim($row->nomi_persetujuan));
		
		//$this->pdf->Text(131,65, 'Manado, '.$row->tanggal_acc);
		$this->pdf->writeHTMLCell(160,5,28,65,'Manado, '.$row->tanggal_acc,0,0,false,true,'R',true);

		
		$this->pdf->Text(25, 71, 'Sifat');
		$this->pdf->Text(40, 71, ':');
		$this->pdf->Text(42, 71, 'Biasa');
		
		$this->pdf->Text(25, 77, 'Hal');
		$this->pdf->Text(40, 77, ':');
		$this->pdf->Text(42, 77, 'Peningkatan Pendidikan dan/atau Pencantuman Gelar Akademik');
		
		$this->pdf->Text(42, 83, 'PNS atas nama '.$row->nama.', '.$row->gelar);
		$this->pdf->Text(42, 89, 'NIP. '.$row->nip);
		
		$this->pdf->Text(25, 109, 'Yth.');
		$this->pdf->writeHTMLCell(155,5,33,109,trim($row->nama_jabatan),0,0,false,true,'J',true);
		$this->pdf->writeHTMLCell(155,5,33,115,trim($row->nama_daerah),0,0,false,true,'J',true);
		$this->pdf->Text(25, 121, 'di '.$row->lokasi_daerah);	
		
		$text='&nbsp;&nbsp;&nbsp;&nbsp;Sesuai dengan surat Saudara Nomor '.$row->agenda_nousul.' tanggal '.$row->tanggal_agenda.' perihal Usul Peningkatan Pendidikan , dengan hormat kami sampaikan hal-hal sebagai berikut:';
        $this->pdf->writeHTMLCell(165,10,25,140,$text,0,0,false,true,'J',false);
		
		$this->pdf->Text(25, 158, 'a.');
		$text='Bahwa berdasarkan Peraturan Pemerintah Nomor 11 Tahun 2017 pasal 175 ayat (1) dinyatakan bahwa Profil Pegawai Negeri Sipil dikelola dan dimutakhirkan oleh pejabat yang berwenang sesuai dengan perkembangan atau perubahan informasi kepegawaian masing-masing Instansi Pemerintah untuk selanjutnya diintegrasikan ke data Sistem Informasi Aparatur Sipil Negara secara nasional yang dikelola oleh Badan Kepegawaian Negara.';
		$this->pdf->writeHTMLCell(160,15,29,158,$text,0,0,false,true,'J',true);
		
		$this->pdf->Text(25, 192, 'b.');
		$text='Bahwa berdasarkan '.$row->nama_ijazah.', pada Program Studi '.$row->prodi.' '.$row->kampus.' yang dikeluarkan di '.$row->lokasi_kampus.', atas nama:';
		$this->pdf->writeHTMLCell(160,15,29,192,$text,0,0,false,true,'J',true);
		
		$this->pdf->Text(29, 210, 'Nama');
		$this->pdf->Text(77, 210, ':');
		$this->pdf->Text(79, 210, $row->nama.', '.$row->gelar);
		
		$this->pdf->Text(29, 216, 'NIP');
		$this->pdf->Text(77, 216, ':');
		$this->pdf->Text(79, 216, $row->nip);
		
		$this->pdf->Text(29, 222, 'Pangkat/Gol.Ruang/TMT');
		$this->pdf->Text(77, 222, ':');
	    $this->pdf->Text(79, 222, $row->pangkat.' / '. $row->nama_golongan.' / '. $row->tmt_golongan);
		
		$this->pdf->Text(29, 228, 'Nomor Ijazah/Tgl.Lulus');
		$this->pdf->Text(77, 228, ':');
		$this->pdf->Text(79, 228, $row->nomor_ijazah.' / '. $row->tgl_ijazah);
		
		$this->pdf->Text(25, 236, 'c.');
		$text='Bahwa berdasarkan ketentuan yang berlaku, maka permohonan Saudara telah memenuhi syarat dan telah kami cantumkan dalam Data Induk Pegawai Sipil, kepada yang bersangkutan berhak mencantumkan gelar '.$row->nama_gelar.' pada Mutasi Kepegawaiannya.';
		$this->pdf->writeHTMLCell(160,15,29,236,$text,0,0,false,true,'J',true);
		
		
		$this->pdf->AddPage('P', 'A4');
		$this->pdf->Text(29,70,'Atas perhatian Bapak/Ibu/Saudara, kami ucapkan terima kasih');

		$this->pdf->Text(124, 85, 'an.');
		$text2='Kepala Kantor Regional XI Badan Kepegawaian Negara '.$row->jabatan;
		$this->pdf->writeHTMLCell(60,20,130,85,$text2,0,0,false,true,'J',true);
		
	
		
		$this->pdf->Text(130, 125, '$');
		
		$this->pdf->Text(29, 155, 'Tembusan, Yth :');
		$this->pdf->Text(29, 163, '1. Kepala Kantor Regional XI sebagai laporan;');
		$this->pdf->Text(29, 168, '2. Kepala Bidang Informasi Kepegawaian  Kanreg  XI BKN;');
		$this->pdf->Text(29, 173, '3. Saudara '.$row->nama);
		

       
		
        /*
		
        $this->pdf->Text(10, 112, 'a.');		
        $text='Bahwa berdasarkan '.$row->nama_ijazah.', pada Program Studi '.$row->prodi.' '.$row->kampus.' yang dikeluarkan di '.$row->lokasi_kampus.', atas nama:';
		$this->pdf->writeHTMLCell(175,15,14,112,$text,0,0,false,true,'',true);
		
		$this->pdf->Text(15, 130, 'Nama');
		$this->pdf->Text(70, 130, ':');
		$this->pdf->Text(75, 130, $row->nama.', '.$row->gelar);
		
		$this->pdf->Text(15, 135, 'NIP');
		$this->pdf->Text(70, 135, ':');
		$this->pdf->Text(75, 135, $row->nip);
		
		$this->pdf->Text(15, 140, 'Pangkat/Gol.Ruang/TMT');
		$this->pdf->Text(70, 140, ':');
		$this->pdf->Text(75, 140, $row->pangkat.' / '. $row->nama_golongan.' / '. $row->tmt_golongan);
		
		$this->pdf->Text(15, 145, 'Nomor Ijazah/Tgl.Lulus');
		$this->pdf->Text(70, 145, ':');
		$this->pdf->Text(75, 145, $row->nomor_ijazah.' / '. $row->tgl_ijazah);
		
		
		$this->pdf->Text(10, 155, 'b. ');	
		$text1='Berdasarkan ketentuan yang berlaku maka permohonan Saudara telah memenuhi syarat dan telah kami cantumkan dalam Data Induk Pegawai Sipil, kepada yang bersangkutan berhak mencantumkan gelar '.$row->nama_gelar.' pada Mutasi Kepegawaiannya.';
		$this->pdf->writeHTMLCell(180,15,14,155,$text1,0,0,false,false,'J',false);
		//$this->pdf->writeHTMLCell(180,5,10,180,'&nbsp;&nbsp;&nbsp;&nbsp;  ',0,0,false,false,'J',true);
		
		$this->pdf->Text(14,175,'Atas perhatian Bapak/Ibu/Saudara, kami ucapkan terima kasih');

        */  
		//set style for barcode
		/*
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
		
		$code   = 'Pencantuman Gelar PNS dengan NIP '.$row->nip.' atas nama '.$row->nama;
		$code  .= ' Nomor : '.$row->nomi_persetujuan;
		
		$this->pdf->write2DBarcode($code, 'QRCODE,Q', 20, 200, 35, 35, $style, 'N');
       
	    */
		
		/*
		
		$this->pdf->Text(125, 195, 'an.');
		$text2='Kepala Kantor Regional XI Badan Kepegawaian Negara '.$row->jabatan;
		$this->pdf->writeHTMLCell(55,125,130,195,$text2,0,0,false,true,'L',true);
		
	
		
		$this->pdf->Text(130, 235, '$');
		
		$this->pdf->Text(10, 240, 'Tembusan, Yth :');
		$this->pdf->Text(10, 245, '1. Kepala Kantor Regional XI sebagai laporan;');
		$this->pdf->Text(10, 250, '2. Kepala Bidang Informasi Kepegawaian  Kanreg  XI BKN;');
		$this->pdf->Text(10, 255, '3. Saudara '.$row->nama);*/
		
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
				$name  = 'PERTEK_KP_'.$nip.'_'.$gol;				
			break;
			case 2:
				$name  = 'PERTEK_KP_'.$nip.'_'.$gol;					
			break;
			case 3:
				$name  = 'PERTEK_KP_'.$nip.'_'.$gol;			
			break;			
			case 4:
				$name  = 'PERTEK_PENSIUN_'.$nip;				
			break;
			case 5:
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
				$name  = 'PERTEK_KP_'.$nip.'_'.$gol;		
			break;
			case 13:
				$name  = 'SK_MUTASI'.$nip;			
			break;
			case 14:
				$name  = 'SK_PG_'.$nip;;			
			break;
			case 18:
				$name  = 'PERTEK_PENSIUN_'.$nip;			
			break;
			case 19:
				$name  = 'PERTEK_PMK_'.$nip;;			
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
		$data['usul_id'] 				= $this->myencrypt->decode($this->input->post('usul'));
		$data['nip']       				= $this->myencrypt->decode($this->input->post('nip'));
		$data['persetujuan']			= $this->input->post('persetujuan');
		$data['tanggal']				= $this->input->post('tanggal');
		$data['pensiun_pokok']			= $this->input->post('pensiun_pokok');
		$data['pensiun_tmt']			= $this->input->post('pensiun_tmt');
		$data['kantor_taspen']			= $this->input->post('kantor');
		$data['tgl_meninggal']			= $this->input->post('tgl_meninggal');
		$data['tgl_menikah']			= $this->input->post('tgl_menikah');
		$data['gaji_pokok_terakhir']	= $this->input->post('gaji_pokok_terakhir');
		$data['usul_spesimen']	        = $this->input->post('spesimenTaspen');
		$data['jd_dd_status']	        = $this->input->post('jandaDuda');
		$data['persetujuan_status']	    = $this->input->post('persetujuanStatus');
		
		$this->form_validation->set_rules('persetujuan', 'Persetujuan', 'required');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
		$this->form_validation->set_rules('pensiun_pokok', 'Pensiun Pokok', 'required');
		$this->form_validation->set_rules('pensiun_tmt', 'Pensiun TMT', 'required');
		$this->form_validation->set_rules('kantor', 'Kantor Taspen', 'required');
		
		$this->form_validation->set_rules('tgl_menikah', 'Tgl Menikah', 'required');
		$this->form_validation->set_rules('gaji_pokok_terakhir', 'Gaji Pokok terakhir', 'required');	
		$this->form_validation->set_rules('spesimenTaspen', 'Spesimen', 'required');
		
		$layanan_id    = $this->input->post('layananId');
		
		if($layanan_id == 15)
		{
            $this->form_validation->set_rules('tgl_meninggal', 'Tgl Meninggal', 'trim');
		}
		else
		{
			$this->form_validation->set_rules('tgl_meninggal', 'Tgl Meninggal', 'required');
        }	
		
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
						$html .='&nbsp;<button class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="upload persetujuan" data-toggle="modal" data-target="#uploadModal" data-layanan="'.$value->layanan_id.'" data-usul="'.$value->usul_id.'" data-nip="'.$value->nip.'"><i class="fa fa-upload"></i></button>';
						
			$html .='</td>
						<td>'.$value->nomor_usul.'</td>
						<td>'.$value->nip.'</td>
						<td>'.$value->nama_pns.'</td>	
						<td>'.$value->nama_janda_duda.'</td>	
						<td>'.$value->layanan_nama.'</td>
						<td>'.$value->usul_verif_date.' Oleh :<b>'.$value->usul_verif_name.'</b></span></td>';
			$html .='<td>';
						if(!empty($value->file_persetujuan))
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
		$data['layanan']       		= $this->myencrypt->decode($this->input->get('l'));   
		
		$layanan		       		= $this->myencrypt->decode($this->input->get('l'));   
		
		switch($layanan){
			case 15:
				$this->_cetakMutasi($data);			
			break;
			case 16:
				$this->_cetakSK($data);
			break;
			case 17:
				$this->_cetakSK($data);
			break;
		}		
		
	}
	
	function _cetakMutasi($data)
	{
		$row						= $this->entry->getEntryOneTaspen($data)->row();
		$mutasiIstri				= $this->entry->getMutasiIstri($row->usul_id);
		$mutasiAnak				    = $this->entry->getMutasiAnak($row->usul_id);
		//$spesimen					= $this->entry->getSpesimenTaspen()->row();
		
		$this->load->library('PDF', array());	
		$this->pdf->setPrintHeader(false);
		$this->pdf->setPrintFooter(false);		
		
		$this->pdf->SetAutoPageBreak(false, 0);
		
		$this->pdf->SetFont('freeSerif', '', 8);
		$this->pdf->AddPage('L', 'FOLIO', false, false);
		
		$this->pdf->Text(225, 10, 'LAMPIRAN XVII SURAT EDARAN BERSAMA KEPALA BADAN ADMINISTRASI');
		$this->pdf->Text(225, 15, 'KEPEGAWAIAN NEGARA DAN DIREKTUR JENDERAL ANGGARAN');
		$this->pdf->Text(225, 20, 'NOMOR : 19/SE/1989');
		$this->pdf->Text(225, 25, 'NOMOR : SE-51/A/1989');
		$this->pdf->Text(225, 30, 'TANGGAL : 14 APRIL 1989');
		
		
		$this->pdf->SetFont('freeSerif', '', 12);
		$this->pdf->Text(100, 45, 'FORMULIR PENDAFTARAN ISTRI(2)/SUAMI/ANAK(2)');
		$this->pdf->Text(115, 50, '( untuk penerima pensiun pegawai )');
		
		$this->pdf->SetFont('freeSerif', '', 9);
		$tbl ='
<table  cellspacing="0" cellpadding="1" border="1">
    <tr style="background-color:#EAEDED;">
        <td align="center" rowspan="2" width="25px;"> NO</td>
        <td  rowspan="2"> NAMA ANAK(2)<br/>&nbsp;KANDUNG</td>
        <td  rowspan="2" width="25px;"> LK/<br/>&nbsp;PR</td>
		<td  rowspan="2"> TANGGAL<br/>&nbsp;LAHIR</td>	
		<td  colspan="3" width="345px;"> KETERANGAN TENTANG IBU/AYAH</td>
		<td  colspan="3" width="330px;"> KETERANGAN TENTANG PENERIMA PENSIUN PEGAWAI</td>
       	
    </tr>
	<tr style="background-color:#EAEDED;">
        <td width="115px;"> NAMA</td>
        <td width="115px;"> CERAI<br/>&nbsp;TANGGAL</td>
		<td width="115px;"> MENINGGAL<br/>&nbsp;TANGGAL</td>	    		
    </tr>';
	$j=1;
	if($mutasiAnak->num_rows() > 0){	
	foreach($mutasiAnak->result() as $value){	
        ($j == 1 ? $n='Ke/p' : $n=$j);	
		$tbl .='<tr>
			<td align="center"> '.$n.'</td>
			<td> '.$value->nama.'</td>
			<td> '.$value->sex.'</td>
			<td> '.$value->atgl_lahir.'</td>
			<td> '.$value->nama_ibu_ayah.'</td>
			<td> '.$value->acerai_tgl.'</td>
			<td> '.$value->ameninggal_tgl.'</td>		
		</tr>';
		$j++;
		}		
	}

    $anak = $mutasiAnak->num_rows();	
	$i = $anak + 1;
	for($i;$i <= 7;$i++){	
	$tbl .='<tr>
        <td align="center"> '.$i.'.</td>
        <td> </td>
        <td> </td>
		<td> </td>
		<td> </td>
		<td> </td>
		<td> </td>
    </tr>';
	 
	}
    $tbl.='<tr style="background-color:#EAEDED;">
        <td align="center"  width="25px;"> NO</td>
        <td> ISTRI(2) SUAMI</td>
        <td  colspan="2"> ISTRI PERTAMA/<br>&nbsp;SUAMI</td>
		<td> ISTRI KEDUA/<br>&nbsp;SUAMI</td>	
		<td> ISTRI KETIGA/<br>&nbsp;SUAMI</td>
		<td> ISTRI KEEMPAT</td>       	
    </tr>';
	$tbl.='<tr>
        <td align="center"> 1.</td>
        <td> Nama</td>';
	$k =1;	
	foreach($mutasiIstri->result() as $value){	
	    ($k == 1 ?  $n='<td colspan="2">' : $n='<td>');
        $tbl.=$n.' '.$value->nama.'</td>';
		$k++;		
	}
	for($i=$mutasiIstri->num_rows()+1; $i<= 4;$i++)
	{	($i == 1 ?  $n='<td colspan="2"></td>' : $n='<td></td>');
		$tbl.= $n;
	}
	$tbl.='</tr>';
	
	$tbl.='<tr>
        <td align="center"> 2.</td>
        <td> Nama Kecil</td>';		
	$k =1;	
	foreach($mutasiIstri->result() as $value){	
	    ($k == 1 ?  $n='<td colspan="2">' : $n='<td>');
        $tbl.=$n.' '.$value->nama_kecil.'</td>';
		$k++;		
	}
	for($i=$mutasiIstri->num_rows()+1; $i<= 4;$i++)
	{	($i == 1 ?  $n='<td colspan="2"></td>' : $n='<td></td>');
		$tbl.= $n;
	}
	$tbl.='</tr>';
	
	
	$tbl .='<tr>
        <td align="center"> 3.</td>
        <td> Tempat/Tgl Lahir</td>';		
    $k =1;	
	foreach($mutasiIstri->result() as $value){	
	    ($k == 1 ?  $n='<td colspan="2">' : $n='<td>');
        $tbl.=$n.' '.$value->tempat_lahir.'/'.$value->atgl_lahir.'</td>';
		$k++;		
	}
	for($i=$mutasiIstri->num_rows()+1; $i<= 4;$i++)
	{	($i == 1 ?  $n='<td colspan="2"></td>' : $n='<td></td>');
		$tbl.= $n;
	}
	$tbl.='</tr>';
	
	$tbl.='<tr>
        <td align="center"> 4.</td>
        <td> Tanggal Nikah</td>';
	$k =1;	
	foreach($mutasiIstri->result() as $value){	
	    ($k == 1 ?  $n='<td colspan="2">' : $n='<td>');
        $tbl.=$n.' '.$value->atgl_nikah.'</td>';
		$k++;		
	}
	for($i=$mutasiIstri->num_rows()+1; $i<= 4;$i++)
	{	($i == 1 ?  $n='<td colspan="2"></td>' : $n='<td></td>');
		$tbl.= $n;
	}
	$tbl.='</tr>';
	
	$tbl.='<tr>
        <td align="center"> 5.</td>
        <td> Tanggal Pendaftaran</td>';
	$k =1;	
	foreach($mutasiIstri->result() as $value){	
	    ($k == 1 ?  $n='<td colspan="2">' : $n='<td>');
        $tbl.=$n.' '.$value->atgl_pendaftaran.'</td>';
		$k++;		
	}
	for($i=$mutasiIstri->num_rows()+1; $i<= 4;$i++)
	{	($i == 1 ?  $n='<td colspan="2"></td>' : $n='<td></td>');
		$tbl.= $n;
	}
	$tbl.='</tr>';
	
	$tbl.='<tr>
        <td align="center"> 6.</td>
        <td> Tanggal Cerai</td>';
	$k =1;	
	foreach($mutasiIstri->result() as $value){	
	    ($k == 1 ?  $n='<td colspan="2">' : $n='<td>');
        $tbl.=$n.' '.$value->atgl_cerai.'</td>';
		$k++;		
	}
	for($i=$mutasiIstri->num_rows()+1; $i<= 4;$i++)
	{	($i == 1 ?  $n='<td colspan="2"></td>' : $n='<td></td>');
		$tbl.= $n;
	}
	$tbl.='</tr>';
	
	$tbl.='<tr>
        <td align="center">7.</td>
        <td> Tanggal Wafat</td>';
	$k =1;	
	foreach($mutasiIstri->result() as $value){	
	    ($k == 1 ?  $n='<td colspan="2">' : $n='<td>');
        $tbl.=$n.' '.$value->atgl_wafat.'</td>';
		$k++;		
	}
	for($i=$mutasiIstri->num_rows()+1; $i<= 4;$i++)
	{	($i == 1 ?  $n='<td colspan="2"></td>' : $n='<td></td>');
		$tbl.= $n;
	}
	$tbl.='</tr>';
	
	$tbl.='<tr>
        <td align="center">8.</td>
        <td > Alamat</td>';
	$k =1;	
	foreach($mutasiIstri->result() as $value){	
	    ($k == 1 ?  $n='<td colspan="2">' : $n='<td>');
        $tbl.=$n.' '.$value->alamat.'</td>';
		$k++;		
	}
	for($i=$mutasiIstri->num_rows()+1; $i<= 4;$i++)
	{	($i == 1 ?  $n='<td colspan="2"></td>' : $n='<td></td>');
		$tbl.= $n;
	}
	$tbl.='</tr>';
	
	$tbl.='<tr>
        <td align="center"> 9.</td>
        <td rowspan="11"> Tanda Tangan<br/>&nbsp;Atau Cap Jempol<br/>&nbsp;Tangan Kiri</td>
        <td rowspan="11" colspan="2"> </td>
		<td rowspan="11"> </td>
		<td rowspan="11"> </td>
		<td rowspan="11"> </td>
    </tr>
	<tr>
        <td align="center"> 10.</td> 
	</tr>
	<tr>
        <td align="center"> 11.</td> 
	</tr>
	<tr>
        <td align="center"> 12.</td> 
	</tr>
	<tr>
        <td align="center"> 13.</td> 
	</tr>
	<tr>
        <td align="center"> 14.</td> 
	</tr>
	<tr>
        <td align="center"> 15.</td> 
	</tr>';
	if($mutasiAnak->num_rows() < 3)
	{	
	$tbl.='<tr>
        <td align="center"> 16.</td> 
	</tr>
	<tr>
        <td align="center"> 17.</td> 
	</tr>';
	}
	
	$tbl .='</table>';

        $this->pdf->SetXY(10, 60);
		$this->pdf->writeHTML($tbl, true, false, false, false, '');	
		
		$this->pdf->Text(212, 65, 'Yang Bertanda Tangan dibawah ini :');
		$this->pdf->Text(212, 70, '1. Nama :');
		$this->pdf->Text(260, 70, ': '.$row->nama_pns);
		
		$this->pdf->Text(212, 75, '2. Nama Kecil ');
		$this->pdf->Text(260, 75, ': '.$row->nama_kecil);
		
		$this->pdf->Text(212, 80, '3. Tempat/Tanggal Lahir ');
		$this->pdf->Text(260, 80, ': '.$row->tempat_lahir.', '.$row->atgl_lahir);
		
		$this->pdf->Text(212, 85, '4. Tgl.No.Surat Keputusan Pensiun ');
		$this->pdf->Text(260, 85, ': '.$row->atgl_skep);
		$this->pdf->Text(260, 90, ': '.$row->nomor_skep);
		
		$this->pdf->Text(212, 95, '5. Pensiun Pokok ');
		$this->pdf->Text(260, 95, ': Rp. '.$row->penpok);
		
		$this->pdf->Text(212, 100, '6. Pensiun Terhitung Mulai ');
		$this->pdf->Text(260, 100, ': '.$row->pensiun);
		
		$this->pdf->Text(212, 105, '7. Alamat ');
		$this->pdf->Text(260, 105, ': ');
		$text1= $row->alamat;
		$this->pdf->writeHTMLCell(65,'',261,105,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(212, 125, '8. Tanda Tangan ');
		$this->pdf->Text(260, 125, ': TTD');
		
		$this->pdf->Text(212, 140, 'Disahkan Tanggal ');
		$this->pdf->Text(260, 140, ': '.$row->persetujuan_tgl);
		
		$this->pdf->Text(212, 145, 'Nomor ');
		$this->pdf->Text(260, 145, ': '.$row->usul_no_persetujuan);
		
		$this->pdf->writeHTMLCell(75,'',235,155,'AN. KEPALA KANTOR',0,0,false,false,'C',true);
		$this->pdf->writeHTMLCell(75,'',235,160,'REGIONAL XI BADAN KEPEGAWAIAN NEGARA',0,0,false,false,'C',true);
		$text1= strtoupper($row->jabatan_spesimen);
		$this->pdf->writeHTMLCell(75,'',235,164,$text1,0,0,false,false,'C',true);
		$this->pdf->Text(250, 185, strtoupper($row->nama_spesimen).(!empty($row->glrblk) ? ','.$row->glrblk : ''));
		$this->pdf->Text(250, 189, 'NIP. '.$row->nip_spesimen);
		
		
		
		// set style for barcode
		$style = array(
			'border' => false,
			'padding' => 0,
			'fgcolor' => array(0, 0, 0),
			'bgcolor' => false, //array(255,255,255)
			'module_width' => 1, // width of a single module in points
			'module_height' => 1 // height of a single module in points
		);
		
		$code  = ' SK Mutasi Keluarga  PNS '.$row->nama_pns ;				
		$this->pdf->write2DBarcode($code, 'QRCODE,Q', 10, 10, 25, 25, $style, 'N');
		
		// break
		$this->pdf->AddPage('P', 'A4', false, false);
		$garuda = base_url() . 'assets/dist/img/garuda.png';
		$this->pdf->Image($garuda, 5, 8, 23, '', 'PNG', '', 'T', false, 145, 'C', false, false, 0, false, false, false);
		
		$this->pdf->SetFont('helvetica', 'B', 12);
		$this->pdf->Text(5, 35,'BADAN KEPEGAWAIAN NEGARA', false, false, true, 0, 4, 'C', false, '', 0, false, 'T', 'M', false);
		$this->pdf->Text(5, 40, 'KANTOR REGIONAL XI', false, false, true, 0, 4, 'C', false, '', 0, false, 'T', 'M', false);
		$style = array(
			'width' => 0.29999999999999999,
			'cap'   => 'butt',
			'join'  => 'miter',
			'dash'  => 0,
			'color' => array(0, 0, 0)
			);
		$this->pdf->Line(5, 46, $this->pdf->getPageWidth() - 5, 46, $style);
		$style1 = array(
			'width' => 1,
			'cap'   => 'butt',
			'join'  => 'miter',
			'dash'  => 0,
			'color' => array(0, 0, 0)
			);
		$this->pdf->Line(5, 47, $this->pdf->getPageWidth() - 5, 47, $style1);
		
		$this->pdf->SetFont('freeSerif', '', 12);
		$this->pdf->Text(150, 50, 'Manado, '.$row->persetujuan_tgl);
		
		$this->pdf->Text(5, 55, 'Nomor ');
		$this->pdf->Text(25, 55, ': '.$row->usul_no_persetujuan);
		
		$this->pdf->Text(5, 60, 'Lampiran ');
		$this->pdf->Text(25, 60, ':  ');
		
		$this->pdf->Text(5, 65, 'Perihal ');
		$this->pdf->Text(25, 65, ': Pengambilan formulir ');
		$this->pdf->Text(27, 70, 'Model A/II/1969 Pens ');
		
		$this->pdf->Text(140, 60, 'Kepada');
		$this->pdf->Text(130, 65, 'Yth.');
		$this->pdf->Text(140, 65,$row->nama_pns);
		$this->pdf->Text(140, 70, 'NIP. '.$row->nip);
		$this->pdf->Text(130, 75, 'D/a. ');
		$text1=$row->alamat;
		$this->pdf->writeHTMLCell(70,'',140,75,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(25, 100, '1.');
		$text1='Menunjuk Surat dari Ka. PT. Taspen (persero) Cabang '.$row->nama_taspen.'  Nomor '.$row->nomor_usul.' Perihal permohonan Saudara Tanggal '.$row->atgl_usul.' untuk '.($row->persetujuan_status == 1 ? 'mengesahkan' : 'mencatat').' mutasi keluarga, bersama ini kami kirimkan kembali Formulir Model A/II/Pens, tentang pendataran Isteri/Suami/Anak sebagai yang berhak menerima pensiun Janda/Duda yang telah '.($row->persetujuan_status == 1 ? 'disahkan' : 'dicatat');
		
		$this->pdf->writeHTMLCell(175,'',30,100,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(25, 125, '2.');
		$text1='Mengingat bahwa bukti pendaftaran tersebut sangat penting sebagai kelengkapan permohonan pensiun Janda/Duda sebagai Isteri/Suami/Anak/Saudara, kami harapkan agar formulir tersebut disimpan dengan baik.';
		$this->pdf->writeHTMLCell(175,'',30,125,$text1,0,0,false,false,'J',true);
		
		if($row->persetujuan_status == 2)
		{	
			$this->pdf->Text(25, 145, '3.');
			$text1='Perlu kami jelaskan bahwa pendaftaran yang saudara lakukan telah melebihi batas waktu 1 (satu) tahun setelah terjadinya perkawinan tersebut sebagaimana ditetapkan dalam pasal 19 ayat 6 Undang-Undang Nomor 11 Tahun 1969, maka pendaftaran tersebut hanya kami catat, tetapi tidak disahkan.';
			$this->pdf->writeHTMLCell(175,'',30,145,$text1,0,0,false,false,'J',true);
			
			$this->pdf->Text(25, 165, '4.');
			$text1='Demikian untuk dipergunakan sebagaimana mestinya.';
			$this->pdf->writeHTMLCell(175,'',30,165,$text1,0,0,false,false,'J',true);
		}
		else
		{
			$this->pdf->Text(25, 145, '3.');
			$text1='Demikian untuk dipergunakan sebagaimana mestinya.';
			$this->pdf->writeHTMLCell(175,'',30,145,$text1,0,0,false,false,'J',true);
		}		
		
		// set style for barcode
		$style = array(
			'border' => false,
			'padding' => 0,
			'fgcolor' => array(0, 0, 0),
			'bgcolor' => false, //array(255,255,255)
			'module_width' => 1, // width of a single module in points
			'module_height' => 1 // height of a single module in points
		);
		
		$code  = 'SK Mutasi Keluarga PNS '.$row->nama_pns;					
		$this->pdf->write2DBarcode($code, 'QRCODE,Q', 20, 190, 25, 25, $style, 'N'); 
		
		$this->pdf->Text(125, 175, 'an.');
		$text2='Kepala Kantor Regional XI Badan Kepegawaian Negara '.$row->jabatan_spesimen;
		$this->pdf->writeHTMLCell(75,125,130,175,$text2,0,0,false,true,'L',true);
		
		$this->pdf->Text(130, 215,ucwords(strtolower($row->nama_spesimen)).(!empty($row->glrblk) ? ','.$row->glrblk : ''));
		$this->pdf->Text(130, 220, 'NIP. '.$row->nip_spesimen);
		
		$this->pdf->Text(20, 225, 'Tembusan, Yth :');
		$this->pdf->Text(20, 230, '1. Kepala Kantor Cabang PT. Taspen (Persero) di '.$row->nama_taspen);
		$this->pdf->Text(20, 235, '2. Direktur Pensiun PNS dan Pejabat Negara BKN di Jakarta');
		
		
		$this->pdf->Output('cetakSuratMutasiKeluarga.pdf', 'D');
	}	
	
	function _cetakSK($data)
	{
		$layanan             		= $data['layanan'];
		$result						= $this->entry->getEntryOneTaspen($data);
		$row						= $result->row();
		
		switch($layanan){			
			case 16:
				$name   = 'Janda/Duda';
				if($row->jd_dd_status == 1)
				{
					$lname  = 'DD.ALM';
				}
				else
				{
					$lname  = 'JD.ALM';
				}
			break;
			case 17:
				$name  = 'Yatim';	
				$lname  = 'YT.ALM';
			break;
		}		
		
		
      
				
		$this->load->library('PDF', array());	
		$this->pdf->setPrintHeader(false);
		$this->pdf->setPrintFooter(false);		
		
		$this->pdf->SetAutoPageBreak(TRUE, 0);
		
		$this->pdf->SetFont('freeSerif', '', 8);
		$this->pdf->AddPage('L', 'FOLIO', false, false);
		
		
		$this->pdf->Text(41, 37, 'KEPUTUSAN KEPALA BADAN KEPEGAWAIAN NEGARA');
		$this->pdf->Text(50, 40, 'NOMOR: '.$row->usul_no_persetujuan);
		$this->pdf->Text(48, 45, 'KEPALA BADAN KEPEGAWAIAN NEGARA');
		
		
		$this->pdf->Text(5, 50, 'Menimbang');
		$this->pdf->Text(30, 50, ':');
		$this->pdf->Text(33, 50, '1. ');
		$text1='bahwa Pegawai Negeri Sipil/pensiunan Pegawai Negeri Sipil *) atas nama Saudara  '.$row->nama_pns.' NIP/NP '.$row->nip.'  telah meninggal dunia pada tanggal '.$row->meninggal;
		$this->pdf->writeHTMLCell(125,'',36,50,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(33, 58, '2. ');
		$text1='bahwa yang namanya tercantum dalam keputusan ini, memenuhi syarat untuk diberikan pensiun '.$name;
		$this->pdf->writeHTMLCell(125,'',36,58,$text1,0,0,false,false,'J',true);
		
		
		$this->pdf->Text(5, 65, 'Mengingat');
		$this->pdf->Text(30, 65, ':');
		$this->pdf->Text(33, 65, '1. Undang- Undang Nomor 11 Tahun 1969;');
		$this->pdf->Text(33, 69, '2. Undang-Undang Nomor 8 Tahun 1974 jo, Undang-Undang Nomor 5 Tahun 2014;');
		$this->pdf->Text(33, 73, '3. Peraturan Pemerintah Nomor 7 tahun 1977 jo. Peraturan Pemerintah Nomor 18 Tahun 2019;');
		$this->pdf->Text(33, 77, '4. Peraturan Pemerintah Nomor 32 Tahun 1979 jo. Peraturan Pemerintah Nomor 19 Tahun 2013;');
		$this->pdf->Text(33, 81, '5. Peraturan Pemerintah Nomor 99 Tahun 2000 jo. Peraturan Pemerintah Nomor 12 Tahun 2002;');
		$this->pdf->Text(33, 85, '6. Peraturan Pemerintah Nomor 9 Tahun 2003 jo Peraturan Pemerintah Nomor 63 Tahun 2009;');
		$this->pdf->Text(33, 89, '7. Surat Kepala BKN Nomor WK-26-30/V33-5/99 Tanggal 30 Januari 2012;');
		
		
		
		$this->pdf->Text(70, 99, 'MEMUTUSKAN');
		$this->pdf->Text(5, 103, 'Menetapkan');
		$this->pdf->Text(30, 103, ':');
		$this->pdf->Text(5, 106, 'PERTAMA');
		$this->pdf->Text(30, 106, ':');
		
		$text1='Kepada yang namanya tercantum dalam lajur 1 terhitung mulai tanggal tersebut dalam lajur 9, diberikan pensiun pokok sebulan sebesar tersebut dalam lajur 11 keputusan ini.';
		$this->pdf->writeHTMLCell(125,'',33,106,$text1,0,0,false,false,'J',true);
		
		$tbl = <<<EOD
<table width="50%" cellspacing="0" cellpadding="1" border="1">
    <tr>
        <td width="25px;" align="center">1</td>
        <td width="125px;"> NAMA</td>
        <td width="250px;"> $row->nama_janda_duda</td>
		<td width="50px;"> $lname</td>
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

        $this->pdf->SetXY(5, 116);
		$this->pdf->writeHTML($tbl, true, false, false, false, '');
		
		$this->pdf->Text(5, 170, 'KEDUA');
		$this->pdf->Text(33, 170, ':');
		$text1='Mencatat bahwa anak penerima pensiun tersebut di atas pada akhir bulan terdiri dari:';
		$this->pdf->writeHTMLCell(165,'',41,170,$text1,0,0,false,false,'J',true);
		
		$tbl ='<table width="42%" cellspacing="0" cellpadding="1" border="1">
    <tr>
        <th width="25px;" align="center">NO</th>
        <th width="152px;" align="center"> NAMA</th>
        <th align="center"> TGL LAHIR</th>
		<th align="center"  width="125px;"> NAMA<br/> AYAH/IBU</th>
		<th align="center"> KETERANGAN</th>
    </tr>';
	
	if(!empty($row->nama_anak))
	{	
		$no =1;
		foreach($result->result() as $value){	
			$tbl .='<tr>
				<td align="center"> '.$no.'</td>
				<td> '.$value->nama_anak.'</td>
				<td align="center"> '.$value->tgl_lahir_anak.'</td>
				<td align="center"> '.$value->nama_ayah.'/'.$value->nama_ibu.'</td>
				<td align="center"> '.$value->keterangan.'</td>
			</tr>';
			$no++;
		}
	}
	else
	{
		$tbl .='<tr>
			<td height="65px;"></td>
			<td height="65px;"></td>
			<td height="65px;"></td>
			<td height="65px;"></td>
			<td height="65px;"></td>
		</tr>';
	}
    $tbl .='</table>';
		$this->pdf->SetXY(5, 175);
		$this->pdf->writeHTML($tbl, true, false, false, false, '');
		
		$this->pdf->Text(170, 32, 'KETIGA');
		$this->pdf->Text(190, 32, ':');
		$this->pdf->Text(193, 32, 'Pembayaran pensiun janda/duda tersebut dilakukan dengan ketentuan:');
		$this->pdf->Text(193, 36, 'a.');
		$text1='Pemberian dan pembayaran pensiun janda/duda dihentikan akhir bulan janda/duda yang bersangkutan menikah lagi atau berakhir apabila meninggal dunia dan tidak terdapat lagi anak yang memenuhi syarat untuk menerima pensiun.';
		$this->pdf->writeHTMLCell(125,'',196,36,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(193, 48, 'b.');
		$text2='Jika janda/duda menikah lagi atau meninggal dunia, selama masih terdapat anak/anak-anak
		yang berusia di bawah 25 tahun tidak berpenghasilan sendiri belum pernah menikah, pensiun janda/duda 
		itu dibayarkan kepada dan atas nama anak pertama tersebut di atas untuk kepentingan anak-anak lainnya 
		terhitung mulai bulan berikutnya terjadinya pernikahan/kematian';
		$this->pdf->writeHTMLCell(125,'',196,48,$text2,0,0,false,true,'J',true);
		
		$text2='Khusus untuk janda apabila janda yang bersangkutan kemudian bercerai lagi, maka pensiun janda yang pembayarannya telah dihentikan, dibayarkan kembali mulai bulan berikutnya perceraian itu berlaku sah.';
		$this->pdf->writeHTMLCell(125,'',196,64,$text2,0,0,false,true,'J',true);
		 
		$this->pdf->Text(170, 80, 'KEEMPAT');
		$this->pdf->Text(190, 80, ':');
		$text2='Di atas pensiun pokok tersebut diberikan tunjangan keluarga dan tunjangan pangan yang berlaku bagi Pegawai Negeri Sipil dan tunjangan-tunjangan lain yang berlaku bagi penerima pensiun.';
		$this->pdf->writeHTMLCell(125,'',193,80,$text2,0,0,false,true,'J',true);
		
		$this->pdf->Text(170, 90, 'KELIMA');
		$this->pdf->Text(190, 90, ':');
		$text2='Apabila dikemudian hari ternyata terdapat kekeliruan dalam keputusan ini, akan diadakan perbaikan dan perhitungan kembali sebagaimana mestinya.';
		$this->pdf->writeHTMLCell(125,'',193,90,$text2,0,0,false,true,'J',true);
		
		$text2='Asli Keputusan ini diberikan kepada yang bersangkutan dengan alamat : '.$row->alamat;
		$this->pdf->writeHTMLCell(125,'',193,100,$text2,0,0,false,true,'J',true);
		
		$this->pdf->Text(170, 115, 'Sebagai bukti sah untuk dipergunakan sebagaimana mestinya.');
		
		$this->pdf->Text(260, 125, 'Ditetapkan di');
		$this->pdf->Text(280, 125, ':');
		$this->pdf->Text(285, 125, 'MANADO');
		
		$this->pdf->Text(260, 130, 'Pada Tanggal');
		$this->pdf->Text(280, 130, ':');
		$this->pdf->Text(285, 130, $row->persetujuan_tgl);
		
		$this->pdf->Text(255, 135, 'an.');
		$this->pdf->Text(260, 135, 'KEPALA BADAN KEPEGAWAIAN NEGARA');
		$this->pdf->writeHTMLCell(60,'',260,139,strtoupper($row->jabatan_spesimen),0,0,false,false,'J',true);
		$this->pdf->Text(260, 170, $row->nama_spesimen.(!empty($row->glrblk) ? ','.$row->glrblk : ''));
		$this->pdf->Text(260, 174, 'NIP. '.$row->nip_spesimen);
		
	    $this->pdf->Text(170, 185, 'Tembusan, Keputusan ini disampaikan kepada :');
		$this->pdf->Text(170, 190, '1. Kepala Kantor Cabang PT.TASPEN (PERSERO)/PT.ASABRI (PERSERO) di '.$row->nama_taspen);
		$this->pdf->Text(170, 195, '2. Direktur Pensiun BKN di Jakarta;');
		$this->pdf->Text(170, 200, '3. Pertinggal ');
		
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
				
		$this->pdf->write2DBarcode($code, 'QRCODE,Q', 172, 155, 25, 25, $style, 'N');		
		
		
		$this->pdf->Output('cetakSuratKeputusanJandaDudaYatim.pdf', 'D');
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
