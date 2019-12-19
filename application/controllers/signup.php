<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller {

	
	public function index()
	{
		$data['message']    = '';
		$data['unit_kerja'] = $this->_getUnitKerja();
		$this->load->view('vsignup',$data);		
	}
	
	public function register()
	{
	    
		$this->load->library('Auth');
		$firstname   = $this->input->post('firstname');
		$lastname    = $this->input->post('lastname');
		$gender		 = $this->input->post('gender');
		$unit_kerja	 = $this->input->post('unit_kerja');
		$jabatan	 = $this->input->post('jabatan');
		$username    = $this->input->post('username');
		$password    = $this->input->post('password');
		
		if($this->input->post())
		{
		
			if($this->auth->register($username, $password,$firstname,$lastname,$gender,$unit_kerja,$jabatan))
			{
				$data['message']    = ' Your Register is successfuly , please contact administrator to active your account';
				$this->load->view('vregsuc',$data);
			}
			else
			{
				$data['message']    = '<p class="register-box-msg text-red">'.$this->auth->getRegMessage().'</p>';
				$data['unit_kerja'] = $this->_getUnitKerja();
				$this->load->view('vsignup',$data);
			}
		}
        else
        {
            $data['message']    = ' ';
			$data['unit_kerja'] = $this->_getUnitKerja();
			$this->load->view('vsignup',$data);
        }		
    }
    
    function _getUnitKerja()
    {
        $this->load->database();
		$this->db->select('id_unit,nama_unit');
		$this->db->order_by('nama_unit', 'asc');
        return $this->db->get('unit_kerja');
    }	
}

