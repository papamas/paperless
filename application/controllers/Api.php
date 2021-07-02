<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Api extends REST_Controller {
	
	
    public function __construct() {
        parent::__construct();
		
	    $this->load->library(array('form_validation','Token'));
        $this->load->model('api/api_model');

    }
	
	/**
     * @AclName Api
     */
	 
    public function login_post() {
		
        $username 		 = $this->post('username');
        $password		 = SHA1($this->post('password'));
		
		$id = $this->api_model->get_user($username);
		
		
		if($id->num_rows() > 0) 
		{
			$row			   		 = $id->row();
			$hashpassword            = $row->password;
			
			if($password   === $hashpassword)
			{					
				$token['user_id'] 	   	 = $row->user_id;
				$token['active']   		 = $row->active;
				$token['username'] 		 = $username;
				$token['instansi']   	 = $row->id_instansi;
				$date 			   		 = new DateTime();
				$token['iat'] 	   		 = $date->getTimestamp();
				$token['exp']      		 = $date->getTimestamp() + 60*60*5;
				
				
				// return response
				$data                    = array('user_id' 		  => $row->user_id,
				                                 'nip'            => $row->nip,
												 'last_name'	  => $row->last_name,
												 'first_name'     => $row->first_name,
												 'email'    	  => $row->email,
												 'created_date'   => $row->created_date,
												 'last_access'    => $row->last_access,
				);
				
				$meta                   = array('token'        => $this->token->generateToken($token));
				
				$output['message']       = "success";
				$output['data']      	 = $data;
				$output['meta']      	 = $meta;
				$output['response']      = TRUE; 
				$this->set_response($output, REST_Controller::HTTP_OK);
			}
			else
			{
				
				$output['message']       = "The given data was invalid";
				$output['response']      = FALSE; 
				$this->response($output, REST_Controller::HTTP_BAD_REQUEST);

			}		
		}
		else 
		{
			
			$output['message']       = "The given data was invalid";
			$output['response']      = FALSE; 
			$this->response($output, REST_Controller::HTTP_BAD_REQUEST);
		}  
 
		
		
    }
	
	/* upload  dokumen */
	
	public function uploadDokumen_post()
	{

		$headers = $this->input->request_headers();
		
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				$instansi		= $decoded->instansi;	
				
				
				
				$target_dir						='./uploads/'.$instansi;		
				$config['upload_path']          = $target_dir;
				$config['allowed_types']        = 'pdf';
				$config['max_size']             = 5120;
				$config['encrypt_name']			= FALSE;	
				$config['overwrite']			= TRUE;	
				$config['detect_mime']			= TRUE;
		
				if(!file_exists($target_dir)){
					mkdir($target_dir,0777);
				}

		
				// validasi NIP
			    if(!$this->api_model->isAdaNIP($_FILES['file']['name']))
				{	
					$error = array( 'response'  => FALSE, 
									'message'   => 'Dokumen yang anda upload ini tidak terdapat NIP');
					$this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE);
					
					return FALSE;
					
				}
				
				
				if(!$this->api_model->_is_arsip($_FILES['file']['name']))
				{
					
					$error = array( 'response'   => FALSE, 
									'message'    => 'File ini tidak diperbolehkan untuk diupload');
                    $this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE);

					return FALSE;
                }

		
				if(!$this->api_model->isSesuaiFormat($_FILES['file']['name']))
				{
					$error = array( 'response'   => FALSE,
									'message' 	 => 'File belum sesuai format, silahkan cek pada daftar tabel');
                    $this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE);
                    return FALSE;
				}
				
				

				if(!$this->api_model->isMinorValid($_FILES['file']['name']))
				{
					$error = array(  'response'   => FALSE,
									 'message' 	  => 'File KODE atau TAHUN salah');
                    $this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE); 
					return FALSE;
				}	

                				
		
				// Try cek file		
				$cekFile	= $this->api_model->isAllowSize($_FILES['file']);
				$response   = $cekFile['response'];
				if(! $response)
				{
					$error = array( 'response'     => FALSE,
					                'message'      => $cekFile['pesan']);
					$this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE);
					return FALSE;
				}
		
		        
				// load upload lib
				$this->load->library('upload');
				$this->upload->initialize($config);
				
				



				if ( ! $this->upload->do_upload('file'))
				{
						$error = array( 'response'     => FALSE,
						                'message'      => strip_tags($this->upload->display_errors()));
						$this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE);
                        
						
				}
				else
				{
						$data 					= $this->upload->data();
						
					
						$data['upload_by'] 		= $userId;	
				        $data['id_instansi']	= $instansi;
						$data['api']            = 1;
				
						
						$result		= $this->api_model->insertUpload($data);
						
						
					
						if($result['response'])
						{
							$out['response']    = TRUE;
							$out['insert']      = TRUE;
							$out['message'] 	= $result['pesan'];
							$this->set_response($out, REST_Controller::HTTP_OK);
							
								
						}
						else
						{
							$out['response']    = TRUE;
							$out['update']      = $this->api_model->updateFile($result);
							$out['message'] 	= 'File dokumen kepegawaian sudah ada, overwrite file';
							$this->set_response($out, REST_Controller::HTTP_OK);
							
						}			
						
				}
				
				
			    
				
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}         		
				
				
		
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
		
    }
	
	/* get dokumen upload*/
	public function listUploadDokumen_get()
	{

		$headers = $this->input->request_headers();
		
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				$instansi		= $decoded->instansi;
				
				$rules = array(					
					array(
						'field' => 'nip',
						'label' => 'nip',
						'rules' => 'required'
					)
				);	
				
				$this->form_validation->set_data($this->get());
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == FALSE)
				{
				    $invalidLogin    = ['response'   =>  FALSE, 
					                    'message' => 'Bad Request '];	
				    $this->response($invalidLogin, REST_Controller::HTTP_BAD_REQUEST);
				
				}		
				else
				{
					$data['instansi']       = $instansi;
					$data['searchby']       = 1;
					$data['search']         = $this->get('nip');
			
					$q				  = $this->api_model->getDaftar($data);	
					$out['response']  = TRUE;
					$out['message']   = ($q->num_rows() > 0 ? 'List Of Document Files' :  'No Files' );
					$out['size']      =  $q->num_rows();
					$out['files']     =  $q->result_array();
					$this->set_response($out, REST_Controller::HTTP_OK);
					
				}	
                				
			
				
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}    
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
	}	 
	
	/* HAPUS file dokumen */
    public function hapusDokumen_delete()
	{

		$headers = $this->input->request_headers();
		
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				$instansi		= $decoded->instansi;
				
				$rules = array(					
					array(
						'field' => 'name',
						'label' => 'name',
						'rules' => 'required'
					)
				);	
				
				$this->form_validation->set_data($this->delete());
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == FALSE)
				{
				    $invalidLogin    = ['response'   =>  FALSE, 
					                    'message' => 'Bad Request '];	
				    $this->response($invalidLogin, REST_Controller::HTTP_BAD_REQUEST);
				
				}		
				else
				{
					$path ='/var/www/html/uploads/'.$instansi.'/';
					$file = $this->delete('name');
					$data['instansi']         = $instansi;
					$data['file']             = $file;
					
					
				    if(@unlink($path.$file) && $this->api_model->hapusFile($data))
					{
						$out['response']  = TRUE;
						$out['pesan'] 	 = 'File dokumen berhasil dihapus';
						$this->set_response($out, REST_Controller::HTTP_OK);
					}
					else
					{
						$out['response']  = FALSE;
						$out['pesan'] 	 = 'File dokumen Gagal dihapus';
						$this->set_response($out, REST_Controller::HTTP_BAD_REQUEST);	
					}

                }	
				
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}  	
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
	}	 		

   
   
    /* upload photo */   
    public function uploadPhoto_post()
	{

		$headers = $this->input->request_headers();
		
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				$instansi		= $decoded->instansi;	
				
				
				$target_dir						='./photo/'.$instansi;		
				$config['upload_path']          = $target_dir;
				$config['allowed_types']        = 'jpeg|jpg|JPG|JPEG';
				$config['max_size']             = 1024;
				$config['encrypt_name']			= FALSE;	
				$config['overwrite']			= TRUE;	
				$config['detect_mime']			= TRUE;
				
				if (!is_dir($target_dir)) {
					mkdir($target_dir, 0777, TRUE);
				}
				
				if(!$this->api_model->isAllowFormatPhoto($_FILES['file']['name']))
				{	
					$error = array(  'response'   => FALSE,
									 'message' 	  => 'File Format photo belum sesuai');
                    $this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE); 
					return FALSE;
					
				}		
				
				if(! $this->api_model->_is_photo($_FILES['file']['name'])){
					
					$error = array(  'response'   => FALSE,
									 'message' 	  => 'NIP photo SALAH');
                    $this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE); 
					return FALSE;
					
				}	
				
				$haystack = $_FILES['file']['name'];
		

				if( stripos( $haystack, "KARIS" ) !== false) {
					$nip                            = $this->api_model->_extract_numbers($haystack);
					$config['file_name']            = 'KARIS_'.$nip[0];
				}		
				
				if( stripos( $haystack, "KARSU" ) !== false) {
					$nip                            = $this->api_model->_extract_numbers($haystack);
					$config['file_name']            = 'KARSU_'.$nip[0];
				}	
				
				if( stripos( $haystack, "KARPEG" ) !== false) {
					$nip                            = $this->api_model->_extract_numbers($haystack);
					$config['file_name']            = 'KARPEG_'.$nip[0];
				}									
						
				
				$this->load->library('upload', $config);
				
				if ( ! $this->upload->do_upload('file'))
				{
					$error = array( 'response'     => FALSE,
						            'message'      => strip_tags($this->upload->display_errors()));
					$this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE);
					
				}
				else
				{

					$data 		= $this->upload->data();
					
					$data['upload_by'] 		= $userId;	
				    $data['id_instansi']	= $instansi;
					$data['api']            = 1;
						
					$result		= $this->api_model->insertUploadPhoto($data);
					$this->resizeImage($instansi,$data);
					
					if($result['response'])
					{
						$out['response']    = TRUE;
						$out['insert']      = TRUE;
						$out['message'] 	= $result['pesan'];
						$this->set_response($out, REST_Controller::HTTP_OK);
					}
					else
					{
						$out['response']    = TRUE;
						$out['update']      = $this->api_model->updatePhoto($result);
						$out['message'] 	= 'Photo sudah ada, overwrite photo';
						$this->set_response($out, REST_Controller::HTTP_OK);
							
					}			
				}		
		
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}  
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
	}	 	

    /* List Upload Photo */
	public function listUploadPhoto_get()
	{

		$headers = $this->input->request_headers();
		
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				$instansi		= $decoded->instansi;
				
				$rules = array(					
					array(
						'field' => 'nip',
						'label' => 'nip',
						'rules' => 'required'
					)
				);	
				
				$this->form_validation->set_data($this->get());
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == FALSE)
				{
				    $invalidLogin    = ['response'   =>  FALSE, 
					                    'message' => 'Bad Request '];	
				    $this->response($invalidLogin, REST_Controller::HTTP_BAD_REQUEST);
				
				}		
				else
				{
					$data['instansi']       = $instansi;
					$data['searchby']       = 1;
					$data['search']         = $this->get('nip');
					$q				        = $this->api_model->getDaftarPhoto($data);
					
					$out['response']  = TRUE;
					$out['message']   = ($q->num_rows() > 0 ? 'List Of Photo Files' :  'No Files' );
					$out['size']      =  $q->num_rows();
					$out['files']     =  $q->result_array();
					$this->set_response($out, REST_Controller::HTTP_OK);
				}	
				
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}  	
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
	}	 	
	
	/* HAPUS Photo */
	public function hapusPhoto_delete()
	{

		$headers = $this->input->request_headers();
		
		
		// cek token dulu
		if (array_key_exists('Token', $headers) && !empty($headers['Token']))
		{
			
			try {
			    
				// try decode
				$decoded 		= $this->token->validateToken($headers['Token']);
			    $userId 		= $decoded->user_id;	
				$instansi		= $decoded->instansi;
				
				$rules = array(					
					array(
						'field' => 'name',
						'label' => 'name',
						'rules' => 'required'
					)
				);	
				
				$this->form_validation->set_data($this->delete());
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == FALSE)
				{
				    $invalidLogin    = ['response'   =>  FALSE, 
					                    'message' => 'Bad Request '];	
				    $this->response($invalidLogin, REST_Controller::HTTP_BAD_REQUEST);
				
				}		
				else
				{
					$path ='/var/www/html/photo/'.$instansi.'/';
					$file = $this->delete('name');
					$data['instansi']         = $instansi;
					$data['file']             = $file;
					
					
				    if(@unlink($path.$file) && $this->api_model->hapusPhoto($data))
					{
						$out['response']  = TRUE;
						$out['pesan'] 	 = 'Photo berhasil dihapus';
						$this->set_response($out, REST_Controller::HTTP_OK);
					}
					else
					{
						$out['response']  = FALSE;
						$out['pesan'] 	 = 'Photo Gagal dihapus';
						$this->set_response($out, REST_Controller::HTTP_BAD_REQUEST);	
					}

                }	
				
			} catch (Exception $e) {
					$invalid = ['response'  =>  FALSE, 'message' => $e->getMessage()]; 
					$this->response($invalid, REST_Controller::HTTP_BAD_REQUEST);//400					
			}  	
		}
		else
		{	

			$output['message']         = "The given data was invalid";
			$output['response']        = FALSE; 
			$this->set_response($output, REST_Controller::HTTP_BAD_REQUEST);
		}
		
	}		
  
    public function resizeImage($instansi,$data)
    {	  
	    $source_path = $data['full_path'];
        $target_path = $data['full_path'];	  
	  
        //Compress Image
		$config['image_library']		= 'gd2';
		$config['source_image']			= $source_path;
		$config['create_thumb']			= FALSE;
		$config['maintain_ratio']		= FALSE;
		$config['width']				= 128;
		$config['height']				= 150;
		$config['new_image']			=  $target_path;
		$config['quality']				= '100%';
		$this->load->library('image_lib', $config);
		
        if (!$this->image_lib->resize()) {
            
			$error = array( 'response'     => FALSE,
							'message'      => strip_tags($this->image_lib->display_errors()));
			$this->set_response($error, REST_Controller::HTTP_NOT_ACCEPTABLE);
			
			
        }
        $this->image_lib->clear();
    }	
}