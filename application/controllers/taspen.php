
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Taspen extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 
	var $menu_id    = 29;
	var $allow 		= FALSE;
 
	 
	function __construct()
    {
        parent::__construct();
		$this->load->library(array('Auth','Menu','Myencrypt','form_validation'));
		$this->load->model('taspen/upload/upload_model', 'uploadFile');
		$this->load->model('taspen/berkas/berkas_model', 'berkas');
		$this->load->model('taspen/validasi/validasi_model', 'validasi');
		$this->load->model('taspen/usul/usul_model', 'usul');
		$this->load->model('menu_model');
		$this->allow = $this->auth->isAuthMenu($this->menu_id);
	}
	
	public function index()
	{
		$data['menu']     =  $this->menu->build_menu();
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		$data['instansi'] =  $this->validasi->getInstansi();
		$data['show']	  =  FALSE;
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('taspen/validasi/index',$data);
		
	}
	
	public function getValidasiSK()
	{
		$this->form_validation->set_rules('instansi', 'instansi', 'required');
		$this->form_validation->set_rules('searchby', 'Filter', 'trim');
		$this->form_validation->set_rules('search', 'Data Pencarian', 'trim');		
		
        $perintah		  = $this->input->post('perintah');	
		
		
		if($this->form_validation->run() == FALSE)
		{				
			$data['menu']     		=  $this->menu->build_menu();		
			$data['name']     		=  $this->auth->getName();
			$data['jabatan']  		=  $this->auth->getJabatan();
			$data['member']	  		=  $this->auth->getCreated();
			$data['avatar']	  		=  $this->auth->getAvatar();
			$data['instansi']  		= $this->validasi->getInstansi();
			
			$data['show']  			= FALSE;
			$this->allow 			= $this->auth->isAuthMenu(29);
			
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('taspen/validasi/index',$data);
		}
        else
        {			
			
			$q				  = $this->validasi->getValidasiSK();
			
			if($perintah == 1) {
				
				$data['menu']    		=  $this->menu->build_menu();
				$data['name']     		=  $this->auth->getName();
				$data['jabatan']  		=  $this->auth->getJabatan();
				$data['member']	  		=  $this->auth->getCreated();
				$data['avatar']	  		=  $this->auth->getAvatar();
				$data['daftar']    		=  $q;
				$data['instansi']		=  $this->validasi->getInstansi();
				
				$data['show']  			=  TRUE;
				$this->allow 			=  $this->auth->isAuthMenu(29);
				
				if(!$this->allow)
				{
					$this->load->view('403/index',$data);
					return;
				}
				$this->load->view('taspen/validasi/index',$data);
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
		$filename         = "DAFTAR DOKUMEN INSTANSI".$now.".xls";
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/x-msdownload');
		header('Content-Disposition:attachment; filename='.$filename);                      
		header('Expires:0'); 
		
		$html  = 'DAFTAR DOKUMEN INSTANSI';
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
					<th>DOKUMEN</th>					
					<th>NIP</th>
					<th>NAMA</th>
					<th>UPLOAD DATE</th>				
					'; 
		$html 	.= '</tr>';
		if($q->num_rows() > 0){
			$i = 1;		        
			foreach ($q->result() as $r) {				
				$html .= "<tr><td>{$r->nama_dokumen}</td>";               			
				$html .= "<td class=str>".(!empty($r->nip_lama) ? $r->nip_lama.'/'.$r->nip_baru : $r->nip_baru)."</td>";	
                $html .= "<td>{$r->nama}</td>";	
				$html .= "<td>{$r->created_date}</td>";									
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
	
	public function getValidSK()
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
	
	
	public function upload()
	{
		$data['menu']     =  $this->menu->build_menu();
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		$data['instansi'] =  $this->validasi->getInstansi();
		$data['dokumen']   =  $this->uploadFile->getDokumen();
		$data['show']	  =  FALSE;
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('taspen/upload/index',$data);
		
	}
	
	public function doUpload()
    {
		$this->form_validation->set_rules('jenis', 'Jenis Dokumen', 'required');
		$this->form_validation->set_rules('nip', 'NIP', 'trim|required');
		
		if($this->form_validation->run() == FALSE)
		{
			
		}
		else
		{	
			$jenis						= $this->input->post('jenis');
			$nip						= $this->input->post('nip');
			
			switch($jenis){
				case 1:
					$name  = 'SURAT_NIKAH_'.$nip;				
				break;
				case 2:
					$name  = 'SPTB_'.$nip;				
				break;
				case 3:
					$name  = 'SUKET_KEMATIAN_'.$nip;				
				break;
				case 4:
					$name  = 'SUKET_JANDA_DUDA_'.$nip;				
				break;
				case 5:
					$name  = 'PHOTO_'.$nip;				
				break;
				case 6:
					$name  = 'SP4B_'.$nip;				
				break;
				case 7:
					$name  = 'SK_PENSIUN_'.$nip;				
				break;
				case 8:
					$name  = 'AKTA_ANAK_'.$nip;				
				break;
				case 9:
					$name  = 'SUKET_KEMATIAN_CERAI_'.$nip;				
				break;
				case 10:
					$name  = 'BINTANG_JASA_'.$nip;				
				break;
				case 11:
					$name  = 'SUKET_MENETAP_'.$nip;				
				break;
				case 12:
					$name  = 'USUL_PK_'.$nip;				
				break;
				case 13:
					$name  = 'USUL_JD_'.$nip;				
				break;
				case 14:
					$name  = 'SK_JD_'.$nip;				
				break;
				case 15:
					$name  = 'SK_PK_'.$nip;				
				break;
				case 16:
					$name  = 'SUKET_ANAK_'.$nip;				
				break;
				case 17:
					$name  = 'SURAT_PERWALIAN_'.$nip;				
				break;
				case 18:
					$name  = 'USUL_YP_'.$nip;				
				break;
				case 19:
					$name  = 'SK_YP_'.$nip;				
				break;
			}
			
			
			$target_dir						= './uploads/taspen';			
			$config['upload_path']          = $target_dir;			
			$config['max_size']             = 3024;
			$config['encrypt_name']			= FALSE;	
			$config['overwrite']			= TRUE;	
			$config['detect_mime']			= TRUE;
			$config['file_name']            = $name;
			
			
			if(!is_dir($target_dir)){
				mkdir($target_dir, 0777, TRUE);
			}
			
			
			if($jenis == 5 )
			{
				$config['allowed_types']        = 'jpg';
			}
			else
			{
				$config['allowed_types']        = 'pdf';
			} 
				
			$this->load->library('upload', $config);	
			$this->upload->display_errors('', '');

			if ( ! $this->upload->do_upload('file'))
			{
				$data['error']	= $this->upload->display_errors();				
			}
			else
			{
				$data 			= $this->upload->data();
				$is_image       = $data['is_image'];
							
				if($is_image === TRUE)
                {
                    $this->resizeImage($data);
                }	
                
				$result         = $this->uploadFile->insertUpload($data);
				$data['upload'] = $data;
                $response       = $result['response'];
				
				if($response === TRUE)
                {					
				    $h ='<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Alert!</h4>'.$result['pesan'].'</div>';
						$data['pesan']	= $h;			
				}
				else
				{
                   $h ='<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Alert!</h4>File Dokumen telah ada, overwrite file</div>';
					$data['pesan']	= $h;
				     $this->uploadFile->updateFile($data);
				}                		
			}
        }
		
		$data['menu']     =  $this->menu->build_menu();
		$data['name']     =  $this->auth->getName();
        $data['jabatan']  =  $this->auth->getJabatan();
		$data['member']	  =  $this->auth->getCreated();
		$data['avatar']	  =  $this->auth->getAvatar();
		$data['instansi'] =  $this->validasi->getInstansi();
		$data['dokumen']  =  $this->usul->getDokumen();
		
		$this->load->view('taspen/upload/index',$data);
	}	
	
	public function resizeImage($data)
    {	  
	    $source_path = $data['full_path'];
        $target_path = $data['full_path'];	  
	  
        //Compress Image
		$config['image_library']		= 'gd2';
		$config['source_image']			= $source_path;
		$config['create_thumb']			= FALSE;
		$config['maintain_ratio']		= FALSE;
		$config['width']				= 300;
		$config['height']				= 450;
		$config['new_image']			=  $target_path;
		$config['quality']				= '100%';
		$this->load->library('image_lib', $config);
		
        if (!$this->image_lib->resize()) {
            $error = array('error' => $this->image_lib->display_errors());
			
        }
        $this->image_lib->clear();
    }
	
	
	
	public function daftar()
	{
			
		$data['menu']     		=  $this->menu->build_menu();		
		$data['name']     		=  $this->auth->getName();
        $data['jabatan']  		=  $this->auth->getJabatan();
		$data['member']	  		=  $this->auth->getCreated();
		$data['avatar']	  		=  $this->auth->getAvatar();
		$data['show']  			= FALSE;
		$this->allow 			= $this->auth->isAuthMenu(28);
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('taspen/upload/daftar',$data);
		
	}	
	
	public function getDaftar()
	{
		$this->form_validation->set_rules('searchby', 'Filter', 'required');
		$this->form_validation->set_rules('search', 'Data', 'required');		
		
        $perintah		  = $this->input->post('perintah');	
		$daftar			  = $this->input->post();
		
		if($this->form_validation->run() == FALSE)
		{				
			$data['menu']     		=  $this->menu->build_menu();		
			$data['name']     		=  $this->auth->getName();
			$data['jabatan']  		=  $this->auth->getJabatan();
			$data['member']	  		=  $this->auth->getCreated();
			$data['avatar']	  		=  $this->auth->getAvatar();
			
			$data['show']  			= FALSE;
			$this->allow 			= $this->auth->isAuthMenu(28);
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('taspen/upload/daftar',$data);
		}
        else
        {			
			
			$q				  = $this->uploadFile->getDaftar($daftar);
			if($perintah == 1) {
				
				$data['menu']    		=  $this->menu->build_menu();
				$data['name']     		=  $this->auth->getName();
				$data['jabatan']  		=  $this->auth->getJabatan();
				$data['member']	  		=  $this->auth->getCreated();
				$data['avatar']	  		=  $this->auth->getAvatar();
				$data['daftar']    		= $q;
				$data['show']  			= TRUE;
				$this->allow 			= $this->auth->isAuthMenu(28);
				if(!$this->allow)
				{
					$this->load->view('403/index',$data);
					return;
				}
				$this->load->view('taspen/upload/daftar',$data);
			}
			else
			{
				$this->_getExcelDokumen($q);
				
			}
	    }	
	}	
	
	private function _getExcelDokumen($q)
	{
		// creating xls file
		$now              = date('dmYHis');
		$filename         = "DAFTAR DOKUMEN TASPEN ".$now.".xls";
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/x-msdownload');
		header('Content-Disposition:attachment; filename='.$filename);                      
		header('Expires:0'); 
		
		$html  = 'DAFTAR DOKUMEN TASPEN';
		if($q->num_rows() > 0){
			$row = $q->row();
		$html .= '<table>';	
		$html .= '<tr><td  colspan=2>TANGGAL</td><td>'.date('d-M-Y H:i:s').'</td></tr>';
		$html .= '</table><p></p>';
		}
		$html .= '<style> .str{mso-number-format:\@;}</style>';
		$html .= '<table border="1">';					
		$html .='<tr>
					<th>DOKUMEN</th>					
					<th>NIP</th>
					<th>KETERANGAN</th>
					<th>BY</th>
					<th>UPLOAD DATE</th>				
					'; 
		$html 	.= '</tr>';
		if($q->num_rows() > 0){
			$i = 1;		        
			foreach ($q->result() as $r) {				
				$html .= "<tr><td>{$r->nama_dokumen}</td>";               			
				$html .= "<td class=str>{$r->nip}</td>";	
                $html .= "<td>{$r->keterangan}</td>";	
				$html .= "<td>{$r->upload_name}</td>";
				$html .= "<td>{$r->created_date}</td>";
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
	
	/* Usul*/
	public function usul()
	{
		$data['menu']      =  $this->menu->build_menu();
		$data['lname']     =  $this->auth->getLastName();        
		$data['name']      =  $this->auth->getName();
		$data['jabatan']   =  $this->auth->getJabatan();
		$data['member']	   =  $this->auth->getCreated();
		$data['avatar']	   =  $this->auth->getAvatar();	
		$data['layanan']   =  $this->usul->getLayanan();
		$data['dokumen']   =  $this->usul->getDokumen();
		$data['upload']	   =  $this->usul->getUpload();
		$data['usul']	   =  $this->usul->getUsul();
		$data['golongan']  =  $this->usul->getGolongan();
		
		$this->load->view('taspen/usul/index',$data);
	}	
	
	public function saveUsul()
	{
	    $this->form_validation->set_rules('usul_id', 'Usul Id', 'trim');
		$this->form_validation->set_rules('nomor_usul', 'Nomor Usul', 'trim|required');	
		$this->form_validation->set_rules('tgl_usul', 'Tanggal Usul', 'trim|required');
		$this->form_validation->set_rules('layanan_id', 'Pelayanan', 'trim|required');
		$this->form_validation->set_rules('nama_janda_duda', 'Nama Janda/Duda', 'required');
		$this->form_validation->set_rules('nama_pns', 'Nama PNS', 'required');
		$this->form_validation->set_rules('nopen', 'NOPEN / No. Dosir', 'required');
		$this->form_validation->set_rules('nip', 'NIP / NRP / NVP', 'required');
		$this->form_validation->set_rules('golongan', 'Pangkat/Golongan', 'required');
		$this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
		$this->form_validation->set_rules('unit_kerja', 'Unit Kerja Terakhir', 'required');
		$this->form_validation->set_rules('tgl_perkawinan', 'Tanggal Perkawinan', 'required');
		$this->form_validation->set_rules('meninggal_dunia', 'Tanggal Meninggal Dunia', 'required');
		$this->form_validation->set_rules('gaji_pokok_terakhir', 'Gaji Pokok Terakhir', 'required');
		$this->form_validation->set_rules('pensiun_pokok_terakhir', 'Pensiun Pokok Terakhir', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat Ybs', 'required'); 
		
		
		if($this->form_validation->run() == FALSE)
		{
			$data['form_error']      =   validation_errors();
		}
		else
		{
			$target_dir						= './uploads/taspen';			
			$config['upload_path']          = $target_dir;			
			$config['max_size']             = 3024;
			$config['encrypt_name']			= FALSE;	
			$config['overwrite']			= TRUE;	
			$config['detect_mime']			= TRUE;
			$config['file_name']            = 'PENGANTAR_'.$this->input->post('nip');
			$config['allowed_types']        = 'pdf';
			
			$this->load->library('upload', $config);	
			$this->upload->display_errors('', '');
			
			$data['nomor_usul']      = $this->input->post('nomor_usul');
			$data['tgl_usul']        = date('Y-m-d',strtotime($this->input->post('tgl_usul')));
			$data['layanan_id']      = $this->input->post('layanan_id');
			$data['nama_janda_duda'] = $this->input->post('nama_janda_duda');
			$data['nama_pns']        = $this->input->post('nama_pns');
			$data['nopen']			 = $this->input->post('nopen');
			$data['nip']             = $this->input->post('nip');
			$data['golongan']        = $this->input->post('golongan');
			$data['jabatan']         = $this->input->post('jabatan');
			$data['unit_kerja']      = $this->input->post('unit_kerja');
			$data['tgl_perkawinan']  = date('Y-m-d',strtotime($this->input->post('tgl_perkawinan')));
			$data['meninggal_dunia'] = date('Y-m-d',strtotime($this->input->post('meninggal_dunia')));
			$data['gaji_pokok_terakhir'] 	= $this->input->post('gaji_pokok_terakhir');
			$data['pensiun_pokok_terakhir'] = $this->input->post('pensiun_pokok_terakhir');
			$data['alamat'] 				= $this->input->post('alamat');
			$usul_id                 	 	= $this->input->post('usul_id');
			
					
			if($_FILES['file']['name'] != NULL){
				
				if ( ! $this->upload->do_upload('file'))
				{
					$data['error']	= $this->upload->display_errors();				
				}
				else
				{
					$upload 			     = $this->upload->data();	
					$data['file_pengantar']  = $upload['file_name'];
					
					if(!empty($usul_id))					
					{
						$data['usul_id'] = $usul_id;
						$result          = $this->usul->updateUsul($data);
					}
					else
					{					
						$result          = $this->usul->saveUsul($data);
					}
					
					$response        = $result['response'];
					
					if($response === TRUE)
					{
						$h ='<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Alert!</h4>'.$result['pesan'].'</div>';
						$data['pesan']	= $h;	
						
					}
					else
					{
						$h ='<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Alert!</h4>'.$result['pesan'].'</div>';
						$data['pesan']	= $h;		
					}	
				}
            }
            else
			{
				if(!empty($usul_id))					
				{
					$data['usul_id'] = $usul_id;
					$result          = $this->usul->updateUsul($data);
				}
				else
				{					
					$result          = $this->usul->saveUsul($data);
				}
				
				$response        = $result['response'];
				
				if($response === TRUE)
				{
					$h ='<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Alert!</h4>'.$result['pesan'].'</div>';
					$data['pesan']	= $h; 		
					
				}
				else
				{
					$h ='<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Alert!</h4>'.$result['pesan'].'</div>';
					$data['pesan']	= $h; 		
				}	
			}
		}
		
		$data['menu']      =  $this->menu->build_menu();
		$data['lname']     =  $this->auth->getLastName();        
		$data['name']      =  $this->auth->getName();
		$data['jabatan']   =  $this->auth->getJabatan();
		$data['member']	   =  $this->auth->getCreated();
		$data['avatar']	   =  $this->auth->getAvatar();	
		$data['layanan']   =  $this->usul->getLayanan();
		$data['dokumen']   =  $this->usul->getDokumen();
		$data['upload']	   =  $this->usul->getUpload();
		$data['usul']	   =  $this->usul->getUsul();
		$data['golongan']  =  $this->usul->getGolongan();
				
		$this->load->view('taspen/usul/index',$data);
		
	}	
	
	
	
	public function getUsul()
	{
		$this->form_validation->set_rules('find', 'find', 'trim');	
		
		if($this->form_validation->run() == FALSE)
		{
			$data['form_error']      =   validation_errors();

		}
		else
		{
		
		}
		
		$data['menu']      =  $this->menu->build_menu();
		$data['lname']     =  $this->auth->getLastName();        
		$data['name']      =  $this->auth->getName();
		$data['jabatan']   =  $this->auth->getJabatan();
		$data['member']	   =  $this->auth->getCreated();
		$data['avatar']	   =  $this->auth->getAvatar();	
		$data['layanan']   =  $this->usul->getLayanan();
		$data['dokumen']   =  $this->usul->getDokumen();
		$data['upload']	   =  $this->usul->getUpload();
		$data['usul']	   =  $this->usul->getUsul();
		$data['golongan']  =  $this->usul->getGolongan();

		$this->load->view('taspen/usul/index',$data);
	}	
	
	public function kirim()
	{
		$data['response']	= $this->usul->setKirim();
		
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
		
	}
	
	public function getUsulAll()
	{
		$usul 		= $this->usul->getUsul();		
		$html = '';
		$html .='<table id="tb-usul" class="table table-striped table-condensed">
						<thead>
						    <tr>
								<th></th>
								<th>NOMOR</th>									
								<th>TGL USUL</th>
								<th>NIP</th>
								<th>NAMA PNS</th>
								<th>NAMA</th>
								<th>PELAYANAN</th>
								<th>FILE</th>
								<th>SYSDATE</th>
						    </tr>
					</thead>';
		$html .='<tbody>';	
		foreach($usul->result() as $value)
		{
			$html .='<tr>';
			$html .='<td>';
            $html .='<button class="edit btn btn-primary btn-xs" data-tooltip="tooltip"  title="Edit Usul" data-nomor="'.$value->nomor_usul.'" data-tgl="'.$value->tgl.'" data-layanan="'.$value->layanan_id.'" data-nama="'.$value->nama_pns.'" data-jd="'.$value->nama_janda_duda.'" data-nopen="'.$value->nopen.'" data-usul="'.$value->usul_id.'" data-nip="'.$value->nip.'"><i class="fa fa-edit"></i></button>';
            $html .='&nbsp;<a href="#" class="btn btn-danger btn-flat btn-xs" data-tooltip="tooltip"  title="Kirim Usul BKN" data-toggle="modal" data-target="#kirimModal" data-nip="'.$value->nip.'" data-usul="'.$value->usul_id.'" ><i class="fa fa-mail-forward"></i></a>';
			$html .='</td>';
			$html .='<td>'.$value->nomor_usul.'</td>';
			$html .='<td>'.$value->tgl.'</td>';
			$html .='<td>'.$value->nip.'</td>';
			$html .='<td>'.$value->nama_pns.'</td>';
			$html .='<td>'.$value->nama_janda_duda.'</td>';
			$html .='<td>'.$value->layanan_nama.'</td>';
			$html .='<td>';
			if(!empty($value->file_pengantar))
			{
				$file = $value->file_pengantar;				
				$html .= '<span data-toggle="tooltip" data-original-title="Ada File Pengantar">
				<i class="fa fa-file-pdf-o" data-toggle="modal" data-target="#showFile" data-id="?t='.$this->myencrypt->encode("application/pdf").'&f='.$this->myencrypt->encode($file).'" style="color:red;"></i></span>';
			}
			else
			{
				$html .= '<span data-toggle="tooltip" data-original-title="Tidak Ada File Pengantar">
				<i class="fa fa-file-o" style="color:red;"></i></span>';
			}
			$html .='</td>';
			$html .='<td>'.$value->created_date.'</td>';
            $html .='</tr>';
		}
		$html .='</tbody></table>';
		echo $html;	
	}	
	
	public function getKelengkapan()	{
		
		$nip         = $this->myencrypt->decode($this->input->get('n'));
		$berkas      = $this->usul->getUploadDokumen($nip);
		$layanan     = $this->myencrypt->decode($this->input->get('l'));
		
		$html = '';
		$html .='<table class="table table-bordered table-striped table-condensed">
						<thead>
						    <tr>
							<td colspan="4">LAYANAN USUL '.$layanan.'</td>
							</tr>
							<tr>
								<th>ADA</th>
								<th>NAMA BERKAS</th><th></th></tr></thead>';
		foreach($berkas->result() as $value)
		{
			$html .='<tr>
						<td><i class="fa fa-check" style="color:green;"></i></td>	
						<td>'.$value->keterangan.'</td>
						<td><button class="btn bg-navy btn-flat btn-xs" data-tooltip="tooltip"  title="Lihat File" data-toggle="modal" data-target="#showFile" data-id="?f='.$this->myencrypt->encode($value->file_name).'&t='.$this->myencrypt->encode($value->file_type).'"><i class="fa fa-search"></i></button></td>
						</tr>';	
		}
		$html .='</table>';
		
		echo $html;
	}
	
	
	
	public function getInlineTaspen()
	{
		$file      = $this->myencrypt->decode($this->input->get('f'));
		$type      = $this->myencrypt->decode($this->input->get('t'));
					
		ob_clean();			
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:'.$type.'');
		header('Content-Disposition:inline; filename='.$file);                      
		header('Expires:0'); 
		readfile(base_url().'uploads/taspen/'.$file);
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
		readfile(base_url().'taspen/dokumen/'.$file);
	}	
	
	/*  cek berkas */
	public function lacak()
	{
	   
		$data['menu']      		=  $this->menu->build_menu();
		$data['lname']    		=  $this->auth->getLastName();        
		$data['name']      		=  $this->auth->getName();
        $data['jabatan']   		=  $this->auth->getJabatan();
		$data['member']	   		=  $this->auth->getCreated();
		$data['avatar']	   		=  $this->auth->getAvatar();
		$data['show']  			= FALSE;
		if(!$this->allow)
		{
			$this->load->view('403/index',$data);
			return;
		}
		$this->load->view('taspen/berkas/index',$data);
	}
	
	public function getBerkas()
	{
		$this->form_validation->set_rules('searchby', 'Filter', 'required');
		$this->form_validation->set_rules('search', 'Data', 'required');		
		
		$perintah           		   = $this->input->post('perintah');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['menu']      =  $this->menu->build_menu();
			$data['lname']     =  $this->auth->getLastName();        
			$data['name']      =  $this->auth->getName();
			$data['jabatan']   =  $this->auth->getJabatan();
			$data['member']	   =  $this->auth->getCreated();
			$data['avatar']	   =  $this->auth->getAvatar();			
			
			$data['show']  	   = FALSE;
			if(!$this->allow)
			{
				$this->load->view('403/index',$data);
				return;
			}
			$this->load->view('taspen/berkas/index',$data);
		}
		else
		{	
			$q	  						   = $this->berkas->getBerkas();
				
			if($perintah == 1)			{
				
				$data['menu']     		=  $this->menu->build_menu();
				$data['lname']    		=  $this->auth->getLastName();        
				$data['name']     		=  $this->auth->getName();
				$data['jabatan']  		=  $this->auth->getJabatan();
				$data['member']	  		=  $this->auth->getCreated();
				$data['avatar']	  		=  $this->auth->getAvatar();
				$data['usul']	  		=  $q;				
				$data['show']  			=  TRUE;
				if(!$this->allow)
				{
					$this->load->view('403/index',$data);
					return;
				}
				$this->load->view('taspen/berkas/index',$data);
			}
			else
			{	
				$this->_getBerkasExcel($q);
			}
		}	
	}
	
	private function _getBerkasExcel($q)
	{
		// creating xls file
		$now              = date('dmYHis');
		$filename         = "DAFTAR USUL TASPEN".$now.".xls";
		
		header('Pragma:public');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Content-type:application/x-msdownload');
		header('Content-Disposition:attachment; filename='.$filename);                      
		header('Expires:0'); 
		
		$html  = 'DAFTAR USUL TASPEN';
		if($q->num_rows() > 0){
			$row = $q->row();
		$html .= '<table>';	
		$html .= '<tr><td  colspan=2>TANGGAL</td><td>'.date('d-M-Y H:i:s').'</td></tr>';				
		$html .= '</table><p></p>';		}
		$html .= '<style> .str{mso-number-format:\@;}</style>';
		$html .= '<table border="1">';					
		$html .='<tr>
					<th>NOMOR</th>					
					<th>NIP</th>
					<th>NAMA PNS</th>
					<th>NAMA</th>
					<th>UPDATE</th>
					<th>PELAYANAN</th>
					<th>STATUS</th>
					<th>TAHAPAN</th>'; 
		$html 	.= '</tr>';
		if($q->num_rows() > 0){
			$i = 1;		        
			foreach ($q->result() as $r) {				
				$html .= "<tr><td>{$r->nomor_usul}</td>";               			
				$html .= "<td class=str>".(!empty($r->nip_lama) ? $r->nip_lama.'/'.$r->nip_baru : $r->nip)."</td>";	
                $html .= "<td>{$r->nama_pns}</td>";	
				$html .= "<td>{$r->nama_janda_duda}</td>";	
				$html .= "<td>{$r->updated_date}</td>";
				$html .= "<td>{$r->layanan_nama}</td>";
				$html .= "<td>{$r->usul_status}</td>";
				$html .= "<td>{$r->tahapan_nama}</td>";
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

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */