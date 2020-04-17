<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends CI_Controller {

	function __construct()
	{
	    parent::__construct();		
	    $this->load->library(array('Auth','form_validation','Telegram'));
		$this->load->model('users_model', 'user');			
	} 
	
	public function index()
	{
		$data['message']    = '';
		$data['unit_kerja'] = $this->_getUnitKerja();
		$data['instansi']   = $this->_getInstansi();
		
		$this->load->view('vsignup',$data);		
	}
	
	public function doReg()
	{
	
	    $this->form_validation->set_rules('fname', 'Firtsname', 'trim|required');
		$this->form_validation->set_rules('lname', 'Lastname', 'trim');
		$this->form_validation->set_rules('sex', 'Sex', 'required');
		$this->form_validation->set_rules('instansi', 'Instansi', 'required');
		$this->form_validation->set_rules('bidang', 'Bidang', 'required');
		$this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		
		$set['first_name']   = $this->input->post('fname');
		$set['last_name']    = $this->input->post('lname');
		$set['gender']		 = $this->input->post('sex');
		$set['id_bidang']	 = $this->input->post('bidang');
		$set['jabatan']	     = $this->input->post('jabatan');
		$set['username']     = $this->input->post('username');
		$set['password']     = SHA1($this->input->post('password'));
		$set['nip']          = $this->input->post('username');
		$set['id_instansi']  = $this->input->post('instansi');
		$set['last_ip']      = $this->input->ip_address();
		$set['email']        = $this->input->post('email');
		$set['user_tipe']    = 'instansi';
		
		if($this->form_validation->run() == FALSE)
		{
			$data['message']      = ' ';
			$data['unit_kerja']   = $this->_getUnitKerja();
		    $data['instansi']     = $this->_getInstansi();
			$this->load->view('vsignup',$data);
		}
		else
		{	
			$this->db->trans_start();

			if($this->auth->register($set))
			{
				$data['message']    = ' Your Register is successfuly , please contact administrator to active your account';
				$this->send_to_Telegram($set);
				$this->load->view('vregsuc',$data);
			}
			else
			{
				$data['message']    = '<p class="text-center text-red">'.$this->auth->getRegMessage().'</p>';
				$data['unit_kerja'] = $this->_getUnitKerja();
				$data['instansi']   = $this->_getInstansi();
				$this->load->view('vsignup',$data);
			}	

			$this->db->trans_complete();	
        }      		
    }
	
	public function getPns()
	{
	    $search   = $this->input->get('q');
	    $sql="SELECT PNS_NIPBARU as id,CONCAT( PNS_NIPBARU ,' - ', PNS_PNSNAM)  as text 
		FROM mirror.pupns 
		WHERE TRIM(PNS_NIPBARU)=TRIM('$search') 
		OR TRIM(PNS_PNSNIP)=TRIM('$search') 
		ORDER BY PNS_PNSNAM ASC";
	  
    	$query= $this->db->query($sql);
	    $ret['results'] = $query->result_array();
	    echo json_encode($ret);
	}	
	
	public function getPnsdata()
	{
	    $nip   = $this->input->get('q');
	    $sql="SELECT * FROM mirror.pupns WHERE PNS_NIPBARU='$nip' ";
	  
    	$query		        = $this->db->query($sql);
	    if($query->num_rows()  > 0)
		{
		    $row		= $query->row();
			
			$xnama      = explode(" ",$row->PNS_PNSNAM,2);
			
			if(count($xnama) == 2)
			{
				$fn 	= $xnama[0];
				$ln		= $xnama[1];
			}
			else
			{
				$fn		= $xnama[0];
				$ln		= '';
			}        			
		
            $data[]		= array('PNS_NIPBARU'     		   => $row->PNS_NIPBARU,
								'FIRSTNAME'  			   => $fn,
								'LASTNAME'				   => $ln,
								'PNS_INSKER'    		   => $row->PNS_INSKER,
								'PNS_PNSSEX'    		   => ($row->PNS_PNSSEX == 2 ? 'P' : 'L'),
								'PNS_EMAIL'    		       => $row->PNS_EMAIL,	
								
			);			
		}
		else
		{
		    $data[]		= array('PNS_NIPBARU'     		   => '',
								'PNS_PNSNAM'  			   => '',
								'PNS_INSKER'    		   => '',								
			);	
		}
		
		echo json_encode($data);	
	}	
    
    function _getUnitKerja()
    {
        return $this->user->getBidang();
    }	
	
	function _getInstansi()
    {
        return $this->user->getInstansi();
    }	
	
	/* Kirim Notifikasi Pendaftaran ke Telegram*/
	
	function send_to_Telegram($data)
	{
		$AdminTelegram   = $this->getAdmin();
		
		if($AdminTelegram->num_rows() > 0)
		{	
			foreach($AdminTelegram->result() as $value)
			{
				$instansi		= $this->_getInstansi_name_by_id($data['id_instansi']);
				// send to telegram API
				$this->telegram->sendApiAction($value->telegram_id);
				$text = "<pre>Hello, <strong>".$value->first_name ." ".$value->last_name. "</strong> Ada Member baru nih";
				$text .= "\n Nama :". $data['first_name']." ".$data['last_name'];
				$text .= "\n NIP  :". $data['nip'];
				$text .= "\n Instansi  :". $instansi.'</pre>';
				$this->telegram->sendApiMsg($value->telegram_id, $text , false, 'HTML');
			}
		}
	}	
	
	function getAdmin()
	{
		$this->db->select('first_name,last_name,telegram_id');
		$this->db->where('is_admin', 1);
		$app_user		= $this->db->get('app_user');
		return $app_user;
	}	
	
	function _getInstansi_name_by_id($id)
    {
        $this->db->select('INS_NAMINS');
		$this->db->where('INS_KODINS', $id);
		$query	= $this->db->get('mirror.instansi');
		$r  = NULL;
		
		if($query->num_rows() > 0)
		{
			$row    = $query->row();
			$r      = $row->INS_NAMINS;	
		}
		
		return $r;
    }
}

