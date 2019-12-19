<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Menu Class
 *
 * Authentication library for Code Igniter.
 * @author		Nur Muhamad Holik -2016
 * @version		1.0.0
 */
 
class Menu extends Auth {   
    
    public function build_menu()
	{
	    $this->ci->load->model('Menu_model','main_menu');
        $query = $this->ci->main_menu->getMenu()->result_array();
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
			
			if (isset($value['parent']))
			{
				$parent = $value['parent'];
				$array[$parent]['children'][] = &$value;
				$array[$parent]['expanded'] = false;				
			}
			else
			{
				$tree[] = &$value;		
			}	
		}
		
		
		/*
		echo "<pre>";
		print_r($tree['parent']);
		echo "</pre>";
		*/
		
		
		unset($value);
		$array = $tree;
		unset($tree);
		
		$myMenu ='';
		foreach($array as $arr){
		  $myMenu .= $this->createMenu($arr);
		  
		}
		return  '<ul class="sidebar-menu"><li class="header">MAIN NAVIGATION</li>'.$myMenu.'</ul>';
		
	
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
							$str .= '<li  class="treeview"><a href="'.$v['link'].'">'.
							'<i class="'.$v['icon'].'"></i><span> '. $v['menu_name'].
							'</span><i class="fa fa-angle-left pull-right"></i></a>';
							$str .='<ul class="treeview-menu">';
							$str .=  $this->createMenu($v['children']);                   
							$str .="</ul>";
						}
						else
						{
						    if(isset($v['menu_name']))
							{
								$str .= '<li  class="treeview"><a href="'.site_url().'/'.$v['link'].'">'.
								'<i class="'.$v['icon'].'"></i><span> '. $v['menu_name'].
								'</span></a>';
							}	
						}
						
						
					}
                }
				else
				{
				    if(empty($arr['children']))
					{
					   $str .= '<li class="treeview"><a href="'.site_url().'/'.$arr['link'].'"><i class="'.$arr['icon'].'"></i><span>'.$arr['menu_name'].'</span></a>';
				   
					}
					else
					{
					$str .= '<li class="treeview"><a href="'.$arr['link'].'"><i class="'.$arr['icon'].'"></i><span>'.$arr['menu_name'].'</span><i class="fa fa-angle-left pull-right"></i></a>';
				    }
				}
				
			if(isset($arr['children'])){
			    
				$str .='<ul class="treeview-menu">';
				$str .= $this->createMenu($arr['children']);                   
				$str .="</ul>";
		    }
			$str .= "</li>";               
		}
		
		return $str;  
	}
	
	
}


	
	
 
