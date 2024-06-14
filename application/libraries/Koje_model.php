<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/


  class Koje_model
  {
    function getForm($str,$params,$readonly=false)
    {
      $form = $this->$str($params);
      if($readonly)
      {
          $this->CI->koje_system->array_add_element($form['item'], array('readonly'=>true));
      }
      if (isset($params['TITLE']))
      {
        $form['title'] = $params['TITLE'];
      }
      foreach ($form['item'] as $key => $value) {
        if (!isset($form['item'][$key]['field'])) $form['item'][$key]['field'] 	= $key;
        if (!isset($form['item'][$key]['id'])) 		$form['item'][$key]['id'] 		= $key;
        if (!isset($form['item'][$key]['name'])) 	$form['item'][$key]['name'] 	= $key;
        if (!isset($form['item'][$key]['label'])) $form['item'][$key]['label'] 	= getLabel($key);
        if (isset($form['item'][$key]['readonly']) && $form['item'][$key]['type']=='select2')
        {
          $form['item'][$key]['type'] = "select";
        }
        if (isset($form['item'][$key]['readonly']) && $form['item'][$key]['type']=='dateTimePicker')
        {
          $form['item'][$key]['type'] = "text";
        }
        if (isset($form['item'][$key]['readonly']) && $form['item'][$key]['type']=='datePicker')
        {
          $form['item'][$key]['type'] = "text";
        }
      }
      return $form;
    }
    function mergeForm($form1, $form2)
    {
      $form = $form1;
      $form['item']  	 = array_merge($form1['item'], $form2['item']);
      $form['display'] = array_merge($form1['display'], $form2['display']);
      $form['result']  = array_merge($form1['result'], $form2['result']);
      return $form;
    }
    function setFormTitle(&$form,$title)
    {
      $form['title'] = $title;
    }

  	function getInput($params=array())
  	{
  		$inputs = $params['INPUT'];
  		foreach ($params['FORMS']['item'] as $key => $value) {
  			if(
          $this->CI->koje_system->getArrayVal($value, 'format')=='number' ||
          strpos($this->CI->koje_system->getArrayVal($value, 'data-inputmask',''),"'decimal'")!==false
          )
        {
  					$inputs[$key] = $this->CI->koje_system->replaceCharacter($params['INPUT'][$key],',');
  			}
        if(
          $this->CI->koje_system->getArrayVal($value, 'format')=='percentage' ||
          strpos($this->CI->koje_system->getArrayVal($value, 'data-inputmask',''),"'percentage'")!==false
          )
        {
            $inputs[$key] = $this->CI->koje_system->replaceCharacter($params['INPUT'][$key],'%');
        }
  		}
  		return $inputs;
  	}
  	function __construct() {
      $this->CI =& get_instance();
    }
  }
