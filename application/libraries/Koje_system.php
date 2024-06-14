<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
class koje_system {
	protected $CI;
  public $breadcrumb = array();
	public function __construct()
  {
		global $config;
  	$this->CI =& get_instance();
		$this->CI->koje_config = $config;
  }

	function getCurrentModule()
	{
		$str = $this->CI->uri->slash_segment(1).$this->CI->uri->segment(2);
		return $str;
	}

	function html_init_css() {
		$base_url = base_url();
		$str ="
			<link rel='shortcut icon' href='{$base_url}assets/favicon.png' type='image/x-icon'>
			<link rel='stylesheet' href='{$base_url}assets/components/googlefonts/roboto-font/roboto-font.css'/>
			<link rel='stylesheet' href='{$base_url}assets/components/datatables/css/jquery.dataTables.min.css'/>
			<link rel='stylesheet' href='{$base_url}assets/components/datatables/css/buttons.dataTables.min.css'/>
			<link rel='stylesheet' href='{$base_url}assets/components/datatables/css/buttons.bootstrap.min.css'/>
			<link rel='stylesheet' href='{$base_url}assets/components/datatables/css/responsive.dataTables.min.css'/>
			<link rel='stylesheet' href='{$base_url}assets/components/datatables/css/colReorder.dataTables.min.css'/>
			<link rel='stylesheet' href='{$base_url}assets/components/datatables/css/fixedHeader.dataTables.min.css'/>
			<link rel='stylesheet' href='{$base_url}assets/components/bootstrap/css/bootstrap.min.css'/>
			<link rel='stylesheet' href='{$base_url}assets/components/fontawesome/css/font-awesome.min.css'/>
			<link rel='stylesheet' href='{$base_url}assets/components/adminlte/css/AdminLTE.min.css'/>
			<link rel='stylesheet' href='{$base_url}assets/components/adminlte/css/bootstrap-material-design.min.css'/>
			<link rel='stylesheet' href='{$base_url}assets/components/adminlte/css/MaterialAdminLTE.min.css'/>
			<link rel='stylesheet' href='{$base_url}assets/components/adminlte/css/skins/skin-md-blue-light.min.css'/>
			<link rel='stylesheet' href='{$base_url}assets/components/datepicker/css/bootstrap-datepicker.min.css'/>
			<link rel='stylesheet' href='{$base_url}assets/components/datetimepicker/css/bootstrap-datetimepicker.min.css'/>
			<link rel='stylesheet' href='{$base_url}assets/components/select2/css/select2.min.css'/>
			<link rel='stylesheet' href='{$base_url}assets/components/gritter/css/jquery.gritter.min.css'/>
			<link rel='stylesheet' href='{$base_url}assets/koje.css' />
		";
		return $str;
	}
	function html_init_js() {
		$base_url = base_url();
		$str ="
			<script src='{$base_url}assets/components/jquery/js/jquery.min.js'></script>
			<script src='{$base_url}assets/components/jqueryui/js/jquery-ui.min.js'></script>
			<script src='{$base_url}assets/components/bootstrap/js/bootstrap.min.js'></script>
			<script src='{$base_url}assets/components/adminlte/js/material.min.js'></script>
			<script src='{$base_url}assets/components/gritter/js/jquery.gritter.min.js'></script>
			<script src='{$base_url}assets/components/lodash/js/lodash.min.js'></script>
			<script src='{$base_url}assets/components/moment/js/moment.min.js'></script>
			<script src='{$base_url}assets/components/select2/js/select2.full.min.js'></script>
			<script src='{$base_url}assets/components/datepicker/js/bootstrap-datepicker.min.js'></script>
			<script src='{$base_url}assets/components/datetimepicker/js/bootstrap-datetimepicker.min.js'></script>
			<script src='{$base_url}assets/components/inputmask/js/jquery.inputmask.bundle.min.js'></script>
			<script src='{$base_url}assets/components/inputmask/js/inputmask.binding.min.js'></script>
			<script src='{$base_url}assets/components/validation/js/jquery.validate.min.js'></script>
			<script src='{$base_url}assets/components/slimscroll/js/jquery.slimscroll.min.js'></script>
			<script src='{$base_url}assets/components/chart/js/Chart.min.js'></script>
			<script src='{$base_url}assets/components/chart/js/Chart.min.js'></script>
			<script src='{$base_url}assets/plugins/numberToWordsInRupiah.js'></script>

			<script src='{$base_url}assets/components/datatables/js/jquery.dataTables.min.js'></script>
			<script src='{$base_url}assets/components/datatables/js/dataTables.buttons.min.js'></script>
			<script src='{$base_url}assets/components/datatables/js/buttons.html5.min.js'></script>
			<script src='{$base_url}assets/components/datatables/js/buttons.print.min.js'></script>
			<script src='{$base_url}assets/components/datatables/js/buttons.colVis.min.js'></script>
			<script src='{$base_url}assets/components/datatables/js/jszip.min.js'></script>
			<script src='{$base_url}assets/components/datatables/js/pdfmake.min.js'></script>
			<script src='{$base_url}assets/components/datatables/js/vfs_fonts.js'></script>
			<script src='{$base_url}assets/components/datatables/js/dataTables.responsive.min.js'></script>
			<script src='{$base_url}assets/components/datatables/js/dataTables.colReorder.min.js'></script>
			<script src='{$base_url}assets/components/datatables/js/dataTables.fixedHeader.min.js'></script>
			<script src='{$base_url}assets/components/datatables/js/percentageBars.js'></script>
			<script src='{$base_url}assets/components/datatables/js/datetime.js'></script>

			<script src='{$base_url}assets/components/adminlte/js/adminlte.min.js'></script>
			<script src='{$base_url}assets/koje.js'></script>
			<script>var base_url = '{$base_url}index.php/';</script>
		";
		return $str;
	}
	function html_side_menu() {
		$menu = $this->getMenu();
		$logo = img(array('src' => $this->getLogoURL(),'class'=>'koje-logo', 'alt'=>'Logo'));
		$str = <<<EOF
			<aside class="main-sidebar">
				<section class="sidebar">
					<div class="user-panel">
						<div class="pull-left image">
							$logo
						</div>
					</div>
					$menu
				</section>
			</aside>
			EOF;
	return $str;
	}
	function html_init_meta() {
		$base_url = base_url();
		$str ="
			<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />
			<meta charset='utf-8' />
			<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
		";
		return $str;
	}
	function html_init_title() {
		return "<title>".$this->CI->koje_config['KOJE_APP_TITLE']."</title>";
	}
	function load_asset()
	{
		$str  = $this->html_init_meta().
						$this->html_init_title().
						link_tag(base_url().$this->CI->config->item('KOJE_FAVICON'), 'shortcut icon', 'image/ico').
						$this->html_init_css().
						$this->html_init_js()
						;
		return $str;
	}

/* ----------------------- Menu -------------------------------------------*/
	function _getItemByModule($el, $arr) {
		if(!is_array($arr)) {
			return false;
		}
		foreach ($arr as $key => $value) {
			if(isset($value['module']) && $value['module']==$el) {
				return $arr[$key];
			}
		}
		return false;
	}
	function _getItembyId($el, $arr) {
		foreach ($arr as $key => $value) {
			if($value['sys_access_id']==$el) {
				return $arr[$key];
			}
		}
		return false;
	}
	function html_init_breadcrumb($items) {
		$this->breadcrumb = array();
		$mod = $this->getCurrentModule();
		$item  = $this->_getItemByModule($mod, $items);
		$finish = false;
		while (!$finish) {
			if($item) {
				if(!$item['module']) {
					$item['module'] = "#";
				}
				$this->breadcrumb[$item['sys_access_id']] = $item;
				if(isset($item['sys_parent_id']))
				{
					$item  = $this->_getItemById($item['sys_parent_id'], $items);
				}
				else {
					$finish = true;
				}
			}
			else {
				$finish = true;
			}
		}
		$this->breadcrumb = array_reverse($this->breadcrumb, true);
	}
	function html_breadcrumb()
	{
		if(!$this->breadcrumb) {
			$this->generateMenu();
		}
		$str = "<ol class='breadcrumb'><li><i class='fa fa-home'></i> Home</li>";
		foreach($this->breadcrumb as $key => $value) {
			$str .= "<li>{$value['label']}</li>";
		}
		$str .= "</ol>";
		return $str;
	}
	function getListLeader($id=false)
  {
    if(!$id) $id = $this->CI->koje_system->getLoginID();
    $ArrLeader=array();
    $found = true;
    while($found)
    {
      $leader_id = $this->CI->adodb->getOne("select emp_leader from sys_users where id=$id");
      if(!empty($leader_id))
      {
        $ArrLeader[] = $leader_id;
        $id = $leader_id;
      }
      else
      {
        $found = false;
      }
    }
    return $ArrLeader;
  }
	function getMenu()
	{
		$str = $this->CI->session->userdata('MENU');
		if(!$str) {
			$str = $this->generateMenu();
		}
		if(!$str)
		{
			$str .= "<br><center><font color=red>TIDAK ADA MENU UNTUK ANDA. PASTIKAN ANDA SUDAH MENDAPAT HAK AKSES DARI ADMIN DAN TELAH DI APPROVE</font></center>";
			$str .= '<center>'.anchor("_system_/auth/login","RE-LOGIN").'</center>';
		}
		return $str;
	}
	public function generateMenu()
	{
		$id = $this->getLoginID();
		$menu = "";
		$granted_menu = array('welcome/','_setting_/profile_controller');
		$sql = "select distinct
					    a.sys_parent_id,
					    a.module,
					    a.sys_access_id,
					    a.label,
					    a.title,
					    a.icon,
					    a.access_type,
					    a.seq
						from sys_access a, sys_access_groups b, sys_users_groups c
						where b.sys_access_id=a.sys_access_id and c.group_id=b.sys_groups_id and
									a.sys_access_id <> 0 and a.status='A' and c.user_id=$id
						order by seq
					";
		$rs = $this->CI->adodb->execute($sql);
		$items = array();
		while($row = $rs->fetchRow())
		{
			if (($row['access_type']=='ITEMMENU' || $row['access_type']=='SUBMENU')) {
				$row['badge'] = '';
				$items[] = $row;
			}
			if($row['module']) $granted_menu[] = $row['module'];
		}

		if (count($items)>0) {
			$this->html_init_breadcrumb($items);
			$menu = new koje_menu();
			$menu = $menu->get_menu_html_sidebar($items);
			$this->CI->session->set_userdata('MENU', $menu);
			$this->CI->session->set_userdata('MENU_ITEM', $items);
			$this->CI->session->set_userdata('ACCESS_RIGHT', $granted_menu);
			$this->CI->session->set_userdata('LIST_LEADER', $this->getListLeader($id));
		}
		else {
			$this->CI->session->set_userdata('MENU', false);
			$this->CI->session->set_userdata('MENU_ITEM', false);
			$this->CI->session->set_userdata('ACCESS_RIGHT', false);
			$this->CI->session->set_userdata('LIST_LEADER', false);
		}
		return $menu;
	}
/* ------------------------------------------ Login --------------------------*/
				  
 
															 
					   
   
				
   
			   
 	function getLoginRO()
	{
		// var_dump($this->CI->session->all_userdata());
		// die();
		$ro_id = $this->CI->session->userdata('r_ro_id');
		if (!empty($ro_id))
		{
			return $ro_id;
		}
		return NULL;
	}
	function getLoginID()
	{
		// var_dump($this->CI->session->all_userdata());
		// die();
		$user_id = $this->CI->session->userdata('user_id');
		if (!empty($user_id))
		{
			return $user_id;
		}
		return NULL;
	}
	function getLoginUser() {
		$id = $this->CI->session->userdata('identity');
		if (!empty($id))
		{
			return $id;
		}
		return NULL;
	}
	function getLoginPict($id=false)
	{
		if(!$id) {
			$id = $this->getLoginID();
		}
		if(!$id) return 'none.jpg';
		if(file_exists("data/picture/{$id}.png")) {
			return "$id.png";
		}
		if(file_exists("data/picture/{$id}.jpg")) {
			return "{$id}.jpg";
		}
		return 'none.jpg';
	}
							 
  
												   
					
   
				
   
			  
  
								  
  
														 
					
   
				
   
			  
  
	function getLoginPictURL($id=false) {
		return base_url()."data/picture/".$this->getLoginPict($id)."?id=".rand();
	}
	function getLogoURL($id=false)
	{
		return base_url().$this->CI->koje_config['KOJE_LOGO'];
	}
	function getLoginEmail()
	{
		$user_email = $this->CI->session->userdata('email');
		if (!empty($user_email))
		{
			return $user_email;
		}
		return NULL;
	}

/* ------------------------------------------- utils ----------------------*/
	function base64Encode($input) {
		return rtrim( strtr( base64_encode( $input ), '+/', '-_'), '=');
	}
	function base64Decode($input) {
		return base64_decode( strtr( $input, '-_', '+/') . str_repeat('=', 3 - ( 3 + strlen( $input )) % 4 ));
	}

