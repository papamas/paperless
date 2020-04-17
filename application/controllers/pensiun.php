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

		
		if($this->form_validation->run() == FALSE)
		{
			$data['menu']     =  $this->menu->build_menu();		
			$data['message']  = '';
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
			$this->load->view('pensiun/pengeluaran',$data);
		}
		else
		{	
			$add				= $this->laporan->addPengeluaran();
			$pengeluaran		= $this->laporan->getPengeluaran();
			$row 				= $pengeluaran->first_row();		
			
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
				
				$this->pdf->Text(130, 60, 'Kepada');
				$this->pdf->Text(120, 65, 'Yth.');
				$this->pdf->writeHTMLCell(75,65,130,65,$row->nama_jabatan,0,0,false,true,'J',true);
				$this->pdf->writeHTMLCell(75,65,130,75,$row->nama_daerah,0,0,false,true,'J',true);
				$this->pdf->Text(130, 80, 'Di');	
				$this->pdf->Text(135, 85, $row->lokasi_daerah);
				
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
					
				}	
			
				$text ='2. Demikian atas kerjasamannya disampaikan terimakasih';
				$this->pdf->Text(10, $starty+5,$text);
			
			
			
				$text2='an.Kepala Kantor Regional XI Badan Kepegawaian Negara '.$row->jabatan_spesimen;
				$this->pdf->writeHTMLCell(60,125,130,$starty+10,$text2,0,0,false,true,'L',true);
				
				$this->pdf->Text(130,$starty+40,ucwords(strtolower($row->nama_spesimen)));
				$this->pdf->Text(130,$starty+45, 'NIP. '.$row->nip_spesimen);
				
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
}
