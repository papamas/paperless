<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pensiun extends MY_Controller {
	
	var $menu_id    = 22;
	var $allow 		= FALSE;
	
	function __construct()
	{
	    parent::__construct();		
	    $this->load->library(array('Auth','Menu','form_validation'));
		$this->load->model('laporan/laporan_model', 'laporan');
		$this->allow = $this->auth->isAuthMenu($this->menu_id);
	} 
	
	public function index()
	{
			
		$data['menu']     =  $this->menu->build_menu();
		
		$data['message']  = '';
		$data['lname']    =  $this->auth->getLastName();        
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		
		$data['layanan']  = $this->laporan->getPelayanan();
		$data['instansi']  = $this->laporan->getInstansi();
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('pensiun/index',$data);
	}
	
	public function getLaporan()
	{
		$this->form_validation->set_rules('instansi', 'instansi', 'trim');
		$this->form_validation->set_rules('layanan', 'layanan', 'trim|required');
		$this->form_validation->set_rules('reportrange', 'Periode', 'required');
		
		$instansi  				= $this->input->post('instansi');
		$layanan    			= $this->input->post('layanan');
		$reportrange    		= $this->input->post('reportrange');
		
		if(!empty($reportrange))
		{	
			$xreportrange       	= explode("-",$reportrange);
			$data['startdate']  	= $xreportrange[0];
			$data['enddate']		= $xreportrange[1];
		}
		
		if($this->form_validation->run() == FALSE)
		{
				
			$data['menu']     =  $this->menu->build_menu();			
			$data['lname']    =  $this->auth->getLastName();        
			$data['name']     =  $this->auth->getName();
			$data['jabatan']  =  $this->auth->getJabatan();
			$data['member']	  =  $this->auth->getCreated();
			$data['avatar']	  =  $this->auth->getAvatar();
			
			$data['layanan']  = $this->laporan->getPelayanan();
			$data['instansi']  = $this->laporan->getInstansi();
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('pensiun/index',$data);
		}
		else
		{	
		    if($instansi != 9)
			{	
				$q                = $this->laporan->getLaporan($this->input->post());
				// creating xls file
				$now              = date('dmYHis');
				$filename         = "LAPORAN BIDANG PENSIUN ".$now.".xls";
				
				header('Pragma:public');
				header('Cache-Control:no-store, no-cache, must-revalidate');
				header('Content-type:application/vnd.ms-excel');
				header('Content-Disposition:attachment; filename='.$filename);                      
				header('Expires:0'); 
				
				$html  = 'LAPORAN BIDANG PENSIUN<br/>';		
				$html .= 'Periode LAPORAN : '.$data['startdate'].' sampai dengan '.$data['enddate'].'<br/>';	
				$html .= '<style> .str{mso-number-format:\@;}</style>';
				$html .= '<table border="1">';					
				$html .='<tr>
							<th>NO</th>
							<th>NIP</th>
							<th>NAMA</th>
							<th>INSTANSI</th>
							<th>LAYANAN</th>
							<th>USUL</th>
							<th>TANGGAL USUL</th>
							<th>TANGGAL VERIFIKATOR</th>
							<th>STATUS</th>
							<th>ALASAN</th>						
							<th>ENTRY</th>						
							
							'; 
				$html 	.= '</tr>';
				if($q->num_rows() > 0){
					$i = 1;		        
					foreach ($q->result() as $r) {
						$html .= "<tr><td>$i</td>";				
						$html .= "<td class=str>{$r->nip}</td>";	
						$html .= "<td>{$r->nama}</td>";					
						$html .= "<td>{$r->instansi}</td>";	
						$html .= "<td>{$r->layanan_nama}</td>";	
						$html .= "<td>{$r->agenda_nousul}</td>";	
						$html .= "<td>{$r->agenda_timestamp}</td>";	
						$html .= "<td>{$r->verify_date}</td>";
						$html .= "<td>{$r->nomi_status}".'<br/>'."{$r->verif_name}</td>";
						$html .= "<td>{$r->nomi_alasan}</td>";				
						$html .= "<td>{$r->entry_name}".'<br/>'."{$r->entry_date}</td>";									
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
			else
			{
				$q                = $this->laporan->getLaporanTaspen($this->input->post());            		
				// creating xls file
				$now              = date('dmYHis');
				$filename         = "LAPORAN BIDANG PENSIUN ".$now.".xls";
				
				header('Pragma:public');
				header('Cache-Control:no-store, no-cache, must-revalidate');
				header('Content-type:application/vnd.ms-excel');
				header('Content-Disposition:attachment; filename='.$filename);                      
				header('Expires:0'); 
				
				$html  = 'LAPORAN BIDANG PENSIUN<br/>';		
				$html .= 'Periode LAPORAN : '.$data['startdate'].' sampai dengan '.$data['enddate'].'<br/>';	
				$html .= '<style> .str{mso-number-format:\@;}</style>';
				$html .= '<table border="1">';					
				$html .='<tr>
							<th>NO</th>
							<th>NIP</th>
							<th>NAMA PNS</th>
							<th>NAMA</th>
							<th>INSTANSI</th>
							<th>LAYANAN</th>
							<th>USUL</th>
							<th>TANGGAL USUL</th>
							<th>TANGGAL VERIFIKATOR</th>
							<th>SPESIMEN</th>
							<th>STATUS</th>
							<th>ALASAN</th>						
							<th>ENTRY</th>						
							
							'; 
				$html 	.= '</tr>';
				if($q->num_rows() > 0){				
					$i = 1;		        
					foreach ($q->result() as $r) {
						$html .= "<tr><td>$i</td>";				
						$html .= "<td class=str>{$r->nip}</td>";	
						$html .= "<td>{$r->nama_pns}</td>";
						$html .= "<td>{$r->nama_janda_duda}</td>";
						$html .= "<td>TASPEN</td>";	
						$html .= "<td>{$r->layanan_nama}</td>";	
						$html .= "<td>{$r->nomor_usul}</td>";	
						$html .= "<td>{$r->tgl_usul}</td>";	
						$html .= "<td>{$r->usul_verif_date}</td>";
						$html .= "<td>{$r->usul_verif_name}</td>";
						$html .= "<td>{$r->usul_status}</td>";					
						$html .= "<td>{$r->usul_alasan}</td>";				
						$html .= "<td>{$r->usul_entry_name}".'<br/>'."{$r->usul_entry_date}</td>";									
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
	}	
	
	public function pengeluaran()
	{
		$data['menu']     =  $this->menu->build_menu();
		
		$data['message']  = '';
		$data['lname']    =  $this->auth->getLastName();        
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		$data['spesimen'] =  $this->laporan->getSpesimen_pengeluaran();
		$data['tab1']	  = 'active';
		$data['tab2']	  = '';
		
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('pensiun/pengeluaran',$data);
	}	
	
	public function getPengeluaran()
	{
		$this->form_validation->set_rules('nomorUsul', 'Nomor Usul', 'required');
		$this->form_validation->set_rules('nomorPengeluaran', 'Nomor Pengeluaran', 'required');
		$this->form_validation->set_rules('spesimenPengeluaran', 'Spesimen Pengeluaran', 'required');
		$this->form_validation->set_rules('checkSatker', 'Check Satker', 'trim');
		$this->form_validation->set_rules('tandaTerima', 'Tanda Terima', 'trim');
		$this->form_validation->set_rules('pilihanPengeluaran', 'Pilihan Pengeluaran', 'trim');
				
		$check		= $this->input->post('checkSatker');
				
		if($this->form_validation->run() == FALSE)
		{
			$data['menu']     =  $this->menu->build_menu();		
			$data['message']  = '';
			$data['lname']    =  $this->auth->getLastName();        
			$data['name']     =  $this->auth->getName();
			$data['jabatan']  =  $this->auth->getJabatan();
			$data['member']	  =  $this->auth->getCreated();
			$data['avatar']	  =  $this->auth->getAvatar();
			$data['spesimen'] =  $this->laporan->getSpesimen_pengeluaran();
			$data['tab1']	  = 'active';
			$data['tab2']	  = '';
			
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('pensiun/pengeluaran',$data);
		}
		else
		{	
			$add				= $this->laporan->addPengeluaran();
			$pengeluaran		= $this->laporan->getPengeluaran();
			$row 				= $pengeluaran->first_row();
			$spesimen           = $this->laporan->getSpesimen_pengeluaran_by_nip();
			
						
			$this->load->library('PDF', array());	
			
			$this->pdf->setPrintHeader(true);
			$this->pdf->setPrintFooter(false);	
			
			$this->pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);
			$this->pdf->SetFont('freeSerif', '', 11);
			
			if($pengeluaran->num_rows() > 0)
			{					
				
				
				$this->pdf->AddPage('P', 'A4');
				// set style for barcode
				$style = array(
					'border' => false,
					'padding' => 0,
					'fgcolor' => array(0, 0, 0),
					'bgcolor' => false, //array(255,255,255)
					'module_width' => 1, // width of a single module in points
					'module_height' => 1 // height of a single module in points
				);
				
				$code  = ' Pertek an.'.$row->PNS_PNSNAM.', dkk Nomor '.$row->nomor_surat ;					
				$this->pdf->write2DBarcode($code, 'QRCODE,Q', 10, 10, 25, 25, $style, 'N'); 
						
				$this->pdf->Text(5, 50, 'Nomor');
				$this->pdf->Text(25, 50, ':');
				$this->pdf->Text(28, 50, $row->nomor_surat);
				
				$this->pdf->Text(150, 50, 'Manado,'.$row->sekarang);
				
				$this->pdf->Text(5, 54, 'Lampiran');
				$this->pdf->Text(25, 54, ':');
				$this->pdf->Text(28, 54, '-');
				
				$this->pdf->Text(5, 58, 'Perihal');
				$this->pdf->Text(25, 58, ':');
				$this->pdf->writeHTMLCell(70,55,28,58,'Penyampaian Asli Pertimbangan Teknis an. '.$row->PNS_PNSNAM.' ,dkk',0,0,false,true,'J',true);
				
				if(strlen($row->satker) > 41 || strlen($row->nama_jabatan) > 41 )
				{
				   $yd	=75;
				}
				else
				{
					$yd  =70;						
				}
					
				$this->pdf->Text(130, 60, 'Kepada');
				$this->pdf->Text(120, 65, 'Yth.');
				$this->pdf->writeHTMLCell(75,65,130,65,(empty($check) ? $row->nama_jabatan : $row->satker),0,0,false,true,'J',true);
				$this->pdf->writeHTMLCell(75,65,130,$yd,$row->nama_daerah,0,0,false,true,'J',true);
				$this->pdf->Text(130, $yd+5, 'Di');	
				$this->pdf->Text(135, $yd+10, (empty($check) ? $row->lokasi_daerah : $row->lokasi));
				
				$text='Berkenaan dengan surat Saudara Nomor : '.$row->agenda_nousul.' Tanggal '.$row->tgl_usul.' , bersama ini disampaikan Asli Keputusan Kepala Badan Kepegawaian Negara Tentang Pensiun PNS atas nama : ';
				$this->pdf->Text(10, 100, '1.');		
				$this->pdf->writeHTMLCell(180,125,15,100,$text,0,0,false,true,'J',true);			
				
				
				$tbl1 ='
						<table  cellspacing="0" cellpadding="1" border="1">
							
								<tr>
									<td width="40px;" align="center">NO</td>
									<td width="225px;"> NAMA/NIP</td>
									<td width="125px;"> NOMOR/TGL PERTEK</td>
									<td>KET</td>
								</tr>
							';		
				
						
				$tbl1 .='</table>';
				
				$header = array('NO', 'NAMA/NIP', 'NOMOR/TGL PERTEK', 'KET');
				$w 		= array(15, 80, 60, 15);

				$this->pdf->SetFillColor(224, 235, 255);
				
				$this->pdf->SetXY(15, 112);
				$num_headers = count($header);
				for($i = 0; $i < $num_headers; ++$i) {
					$this->pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
				}
						
				$this->pdf->SetFillColor(255, 255, 255);
				$starty= 119;
				$y=1;
							
						
				foreach($pengeluaran->result() as $value)
				{
					
					
					if(strlen($value->PNS_PNSNAM) > 31)
					{
						$ynama='';
						$xnama  = explode(' ',$value->PNS_PNSNAM,-1);
						for($x=0;$x < count($xnama);$x++)
						{
							$ynama   .= $xnama[$x].' ';
						}
						
						$nama   = $ynama;
					}
					else
					{
						$nama  = $value->PNS_PNSNAM;	
					}
						if($pengeluaran->num_rows() > 13)
						{	
							if($y % 16 == 0 )
							{	
								$this->pdf->AddPage('P', 'A4');
								$starty = 50;				
								$this->pdf->MultiCell($w[0], 12, $y, 1, 'C', 1, 0, 15,$starty, true, 1, true, true, 0);
								$this->pdf->MultiCell($w[1], 12, $nama.'<br>'.$value->PNS_NIPBARU,1, 'J', 1, 0, 30,$starty, true, 1, true, true, 0);
								$this->pdf->MultiCell($w[2], 12, $value->nomi_persetujuan.'<br>'.$value->tgl_acc,1, 'J', 1, 0, 110,$starty, true, 1, true, true, 0);
								$this->pdf->MultiCell($w[3], 12, '' ,1, 'J', 1, 0, 170,$starty, true, 1, true, true, 0); 
								
								// set style for barcode
								$style = array(
									'border' => false,
									'padding' => 0,
									'fgcolor' => array(0, 0, 0),
									'bgcolor' => false, //array(255,255,255)
									'module_width' => 1, // width of a single module in points
									'module_height' => 1 // height of a single module in points
								);
								
								$code  = ' Pertek an.'.$row->PNS_PNSNAM.', dkk Nomor '.$row->nomor_surat ;					
								$this->pdf->write2DBarcode($code, 'QRCODE,Q', 10, 10, 25, 25, $style, 'N'); 
										
							}
							else
							{
								
								$this->pdf->SetXY(15,$starty);					
								$this->pdf->MultiCell($w[0], 12, $y,1, 'C', 1, 0, 15,$starty, true, 1, true, true, 0);
								$this->pdf->MultiCell($w[1], 12, $nama.'<br>'.$value->PNS_NIPBARU,1, 'J', 1, 0, 30,$starty, true, 3, true, true, 0);
								$this->pdf->MultiCell($w[2], 12, $value->nomi_persetujuan.'<br>'.$value->tgl_acc,1, 'J', 1, 0, 110,$starty, true, 1, true, true, 0);
								$this->pdf->MultiCell($w[3], 12, '',1, 'J', 1, 0, 170,$starty, true, 1, true, true, 0);
									
								
							}
						}
						else
						{
							if($y % 12 == 0 )
							{
											
								$this->pdf->AddPage('P', 'A4');
								$starty = 50;				
								$this->pdf->MultiCell($w[0], 12, $y, 1, 'C', 1, 0, 15,$starty, true, 1, true, true, 0);
								$this->pdf->MultiCell($w[1], 12, $nama.'<br>'.$value->PNS_NIPBARU,1, 'J', 1, 0, 30,$starty, true, 1, true, true, 0);
								$this->pdf->MultiCell($w[2], 12, $value->nomi_persetujuan.'<br>'.$value->tgl_acc,1, 'J', 1, 0, 110,$starty, true, 1, true, true, 0);
								$this->pdf->MultiCell($w[3], 12, '',1, 'J', 1, 0, 170,$starty, true, 1, true, true, 0); 
								
								// set style for barcode
								$style = array(
									'border' => false,
									'padding' => 0,
									'fgcolor' => array(0, 0, 0),
									'bgcolor' => false, //array(255,255,255)
									'module_width' => 1, // width of a single module in points
									'module_height' => 1 // height of a single module in points
								);
								
								$code  = ' Pertek an.'.$row->PNS_PNSNAM.', dkk Nomor '.$row->nomor_surat ;					
								$this->pdf->write2DBarcode($code, 'QRCODE,Q', 10, 10, 25, 25, $style, 'N'); 
										
							}
							else
							{
								$this->pdf->SetXY(15,$starty);					
								$this->pdf->MultiCell($w[0], 12, $y,1, 'C', 1, 0, 15,$starty, true, 1, true, true, 0);
								$this->pdf->MultiCell($w[1], 12, $nama.'<br>'.$value->PNS_NIPBARU,1, 'J', 1, 0, 30,$starty, true, 3, true, true, 0);
								$this->pdf->MultiCell($w[2], 12, $value->nomi_persetujuan.'<br>'.$value->tgl_acc,1, 'J', 1, 0, 110,$starty, true, 1, true, true, 0);
								$this->pdf->MultiCell($w[3], 12, '',1, 'J', 1, 0, 170,$starty, true, 1, true, true, 0);
									
							}	
						}
					
					
				
					$y++;
					$starty +=10;
					// flag status
					$this->laporan->update_out_status($value->agenda_id,$value->nip);
				}	
			
				$text ='2. Demikian atas kerjasamannya disampaikan terimakasih';
				$this->pdf->Text(10, $starty+5,$text);
			
			
			
				$text2='an.Kepala Kantor Regional XI Badan Kepegawaian Negara '.$spesimen->jabatan_spesimen;
				$this->pdf->writeHTMLCell(60,125,130,$starty+10,$text2,0,0,false,true,'L',true);
				
				$this->pdf->Text(130,$starty+40,$spesimen->glrdpn.''.ucwords(strtolower($spesimen->nama_spesimen)).''.(!empty($spesimen->glrblk) ? ','.$spesimen->glrblk :''));
				$this->pdf->Text(130,$starty+45, 'NIP. '.$spesimen->nip_spesimen);
				
				if($this->input->post('tandaTerima') == 1)
				{		
					$tbl ='
						<table  cellspacing="0" cellpadding="1" border="1" nobr="true">
							<tr>
								<td width="80px;">Diterima</td>
								<td width="200px;"> MANADO</td>			
							</tr>
							<tr>
								<td>Pada Tanggal</td>
								<td></td>			
							</tr>
							<tr>
								<td>Nama</td>
								<td></td>			
							</tr>
							<tr>
								<td>NIP</td>
								<td></td>			
							</tr>
							<tr>
								<td>Jabatan</td>
								<td></td>			
							</tr>
							<tr>
								<td height="55px">Tanda Tangan</td>
								<td></td>			
							</tr></table>';		
					$this->pdf->SetXY(10,$starty+15);
					$this->pdf->writeHTML($tbl, true, false, false, false, '');	
				}
				
			}		
		
			$this->pdf->Output('cetakPengeluaran.pdf', 'D');
		}	
	}	
	
	public function getAgenda()
	{
	    $search   		= $this->input->get('q');	    
    	$query			= $this->laporan->getAgenda($search);
	    $ret['results'] = $query->result_array();
	    echo json_encode($ret);
	}	
	
	public function getNomorPengeluaran()
	{
		$search   		= $this->input->get('q');	    
    	$query			= $this->laporan->getPengeluaran_byid($search);
		if($query->num_rows() > 0)
		{
			$row					= $query->row();
			$data['last_number']	= $row->last_number;
			$data['ada']			= TRUE;
		}
		else
		{
			$data['last_number']	= NULL;
			$data['ada']			= FALSE;
		}
	    echo json_encode($data);
	}

	
	
	public function getUsulTaspen()
	{
		$search   		= $this->input->get('q');	    
    	$query			= $this->laporan->getUsulTaspen($search);
	    $ret['results'] = $query->result_array();
	    echo json_encode($ret);
	}
	
	public function getPengeluaranTaspen()
	{
		$this->form_validation->set_rules('usulTaspen', 'Nomor Usul', 'required');
		$this->form_validation->set_rules('nomorPengeluaranTaspen', 'Nomor Pengeluaran', 'required');
		$this->form_validation->set_rules('spesimenPengeluaranTaspen', 'Spesimen Pengeluaran', 'required');
		$this->form_validation->set_rules('tandaTerimaTaspen', 'Tanda Terima', 'trim');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['menu']     =  $this->menu->build_menu();		
			$data['message']  = '';
			$data['lname']    =  $this->auth->getLastName();        
			$data['name']     =  $this->auth->getName();
			$data['jabatan']  =  $this->auth->getJabatan();
			$data['member']	  =  $this->auth->getCreated();
			$data['avatar']	  =  $this->auth->getAvatar();
			$data['spesimen'] =  $this->laporan->getSpesimen_pengeluaran();
			$data['tab1']	  = '';
			$data['tab2']	  = 'active';
			
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('pensiun/pengeluaran',$data);
		}
		else
		{
			$usul_id	= $this->input->post('usulTaspen');
			$row		= $this->laporan->getUsulTaspen_byid($usul_id)->row();
			
			if($row->layanan_id == 15)
			{
				$this->cetakMk();
			}
			else
			{
				$this->cetakJd();
			}		
		}
	}	
	
	function cetakMk()
	{
		$add				        = $this->laporan->addPengeluaranTaspen();
		$row						= $this->laporan->getEntryOneTaspen()->row();
		$spesimen                   = $this->laporan->getSpesimen_pengeluaranTaspen_by_nip();
		
		
		$this->load->library('PDF', array());	
		$this->pdf->setPrintHeader(true);
		$this->pdf->setPrintFooter(false);		
		
		$this->pdf->SetAutoPageBreak(false, 0);
		$this->pdf->AddPage('P', 'A4', false, false);
		
		$this->pdf->SetFont('freeSerif', '', 12);
		$this->pdf->Text(150, 50, 'Manado, '.$row->persetujuan_tgl);
		
		$this->pdf->Text(5, 55, 'Nomor ');
		$this->pdf->Text(25, 55, ': '.$row->usul_no_persetujuan);
		
		$this->pdf->Text(5, 60, 'Lampiran ');
		$this->pdf->Text(25, 60, ':  ');
		
		$this->pdf->Text(5, 65, 'Perihal ');
		$this->pdf->Text(25, 65, ': Pengambilan formulir ');
		$this->pdf->Text(27, 70, 'Model A/II/1969 Pens ');
		
		$this->pdf->Text(140, 60, 'Kepada ');
		$this->pdf->Text(130, 65, 'Yth.');
		$this->pdf->Text(140, 65, $row->nama_pns);
		$this->pdf->Text(140, 70, 'NIP.'.$row->nip);
		$this->pdf->Text(130, 75, 'D/a.');
		$text1=$row->alamat;
		$this->pdf->writeHTMLCell(70,'',140,75,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(25, 100, '1.');
		$text1='Menunjuk Surat dari Ka. PT. Taspen (persero) Cabang '.$row->nama_taspen.'  Nomor '.$row->nomor_usul.' Perihal permohonan Saudara Tanggal '.$row->atgl_usul.' untuk mengesahkan/mencatat mutasi keluarga, bersama ini kami kirimkan kembali Formulir Model A/II/Pens, tentang pendataran Isteri/Suami/Anak sebagai yang berhak menerima pensiun Janda/Duda yang telah disahkan/dicatat.';
		$this->pdf->writeHTMLCell(175,'',30,100,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(25, 125, '2.');
		$text1='Mengingat bahwa bukti pendaftaran tersebut sangat penting sebagai kelengkapan permohonan pensiun Janda/Duda sebagai Isteri/Suami/Anak/Saudara, kami harapkan agar formulir tersebut disimpan dengan baik.';
		$this->pdf->writeHTMLCell(175,'',30,125,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(25, 145, '3.');
		$text1='Perlu kami jelaskan bahwa pendaftaran yang saudara lakukan telah melebihi batas waktu 1 (satu) tahun setelah terjadinya perkawinan tersebut sebagaimana ditetapkan dalam pasal 19 ayat 6 Undang-Undang Nomor 11 Tahun 1969, maka pendaftaran tersebut hanya kami catat, tetapi tidak disahkan.';
		$this->pdf->writeHTMLCell(175,'',30,145,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(25, 165, '4.');
		$text1='Demikian untuk dipergunakan sebagaimana mestinya.';
		$this->pdf->writeHTMLCell(175,'',30,165,$text1,0,0,false,false,'J',true);
		
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
		$text2='Kepala Kantor Regional XI Badan Kepegawaian Negara '.$spesimen->jabatan;
		$this->pdf->writeHTMLCell(75,125,130,175,$text2,0,0,false,true,'L',true);
		
		$this->pdf->Text(130, 215, $spesimen->glrdpn.''.ucwords(strtolower($spesimen->nama_spesimen)).''.(!empty($spesimen->glrblk) ? ','.$spesimen->glrblk :''));
		$this->pdf->Text(130, 220, 'NIP.'.$spesimen->nip);
		
		$this->pdf->Text(20, 225, 'Tembusan, Yth :');
		$this->pdf->Text(20, 230, '1. Kepala Kantor Cabang PT. Taspen (Persero) di '.$row->nama_taspen);
		$this->pdf->Text(20, 235, '2. Direktur Pensiun PNS dan Pejabat Negara BKN di Jakarta');
		
		if($this->input->post('tandaTerimaTaspen') == 1)
		{
			$tbl ='
				<table  cellspacing="0" cellpadding="1" border="1" nobr="true">
					<tr>
						<td width="80px;">Diterima</td>
						<td width="200px;"> MANADO</td>			
					</tr>
					<tr>
						<td>Pada Tanggal</td>
						<td></td>			
					</tr>
					<tr>
						<td>Nama</td>
						<td></td>			
					</tr>
					<tr>
						<td>NIP</td>
						<td></td>			
					</tr>
					<tr>
						<td>Jabatan</td>
						<td></td>			
					</tr>
					<tr>
						<td height="55px">Tanda Tangan</td>
						<td></td>			
					</tr></table>';		
			$this->pdf->SetXY(15,245);
			$this->pdf->writeHTML($tbl, true, false, false, false, '');			
		}
		
		$this->pdf->Output('cetakSuratMutasiKeluarga.pdf', 'D');
	}	
	
	function cetakJd()
	{
	    $add				        = $this->laporan->addPengeluaranTaspen();
		$row						= $this->laporan->getEntryOneTaspen()->row();
		$spesimen                   = $this->laporan->getSpesimen_pengeluaranTaspen_by_nip();
		
		if($row->layanan_id == 16)
		{
			$label  = 'JD.ALM';
		}
		else
		{
			$label  = 'YT.ALM';
		}
		
		$this->load->library('PDF', array());	
			
		$this->pdf->setPrintHeader(true);
		$this->pdf->setPrintFooter(false);	
		
		$this->pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);
		$this->pdf->AddPage('P', 'A4', false, false);
		$this->pdf->SetFont('freeSerif', '', 11);

		$this->pdf->Text(17, 60, 'Kepada');
		$this->pdf->Text(10, 65,  'Yth:');
		$this->pdf->Text(17, 65, $row->nama_janda_duda);
		$this->pdf->Text(100, 65,$label );
		$this->pdf->Text(17, 70, $row->nama_pns);
		$this->pdf->Text(17, 75, 'NIP. '.$row->nip);
		$text1= $row->alamat;
		$this->pdf->writeHTMLCell(60,'',17,80,$text1,0,0,false,false,'J',true);
		
		$this->pdf->SetFont('freeSerif', 'B', 12);
		$text1='SURAT PENGANTAR';
		$this->pdf->writeHTMLCell(170,'',20,100,$text1,0,0,false,false,'C',true);
		
		$text1='Nomor : '.$row->nomor_surat;
		$this->pdf->writeHTMLCell(170,'',20,104,$text1,0,0,false,false,'C',true);

		$this->pdf->SetFont('freeSerif', '', 11);
        $this->pdf->Text(10, 115, '1. ');
		$text1='Bersama ini kami sampaikan Asli Keputusan Kepala Badan Kepegawaian Negara Nomor '.$row->usul_no_persetujuan.' Tanggal '.$row->persetujuan_tgl;
		$this->pdf->writeHTMLCell(170,'',15,115,$text1,0,0,false,false,'J',true);
		
		$this->pdf->Text(10, 125, '2. ');
		$text1='Demikian untuk digunakan sebagaimana mestinya';
		$this->pdf->writeHTMLCell(170,'',15,125,$text1,0,0,false,false,'J',true);
		
		// set style for barcode
		$style = array(
			'border' => false,
			'padding' => 0,
			'fgcolor' => array(0, 0, 0),
			'bgcolor' => false, //array(255,255,255)
			'module_width' => 1, // width of a single module in points
			'module_height' => 1 // height of a single module in points
		);
		
		$code  = ' Pengantar SK '.$row->nama_janda_duda.' Nomor : '.$row->nomor_surat ;					
		$this->pdf->write2DBarcode($code, 'QRCODE,Q', 15, 155, 25, 25, $style, 'N'); 
				
		
		$this->pdf->Text(125, 155, 'an.');
		$text2='Kepala Kantor Regional XI Badan Kepegawaian Negara '.$spesimen->jabatan;
		$this->pdf->writeHTMLCell(75,125,130,155,$text2,0,0,false,true,'L',true);
		
		$this->pdf->Text(130, 190, $spesimen->glrdpn.''.ucwords(strtolower($spesimen->nama_spesimen)).''.(!empty($spesimen->glrblk) ? ','.$spesimen->glrblk :''));
		$this->pdf->Text(130, 195, 'NIP. '.$spesimen->nip);
		
		$this->pdf->Text(15, 200, 'Tembusan, Yth :');
		$this->pdf->Text(15, 205, '1. Kepala Kantor Cabang PT. Taspen (Persero) di '.$row->nama_taspen);
		$this->pdf->Text(15, 210, '2. Pertinggal');
		
		if($this->input->post('tandaTerimaTaspen') == 1)
		{
			$tbl ='
				<table  cellspacing="0" cellpadding="1" border="1" nobr="true">
					<tr>
						<td width="80px;">Diterima</td>
						<td width="200px;"> MANADO</td>			
					</tr>
					<tr>
						<td>Pada Tanggal</td>
						<td></td>			
					</tr>
					<tr>
						<td>Nama</td>
						<td></td>			
					</tr>
					<tr>
						<td>NIP</td>
						<td></td>			
					</tr>
					<tr>
						<td>Jabatan</td>
						<td></td>			
					</tr>
					<tr>
						<td height="55px">Tanda Tangan</td>
						<td></td>			
					</tr></table>';		
			$this->pdf->SetXY(15,220);
			$this->pdf->writeHTML($tbl, true, false, false, false, '');			
		}
		$this->pdf->Output('pengantarJandaDudaYatim.pdf', 'D');
	}	
	
	public function getNomorPengeluaranTaspen()
	{
		$search   		= $this->input->get('q');	    
    	$query			= $this->laporan->getPengeluaranTaspen_byid($search);
		if($query->num_rows() > 0)
		{
			$row					= $query->row();
			$data['last_number']	= $row->last_number;
			$data['ada']			= TRUE;
		}
		else
		{
			$data['last_number']	= NULL;
			$data['ada']			= FALSE;
		}
	    echo json_encode($data);
	}
}
