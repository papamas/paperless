<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mutasi extends MY_Controller {
	
	var $menu_id    = 26;
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
		$this->load->view('mutasi/index',$data);
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
				
			$data['menu']      =  $this->menu->build_menu();	
			$data['lname']     =  $this->auth->getLastName();        
			$data['name']      =  $this->auth->getName();
			$data['jabatan']   =  $this->auth->getJabatan();
			$data['member']	   =  $this->auth->getCreated();
			$data['avatar']	   =  $this->auth->getAvatar();
			
			$data['layanan']   = $this->laporan->getPelayanan();
			$data['instansi']  = $this->laporan->getInstansi();
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('mutasi/index',$data);
			
		}
		else
		{	
		
			$q                = $this->laporan->getLaporan($this->input->post());
		
			// creating xls file
			$now              = date('dmYHis');
			$filename         = "LAPORAN BIDANG MUTASI ".$now.".xls";
			
			header('Pragma:public');
			header('Cache-Control:no-store, no-cache, must-revalidate');
			header('Content-type:application/vnd.ms-excel');
			header('Content-Disposition:attachment; filename='.$filename);                      
			header('Expires:0'); 
			
			$html  = 'LAPORAN BIDANG MUTASI<br/>';		
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
	}
	
	public function pengeluaran()
	{
		$data['menu']     	=  $this->menu->build_menu();
		
		$data['message']  	= '';
		$data['lname']    	=  $this->auth->getLastName();        
		$data['name']     	=  $this->auth->getName();
        $data['jabatan']  	=  $this->auth->getJabatan();
		$data['member']	  	=  $this->auth->getCreated();
		$data['avatar']	  	=  $this->auth->getAvatar();
		$data['spesimen'] 	=  $this->laporan->getSpesimen_pengeluaran();
		
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('mutasi/pengeluaran',$data);
		
	}	
	
	public function getPengeluaran()
	{
		$this->form_validation->set_rules('nomorUsul', 'Nomor Usul', 'required');
		$this->form_validation->set_rules('nomorPengeluaran', 'Nomor Pengeluaran', 'required');
		$this->form_validation->set_rules('nomor', 'Nomor', 'required');
		$this->form_validation->set_rules('spesimenPengeluaran', 'Spesimen Pengeluaran', 'required');
		$this->form_validation->set_rules('checkSatker', 'Check Satker', 'trim');
		$this->form_validation->set_rules('satker', 'Satker', 'trim');
		$this->form_validation->set_rules('namaDaerah', 'Nama Daerah', 'trim');
		$this->form_validation->set_rules('lokasiSatker', 'Lokasi Satker', 'trim');
		
		$check				= $this->input->post('checkSatker');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['menu']     	=  $this->menu->build_menu();
		
			$data['message']  	= '';
			$data['lname']    	=  $this->auth->getLastName();        
			$data['name']     	=  $this->auth->getName();
			$data['jabatan']  	=  $this->auth->getJabatan();
			$data['member']	  	=  $this->auth->getCreated();
			$data['avatar']	  	=  $this->auth->getAvatar();
			$data['spesimen'] 	=  $this->laporan->getSpesimen_pengeluaran();
			
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('mutasi/pengeluaran',$data);
			}
		else
		{		
		
			$spesimen           = $this->laporan->getSpesimen_pengeluaran_by_nip();
			$pengeluaran		= $this->laporan->getPengeluaranMutasi();
			$row 				= $pengeluaran->first_row();
			
			// insert nomor pengeluaran
			$this->laporan->insertPengeluaranMutasi();
			
			$this->load->library('PDF', array());	
				
			$this->pdf->setPrintHeader(true);
			$this->pdf->setPrintFooter(false);	
			
			$this->pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);
			$this->pdf->SetFont('freeSerif', '', 11);
			
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
			
			$code  = $row->layanan_label.' an.'.$row->PNS_PNSNAM.', dkk Nomor '.$this->input->post('nomorPengeluaran') ;					
			$this->pdf->write2DBarcode($code, 'QRCODE,Q', 10, 10, 25, 25, $style, 'N'); 
			
			$this->pdf->Text(5, 50, 'Nomor');
			$this->pdf->Text(25, 50, ':');
			$this->pdf->Text(28, 50, $this->input->post('nomorPengeluaran'));
			
			$this->pdf->Text(150, 50, 'Manado, '.$row->sekarang);
			
			$this->pdf->Text(5, 54, 'Lampiran');
			$this->pdf->Text(25, 54, ':');
			$this->pdf->Text(28, 54, $pengeluaran->num_rows().' Kartu');
			
			$this->pdf->Text(5, 58, 'Perihal');
			$this->pdf->Text(25, 58, ':');
			$this->pdf->Text(28, 58, 'Penyampaian '.$row->layanan_label);
			
			$this->pdf->Text(5, 68, 'Kepada');
			$this->pdf->Text(5, 73, 'Yth.');
			$yd  =78;	
			$this->pdf->writeHTMLCell(150,65,15,73,(empty($check) ? $row->nama_jabatan : $this->input->post('satker')),0,0,false,true,'J',true);
			$this->pdf->writeHTMLCell(75,65,15,$yd,(empty($check) ? $row->nama_daerah : $this->input->post('namaDaerah')),0,0,false,true,'J',true);
			$this->pdf->Text(15, $yd+5, 'Di -');	
			$this->pdf->Text(15, $yd+10, (empty($check) ? $row->lokasi_daerah : $this->input->post('lokasiSatker')));
			
			$text='Bersama ini disampaikan dengan hormat '.strtoupper(strtolower($row->layanan_label)).' atas nama yang tersebut dibawah ini, untuk dipergunakan sebagaimana mestinya';
			$this->pdf->writeHTMLCell(200,125,5,100,$text,0,0,false,true,'L',true);
			
			$header = array('NO', 'NIP', 'NAMA', 'LAHIR', 'NO SERI');
			$w 		= array(10,40,90,30,30);

			$this->pdf->SetFillColor(224, 235, 255);
			//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M') {

			$this->pdf->SetXY(5, 112);
			$num_headers = count($header);
			for($i = 0; $i < $num_headers; ++$i) {
				$this->pdf->Cell($w[$i],7, $header[$i], 1, 0, 'C', 1,'',0);
			}
			
			$starty= 119;
			$y=1;
			// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false) {

			foreach($pengeluaran->result() as $value)
			{
				$ap3k    	= $this->laporan->getRealisasiAp3k($value->nip);
				
				$this->pdf->SetXY(15,$starty);					
				$this->pdf->MultiCell($w[0], 7, $y,1, 'C', 1, 2, 5,$starty, true, 1, true, true, 0);
				$this->pdf->MultiCell($w[1], 7, $value->nip,1, 'J', 1, 2, 15,$starty, true, 3, true, true, 0);
				$this->pdf->MultiCell($w[2], 7, $value->PNS_PNSNAM,1, 'J', 1, 2, 55,$starty, true, 1, true, true, 0);
				$this->pdf->MultiCell($w[3], 7, $value->PNS_TGLLHR,1, 'C', 1, 2, 145,$starty, true, 1, true, true, 0);
				$this->pdf->MultiCell($w[4], 7, $ap3k,1, 'C', 1, 2, 175,$starty, true, 1, true, true, 0);
				
				if($y % 20 == 0 )
				{
					$this->pdf->AddPage('P', 'A4');
					$starty = 50;		
					// set style for barcode
					$style = array(
						'border' => false,
						'padding' => 0,
						'fgcolor' => array(0, 0, 0),
						'bgcolor' => false, //array(255,255,255)
						'module_width' => 1, // width of a single module in points
						'module_height' => 1 // height of a single module in points
					);
					
					$code  = $row->layanan_label.' an.'.$row->PNS_PNSNAM.', dkk Nomor '.$this->input->post('nomorPengeluaran') ;					
					$this->pdf->write2DBarcode($code, 'QRCODE,Q', 10, 10, 25, 25, $style, 'N'); 				
				}	
				$y++;
				$starty +=7;
			}	
					
			$this->pdf->SetFont('freeSerif', '', 10);		
			$text2='An.KEPALA KANTOR REGIONAL XI BKN ';
			$this->pdf->writeHTMLCell(90,125,120,$starty+10,$text2,0,0,false,true,'C',true);
			
			$text2='u.b';
			$this->pdf->writeHTMLCell(90,125,120,$starty+14,$text2,0,0,false,true,'C',true);
			
			$text2=$spesimen->jabatan_spesimen;
			$this->pdf->writeHTMLCell(90,125,120,$starty+18,$text2,0,0,false,true,'C',true);
			
			$text3 = $spesimen->glrdpn.''.$spesimen->nama_spesimen.''.(!empty($spesimen->glrblk) ? ','.$spesimen->glrblk :'');
			$this->pdf->writeHTMLCell(90,125,120,$starty+40,$text3,0,0,false,true,'C',true);
			
			$this->pdf->writeHTMLCell(90,125,120,$starty+44,'NIP. '.$spesimen->nip_spesimen,0,0,false,true,'C',true);

			
			$this->pdf->Output('cetakPengeluaran.pdf', 'D');
		}	
	}	
	
	public function getAgenda()
	{
	    $query		= $this->laporan->getAgendaMutasi();
		echo json_encode($query->result());
			
	}	
	
	public function getNomorPengeluaran()
	{
     	$query			= $this->laporan->getLastNumberPengeluaranMutasi()->row();
		echo json_encode($query);
	}
}
