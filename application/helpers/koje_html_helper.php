<?php


function getValueEqual($str, $str1, $str2, $str3=false)
{
  return $str==$str1 ? $str2 : $str3;
}

function getValueNull($str, $str1)
{
  return $str ? $str : $str1;
}
function ifNull($str, $str1)
{
  return $str ? $str : $str1;
}
function getLabel($str)
{ global $koje_label;
  $str = (substr($str,-5)=='_desc') ? substr($str,0,strlen($str)-5) : $str;
  return isset($koje_label[$str]) ? $koje_label[$str] : $str;
}
function getArrayVal($arr, $key, $default=false)
{
  if (isset($arr[$key]) && is_array($arr)) return $arr[$key];
  else return $default;
}
function getMonthFull($no)
{
  $arrMonthFullLabel = array('January', 'February', 'March', 'April', 'May', 'June', 'July','August','September','October','November','December');
  return getArrayVal($arrMonthFullLabel,intval($no)-1);
}
function dateFormat($dt)
{
  return substr($dt,8,2).' '. getMonthFull(substr($dt,5,2)).' ' .substr($dt,0,4);
}

/* HTML Utils */
function html_title($str) {
  $str = "<h4 class='text-right tex-blue'><b>$str</b></h5>";
  return $str;
}

function html_col1Start() {
  $str = 	"<div class='col-md-12'>";
  return $str;
}
function html_col2Start() {
  $str = 	"<div class='col-md-6'>";
  return $str;
}
function html_col3Start() {
  $str = 	"<div class='col-md-3'>";
  return $str;
}
function html_colEnd() {
  $str = 	"</div>";
  return $str;
}
function html_blockStart() {
  $str = 	"<div class='box-primary'>";
  return $str;
}
function html_blockHeader($str) {
  $str = "<h5 class='text-center'><b>$str</b></h5>";
  return $str;
}
function html_blockEnd() {
  $str = 	"<br></div>";
  return $str;
}
function html_class($class="") {
  $class = str_replace("'","",$class);
  return "class='$class'";
}

/* URL Utils */
function html_getTitleTable($str, $default = false) {
  global $TABLE_TITLE;
  $str = strtoupper($str);
  return isset($TABLE_TITLE[$str]) ? $TABLE_TITLE[$str] : $default;
}

function html_URLAdd($url, $param="") {
  $str = $url;
  $sign = (strpos($url,'?')===false) ? "?" : "&";
  $str = $url.$sign.$param;
  $str = str_replace("??","?",$str);
  $str = str_replace("?&","?",$str);
  $str = str_replace("&&","&",$str);
  return $str;
}

function html_removeLastChar($str,$chr) {
  $lc = substr($str,-1);
  return $lc==$chr ? substr($str,0,-1) : $str;
}

function HTML_link($link, $text, $others="") {
  if(strpos($others,'class=')===false) $others .= "class='".STYLE::BUTTON_URL."'";
  return "<a $others href='$link'>$text</a>";
}
function HTML_link_active($link, $text, $others="") {
  if(strpos($others,'class=')===false) $others .= "class='".STYLE::BUTTON_URL_ACTIVE."'";
  return "<a $others href='$link'>$text</a>";
}
function HTML_link_icon($link, $text, $icon,$others="") {
  if(strpos($others,'class=')===false) $others .= "class='".STYLE::BUTTON_URL."'"; 
  return "<a class='btn btn-sm btn-social btn-google' href='$link' $others><i class='$icon'></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$text</a>";
}
function html_form_readonly()
{
  $str = <<<EOF
    <script>
      $(document).ready(function(){
        $("input").prop('readonly', true);
        $("select").prop('disabled', true);
      });
    </script>
EOF;
  return $str;
}

function html_button_group($params=false)
{

  $list = array();
  $attributes = array();
  foreach ($params['list'] as $key => $value) {
    $url = $value['url'];
    $label = $value['label'];
    $list[] = "<a href='$url'>$label</a>";
  }
  $attributes['class'] = 'dropdown-menu';
  $str1 = ul($list, $attributes);
  $btnLabel = isset($params['title']) ? $params['title'] : "";
  $str = "
  <div class='input-group margin'>
    <div class='input-group-btn'>
      <button type='button' class='btn koje-btn btn-sm btn-raised btn-primary dropdown-toggle' data-toggle='dropdown'>
        $btnLabel&nbsp;<span class='fa fa-caret-down'></span>
      </button>
      $str1
    </div>
  </div>";
  return $str;
}

function NOW($format="Y-m-d H:i:s")
{
    return date($format);
}

function date_now($format="Y-m-d H:i:s")
{
    return date($format);
}

