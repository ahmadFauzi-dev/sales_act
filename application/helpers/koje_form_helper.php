<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class koje_form	{
	var $id;
	var $CI;
	var $items = array();
	var $open;
	var $close;
	var $title;
	var $currentURL;
	var $formDisplay=array();
	var $formResult=array();
	var $formUrlBack='';
	var $formUrlCancel='';
	var $prefixID='';
	var $JSPre="";
	var $JSVal="";
	var $JSFunc="";
	var $attributeValid = array(
			"ACCEPT","ACCESSKEY","ALT","ALIGN","ID","CHECKED","CLASS","COLS","MAXLENGTH","MULTIPLE",
			"NAME","ROWS","SELECTED","SIZE","SRC","STYLE","TYPE","TABINDEX","VALUE","TITLE","ONBLUR",
			"ONCHANGE","ONCLICK","ONDBLCLICK","ONFOCUS","ONKEYDOWN","ONKEYPRESS","ONKEYUP","ONMOUSEDOWN",
			"ONMOUSEMOVE","ONMOUSEOUT","ONMOUSEOVER","ONMOUSEUP","ONSELECT","READONLY","PLACEHOLDER", "HREF", "MIN","MAX","REQUIRED","DISABLED",
			"DATA-INPUTMASK",
			"DATA-DATE-START-DATE",
			"TEMPLATE"
		);
	var $focus = false;
	var $style = "COMPLETE";
	var $rowNo=0;
	var $gridForm ='';
	var $defaultSizelabel='4';
	var $defaultSizeInput='8';
	var $defaultSizeAlert='4';
	var $fileUpload = false;

	function __construct($id, $params=array(), $linkPagerID=false)
	{
		$this->CI =& get_instance();
		$this->id = $id;
		$action = array_key_exists('url', $params) ? $params['url'] : false;
		$multipart = array_key_exists('multipart', $params) ? $params['multipart'] : true;
		$attributes = array_key_exists('attributes', $params) ? $params['attributes'] : '';
		$hidden = array_key_exists('hidden', $params) ? $params['hidden'] : array();
		$extra = array_key_exists('extra', $params) ? $params['extra'] : '';
		$link = array_key_exists('link', $params) ? $params['link'] : '';

		$this->currentURL = $action ? $action : $this->CI->koje_system->URLRemoveBlank($this->CI->koje_system->URLCurrent());

		$attributes .= "id='$id' class='form-horizontal fileupload' name='$id' onsubmit='' autocomplete='off'";
		$this->open = $multipart ? form_open_multipart($this->currentURL, $attributes, $hidden) : form_open($this->currentURL, $attributes, $hidden);
		$this->close = form_close($extra);
		if (!isset($_SESSION['_ACCESS_KEY'])) {
			$_SESSION['_ACCESS_KEY'] = rand();
		}
		$this->formUrlBack = $this->CI->koje_system->URLCancel();
		$this->formUrlCancel = $this->CI->koje_system->URLCancel();
		if($linkPagerID) $this->linkSearchForm($linkPagerID);
		if(array_key_exists('FORMS',$params)) {
			if(array_key_exists('title',$params['FORMS'])) $this->setTitle($params['FORMS']['title']);
			if(array_key_exists('item',$params['FORMS'])) $this->setItem($params['FORMS']['item']);
			if(array_key_exists('display',$params['FORMS'])) $this->setDisplay($params['FORMS']['display']);
			if(array_key_exists('result',$params['FORMS'])) $this->setResult($params['FORMS']['result']);
			$this->setUrlBack($params['FORMS']);
			$this->setUrlCancel($params['FORMS']);
		}
	}
	function _getParam($params, $field, $default=false) {
		if (isset($params[$field])) return $params[$field]; else return $default;
	}
	function _removeParam(&$params, $field, $default=false) {
		if (isset($params[$field])) {
			$val = $params[$field];
			unset($params[$field]);
			return $val;
		}
		return $default;
	}
	function _parseAttribute($params=array()) {
		$str = "";
		$id =$this->_getParam($params,'id',false);
		foreach($params as $key => $val) {
			if (!is_array($val) && in_array(strtoupper($key),$this->attributeValid)) {
				$str .= "$key=\"$val\"";
			}
		}
		$req = $this->isItemRequired($id) ? " required='true' " : "";
		return $str.$req;
	}

	function _getValue($params, $id) {
		$val = $this->CI->input->get_post($id);
		if($val==false && $val!=="") {
			$val = $this->_getParam($params,'value');
		}
		return $val;
	}
	function rowID() {
		$this->rowNo ++;
		return 'TR_'.$this->id.'_'. $this->rowNo;
	}
	/* Start Of Generate Item */
	function _itemInput($params) {
		$params['name'] = $id = $this->_getParam($params,'id');
		$size = $this->_getParam($params,'size',$this->defaultSizeInput);
		$v = $this->_getValue($params, $id);

		$params['value'] = $this->_getValue($params, $id);
		$spelling = $this->_getParam($params, 'spelling')===true ? 1 : 0;
		$params['class'] = $this->_getParam($params,'class') . 'form-control';
		$readonly = $this->_getParam($params, 'readonly',false);		  
		$format = $this->_getParam($params, 'format','none');
		if($format=='date') {
		  if ($readonly)
			$params['value'] = $this->CI->koje_system->date_format($params['value']);
		  else
			$params["data-inputmask"] = "'alias': '99/99/9999'";
		}
		if($format=='number') {
			$params["data-inputmask"] = "'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true";
		}
		if($format=='percentage') {
			$params["data-inputmask"] = "'alias': 'percentage', 'groupSeparator': ',', 'autoGroup': true";
		}
		if ($this->_getParam($params, 'type')=='hidden')
		{
			return "<input " . $this->_parseAttribute($params) . "/>";
		}
		else
		{
			return "<div class='col-sm-$size'><input " . $this->_parseAttribute($params) . "/></div>";
		}
	}
	function _itemButton($params) {
		$params['name'] = $this->_getParam($params,'id');
		$params['class'] = $this->_getParam($params,'class',STYLE::BUTTON_SUBMIT);
		$params['value'] = $this->_getParam($params,'label');
		return "<button " . $this->_parseAttribute($params) . ">".$params['value']."</button> ";
	}
	function _itemLink($params) {
		$params['name'] = $this->_getParam($params,'id');
		$params['class'] = $this->_getParam($params,'class','bg-dblue btn koje-left-flat nm small-caps');
		$params['value'] = $this->_getParam($params,'value');
		return "<a " . $this->_parseAttribute($params) . ">".$params['value']."</a> ";
	}
	function _itemTextarea($params) {
		$params['name'] = $id = $this->_getParam($params,'id');
		$value = $this->_getValue($params, $id);
		$width = $this->_removeParam($params, 'width',$this->defaultSizeInput);
		$this->_removeParam($params, 'value','');
		return "<div class='col-sm-".$width."'><textarea class='form-control limited' maxlength=1000 " . $this->_parseAttribute($params) . ">$value</textarea></div>";
	}
	function _itemTextareaHTML($params) {
		$params['name'] = $id = $this->_getParam($params,'id');
		$value = $this->_getValue($params, $id);
		$this->_removeParam($params, 'value','');
		return "<textarea class='tmce' " . $this->_parseAttribute($params) . ">$value</textarea>";
	}
	function _itemLabel($params) {
		$params['name'] = $id = $this->_getParam($params,'id');
		$value = $this->_getValue($params, $id);
		$this->_removeParam($params, 'value','');
		return "<div ".$this->_parseAttribute($params) . ">$value</div>";
	}
	function _itemSelect($params) {
		$multiple = $this->_getParam($params,'multiple') ? '[]' : '';
		$params['name'] = $this->_getParam($params,'id').$multiple;
		$options = $this->_removeParam($params, 'options', array());
		$id = $this->_getParam($params, 'id','');
		$size = $this->_removeParam($params, 'size',$this->defaultSizeInput);
		$type = $this->_removeParam($params, 'type','');
		$readonly = $this->_getParam($params, 'readonly',false);
		$value = $this->_removeParam($params, "value");
		if(!$value) {
		  $value = $this->_getValue($params, $id);
		}
		$str = "<div class='col-sm-$size'><div class='input-group'><select class='form-control' " . $this->_parseAttribute($params). ">";

		if(!$readonly) {
			$str .= "<option value=\"\"></option>";
		}
		if (is_array($options)) {
			foreach($options as $key => $val) {
				if($key==$value)
				{
					$sel = " SELECTED style='font-weight:bold'";
					$selc= " *";
				}
				else {
					$sel = "";
					$selc= "";
				}
				if (!$readonly || $key==$value)
				{
					$str .= "<option value=\"$key\" $sel>$val $selc</option>";
				}
			}
		}
		$str .= "</select>
						 <div class='input-group-addon vertical-align-bottom'><i class='fa fa-caret-down'></i></div>
					</div>
				</div>";
		return $str;
	}

	function _itemSelect2($params) {
		$multiple = $this->_getParam($params,'multiple') ? '[]' : '';
		$params['name'] = $this->_getParam($params,'id').$multiple;
		$options = $this->_removeParam($params, 'options', array());
		$id = $this->_getParam($params, 'id','');
		$size = $this->_removeParam($params, 'size',$this->defaultSizeInput);
		$type = $this->_removeParam($params, 'type','');
		$readonly = $this->_getParam($params, 'readonly',false);
		$value = $this->_removeParam($params, "value");
		$select2 = $readonly ? "" : "select2";

		$str = "<div class='col-sm-$size'><div class='input-group col-sm-12'><select class='$select2 form-control' " . $this->_parseAttribute($params). ">";

 		if(!$readonly) {
			$str .= "<option value=\"\"></option>";
		}

		if ($readonly) {
			$this->JSPre("$('#$id').select2('readonly', true);");
		}

		if (is_array($options)) {
			foreach($options as $key => $val) {
				if($key==$value)
				{
					$sel = " SELECTED style='font-weight:bold'";
					$selc= " *";

				}
				else {
					$sel = "";
					$selc= "";
				}
				if (!$readonly || $key==$value)
				{
					$str .= "<option value=\"$key\" $sel>$val $selc</option>";
				}
			}
		}
		$str .= "</select></div></div>";

		return $str;
	}

	function _itemDatePicker($params) {
		$attr = $this->_parseAttribute($params);
		$size = $this->defaultSizeInput;
		$str = "<div class='col-sm-$size'>
							<div class='input-group'>
								<input type='text' readonly=true class='date form-control pull-right' $attr />
								<span class='input-group-addon'><i class='fa fa-calendar'></i></span>
							</div>
						</div>";
		return $str;
	}
	function _itemDateTimePicker($params) {
		$attr = $this->_parseAttribute($params);
		$size = $this->defaultSizeInput;
		$str = "<div class='col-sm-$size'>
					<div class='input-group'>
						<input class='form-control datetime'
								type='text'
								readonly = true
								data-date-format='YYYY-MM-DD HH:mm'
								$attr />
						<span class='input-group-addon'><i class='fa fa-calendar'></i></span>
					</div>
				</div>";
		return $str;
	}
	function _itemRadio($params) {
		$str = "";
		$options = $this->_removeParam($params, 'options');
		$id	= $this->_removeParam($params, 'id');
		$type = $this->_removeParam($params, 'type');
		$value = $this->_getValue($params, $id);

		$i=1;
		foreach($options as $key => $lbl) {
			$sel = $key==$value ? "CHECKED":"";
			$str .= "<div class='radiobox-inline'><label><input type=\"$type\" id=\"$id"."_"."$i\" name=\"".$id."\" $sel value=\"$key\" />$lbl</label></div>";
			$i++;
		}
		return $str;
	}

	function _itemCheckbox($params)
	{
		$options = $this->_removeParam($params, 'options');
		$id	= $this->_removeParam($params, 'id');
		$type = $this->_removeParam($params, 'type');
		$post = $this->CI->input->get_post(str_replace('[]','',$id));
		if (!is_array($post)) $post = array($id=>$post);

//		$values = isset($params['value']) ? json_decode($params['value']) : array();
		// BUGGGGGGGGGGGGGGGGGGGGGGGGG
		$values = array();
		$str = "<div id=itemCheckbox_{$id}>";
		$i=1;
		foreach($options as $key => $val)
		{
			$sel = in_array($key,$values) || $val[1]==true ? "CHECKED":"";
			$sel = in_array($key,$post) ? "CHECKED":$sel;
			$str .= " <label><input type=\"$type\" id=\"$id"."_"."$i\" name=\"".$id."[]\" $sel value=\"$key\" /> $val[0] </label> ";
			$i++;
		}
		$str .= '</div>';
		return $str;
	}

	function _itemFile($params) {
		$this->fileUpload=true;
		$params['name'] = $id = $this->_getParam($params,'id');
		$params['class'] = $this->_getParam($params,'class','file-input form-control');
		$params['value'] = $this->_getValue($params, $id);
		$template = $this->_getParam($params, 'template');
		$tpl_link = $template ? "<br/><a href='".base_url().'data/template/'.$template."'>DOWNLAOD TEMPLATE</a>" : "";
		$size = $this->defaultSizeInput;
		$str ="<div class='col-sm-$size'>
					<input type='file' ".$this->_parseAttribute($params). ">
					$tpl_link
				</div>";
		return $str;
	}
	function _itemFileList($params) {
		$params['name'] = $id = $this->_getParam($params,'id');
		$params['class'] = $this->_getParam($params,'class','file-input form-control');
		$params['value'] = $this->_getValue($params, $id);
		$str =app_libs::inputMultiFIleUpload($params);
		return $str;
	}
	function _itemPickList($params) {
		$attr = $this->_parseAttribute($params);
		$size = $this->defaultSizeInput;
		
		$id	= $this->_removeParam($params, 'id');
		$name = 'pick_'.$id.rand();
		$lov = $this->_removeParam($params, 'pickList');
		$opt = $this->_removeParam($params, 'pickListStyle');
		$lov = $this->CI->koje_system->base64Encode($_SESSION['_ACCESS_KEY'].":$lov");

		$assignTo = $this->_removeParam($params, 'pickListAssign', array());
		$assign="";
		foreach($assignTo as $key => $val) {
			$assign .= ($assign=="" ? "":";") . "$key:$val";
		}
		$assign = $this->CI->koje_system->base64Encode($_SESSION['_ACCESS_KEY'].":$assign");

		$filterTo = $this->_removeParam($params, 'pickListFilter', array());
		$filter="''";
		foreach($filterTo as $from => $to) {
			$filter .= "+'&$to='+$('#$from').val()";
		}
		$checkColumn = $this->_removeParam($params, 'pickCheckAssign', false);

		$link = base_url()."index.php/_system_/syspicklist/index?_lov=$lov&assign=$assign&assignCheck=$checkColumn";

		$str = "<div class='col-sm-$size'>
              <div class='input-group'>
								<input type='text' class='form-control pull-right' $attr />
                <a title='pick from list' type='button' id='$name' class='input-group-addon vertical-align-middle pickBtn' onclick='JavaScript:func_$name();'>
                  <span class='fa fa-search btnSearch'></span>
                </a>
							</div>
            </div>";

		$this->JSFunc(
				"\nfunction func_$name(){
				var name = '$name';
				var opt = '$opt';
				var val = $filter;
				var url = '$link'+val;
				window_$name=window.open(url,name,opt);
	};"
				);
		return $str;
	}

	function _itemGrid($params=array()) {
		if(!isset($params['rows'])) $params['rows']=0;
		$str  = '<table class="table small-caps"><tbody>';
		$str .= '<tr><th>No.</th>';
		foreach ($params['gridHeader'] as $val) {
			if(isset($params['fieldHidden']) && in_array($val, $params['fieldHidden'])) continue;
			$str .= "<th>$val</th>";
		}
		$str .= '</tr>';
		$FMODE = $this->CI->adodb->fetchMode;
		$this->CI->adodb->SetFetchMode(ADODB_FETCH_ASSOC);
		$rs = $this->CI->adodb->execute($params['SQL']);
		$i=1;
		$field = false;
		while($row = $rs->fetchRow()) {
			if(!$field) $field = $row;
			$str .= "<tr><td style='vertical-align:middle'>$i.</span></td>";
			$cno=0;
			foreach ($row as $key => $val) {
				$type = isset($params['fieldType'][$cno]) ? $params['fieldType'][$cno] : 'text';
				$size = isset($params['fieldSize'][$cno]) ? $params['fieldSize'][$cno] : '10';
				$hdr  = isset($params['gridHeader'][$cno]) ? $params['gridHeader'][$cno] : '';
				if(isset($params['fieldHidden']) && in_array($key, $params['fieldHidden'])) $type="hidden";
				switch ($type) {
					case 'label' :
						$str .= "<td align=left style='vertical-align:middle'>$val<input type='hidden' id='{$key}_{$i}' name='{$key}_{$i}' value='$val'></td>";
						break;
					case 'checkbox':
						$str .= "<td align=left style='vertical-align:middle'><label><input type='checkbox' id='{$key}_{$i}' name='{$key}_{$i}' value='$val'>$val</label></td>";
						break;
					case 'hidden' :
						$str .= "<input type='hidden' id='{$key}_{$i}' name='{$key}_{$i}' value='$val'>";
						break;
					default :
						$str .= "<td align=left style='vertical-align:middle'><input size=$size type='$type' id='{$key}_{$i}' name='{$key}_{$i}' class='form-control koje-filter-control' value='$val'></td>";
						break;
				}
				$cno++;
			}
			$str .= '</tr>';
			$i++;
		}

		while($i<=$params['rows']) {
			$str .= "<tr><td style='vertical-align:middle'>$i.</span></td>";
			$cno=0;
			foreach ($field as $key => $val) {
				$type = isset($params['fieldType'][$cno]) ? $params['fieldType'][$cno] : 'text';
				$size = isset($params['fieldSize'][$cno]) ? $params['fieldSize'][$cno] : '10';
				$hdr  = isset($params['gridHeader'][$cno]) ? $params['gridHeader'][$cno] : '';
				$value = isset($params['fieldValue'][$cno]) ? $params['fieldValue'][$cno] : '';
				switch ($type) {
					case 'label' :
						$str .= "<td align=left style='vertical-align:middle'><input type='hidden' id='{$key}_{$i}' name='{$key}_{$i}' value='$value'>$value</td>";
						break;
					case 'checkbox':
						$str .= "<td align=left style='vertical-align:middle'><label><input type='checkbox' id='{$key}_{$i}' name='{$key}_{$i}' value='$value'>$value</label></td>";
						break;
					default :
						$str .= "<td align=left style='vertical-align:middle'><input size=$size type='$type' id='{$key}_{$i}' name='{$key}_{$i}' class='form-control koje-filter-control' value='$value'></td>";
						break;
				}
				$cno++;
			}
			$str .= '</tr>';
			$i++;
		}

		$i--;

		$str .= '</tbody></table>';
		$str .= "<input type=hidden id='{$params['primaryKey']}_CNT' name='{$params['primaryKey']}_CNT' value='$i'>";

		$this->CI->adodb->SetFetchMode($FMODE);
		return $str;
	}
	/* End Of Generate Item */

	function _error($str) {
		print $str;
	}
	function JSPre($text) {
		$this->JSPre .= $text;
	}
	function JSVal($text) {
		$this->JSVal .= $text;
	}
	function JSFunc($text) {
		$this->JSFunc .= $text;
	}
	function linkSearchForm($id) {
		$this->prefixID = $id.'ss_';
	}
	function getId($id) {
		return $this->prefixID.$id;
	}
	function addItem($field,$params) {
		$id = $this->_getParam($params,'id');
		$id = $this->getId($id);
		$params['id'] = $id;
		$type = $this->_getParam($params,'type');
		if (!$id) { $this->_error('array parameter tidak ada index id.'); print_r($params);};
		$this->items[$id] = $params;
	}
	function getLabel($id) {
		$id = $this->getId($id);

		$type = $this->_getParam($this->items[$id], 'type');
		if ($type=='button') return '';
		$label = isset($this->items[$id]['label']) ? $this->items[$id]['label'] : "";
		$lbs = explode(' ',$label);
		$str = '';

		foreach($lbs as $key) {
			$str .= strlen($key) <= 2 ? $key : (substr($key,0,1) .  substr($key,1));
			$str .= ' ';
		}

		$str = trim($str);

		$mark = $this->isItemRequired($id) ? "<span id='req_$id'> *</span>" :"<span style='display:none' id='req_$id'> *</span>";
		return $str.$mark;
	}
	function isItemRequired($id) {
		if(!isset($this->items[$id]['rules'])) {
			return false;
		}
		return strpos($this->items[$id]['rules'],'required');
	}
	function getItem($id) {
		$id = $this->getId($id);
		$type = $this->_getParam($this->items[$id], 'type');
		$str = '';
		if($this->_getParam($this->items[$id], 'textbefore'))
			$str .= '<div class="input-group-addon">'.$this->_getParam($this->items[$id], 'textbefore').'</div>';

		$linkID = $this->_getParam($this->items[$id],'linkID');
		$format = $this->_getParam($this->items[$id],'format');

		if(!$this->_getParam($this->items[$id], 'readonly', false)) {
			$this->_removeParam($this->items[$id], 'readonly');
		}

		switch ($type) {
			case 'link' :
				$str .= $this->_itemLink($this->items[$id]); break;
				break;
			case 'button':
			case 'submit':
			case 'reset':
				$str .= $this->_itemButton($this->items[$id]); break;
			case 'text'	:
			case 'hidden':
			case 'password':
				$str .= $this->_itemInput($this->items[$id]); break;
			case 'label' :
				$str .= $this->_itemLabel($this->items[$id]); break;
			case 'textarea' :
				$str .= $this->_itemTextarea($this->items[$id]); break;
			case 'textareaHTML':
				$str .= $this->_itemTextareaHTML($this->items[$id]);
				break;
			case 'select':
				$str .= $this->_itemSelect($this->items[$id]);
				if ($linkID) {
					$linkSQL = $this->_getParam($this->items[$id],'linkSQL');
					$SQL = $this->CI->koje_system->base64Encode($_SESSION['_ACCESS_KEY'].":".$linkSQL);
					$val = 	$this->_getParam($this->items[$id],'value');
					$this->JSPre(
							'	$.ajax({
								url: baseURL + "index.php/_system/dropdownlist",
								data: {MSG: "'.$SQL.'",linkID:$("#'.$linkID.'").val()},
								type: "post",
								success: function(msg){
											$("#'.$id.'").html(msg);
											$("#'.$id.'").val("'.$val.'");
										 }
							});

							$("#'.$linkID.'").change(function(){
								$.ajax({
									url: baseURL + "index.php/_system/dropdownlist",
									data: {MSG: "'.$SQL.'",linkID:$("#'.$linkID.'").val()},
									type: "post",
									success: function(msg){
												$("#'.$id.'").html(msg);
												$("#'.$id.'").val("'.$val.'");
											}
								});
							});
						'
							);
				}
				break;
			case 'select2':
				$str .= $this->_itemSelect2($this->items[$id]);
				if ($linkID) {
					$linkSQL = $this->_getParam($this->items[$id],'linkSQL');
					$SQL = $this->CI->koje_system->base64Encode($_SESSION['_ACCESS_KEY'].":".$linkSQL);
					$val = 	$this->_getParam($this->items[$id],'value');
					$this->JSPre(
							'	$.ajax({
								url: baseURL + "index.php/_system/dropdownlist",
								data: {MSG: "'.$SQL.'",linkID:$("#'.$linkID.'").val()},
								type: "post",
								success: function(msg){
											$("#'.$id.'").select2("val", "'.$val.'", true);
										 }
							});

							$("#'.$linkID.'").change(function(){
								$.ajax({
									url: baseURL + "index.php/_system/dropdownlist",
									data: {MSG: "'.$SQL.'",linkID:$("#'.$linkID.'").val()},
									type: "post",
									success: function(msg){
												$("#'.$id.'").select2("val", "'.$val.'", true);
											}
								});
							});
						'
							);
				}
				break;
			case 'datePicker':
				$str .= $this->_itemdatePicker($this->items[$id]); break;
			case 'dateTimePicker':
				$str .= $this->_itemdateTimePicker($this->items[$id]); break;
			case 'radio':
				$str .= $this->_itemRadio($this->items[$id]); break;
			case 'checkbox':
				$str .= $this->_itemCheckbox($this->items[$id]); break;
			case 'file':
				$str .= $this->_itemFile($this->items[$id]); break;
			case 'fileList':
				$str .= $this->_itemFileList($this->items[$id]); break;
				case 'grid' :
				$str .= $this->_itemGrid($this->items[$id]); break;
			case 'pickList' :
					$str .= $this->_itemPickList($this->items[$id]); break;				
		}

		if($this->_getParam($this->items[$id], 'textafter')) {
			$str .='<span class="middle">'.$this->_getParam($this->items[$id], 'textafter').'</span>';
		}
		else {
			$str .='';
		}

		return $str;
	}
	function getInfo($id) {
		$id = $this->getId($id);
		$error = $this->CI->koje_system->form_error($id,"<label class='control-label pull-right' id='error_$id'>",'</label>');
		$info = isset($this->items[$id]['info']) ? $this->items[$id]['info'] : "";
		$this->CI->koje_system->form_error_remove($id);
		if ($error && !$this->focus) {
			$this->focus = $id;
			$this->JSPre("\$('#$id').focus();\n");
		}

		if (!$error) $error ="";
		return "<div class='pull-right text-info'>".$info.$error.'</div>';
	}
	function generateItemHidden() {
		$str ="";
		foreach ($this->items as $id => $val) {
			if ($this->isItemHidden0($id)) $str .= $this->getItem($this->getId($id));
		}
		return $str;
	}
	function isItemValid($id) {
		$id = $this->getId($id);
		return isset($this->items[$id]);
	}
	function isItemHidden0($id) {
		$type = $this->_getParam($this->items[$id], 'type');
		return ($type=='hidden');
	}
	function isItemHidden($id) {
		$id = $this->getId($id);
		$type = $this->_getParam($this->items[$id], 'type');
		return ($type=='hidden');
	}
	function getItemDiv($data) {
		if ($this->style=='ITEM_ONLY') return "<div>".$this->getItem($data).'</div>';
		return "<label class='col-sm-".$this->defaultSizelabel." control-label'>".$this->getLabel($data)."</label>".
					($this->isItemHidden($data)?'':($this->getItem($data))).
					$this->getInfo($data);
	}

	function render()
	{
		$CI =& get_instance();
		$str = "";
		$str .= "<div class='box box-primary box-solid'>";
		$str  .= "<div class='box-header with-border'><h4 class='box-title pull-right'>".strtoupper($this->title)."</h4></div>";
		$str  .= "<div class='box-body'>";
		$list = $this->formDisplay;
		foreach ($list as $key => $rec) {
			if(is_array($rec)) {
				foreach ($rec as $key1 => $val1) {
					if($this->isItemValid($val1)) {
						$str .= "<div id='grp_{$val1}' class='form-group is-empty'>";
						$str .= $this->getItemDiv($val1);
						$str .= "</div>";
					}
					elseif ($val1=='_SPACER_') {
						$str .= "<div class='form-group is-empty hidden-xs'><label class='form-control no-background-image'></label></div>";
					}
					else {
						$str .= $val1;
					}
				}
			}
			elseif($rec==='_BUTTON_') {
				$str .= "<div class='col-sm-12 text-center'>";
				foreach($this->items as $btn => $val) {
					if ($val['type'] == 'button' || $val['type'] == 'submit' || $val['type'] == 'reset') {
						$str .= $this->getItem($btn);
					}
					if ($val['type']=='cancel') {
						$str .= anchor($this->formUrlCancel,$val['label'],html_class(STYLE::BUTTON_CANCEL));
					}
				}
				$str .= "</div>";
			}
			elseif ($rec=='_SPACER_') {
				$str .= "<div class='form-group is-empty hidden-xs'><label class='form-control koje-spacer'></label></div>";
			}
			else {
				$str .= $rec;
			}
		}
		$str .= "</div>";
		if($this->fileUpload)
		{
			$CI->config->load('upload');
			$str .= "<i>upload allowed types : <b>". str_replace('|',', ',$CI->config->item('allowed_types')).'</b></i>';
		}
		$str .= "</div>";
		return $str;
	}

	function renderSearchDataTables()
	{
		$CI =& get_instance();
		$frmid = $this->prefixID;
		$str = "";
		$str  .= "<div class=''>";
		$list = $this->formDisplay;
		foreach ($list as $key => $rec) {
			if(is_array($rec)) {
				foreach ($rec as $key1 => $val1) {
					if($this->isItemValid($val1)) {
						$str .= "<div class='form-group is-empty'>";
						$str .= $this->getItemDiv($val1);
						$str .= "</div>";
					}
					elseif ($val1=='_SPACER_') {
						$str .= "<div class='form-group is-empty hidden-xs'><label class='form-control koje-spacer'></label></div>";
					}
					else {
						$str .= $val1;
					}
				}
			}
			elseif($rec==='_BUTTON_') {
				$str .= "<div class='col-sm-12 text-center'>";
				foreach($this->items as $btn => $val) {
					if ($val['type'] == 'button' || $val['type'] == 'submit' || $val['type'] == 'reset') {
						$str .= $this->getItem($btn);
					}
					if ($val['type']=='cancel') {
						$str .= html_link($this->formUrlCancel,$val['label'],"class='".STYLE::BUTTON_CANCEL."'");
					}
				}
				$str .= "</div>";
			}
			elseif ($rec=='_SPACER_') {
				$str .= "<div class='form-group is-empty hidden-xs'><label class='form-control koje-spacer'></label></div>";
			}
			else {
				$str .= $rec;
			}
		}
		$str .= "<div class='col-sm-2' id='{$frmid}__koje_filter'></div>";
		$str .= "</div>";
		return $str;
	}

	function errorMsgAvail() {
		$err = $this->CI->koje_validation->form_errors_avail();
		$str = '';
		foreach ($err as $val) {
			$str .= "<div class=infoVerify>$val</div>";
		}
		return $str;
	}

	function showForm($params) {
		$this->CI->koje_validation->form_error_copy();
		print "<div class='box box-info'>";
		print $this->open;
		print $this->generateItemHidden();
		$str = $this->gridForm ? $this->gridForm : $this->render();
		print $str;
		print $this->errorMsgAvail();
		print $this->close;
		print "</div>";
		print "\n<script>
		$this->JSFunc
		\$(document).ready(function() {
		$this->JSPre
	});
	function ValidateForm(frm) {
	$this->JSVal
	}
	</script>\n";
	}
	function showResult($Result, $input) {
		if (!is_array($Result)) $Result = array($Result,"DONE");
		$panel = $Result[1]=='00' ? 'box-primary' : 'box-danger';
		$span = $Result[1]=='00' ? '' : 'label label-sm label-danger';
		$i = $Result[1]=='00' ? '' : 'fa fa-exclamation-triangle bigger-120';
		if (!isset($Result[2])) $Result[2] ='DONE';
		$s  = "<div class='row'>
							<div class='col-sm-3'></div>
							<div class='col-sm-6'>
								<div class=''>
									<div class='box $panel box-solid'>
										<div class='box-header with-border'><h4><i class='$i'></i> $this->title</h4></div>
										<div class='box-body table-responsive no-padding'>
											<table class='table table-bordered table-striped table-hover'>
												<tr><td class='koje-th bg-blue'><b>".TITLE_MSG::LABEL_RESULT."</b></td>
														<td class='koje-th bg-blue'><b><span class='$span'>".$Result[2]."</span></b></td>
												</tr>
					";

		foreach ($this->formResult as $key) {
			$val = "";
			if(isset($input[$key])) {
				if(($this->items[$key]['type']=='select' || $this->items[$key]['type']=='select2') &&
				    isset($this->items[$key]['options'][$input[$key]])
					)
				{
					$val = $this->items[$key]['options'][$input[$key]];
				}
				else {
					$val = $input[$key];
				}
			}
			if(is_array($val))
			{
				$val = $val[0];
			}
			$s .= "				<tr><td>".$this->items[$key]['label']."</td><td>".$val."</td></tr>";
		}

			$s .= "				</table>
									<center>".
									html_link($this->formUrlBack,"BACK","class='".STYLE::BUTTON_BACK." text-center'")."
									</center></div>
								</div>
							</div>
						</div>
						<div class='col-sm-3'></div>
						</div>
						";
		print $s;
	}
	function show($params) {
		if ($params['SUBMIT']) {
			$this->showResult($params['RESULT'], $params['INPUT']);
		}
		else{
			$this->showForm($params);
		}
	}
	function setItem($params) {
		foreach($params as $key=>$val) {
			$this->addItem($key,$val);
		}
	}
	function setTitle($params='') {
		$this->title = $params;
	}
	function setDisplay($params=array()) {
		$this->formDisplay = $params;
	}
	function setResult($params=array()) {
		$this->formResult = $params;
	}
	function setUrlBack($params=array()) {
		if (isset($params['url_back']))
		{
			$url = $this->CI->koje_system->linkParam($params['url_back']['url'],$params['url_back']['params']);
			$this->formUrlBack = $url;
		}
	}
	function setUrlBackCloseWin() {
		$this->formUrlBack = base_url('index.php/_system/closewin');
	}
	function setUrlCancel($params=array()) {
		if (isset($params['url_back']))
		{
			$url = $this->CI->koje_system->linkParam($params['url_back']['url'],$params['url_back']['params']);
			$this->formUrlCancel = $url;
		}
	}
	function setStyle($style='COMPLETE') {
		$this->style = $style;
	}
	function setGridForm($params=array()) {
		$str  = '';
		$str .= '<div class="block block-fill-white">
				<div class="head bg-dblue bg-light-rtl small-caps">'.$this->title.'</div>
				<div class="content">';
		$str .= $this->_itemGrid($params);
		foreach ($this->formDisplay as $key => $rec) {
			if($rec==='_BUTTON_') {
				$str .= "<div class='btn-group1'>";
				foreach($this->items as $btn => $val) {
					if ($val['type'] == 'button' || $val['type'] == 'submit' || $val['type'] == 'reset') {
						$str .= $this->getItem($btn);
					}
					if ($val['type']=='cancel') {
						$str .= HTML_link($this->formUrlCancel,$val['label'],"class='btn btn-danger btn-round btn-xs'");
					}
				}
				$str .= '</div>';
			}
		}
		$str .= '</div></div>';
		$this->gridForm = $str;
	}
	function buttonClearFilter() {
		$link = $this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->currentURL, 'p1sf_'),'p2sf_'),'p3sf_'),'p4sf'),'p5sf_'),'p6sf_'),'p7sf_'),'p8sf_'),'p9sf_'),'p10sf_'),'p11sf_'),'p12sf_'),'p13sf_'),'p14sf_'),'p15sf_'),'p16sf_'),'p17sf_'),'p18sf_'),'p19sf_'),'p20sf_');
		$link = $this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($link, 'p1ss_'),'p2ss_'),'p3ss_'),'p4ss_'),'p5ss_'),'p6ss_'),'p7ss_'),'p8ss_'),'p9ss_'),'p10ss_'),'p11ss_'),'p12ss_'),'p13ss_'),'p14ss_'),'p15ss_'),'p16ss_'),'p17ss_'),'p18ss_'),'p19ss_'),'p20ss_');
		$link = $this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($this->CI->koje_system->URLRemoveParamsL($link, 'p1sg_'),'p2sg_'),'p3sg_'),'p4sg_'),'p5sg_'),'p6sg_'),'p7sg_'),'p8sg_'),'p9sg_'),'p10sg_'),'p11sg_'),'p12sg_'),'p13sg_'),'p14sg_'),'p15sg_'),'p16sg_'),'p17sg_'),'p18sg_'),'p19sg_'),'p20sg_');
		$str = "<a class='btn btn-warning btn-round btn-minier' href='$link'><i class='ace-icon glyphicon glyphicon-refresh'></i>Clear</a>";
		return $str;
	}

}