	function removeZeroTime($str)
	{
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) 00:00:00$/",$str))
		{
			return str_replace(" 00:00:00","", $str);
		}
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) 00:00$/",$str))
		{
			return str_replace(" 00:00","", $str);
		}
		return $str;
	}

	function number_format($number , int $decimals = 0 , $dec_point = "." , $thousands_sep = "," )
	{
		return number_format ($number,$decimals,$dec_point,$thousands_sep);
	}

	function getTitleLabel($key, $val, $title, $default='')
	{
		global $koje_label;
		$str = isset($val[$title]) ? $val[$title] : false;
		if(!$str) {
			$str = isset($koje_label[$key]) ? $koje_label[$key] : $default;
		}
		if(!$str) {
			$str = ucwords(str_replace('_',' ',str_replace('_desc','',$key)));
		}
		return $str;
	}
	function uploadFile($fname)
	{
		if(empty($_FILES[$fname]['name']))
		{
			$result[1] = "10";
			$result[2] = "NO FILE";
		}
		elseif (!$this->CI->upload->do_upload($fname))
    {
			$result[1] = "01";
			$result[2] = $_FILES[$fname]['name'].'=>'.$this->CI->upload->display_errors();
    }
    else
    {
			$data 		 = $this->CI->upload->data();
			$result[1] = "00";
			$result[2] = $this->CI->app_db->createFile($data);
    }
		return $result;
	}