function html_table($params=false)
{
  $CI       =& get_instance();
  $id       = $CI->koje_system->getArrayVal($params,'id','myTable'.rand());
  $cols     = $params['cols'];
  $data     = array();
  $template = $CI->koje_system->getArrayVal($params,'template',array('table_open' => '<div id=table_div_'.$id.'><table id='.$id.' width="99%" border="0" class="table table-striped table-bordered">','table_close'=>'</table></div>'));
  $heading  = $CI->koje_system->getArrayVal($params,'heading',false);
  $caption  = '<b>'.$CI->koje_system->getArrayVal($params,'caption').'</b>';

  if(!$heading)
  {
    foreach ($cols as $key => $value) {
      $heading[] = getLabel($value);
    }
  }

  foreach ($params['data'] as $key => $value)
  {
    foreach ($cols as $key1 => $value1)
    {
      $data[$key][$key1] = $params['data'][$key][$value1];
    }
  }
  $CI->table->set_template($template);
  $CI->table->set_heading($heading);
  $CI->table->set_caption($caption);
  $table = $CI->table->generate($data);
  /* BUGS HANDLING UTK HEADING MULTI COLUMN */
  $s = str_replace('<th> <tr>','',$table);
  $s .= <<<EOF
  <script>
  $(document).ready(function(){
      var table = $('#{$id}').dataTable( {
        serverSide  : false,
        responsive  : false,
        scrollX 		: true,
        dom         : 't',
        pageLength  : 50,
      });
  })
  </script>
EOF;
  return $s;
}
function html_table_clear($params=false)
{
  $CI       =& get_instance();
  $id       = $CI->koje_system->getArrayVal($params,'id','myTable'.rand());
  $cols     = $params['cols'];
  $data     = array();
  $template = $CI->koje_system->getArrayVal($params,'template',array('table_open' => '<div id=table_div_'.$id.'><table id='.$id.' width="99%" border="0" class="table table-striped">','table_close'=>'</table></div>'));
  $heading  = $CI->koje_system->getArrayVal($params,'heading',false);
  $caption  = '<b>'.$CI->koje_system->getArrayVal($params,'caption').'</b>';

  if(!$heading)
  {
    foreach ($cols as $key => $value) {
      $heading[] = getLabel($value);
    }
  }

  foreach ($params['data'] as $key => $value)
  {
    foreach ($cols as $key1 => $value1)
    {
      $data[$key][$key1] = $params['data'][$key][$value1];
    }
  }
  $CI->table->set_template($template);
  $CI->table->set_heading($heading);
  $CI->table->set_caption($caption);
  $table = $CI->table->generate($data);
  /* BUGS HANDLING UTK HEADING MULTI COLUMN */
  $s = str_replace('<th> <tr>','',$table);
  return $s;
}
function html_content_table($params=false)
{
    $CI       =& get_instance();
    $title    = $params['title'];

		$s = '<div class="box box-widget widget-user-2">
				  <h4 class="widget-user-header bg-blue">'.$title.'</h4>
				      <div class="content">
				          <table class="table table-bordered"><tbody>';
		$cols = 0;
		foreach ($params['format'] as $rec) {
			if (is_array($rec)) {
				if($cols==0) $cols=count($rec);
				$s .= '<tr>';
				foreach($rec as $key => $val) {
          $k = is_int($key) ? $val : $key;
          $s .= '<td class=small-caps width="1%" nowrap>'.getLabel($val).'</td><td width="49%" >'.
                      $CI->koje_system->getArrayVal($params['data'], $k,'&nbsp;').
                '</td>';
				}
				$s .= '</tr>';
			}
			else {
				$s .= '<tr>';
				$s .= "<td colspan=$cols>$rec</td>";
				$s .= '</tr>';
			}
		}
		$s .= '</tbody></table></div></div>';
		return $s;
}

function html_table_form($params=false)
{
    $CI       =& get_instance();
    $id       = getArrayVal($params,'id','myTableForm'.rand());
    $template = getArrayVal($params,'template',array('table_open' => '<table id="'.$id.'" class="table table-border table-stripped" width="100%">'));
    $heading  = getArrayVal($params,'heading');
    $title    = $params['title'];

		$s = '<div class="box box-widget widget-user-2">
				  <h4 class="widget-user-header bg-blue">'.getLabel($title).'</h4>
				      <div class="content">';
    $cols = count($params['cols']);
    $rows = 0;
    foreach ($params['cols'] as $key => $value) {
      $cr = is_array($params['cols'][$key]) ? count($params['cols'][$key]) : 0;
      if($rows<$cr)
      {
        $rows = $cr;
      }
    }
    $data = array();
    $heading = array();
    for($i=0;$i<$cols;$i++)
    {
      $heading[] = "Attribute";
      $heading[] = "Value";
      for($j=0;$j<$rows;$j++) {
        $col = getArrayVal($params['cols'][$i],$j);
        if($col && array_key_exists($col,$params['data']))
        {
          $data[$j][$i*2] = getLabel($col);
          $data[$j][$i*2+1] = getArrayVal($params['data'],$col);
        }
        else {
          $data[$j][$i*2] = $col;
          $data[$j][$i*2+1] = false;
        }
      }
    }
    $CI->table->set_template($template);
    $CI->table->set_heading($heading);
    $s .= $CI->table->generate($data);
		$s .= '</div></div>';
    $s .= <<<EOF
    <script>
    $(document).ready(function(){
        var table = $('#{$id}').dataTable( {
          serverSide  : false,
          responsive  : false,
          scrollX 		: true,
          dom         : 't',
          pageLength  : 50,
          ordering    : false,
        });
    })
    </script>
EOF;
		return $s;
}
function html_warning($str)
{
  return "<font color='red'><b>".getLabel($str)."</b></font>";
}
function http_curl_json($url,$data=array())
{
  $ch   = curl_init($url);
  $json = json_encode($data);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
}
