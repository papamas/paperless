<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pmk extends MY_Controller {
	
	var $menu_id    = 7;
	var $allow 		= FALSE;
	
	function __construct()
	{
	    parent::__construct();		
	    $this->load->library(array('Auth','Menu','form_validation','Myencrypt'));
		$this->load->model('pmk/pmk_model', 'pmk');
		$this->allow = $this->auth->isAuthMenu($this->menu_id);
	} 
	
	/*	
	public function index()
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
		
		$this->load->view('pmk/index',$data);
		
	}
	*/
	
	public function saveUsul()
	{
		$this->form_validation->set_rules('agendaId', 'agendaId', 'required');
		$this->form_validation->set_rules('nip', 'nip', 'required');
		$this->form_validation->set_rules('oldTahun', 'oldTahun', 'required|is_natural');
		$this->form_validation->set_rules('oldBulan', 'oldBulan', 'required|is_natural');
		$this->form_validation->set_rules('oldGaji', 'oldGaji', 'required|is_natural');
		$this->form_validation->set_rules('oldTmtGaji', 'oldTmtGaji', 'required');
		$this->form_validation->set_rules('nomorPersetujuan', 'nomorPersetujuan', 'required');
		$this->form_validation->set_rules('tanggalPersetujuan', 'tanggalPersetujuan', 'required');
		$this->form_validation->set_rules('baruTahun', 'baruTahun', 'required|is_natural');
		$this->form_validation->set_rules('baruBulan', 'baruBulan', 'required|is_natural');
		$this->form_validation->set_rules('baruGaji', 'baruGaji', 'required|is_natural');
		$this->form_validation->set_rules('baruTmtGaji', 'baruTmtGaji', 'required');
		$this->form_validation->set_rules('mulaiHonor', 'mulaiHonor', 'required');
		$this->form_validation->set_rules('sampaiHonor', 'sampaiHonor', 'required');
		$this->form_validation->set_rules('tahunHonor', 'tahunHonor', 'required|is_natural');
		$this->form_validation->set_rules('bulanHonor', 'bulanHonor', 'required|is_natural');
		$this->form_validation->set_rules('mulaiPegawai', 'mulaiPegawai', 'required');
		$this->form_validation->set_rules('sampaiPegawai', 'sampaiPegawai', 'required');
		$this->form_validation->set_rules('tahunPegawai', 'tahunPegawai', 'required|is_natural');
		$this->form_validation->set_rules('bulanPegawai', 'bulanPegawai', 'required|is_natural');
		$this->form_validation->set_rules('salinanSah', 'salinanSah', 'required');
		$this->form_validation->set_rules('skPangkat', 'skPangkat', 'required');
		$this->form_validation->set_rules('tempatLahir', 'tempatLahir', 'required');
		
		$this->form_validation->set_rules('tingkat1', 'tingkat1', 'required');
		$this->form_validation->set_rules('nomorIjazah1', 'nomorIjazah1', 'required');
		$this->form_validation->set_rules('tanggalIjazah1', 'tanggalIjazah1', 'required');
		
		$this->form_validation->set_rules('tingkat2', 'tingkat2', 'required');
		$this->form_validation->set_rules('nomorIjazah2', 'nomorIjazah2', 'required');
		$this->form_validation->set_rules('tanggalIjazah2', 'tanggalIjazah2', 'required');
		
		$this->form_validation->set_rules('tingkat3', 'tingkat3', 'required');
		$this->form_validation->set_rules('nomorIjazah3', 'nomorIjazah3', 'required');
		$this->form_validation->set_rules('tanggalIjazah3', 'tanggalIjazah3', 'required');
		
		$this->form_validation->set_rules('tingkat4', 'tingkat4', 'required');
		$this->form_validation->set_rules('nomorIjazah4', 'nomorIjazah4', 'required');
		$this->form_validation->set_rules('tanggalIjazah4', 'tanggalIjazah4', 'required');
		
		$this->form_validation->set_rules('lokasiTtd', 'lokasiTtd', 'required');
		$this->form_validation->set_rules('tanggalTtd', 'tanggalTtd', 'required');
		$this->form_validation->set_rules('jabatanTtd', 'jabatanTtd', 'required');
		$this->form_validation->set_rules('namaTtd', 'namaTtd', 'required');
		$this->form_validation->set_rules('pangkatTtd', 'pangkatTtd', 'required');
		$this->form_validation->set_rules('nipTtd', 'nipTtd', 'required|is_natural');
	
		
		
		if($this->form_validation->run() == FALSE)
		{
			$data['pesan']	= "Lengkapi/Perbaiki Form";
			$this->output
				->set_status_header(406)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
			return FALSE;	
		}	
		else
		{
			$result     = $this->pmk->saveUsul();
			
			$data['pesan']		= $result['pesan'];
			$data['response']	= $result['response'];
			
			if($result['response'])
			{
			
				$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
			}
			else
			{
				$this->output
					->set_status_header(406)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($data));
					return FALSE;	
			}		
		}
	}	
	
	public function getUsul()
	{
		$agenda     = $this->input->get('agendaId');
		$nip        = $this->input->get('nip');
		
		$result     = $this->pmk->getUsul($agenda,$nip);
		echo json_encode($result->row());
	}	
	
	public function cetakNotaUsul()
	{
		$agenda_id  = $this->myencrypt->decode($this->input->get('i'));
		$nip        = $this->myencrypt->decode($this->input->get('n'));
		
		$row        =  $this->pmk->getCetakUsul($agenda_id,$nip)->row();
		
		
		$this->load->library('PDF', array());
		
		
		$this->pdf->setPrintHeader(false);
		$this->pdf->setPrintFooter(false);	
		
		$this->pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);
		$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		$this->pdf->SetFont('freeSerif', '', 12);		
		$this->pdf->AddPage('P', 'A4');
		$this->pdf->Text(145,10, 'PENINJAUAN MASA KERJA');
		
		
		$this->pdf->SetFont('freeSerif', '', 14);		
		$this->pdf->writeHTMLCell(0,125,2,25,'USUL PENINJAUAN MASA KERJA',0,0,false,true,'C',true);
		$this->pdf->writeHTMLCell(0,125,2,30,'NOMOR :'.$row->agenda_nousul,0,0,false,true,'C',true);

		$this->pdf->SetFont('freeSerif', '', 12);
		$this->pdf->Text(5,45, 'INSTANSI : '.$row->INS_NAMINS);
		
		$this->pdf->SetFont('freeSerif', '', 11);
		$html = '
