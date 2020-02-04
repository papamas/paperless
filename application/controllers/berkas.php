<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Berkas extends MY_Controller {
	
	var $menu_id    = 13;
	var $allow 		= FALSE;
	
	function __construct()
	{
	    parent::__construct();		
	    $this->load->library(array('Auth','Menu','form_validation','Myencrypt'));
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
		
		$search['search']              = $this->input->post('search');
		$search['searchby']            = $this->input->post('searchby');
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
		
		$nip         = $this->myencrypt->decode($this->input->get('n'));
		$berkas      = $this->berkas->getUploadDokumen($nip);
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
		readfile(base_url().'uploads/'.$instansi.'/'.$file);
	}	
	
	
	public function kirim()
	{
		$data['nip']     = $this->myencrypt->decode($this->input->post('nip'));
		$data['agenda']  = $this->myencrypt->decode($this->input->post('agenda'));
		
		$data['response']	= $this->berkas->KirimUlang($data);
		
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		
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
	
	
	
}