/* ------------------------ Url -------------------------------------------*/
	function linkParam($link, $Param) {
			$CI =& get_instance();
			$arrURL = parse_url($link);
			$arrParam = array();
			if (isset($arrURL['query'])) parse_str($arrURL['query'],$arrParam);
			$arrParam = array_merge($arrParam,$Param);
			return site_url($arrURL['path'].'?'.http_build_query($arrParam));
	}

	function URLRemoveBlank($link) {
		$arrURL = parse_url($link);
		$arrParam = array();
		if (isset($arrURL['query'])) parse_str($arrURL['query'],$arrParam);
		$this->ArrayRemoveEmpty($arrParam);
		return $this->URLBuild(false, false, http_build_query($arrParam));
	}

	function URLCurrent() {
		return $this->URLBuild(false,false,$_SERVER['QUERY_STRING']);
	}

	function URLCancel() {
		return $this->CI->koje_system->URLBuild(false,'index',$_SERVER['QUERY_STRING']);
	}
	function URLBuild($class=false, $method=false, $qstr=false) {
		$sub = $this->CI->uri->slash_segment(1);
		if (!$class) $class = $this->CI->router->class;
		if (!$method) $method = $this->CI->router->method;
		$method = $method=='index' ? "" : "/".$method;
		return site_url($sub.$class.$method."?".$qstr);
	}

	function ArrayRemoveEmpty(&$params) {
		foreach($params as $key => $val) {
			if (!$val || $val=="") {
				unset($params[$key]);
			}
		}
	}

	function getArrayVal($arr, $key, $default=false) {
		if (isset($arr[$key]) && is_array($arr)) return $arr[$key];
		else return $default;
	}

	static function getRemoteAddr() {
		if (!isset($_SERVER['REMOTE_ADDR'])) return '127.0.0.1';
		else return $_SERVER['REMOTE_ADDR'];
	}

	function URLAdd($url, $param="") {
    $str = $url;
    $sign = (strpos($url,'?')===false) ? "?" : "&";
    $str = $url.$sign.$param;
    $str = str_replace("??","?",$str);
    $str = str_replace("?&","?",$str);
    $str = str_replace("&&","&",$str);
    return $str;
  }

	function HTML_SetTitle($str=false) {
		$str = $str . ' - ' . $this->CI->koje_config['KOJE_APP_TITLE'];
		$str ="<script>$('title').html('$str');</script>";
		return $str;
	}

	function nestedList($sql)
	{
		$rs = $this->CI->adodb->execute($sql);
		$items = array();
		while($row = $rs->fetchRow())
		{
			if (($row['access_type']=='ITEMMENU' || $row['access_type']=='SUBMENU')) {
				$items[] = $row;
			}
		}
		$list = false;
		if (count($items)>0) {
			$menu = new koje_menu();
			$list = $menu->get_nested_list($items);
			$list = str_replace('class','class1',$list);
		}
		return $list;
	}

	function configAddItem(&$params, $row, $type)
	{
		foreach ($params as $key => $value) {
			$id = $params[$key]['field'];
			if (isset($row[$id]))
				$params[$key][$type] = $this->CI->koje_system->getArrayVal($row, $id);
		}
	}
	function replaceCharacter($str,$chr1, $chr2='')
	{
		return str_replace($chr1,$chr2,$str);
	}

  function removeLastChar($str,$chr) {
    $lc = substr($str,-1);
    return $lc==$chr ? substr($str,0,-1) : $str;
  }

  function assignPickLOV($assign) {
    if($assign) {
      $assign = str_replace($_SESSION['_ACCESS_KEY'].':','',$this->base64Decode($assign));
      $arr = explode(';',$assign);
      foreach($arr as $val) {
        list($p0,$p1) = explode(':',$val);
        $pick[$p0] = $p1;
      }
    }
    else {
      $pick = array();
    }    	 
    return $pick;
  }

	/* ---------------------- DBUtils ---------------------------------------*/

	function getRefDomainList($str, $parent_id=false, $order=2, $value=false) {
		$CI =& get_instance();
		$where = $parent_id ? " AND parent_id=$parent_id" : "";
		$where.= $value ? " AND upper(value)=upper('$value')" : "";
		$rows  = $CI->adodb->getAssoc("select val, description from t_reference where group_reference=? $where order by priority, ?",
																	array($str, $order));
		return $rows;
	}
	function getRefDomainDescList($str, $order=2, $parent_id=false, $value=false) {
		$where = $parent_id ? " AND upper(parent_id)=upper('$parent_id')" : "";
		$where.= $value ? " AND upper(value)=upper('$value')" : "";
		$rows = $this->CI->adodb->getAssoc("select description val, description from t_reference where group_reference=? $where order by priority, ?", array($str, $order));
		return $rows;
	}
	
	