<table cellspacing="0" cellpadding="1" border="1">
    <tr style="">
	    <td colspan="2" width="200px">NAMA LENGKAP</td>
        <td width="230px">&nbsp;'.(!empty($row->PNS_GLRDPN) ? $row->PNS_GLRDPN.' ' : '').$row->PNS_PNSNAM.' '.(!empty($row->PNS_GLRBLK) ? $row->PNS_GLRBLK : '') .'</td>        
		<td width="270px"></td>
    </tr>
	<tr style="">		
        <td colspan="2" width="200px">TEMPAT/ TANGGAL LAHIR</td>
		<td>&nbsp;'.$row->tempat_lahir.','.$row->tanggal_lahir.'</td>
		<td width="20px" style="font-size:11px">&nbsp;A.</td>
		<td width="250px" colspan="3" style="font-size:11px">&nbsp;STTB/Ijazah/Diploma/Akta </td>
    </tr>
	<tr style="">		
        <td colspan="2" width="200px">NIP</td>
		<td>&nbsp;'.$row->nip.'</td>
		<td width="20px"></td>
		<td width="35px"  style="font-size:10px">&nbsp;1.'.$row->tingkat1.'</td>
		<td width="130px" style="font-size:10px">&nbsp;No.'.$row->nomor_ijazah1.'</td>
		<td width="85px" style="font-size:10px">&nbsp;tgl.'.$row->tanggal_ijazah1.' </td>	
    </tr>
	
	<tr style="">        
		<td colspan="2" width="200px">STATUS</td>
		<td>&nbsp;'.$row->status.'</td>
		<td width="20px"></td>
		<td style="font-size:10px">&nbsp;2.'.$row->tingkat2.'</td>
		<td style="font-size:10px">&nbsp;No.'.$row->nomor_ijazah2.'</td>
		<td style="font-size:10px">&nbsp;tgl.'.$row->tanggal_ijazah2.' </td>
    </tr>
	<tr style="">		
        <td colspan="2" width="200px">PANGKAT</td>
		<td>&nbsp;'.$row->pangkat.'</td>
		<td width="20px"></td>
		<td style="font-size:10px">&nbsp;3.'.$row->tingkat3.'</td>
		<td style="font-size:10px">&nbsp;No.'.$row->nomor_ijazah3.'</td>
		<td style="font-size:10px">&nbsp;tgl.'.$row->tanggal_ijazah3.'</td>		
    </tr>
    <tr style="">		
        <td colspan="2" width="200px">GOLONGAN RUANG</td>
		<td>&nbsp;'.$row->golongan.'</td>
		<td width="20px"></td>
		<td style="font-size:10px">&nbsp;4.'.$row->tingkat4.'</td>
		<td style="font-size:10px">&nbsp;No.'.$row->nomor_ijazah4.'</td>
		<td style="font-size:10px">&nbsp;tgl.'.$row->tanggal_ijazah4.'</td>
    </tr>
	<tr style="">
		<td rowspan="5"  width="20px" align="center">LAMA</td>
		<td width="180px">1. MASA KERJA GOL</td>
		<td>&nbsp;'.$row->old_masa_kerja_tahun.'  TAHUN  '.$row->old_masa_kerja_bulan.'  BL</td>
		<td width="20px"></td>
		<td style="font-size:10px">&nbsp;5.'.$row->tingkat5.'</td>
		<td style="font-size:10px">&nbsp;No.'.$row->nomor_ijazah5.'</td>
		<td style="font-size:10px">&nbsp;tgl.'.$row->tanggal_ijazah5.'</td>
    </tr>
	<tr style="">
		<td>2. GAJI POKOK</td>
		<td>&nbsp;Rp. '.number_format($row->old_gaji_pokok,0).'</td>
		<td style="font-size:11px" width="20px">&nbsp;B.</td>
		<td colspan="3" style="font-size:11px">&nbsp;Daftar Riwayat Pekerjaan</td>		
    </tr>
	<tr style="">
		<td>3. SEJAK</td>
		<td>&nbsp;'.$row->old_tmt_gaji.'</td>
		<td width="20px" style="font-size:11px">&nbsp;C.</td>
		<td colspan="3" style="font-size:11px">&nbsp;Salinan Sah dan bukti-bukti pengalaman kerja</td>	
    </tr>
	<tr style="">
	    <td rowspan="2">4. PERSETUJUAN BKN</td>
		<td>&nbsp;'.$row->nomor_persetujuan.'</td>
		<td width="20px" rowspan="3"></td>
		<td colspan="3" rowspan="3" style="font-size:10px">&nbsp;'.$row->salinan_sah.'</td>
    </tr>
	<tr style="">		
		<td>&nbsp;'.$row->tanggal_persetujuan.'</td>
		
    </tr>
	<tr style="">
		<td rowspan="3"  width="20px" align="center">BARU</td>
		<td>1. MASA KERJA GOL</td>
		<td>&nbsp;'.$row->baru_masa_kerja_tahun.'  TAHUN  '.$row->baru_masa_kerja_bulan.' BL</td>
		
    </tr>
	<tr style="">
		<td>2. GAJI POKOK</td>
		<td>&nbsp;Rp. '.number_format($row->baru_gaji_pokok,0).'</td>
		<td width="20px" style="font-size:11px">&nbsp;D.</td>
		<td colspan="3" style="font-size:11px">&nbsp;Surat Keputusan</td>
    </tr>
	<tr style="">
		<td>BERLAKU TERHITUNG MULAI TANGGAL</td>
		<td>&nbsp;'.$row->baru_tmt_gaji.'</td>
		<td width="20px"></td>
		<td colspan="3" style="font-size:10px">&nbsp;'.$row->sk_pangkat.'</td>
    </tr>
	<tr style="">
		<td colspan="7" align="center">PERHITUNGAN MASA KERJA</td>		
    </tr>
	<tr style="">
		<td rowspan="3"  width="20px" align="center">LAMA</td>
		<td width="180px" rowspan="2" align="center"> PENGALAMAN KERJA</td>
		<td  align="center" rowspan="2">MULAI DAN SAMPAI DENGAN TGL. BL. TH</td>
		<td  align="center" colspan="2" width="70px">JUMLAH</td>
		<td rowspan="2" align="center" width="70px">DINILAI</td>
		<td  align="center" colspan="2" width="70px">JUMLAH</td>
		<td width="60px" rowspan="2" align="center">KET</td>
    </tr>
	<tr style="">
		<td  align="center">TH</td>
		<td  align="center">BL</td>
		<td  align="center">TH</td>
		<td  align="center">BL</td>
		
    </tr>
	<tr style="">
		<td width="180px"  align="center"> DIANGKAT SEBAGAI HONORER</td>
		<td  align="center">'.$row->mulai_honor.' s/d '.$row->sampai_honor.'</td>
		<td align="center">'.$row->tahun_honor.'</td>
		<td align="center">'.$row->bulan_honor.'</td>
		<td align="center"></td>
		<td align="center">'.$row->tahun_honor.'</td>
		<td align="center">'.$row->bulan_honor.'</td>
		<td align="center"></td>
    </tr>
	<tr style="">
		<td rowspan="2"  width="20px" align="center">BARU</td>
		<td width="180px" rowspan="2" align="center"> DIANGKAT SEBAGAI CALON PEGAWAI</td>
		<td  align="center" rowspan="2">'.$row->mulai_pegawai.' s/d '.$row->sampai_pegawai.'</td>
		<td  rowspan="2" align="center" >'.$row->tahun_pegawai.'</td>
		<td  rowspan="2" align="center" >'.$row->bulan_pegawai.'</td>
		<td  align="center" rowspan="2"></td>
		<td  rowspan="2" align="center" >'.$row->tahun_pegawai.'</td>
		<td  rowspan="2" align="center" >'.$row->bulan_pegawai.'</td>
    </tr>
	<tr style="">
		<td align="center"></td>
	</tr>
	<tr style="">
		<td colspan="3"> JUMLAH SELURUHNYA</td>
		<td align="center">'.($row->tahun_honor+$row->tahun_pegawai).'</td>
		<td align="center">'.($row->bulan_honor+$row->bulan_pegawai).'</td>
		<td align="center"></td>
		<td align="center">'.($row->tahun_honor+$row->tahun_pegawai).'</td>
		<td align="center">'.($row->bulan_honor+$row->bulan_pegawai).'</td>
		<td align="center"></td>
    </tr>
	<tr style="">
		<td colspan="3"> CATATAN BKN</td>
		<td colspan="6"> WILAYAH PEMBAYARAN</td>		
    </tr>
	<tr style="">
		<td colspan="3"> PERSETUJUAN BKN NO.</td>
		<td colspan="6"> USUL NOMOR</td>
		
    </tr>
