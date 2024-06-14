<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class koje_menu
{
  protected $CI;
  var $activeModule;
  function __construct() {
    $this->CI =& get_instance();
  	$this->activeModule = $this->CI->koje_system->getCurrentModule();
  }
  function getLink($link) {
  	if (!$link) return '#';
  	else return base_url().'index.php/'.$link;
  }

  function getIcon($icon, $default='fa fa-circle-o') {
    $ic = $icon ? $icon :  $default;
  	if(substr($ic,0,5)=='glyph')
  		return 'glyphicon '.$ic;
  	else
  		return 'fa '.$ic;
  }
  function get_menu_html_sidebar($menu_items)
  {
  	$str = "<ul class='sidebar-menu' data-widget='tree'><li class='header'>MAIN NAVIGATION</li>";
  	global $menuItems;
  	global $parentMenuIds;
    foreach($menu_items as $parentId)
    {
    	$parentMenuIds[] = $parentId['sys_parent_id'];
    }
    $menuItems = $menu_items;
    $str .= $this->_generate_menu_sidebar(0);
    $str .= '</ul>';
    return $str;
  }

  function _generate_menu_sidebar($parent)
  {  	$str = '';
  	$has_childs = false;
  	global $menuItems;
  	global $parentMenuIds;

  	$activeModule = $this->CI->koje_system->getCurrentModule();

  	foreach($menuItems as $key => $value)
  	{
      $lnk = $this->getLink($value['module']);
      $active = isset($this->CI->koje_system->breadcrumb[$value['sys_access_id']])  ? "active" : "";

  		if ($value['sys_parent_id'] == $parent)
  		{
  			if ($has_childs === false)
  			{
  				$has_childs = true;
  				if($parent != 0)
  				{
  						$str .= "<ul class='treeview-menu $active' id='mnuli".$value['sys_access_id']."'>";
  				}
  			}

        //SubMenu
  			if(in_array($value['sys_access_id'], $parentMenuIds))
  			{
          $classSubmenu = "treeview";
          $leftIcon     = 'fa-circle-o';
          $rightIcon    = "fa-angle-left";
          $classLabel   = $active ? "text-bold" : "";
  			}
  			else
  			{
          $classSubmenu = "";
          $rightIcon    = "";
          $leftIcon     = 'fa-circle-o';
          $classLabel   = ($value['module']==$activeModule || $active) ? "text-aqua text-bold" : "";
  			}

        $icon = $this->getIcon($value['icon'], $leftIcon);
    		$label = "<span class='$classLabel'>".$value['label']."</span>";
        $subMenu = $this->_generate_menu_sidebar($value['sys_access_id']);
        $str .="<li class='$classSubmenu $active'>
                    <a id=mnua".$value['sys_access_id']."  title='".$value['title']."' href='$lnk'>
                      <i class='text-center $icon'></i><span>$label</span><i class='fa $rightIcon pull-right'></i>
                    </a>
                    $subMenu
                </li>";
  		}
  	}
  	if ($has_childs === true) $str .= '</ul>';
  	return $str;
  }

  function get_nested_list($menu_items)
  {
  	$str = '<ul style="list-style: none">';
  	global $nestedItems;
    $nestedItems = $menu_items;
    $str .= $this->_generate_nested_list(0);
    $str .= '</ul>';
    return $str;
  }

  function _generate_nested_list($parent)
  {
    $str = '';
  	$has_childs = false;
  	global $nestedItems;

  	foreach($nestedItems as $key => $value)
  	{
  		if ($value['sys_parent_id'] == $parent)
  		{
  			if ($has_childs === false)
  			{
  				$has_childs = true;
  				if($parent != 0)
  				{
  						$str .= "<ul style='list-style: none'>";
  				}
  			}
    		$label = "<span>".$value['label']."</span>";
        $subMenu = $this->_generate_nested_list($value['sys_access_id']);
        $checked = isset($value['checked']) ? "checked" : "";
        $str .="<li>
                      <input id=access_{$value['sys_access_id']} name='access[]' type='checkbox' value='{$value['sys_access_id']}' $checked />
                      <i class='text-center'></i><span>$label</span>
                    $subMenu
                </li>";
  		}
  	}
  	if ($has_childs === true) $str .= '</ul>';
  	return $str;
  }

}
?>