/* ---------- validation --------------------------------------------*/
	var $error_avail = array();
	public function form_error($id,$prefix='', $sufix='') {
		if (isset($this->CI->koje_system->_error_array[$id])) return $prefix.$this->CI->koje_system->_error_array[$id].$sufix;
		return false;
	}
	public function form_error_count() {
		return count($this->CI->form_validation->_error_array);
	}
	public function form_error_remove($id) {
		unset($this->error_avail[$id]);
	}
	public function form_errors_avail() {
		return $this->error_avail;
	}
	public function form_error_copy() {
		$this->error_avail = $this->CI->koje_system->_error_array;
	}


/**************************************** others *****************/
	function array_add_element(&$items, $arr) {
		foreach ($items as $key => $value) {
			foreach ($arr as $key1 => $value1) {
				$items[$key][$key1] = $value1;
			}
		}
	}
	function array_set_element(&$items, $arr, $attr) {
		foreach ($arr as $key => $value) {
				if(isset($items[$key]))
				{
					$items[$key][$attr] = $value;
				}
		}
	}
	function array_remove_element(&$items, $str) {
		foreach ($items as $key => $value) {
			if($value==$str)
			{
				unset($items[$key]);
			}
		}
	}
  
   function dateArrayFormat($params){
    $arr = $params;
    foreach ($params as $key => $val) {
      $arr[$key]=$this->date_format($val);
    }
    return $arr;
  }
  function nullArrayEmpty($params) {
    $arr = $params;
    foreach ($params as $key => $val) {
	  $arr[$key] = trim($arr[$key]);
      if($val==="" || $val===false) $arr[$key]=NULL;
    }
    return $arr;
  }
  
  function totalDaysInMonth($year, $month)
  {
    switch($month)
    {
      case '02' :
                  if($year%4 == 0 && $year%100 != 0) {
                      return 29;
                  } elseif($year%400 == 0) {
                      return 29;
                  } else {
                      return 28;
                  }
      case '04': case '06': case '09': case '11'                    : return 30; break;
      case '01': case '03': case '05': case '07': case  '08': case  '10': case  '12'  : return 31;break;
    }
  }
  function listDaysInMonth($year,$month)
  {
    $cnt = $this->totalDaysInMonth($year,$month);
    $arr = array();
    for($i=0;$i<$cnt;$i++)
    {
      $arr[] = $i+1;
    }
    return $arr;
  }
						
																																								   
   
	function listYear($start, $end, $sort='DESC')
	{
		$arr = array();
		if ($sort=='ASC') {
			$current = $start;
			while ($current <= $end) {
				$arr[$current] = $current;
				$current += 1;
			}
			return $arr;
		}
		else {
			$current = $end;
			while ($current >= $start) {
				$arr[$current] = $current;
				$current -= 1;
			}
			return $arr;
		}
	}		
}
						  
  
																															
				
						  
					
												
					 
																			   
					 
													
					  
																				 
					  
													
						 
																				  
							
																						
							   
																								
   

 