</table>';

	// Print text using writeHTMLCell()
	$this->pdf->writeHTMLCell(0, 125, 5, 50, $html, 0, 0, false, true, 'L', true);
	
	
	$this->pdf->writeHTMLCell(0,125,130,205,$row->lokasi_ttd.','.$row->tanggal_ttd,0,0,false,true,'L',true);

	$this->pdf->writeHTMLCell(0,125,130,215,$row->jabatan_ttd,0,0,false,true,'C',true);
	
	$this->pdf->writeHTMLCell(0,125,130,235,'<u>'.$row->nama_ttd.'<u>',0,0,false,true,'C',true);
	$this->pdf->writeHTMLCell(0,125,130,239,$row->pangkat_ttd,0,0,false,true,'C',true);
	$this->pdf->writeHTMLCell(0,125,130,242,'NIP.'.$row->nip_ttd,0,0,false,true,'C',true);

	// set style for barcode
	$style = array(
		'border' => false,
		'padding' => 0,
		'fgcolor' => array(0, 0, 0),
		'bgcolor' => false, //array(255,255,255)
		'module_width' => 1, // width of a single module in points
		'module_height' => 1 // height of a single module in points
	);
	
	$code  = $row->PNS_PNSNAM.' NIP.'.$row->nip;
	
	$this->pdf->write2DBarcode($code, 'QRCODE', 15, 210, 35, 35, $style, 'N');
	$this->pdf->Output('USUL_PMK_'.$row->nip.'.pdf', 'D');
	
	}	
	
	
	public function cetakAccPmk()
	{
		
		$agenda_id  = $this->myencrypt->decode($this->input->get('a'));
		$nip        = $this->myencrypt->decode($this->input->get('n'));
				
		$row        =  $this->pmk->getCetakAccPmk($agenda_id,$nip)->row();
		
		$this->load->library('PDF', array());
		
		
		$this->pdf->setPrintHeader(false);
		$this->pdf->setPrintFooter(false);	
		
		$this->pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);
		$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		$this->pdf->SetFont('freeSerif', '', 12);		
		$this->pdf->AddPage('P', 'A4');
		
		$this->pdf->Text(5,5,  'Nomor Usul');
		$this->pdf->Text(50,5, ': '.$row->agenda_nousul);
		$this->pdf->Text(5,10,  'Tanggal Agenda');
		$this->pdf->Text(50,10, ': '.$row->agenda_tgl);
		$this->pdf->Text(5,15, 'Tanggal Kirim BKN');
		$this->pdf->Text(50,15, ': '.$row->diterima);
		
		$this->pdf->Text(145,10, 'PENINJAUAN MASA KERJA');
		
		
		$this->pdf->SetFont('freeSerif', '', 14);		
		$this->pdf->writeHTMLCell(0,125,2,25,'NOTA PERSETUJUAN TEKNIS',0,0,false,true,'C',true);
		$this->pdf->writeHTMLCell(0,125,2,30,'KEPALA KANTOR REGIONAL XI BADAN KEPEGAWAIAN NEGARA',0,0,false,true,'C',true);
		$this->pdf->writeHTMLCell(0,125,2,35,'TENTANG',0,0,false,true,'C',true);
		$this->pdf->writeHTMLCell(0,125,2,40,'PENINJAUAN MASA KERJA PEGAWAI NEGERI SIPIL',0,0,false,true,'C',true);

		$this->pdf->SetFont('freeSerif', '', 12);
		$this->pdf->Text(5,55, 'INSTANSI : '.$row->INS_NAMINS);
		
		$this->pdf->SetFont('freeSerif', '', 11);
		$html = '
