<?php

class MY_Controller  extends CI_Controller  {
	function __construct()
	{
		parent::__construct();	
		$this->load->library('Auth');
		if(!$this->auth->isLoggedin())
		{
			redirect('autho','refresh');
	        		
		}
		else
		{
            $this->auth->setLastAccess();
			
        }	
	}
	
}