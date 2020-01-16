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
	
	
	
}