<table cellspacing="0" cellpadding="1" border="1">
    <tr style="">
	    <td colspan="2" width="200px">NAMA LENGKAP</td>
         <td width="230px">&nbsp;'.(!empty($row->PNS_GLRDPN) ? $row->PNS_GLRDPN.' ' : '').$row->PNS_PNSNAM.' '.(!empty($row->PNS_GLRBLK) ? $row->PNS_GLRBLK : '') .'</td>       
		<td width="270px"></td>
    </tr>
	<tr style="">		
        <td colspan="2" width="200px">TEMPAT/ TANGGAL LAHIR</td>
		<td>&nbsp;'.$row->tempat_lahir.','.$row->tanggal_lahir.'</td>
		<td width="20px" style="font-size:11px">&nbsp;A.</td>
		<td width="250px" colspan="3" style="font-size:11px">&nbsp;STTB/Ijazah/Diploma/Akta </td>
    </tr>
	<tr style="">		
        <td colspan="2" width="200px">NIP</td>
		<td>&nbsp;'.$row->nip.'</td>
		<td width="20px"></td>
		<td width="35px"  style="font-size:10px">&nbsp;1.'.$row->tingkat1.'</td>
		<td width="130px" style="font-size:10px">&nbsp;No.'.$row->nomor_ijazah1.'</td>
		<td width="85px" style="font-size:10px">&nbsp;tgl.'.$row->tanggal_ijazah1.' </td>	
    </tr>
	
	<tr style="">        
		<td colspan="2" width="200px">STATUS</td>
		<td>&nbsp;'.$row->status.'</td>
		<td width="20px"></td>
		<td style="font-size:10px">&nbsp;2.'.$row->tingkat2.'</td>
		<td style="font-size:10px">&nbsp;No.'.$row->nomor_ijazah2.'</td>
		<td style="font-size:10px">&nbsp;tgl.'.$row->tanggal_ijazah2.' </td>
    </tr>
	<tr style="">		
        <td colspan="2" width="200px">PANGKAT</td>
		<td>&nbsp;'.$row->pangkat.'</td>
		<td width="20px"></td>
		<td style="font-size:10px">&nbsp;3.'.$row->tingkat3.'</td>
		<td style="font-size:10px">&nbsp;No.'.$row->nomor_ijazah3.'</td>
		<td style="font-size:10px">&nbsp;tgl.'.$row->tanggal_ijazah3.'</td>		
    </tr>
    <tr style="">		
        <td colspan="2" width="200px">GOLONGAN RUANG</td>
		<td>&nbsp;'.$row->golongan.'</td>
		<td width="20px"></td>
		<td style="font-size:10px">&nbsp;4.'.$row->tingkat4.'</td>
		<td style="font-size:10px">&nbsp;No.'.$row->nomor_ijazah4.'</td>
		<td style="font-size:10px">&nbsp;tgl.'.$row->tanggal_ijazah4.'</td>
    </tr>
	<tr style="">
		<td rowspan="5"  width="20px" align="center">LAMA</td>
		<td width="180px">1. MASA KERJA GOL</td>
		<td>&nbsp;'.$row->old_masa_kerja_tahun.'  TAHUN  '.$row->old_masa_kerja_bulan.'  BL</td>
		<td width="20px"></td>
		<td style="font-size:10px">&nbsp;5.'.$row->tingkat5.'</td>
		<td style="font-size:10px">&nbsp;No.'.$row->nomor_ijazah5.'</td>
		<td style="font-size:10px">&nbsp;tgl.'.$row->tanggal_ijazah5.'</td>
    </tr>
	<tr style="">
		<td>2. GAJI POKOK</td>
		<td>&nbsp;Rp. '.number_format($row->old_gaji_pokok,0).'</td>
		<td style="font-size:11px" width="20px">&nbsp;B.</td>
		<td colspan="3" style="font-size:11px">&nbsp;Daftar Riwayat Pekerjaan</td>		
    </tr>
	<tr style="">
		<td>3. SEJAK</td>
		<td>&nbsp;'.$row->old_tmt_gaji.'</td>
		<td width="20px" style="font-size:11px">&nbsp;C.</td>
		<td colspan="3" style="font-size:11px">&nbsp;Salinan Sah dan bukti-bukti pengalaman kerja</td>	
    </tr>
	<tr style="">
	    <td rowspan="2">4. PERSETUJUAN BKN</td>
		<td>&nbsp;'.$row->nomor_persetujuan.'</td>
		<td width="20px" rowspan="3"></td>
		<td colspan="3" rowspan="3" style="font-size:10px">&nbsp;'.$row->salinan_sah.'</td>
    </tr>
	<tr style="">		
		<td>&nbsp;'.$row->tanggal_persetujuan.'</td>
		
    </tr>
	<tr style="">
		<td rowspan="3"  width="20px" align="center">BARU</td>
		<td>1. MASA KERJA GOL</td>
		<td>&nbsp;'.($row->dinilai_tahun_honor+$row->dinilai_tahun_pegawai).'  TAHUN  '.($row->dinilai_bulan_honor+$row->dinilai_bulan_pegawai).' BL</td>
		
    </tr>
	<tr style="">
		<td>2. GAJI POKOK</td>
		<td>&nbsp;Rp. '.number_format($row->baru_gaji_pokok,0).'</td>
		<td width="20px" style="font-size:11px">&nbsp;D.</td>
		<td colspan="3" style="font-size:11px">&nbsp;Surat Keputusan</td>
    </tr>
	<tr style="">
		<td>BERLAKU TERHITUNG MULAI TANGGAL</td>
		<td>&nbsp;'.$row->baru_tmt_gaji.'</td>
		<td width="20px"></td>
		<td colspan="3" style="font-size:10px">&nbsp;'.$row->sk_pangkat.'</td>
    </tr>
	<tr style="">
		<td colspan="7" align="center">PERHITUNGAN MASA KERJA</td>		
    </tr>
	<tr style="">
		<td rowspan="3"  width="20px" align="center">LAMA</td>
		<td width="180px" rowspan="2" align="center"> PENGALAMAN KERJA</td>
		<td  align="center" rowspan="2">MULAI DAN SAMPAI DENGAN TGL. BL. TH</td>
		<td  align="center" colspan="2" width="70px">JUMLAH</td>
		<td rowspan="2" align="center" width="70px">DINILAI</td>
		<td  align="center" colspan="2" width="70px">JUMLAH</td>
		<td width="60px" rowspan="2" align="center">KET</td>
    </tr>
	<tr style="">
		<td  align="center">TH</td>
		<td  align="center">BL</td>
		<td  align="center">TH</td>
		<td  align="center">BL</td>
		
    </tr>
	<tr style="">
		<td width="180px"  align="center"> DIANGKAT SEBAGAI HONORER</td>
		<td  align="center">'.$row->mulai_honor.' s/d '.$row->sampai_honor.'</td>
		<td align="center">'.$row->tahun_honor.'</td>
		<td align="center">'.$row->bulan_honor.'</td>
		<td align="center"></td>
		<td align="center">'.$row->dinilai_tahun_honor.'</td>
		<td align="center">'.$row->dinilai_bulan_honor.'</td>
		<td align="center">'.$row->keterangan.'</td>
    </tr>
	<tr style="">
		<td rowspan="2"  width="20px" align="center">BARU</td>
		<td width="180px" rowspan="2" align="center"> DIANGKAT SEBAGAI CALON PEGAWAI</td>
		<td  align="center" rowspan="2">'.$row->mulai_pegawai.' s/d '.$row->sampai_pegawai.'</td>
		<td  rowspan="2" align="center" >'.$row->tahun_pegawai.'</td>
		<td  rowspan="2" align="center" >'.$row->bulan_pegawai.'</td>
		<td  align="center" rowspan="2"></td>
		<td  rowspan="2" align="center" >'.$row->dinilai_tahun_pegawai.'</td>
		<td  rowspan="2" align="center" >'.$row->dinilai_bulan_pegawai.'</td>
    </tr>
	<tr style="">
		<td align="center"></td>
	</tr>
	<tr style="">
		<td colspan="3"> JUMLAH SELURUHNYA</td>
		<td align="center">'.($row->tahun_honor+$row->tahun_pegawai).'</td>
		<td align="center">'.($row->bulan_honor+$row->bulan_pegawai).'</td>
		<td align="center"></td>
		<td align="center">'.($row->dinilai_tahun_honor+$row->dinilai_tahun_pegawai).'</td>
		<td align="center">'.($row->dinilai_bulan_honor+$row->dinilai_bulan_pegawai).'</td>
		<td align="center"></td>
    </tr>
	
	
