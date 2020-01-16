<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Distribusi extends MY_Controller {
	
	var $menu_id    = 24;
	var $allow 		= FALSE;
	
	function __construct()
	{
	    parent::__construct();		
	    $this->load->library(array('Auth','Menu','form_validation','Myencrypt'));
		$this->load->model('distribusi/distribusi_model', 'distribusi');
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
		
		$data['instansi']  		= $this->distribusi->getInstansi();
		$data['layanan']  		= $this->distribusi->getLayanan();
		$data['golongan']  		= $this->distribusi->getGolongan();
		$data['penerima']    	= $this->distribusi->getPenerima();
		$data['show']  			= FALSE;
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('distribusi/index',$data);
	}
	
	
	public function getBerkas()
	{	
		$this->form_validation->set_rules('instansi', 'instansi', 'trim');
		$this->form_validation->set_rules('layanan', 'layanan', 'trim|required');
		$this->form_validation->set_rules('golongan', 'golongan', 'trim');
		
		if($this->form_validation->run() == FALSE)
		{
		    $data['menu']      		=  $this->menu->build_menu();
			$data['lname']    		=  $this->auth->getLastName();        
			$data['name']      		=  $this->auth->getName();
			$data['jabatan']   		=  $this->auth->getJabatan();
			$data['member']	   		=  $this->auth->getCreated();
			$data['avatar']	   		=  $this->auth->getAvatar();
			
			$data['instansi']  		= $this->distribusi->getInstansi();
			$data['layanan']  		= $this->distribusi->getLayanan();
			$data['golongan']  		= $this->distribusi->getGolongan();
			$data['penerima']  	    = $this->distribusi->getPenerima();
			$data['show']  			= FALSE;
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('distribusi/index',$data);
			}
        else
		{			
			$q	  			        = $this->distribusi->getUsulDokumen();	
				
			$data['menu']     		=  $this->menu->build_menu();
			$data['lname']    		=  $this->auth->getLastName();        
			$data['name']     		=  $this->auth->getName();
			$data['jabatan']  		=  $this->auth->getJabatan();
			$data['member']	  		=  $this->auth->getCreated();
			$data['avatar']	  		=  $this->auth->getAvatar();
			$data['usul']	  		=  $q;
			$data['instansi']  		=  $this->distribusi->getInstansi();
			$data['layanan']  		=  $this->distribusi->getLayanan();
			$data['golongan']  		=  $this->distribusi->getGolongan();
			$data['penerima']  	    = $this->distribusi->getPenerima();
			$data['show']  			=  TRUE;
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('distribusi/index',$data);
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
		$this->form_validation->set_rules('penerima', 'penerima', 'trim|required');
		if($this->form_validation->run() == FALSE)
		{
			$data['response']	= FALSE;
			$data['message']    = validation_errors();
			
			$this->output
				->set_status_header(400)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
		}
		else
		{	
		
			$data['response']	= $this->distribusi->setKirim();
		
			$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
		}
	}
	
	public function kirimAll()
	{
		$nip                = $this->input->post('nip');
		$agenda             = $this->input->post('agenda');
		$penerima           = $this->input->post('penerima');		
		
		$this->form_validation->set_rules('penerima', 'penerima', 'trim|required');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['response']	= FALSE;
			$data['message']    = validation_errors();
			
			$this->output
				->set_status_header(400)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data));
		}
        else
        {			
			for($i=0;$i < count($nip);$i++)
			{
				$data['agenda']          = $agenda[$i];
				$data['nip']             = $nip[$i];
				$data['penerima']        = $penerima;
				
				$data['response']	= $this->distribusi->setKirimAll($data);
				$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($data));
				
			}   
        }	
	}

    public function getVerifikasi()
	{
		$usul 		= $this->distribusi->getUsulDokumen();
		
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
}
