<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Smenu extends MY_Controller {
	
	function __construct()
	{
	    parent::__construct();		
	    $this->load->library(array('Auth','Menu'));
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
		$data['menu2']     =  $this->build_menu();
		$this->load->view('menu/index',$data);
	}
	
	
	public function build_menu()
	{
	    $this->load->model('Menu_model','main_menu');
        $query = $this->main_menu->getMenu()->result_array();
		
	    $array = $query;
		// key the array by id
		$keyed = array();
		foreach($array as &$value)
		{
			$keyed[$value['menu_id']] = &$value;
		}
		unset($value);
		$array = $keyed;
		unset($keyed);

		// tree it
		$tree = array();
		foreach($array as &$value)
		{
			if ($parent = $value['parent'])
			{
				$array[$parent]['children'][] = &$value;
				$array[$parent]['expanded'] = false;				
			}
			else
			{
				$tree[] = &$value;		
			}	
		}
		unset($value);
		$array = $tree;
		unset($tree);
        
	    /*
		echo "<pre>";
		print_r($array);
        echo "</pre>"; */
				
		$myMenu ='';
		foreach($array as $arr){
		  $myMenu .= $this->createMenu($arr);
		  
		}
		return  '<ul><li>MAIN NAVIGATION</li>'.$myMenu.'</ul>';
		
	
	}
	
	function createMenu($arr)
	{
     	$str = '';
		if(is_array($arr)){
                if(!isset($arr['menu_name']))
			    {
			        foreach($arr as $v)
					{
				     	if(isset($v['children']))
						{
							$str .= '<li><a href="'.$v['link'].'"> '.
							'<i class="'.$v['icon'].'"></i><span> '. $v['menu_name'].
							'</span></a>';
							$str .='<ul>';
							$str .=  $this->createMenu($v['children']);                   
							$str .="</ul>";
						}
						else
						{
						    $str .= '<li><a href="'.site_url().'/'.$v['link'].'">'.
							'<i class="'.$v['icon'].'"></i><span> '. $v['menu_name'].
							'</span></a>';
						}
						
						
					}
                }
				else
				{
				    if(empty($arr['children']))
					{
					   $str .= '<li ><a href="'.$arr['link'].'"> <i class="'.$arr['icon'].'"></i><span>'.$arr['menu_name'].'</span></a>';
				   
					}
					else
					{
					$str .= '<li ><a href="'.$arr['link'].'"><i class="'.$arr['icon'].'"></i><span>'.$arr['menu_name'].'</span></a>';
				    }
				}
				
			if(isset($arr['children'])){
			    
				$str .='<ul>';
				$str .= $this->createMenu($arr['children']);                   
				$str .="</ul>";
		    }
			$str .= "</li>";               
		}
		
		return $str;  
	}
}