</table>';

	// Print text using writeHTMLCell()
	$this->pdf->writeHTMLCell(0, 125, 5, 60, $html, 0, 0, false, true, 'L', true);
	
	$this->pdf->SetFont('freeSerif', '', 14);
	$this->pdf->writeHTMLCell(0,125,90,210,'Nomor '.$row->nomi_persetujuan,0,0,false,true,'L',true);
	
	$this->pdf->SetFont('freeSerif', '', 12);
	$this->pdf->writeHTMLCell(0,125,90,217,'Manado, '.$row->tanggal_persetujuan_nota,0,0,false,true,'L',true);

	$this->pdf->writeHTMLCell(0,125,90,225,'a.n. Kepala Kantor Regional XI Badan Kepegawaian Negara',0,0,false,true,'C',true);
	$this->pdf->writeHTMLCell(0,125,90,230,$row->jabatan,0,0,false,true,'C',true);
	
	$this->pdf->writeHTMLCell(0,125,90,260,$row->glrdpn_acc.' '.$row->nama_acc.' '.$row->glrblk_acc,0,0,false,true,'C',true);
	$this->pdf->writeHTMLCell(0,125,90,264,'NIP.'.$row->nip_acc,0,0,false,true,'C',true);

	// set style for barcode
	$style = array(
		'border' => false,
		'padding' => 0,
		'fgcolor' => array(0, 0, 0),
		'bgcolor' => false, //array(255,255,255)
		'module_width' => 1, // width of a single module in points
		'module_height' => 1 // height of a single module in points
	);
	
	$code  = 'Persetujuan PMK Nomor '.$row->nomi_persetujuan.' '.$row->PNS_PNSNAM.' NIP.'.$row->nip;
	
	$this->pdf->write2DBarcode($code, 'QRCODE', 15, 220, 35, 35, $style, 'N');
	$this->pdf->Output('NPPMK_'.$row->nip.'.pdf', 'D');
	
	}	
	
}
