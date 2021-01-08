
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bot extends CI_Controller {
		
	public function index()
	{
		while (true) {
			$this->_init();			
		} 
	}
	
	function _init()
	{
		$this->load->library('Telegram');
		
		$idfile = 'botposesid.txt';
        $update_id = 0;
		
		if (file_exists($idfile)) {
			$update_id = (int) file_get_contents($idfile);
			echo '-';
		}
		
		$this->telegram->setOffset($update_id);				
		$updates   = $this->telegram->getApiUpdate();
		
		foreach ($updates as $message) {
			$update_id = $this->telegram->prosesApiMessage($message);
			echo '+';
		}
		
		file_put_contents($idfile, $update_id + 1);
	}	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */