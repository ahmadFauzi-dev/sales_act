<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/


class koje_datatables
{
    private $raw_query = array();
    private $select = "";
    private $from = "";
    private $where = "";
    private $paging = "";
    private $sorting = "";
    private $params = array();
    private $raw_sql = null;
    private $search_value = null;
    private $names = array();
    private $data = array();
    private $searchable = array();
    private $total_rows;
    private $limit = 0;
    private $offset = 0;
    private $primary_key = null;
    var $pager_id = null;
    private $koje_button = false;
    protected $CI;
    /**
     * Constructor
     *
     * @param Array $query The sql query definition in the following way:
     *                     $query["from"] = " FROM cc.user ";
     *                     $query["where"] = " WHERE last_activity between  :start_date AND  :end_date ";
     *                     $query["params"] = array(
     *                                         'start_date'=>'2015-03-18',
     *                                          'end_date'=>'2016-03-18'
     *                                         );
     *
     *                     NOTE: The select statement is build automatically taking the columns from the
     *                           the Datatables client side.
     *
     *                           $('#example').dataTable( {
     *                                 processing: true,
     *                                 serverSide: true,
     *
     *                                 columns: [
     *                                     {
     *                                         "name": "username",
     *                                         "searchable":true,
     *                                         "orderable":true
     *                                     },
     *                                     {
     *                                         "name": "last_activity",
     *                                         "searchable":false,
     *                                         "orderable":true
     *                                     },
     *                                     {
     *                                         "name": "user_id",
     *                                         "searchable":false,
     *                                         "orderable":false
     *                                     }
     *                                  ]
     */
    public function __construct($pager_id='p1')
    { $this->CI =& get_instance();
      $this->pager_id = $pager_id;
    }

    public function init(array $query)
    {
        $this->raw_query = $query;
        $this->setPagerID();
        $this->setColumns();
        $this->setQueryParams();
        $this->setQuerySelect();
        $this->setQueryFrom();
        $this->setQueryWhere();
        $this->setQueryPaging();
        $this->setQuerySorting();
        $this->setTotalRows();
    }

    function setPagerID() {
      $this->pager_id = isset($_REQUEST['pager_id']) ? $_REQUEST['pager_id'] : 'p0';
    }
    /**
     * Set query select
     */
    private function setQuerySelect()
    {
        if (!isset($this->query['select']) && count($this->getNames()) <= 0) {
            throw new Exception(basename(__FILE__, '.php') . " error: " . "The SELECT statement was not found! Be sure to pass the columns array or set the select value in the query array");
        }

        $this->select = isset($this->query['select']) ? $this->query["select"] : "SELECT " .
        implode(", ", $this->getNames());
        $this->select .= " ";
    }
    /**
     * Set query from
     */
    private function setQueryFrom()
    {
        $this->from = self::required($this->raw_query, "from");
    }
    private function setQueryWhere()
    {
        $where = " ".self::optional($this->raw_query, "where");
        $this->search_value = self::optional($_REQUEST['search'], "value");
        if ($this->search_value) {
            $searchable_columns_statements = array();
            foreach ($this->searchable as $column) {
                $searchable_columns_statements[] = " $column::text ilike ? ";
                $this->params[] = trim("%" . $this->search_value . "%");
            }
            if (count($searchable_columns_statements) > 0) {
                $searchable_where = implode(" OR ", $searchable_columns_statements);
                $this->where = is_null($where) ? " WHERE $searchable_where " :  " $where AND (" . $searchable_where . ")";
            }
        } else {
            $this->where = $where;
        }
    }
    /**
     * Set query paging
     *
     * Compose the pagination for the results.
     */
    private function setQueryPaging()
    {
        $this->limit = self::optional($_REQUEST, 'length', 10);
        $this->offset = self::optional($_REQUEST, 'start', 0);
        if ($this->limit>0) {
          $this->paging = " LIMIT {$this->limit} OFFSET {$this->offset} ";
        }
        else {
          $this->paging = " LIMIT 1000 ";
        }
    }

    /**
     * Set query sorting
     */
    private function setQuerySorting()
    {
        // get column and direction (order)
        $items = self::optional($_REQUEST, 'order', array());
        if (count($items) > 0) {
            $fields = array();
            foreach ($items as $item) {
                $column_index = $item['column'];
                $field_name = $this->names[$column_index];
                $fields[] = $field_name . " " . $item['dir'];
            }
            $str = implode(',', $fields);
            $this->sorting = " ORDER BY $str";
        }
    }

    /**
     * Set query parameters
     *
     * Parameters that are used in the where statement to filter the results
     */
    private function setQueryParams()
    {
        $this->params = self::optional($this->raw_query, 'params', array());
    }

    /**
     * Set Columns
     *
     * Set columns property value from the request array passed by data table client api
     */
    private function setColumns()
    {
        $cols = self::optional($_REQUEST, 'columns', array());
        foreach ($cols as $column) {
              $datas[] = $column['data'];
              if ($column['data'] ==='_KOJE_BUTTON_') {
                $this->koje_button = true;
                $this->primary_key = $column['name'];
                $names[] = $column['name'];
              }
              else {
                $names[] = $column['name']!=="" ? $column['name'] : $column['data'];
                if (filter_var($column['searchable'], FILTER_VALIDATE_BOOLEAN)) {
                    $searchable[] = $column['name']!=="" ? $column['name'] : $column['data'];;
                }
              }
        }
        $this->names = $names;
        $this->datas = $datas;
        $this->searchable = $searchable;
    }

    /**
     * Get columns
     *
     * @return array
     */
    private function getData()
    {
        return $this->datas;
    }

    private function getNames()
    {
       return $this->names;
    }
    /**
     * Set Total Rows
     */
    private function setTotalRows()
    {
        // Get the raw variables passed
        $where = self::optional($this->raw_query, "where");
        $params = self::optional($this->raw_query, "params", array());
        $sql = "SELECT COUNT(*) count {$this->from} $where";
        $this->total_rows = $this->CI->adodb->getOne($sql, $params);
    }

    /**
     * Get total rows filtered
     *
     * @return integer
     */
    private function getTotalRowsFiltered()
    {
        $sql = "SELECT COUNT(*) {$this->from} {$this->where}";
        $row = $this->CI->adodb->getAll($sql, $this->params);
        return $row;
    }

    /**
     * Get total rows
     *
     * @return integer
     */
    public function getTotalRows()
    {
        return $this->total_rows;
    }

    /**
     * Get the json representation of the query result needed by data tables
     * @return json
     */
    public function getDataTables(array $query)
    {
        $this->init($query);
        $results = $this->search();
        $data = array();

        // get rows
        foreach ($results as $row) {
            $nestedData = array();

            // get column's index
            if(isset($this->primary_key)) {
              $nestedData['DT_RowId'] = $this->pager_id.'_row_'.$row[$this->primary_key];
            }
            foreach ($this->datas as $index => $value) {
              $nestedData[$value] = isset($row[$index]) ? $row[$index] : "";
            }

            $data[] = $nestedData;
        }

        // total rows filtered
        $total_rows_filtered = ($this->search_value) ? $this->getTotalRowsFiltered() : null;

        // data
        $json_data = array(
            /**
             * For every request/draw by client side , they send a number as a parameter, when they receive
             * a response/data they first check the draw number, so we are sending same number in draw.
             */
            "draw" => intval(self::optional($_REQUEST, 'draw', 0)),
            "sql" => $this->raw_sql. implode(",",$this->params),
            "recordsTotal" => intval($this->getTotalRows()), //total number of records before filtering
            "recordsFiltered" => intval(($total_rows_filtered) ? $total_rows_filtered : $this->getTotalRows()), //total number of records after searching
            "data" => $data
        );
        echo json_encode($json_data);  //send data as json format
    }

    /**
     * Search for some value
     *
     * @return array
     */
    public function search()
    {
        $sql =  $this->select . $this->from . $this->where . $this->sorting . $this->paging;
        $this->raw_sql = $sql;
        $rows = $this->CI->adodb->getAll($sql, $this->params);
        return $rows;
    }

    /**
     * Get raw sql
     *
     * @return string
     */
    public function getRawSql()
    {
        $sql = $this->raw_sql;
        foreach ($this->params as $key => $value) {
            $sql = str_replace(":$key", $value, $sql);
        }
        return $sql;
    }

    /**
     * Get a parameter
     *
     * This method is used to validate if a required parameter was passed from the client,
     * if it is found then return its value.
     *
     * @param  array $data  The array with all the parameters
     * @param  string $key  The key to validate if is in the array of passed parameters
     * @return string
     */
    public static function required($data, $key = null)
    {
        try {
            if (!isset($data[$key])) {
                throw new Exception("Missing required parameter '{$key}' in request");
            } else {
                return $data[$key];
            }
        } catch (Exception $e) {
            header("Status: 500 Server Error");
            echo $e->getMessage();
        }
    }

    /**
     * Get optional parameters
     *
     * This method is check if an optional parameter was passed from the client,
     * if it is found return its value otherwise return a default value.
     *
     * @param  array $data          [description]
     * @param  string $key           [description]
     * @param  string $default_value [description]
     * @return string                [description]
     */
    public static function optional($data, $key, $default_value = null)
    {
        if (!isset($data[$key])) {
            return $default_value;
        } else {
            return $data[$key];
        }
    }

    function renderHeader($params=array()) {
      $CI =& get_instance();
      $label_admin  = "ADMIN";

      $template = $CI->koje_system->getArrayVal($params,'template',false);
      if($template) { return $template; }
      $title = $CI->koje_system->getArrayVal($params,'title',false);
      $heading = $CI->koje_system->getArrayVal($params,'heading',false);

      $colspan = count($params['columns'])-1;
      $id = $CI->koje_system->getArrayVal($params,'id','999');
      $strSearch =$this->renderSearchForm($params);
      if($strSearch) {
        $strSearch = "
        <table width='100%' class='table compact table-bordered table-striped'>
            <tr>
              <th width='1%'><label class='control-label'><b>DATA FILTER</b></label><br/>
                  <button class='dt-button' onclick='DataTablesFilterClear(\"{$id}\");'><span>Clear Filter</span></button>
              </th>
              <th colspan='$colspan'>$strSearch</th>
              <th width='10%'><div id='{$id}ss___koje_filter_div'></div></th>
            </tr>
          </table>";
      }
//      $str = $this->CI->koje_system->HTML_SetTitle($title);
      $thfoot = "";
      $str ="
          <div class='' id='table_{$id}'>
            <h4>$title</h4>
            $strSearch
            <table id='$id' name='$id' width='100%' class='table compact table-bordered table-striped'>
              <thead>
                {$heading}";

      if(!$heading)
      {
        $str .="<tr>";
        foreach ($params['columns'] as $key => $value) {
          switch ($key) {
            case '_KOJE_BUTTON_':
              $str   .= "<th>$label_admin</th>";
              $thfoot .= "<th>Summary</th>";
              break;
            default:
              $str .= "<th title='hold SHIFT key for multi sort'>".$this->CI->koje_system->getTitleLabel($key,$value,'title')."</th>";
              $thfoot .= "<th>&nbsp;</th>";
              break;
          }
        }
        $str .="</tr>";
     }
     $str .="</thead>
                <tfoot>
                  <tr>$thfoot</tr>
                </tfoot>
	   
					  
          	</table>
          </div>
          ";
      return $str;
    }
    function renderDataTable($params = array())
    {
      $db = isset($params['db']) ? '&db=billing':''; 
      $id           = $this->CI->koje_system->getArrayVal($params,'id',$this->pager_id);
      $title        = $this->CI->koje_system->getArrayVal($params,'title','');
      $primary_key  = $params['primary_key'];
      $button       = $params['toolbarButton'];
      $search       = $params['search'];
      $responsive   = $this->CI->koje_system->getArrayVal($params,'responsive',true) ? 'true' : 'false';
      $scrollX      = $responsive=='true' ? 'false' : $this->CI->koje_system->getArrayVal($params,'scrollX','true');
      $summaryCols  = $this->CI->koje_system->getArrayVal($params,'summary',array());
      $heading      = $this->CI->koje_system->getArrayVal($params,'heading',false);
      $strSummary   = "";
      foreach ($summaryCols as $key => $value) {
        $strSummary .= " DataTablesSummaryUpdate(api, $value);";
      }

      $f            = $this->CI->koje_system->base64Encode($params['query']['from']);
      $w            = $this->CI->koje_system->base64Encode($params['query']['where']);
      $strAjaxUrl  = base_url()."index.php/_system_/getJSON?pager_id=$id&f=".$f."&w=".$w.$db;
      $strAjaxData  = "";
      $str          = $this->renderHeader($params);

      $cols         = array();
      $jsPickList   = "";

      foreach ($params['columns'] as $key => $value) {
        switch ($key) {
          case '_KOJE_PICK_':
                      $jsPickList = "
                      $('#$id tbody').on('click', 'a.pickList', function (e) {
                        var data = table_$id.row( $(this).parents('tr') ).data();
                        passBack(data[0],data);
                      });
                      ";
                      $cols[$key] = "{
                                      data :'$key',
                                      name : '$primary_key',
                                      searchable:false,
                                      width: '1%',
                                      className: 'dt-body-center',
                                      orderable:false,
                                      render: function(data, type, row) {
                                        var str = '<a href=\"#\" class=\"pickList btn koje-btn btn-raised btn-primary btn-sm btn-round\">PICK</a>';
                                        return str;
                                      }
                                    }
                        ";
              break;
          case '_KOJE_CHECK_':
                $cols[$key] = "{
                                data :'$key',
                                name : '$primary_key',
                                searchable:false,
                                width: '1%',
                                className: 'dt-body-center',
                                orderable:false,
                                render: function(data, type, row) {
                                  var str = '<input type=\"checkbox\" class=\"checkList\" />';
                                  return str;
                                }
                              }
                  ";
            break;
          case '_KOJE_BUTTON_':
                      $btnGroup = $this->renderBtnGroup($value,$primary_key);
                      $visible = $this->CI->koje_system->getArrayVal($value,'btn_visibility',array());
                      $btnDisabled  = "XXXX";
                      $hDisabled    = "XXXX";
                      foreach ($visible as $k1 => $v1) {
                        $btnDisabled .= "|C_DISABLED$v1";
                        $hDisabled   .= "|H_DISABLED$v1";
                      }
                      $cols[$key] = "{
                                        data: '$key',
                                        name : '$primary_key',
                                        searchable:false,
                                        width: '1%',
                                        className: 'dt-body-center',
                                        orderable:false,
                                        render: function(data, type, row) {
                                                  var dt = data.replace('.','_');																				 
                                                  var str = (\"$btnGroup\");
                                                  if(typeof row.btn_visibility != 'undefined' && row.btn_visibility !=='') {
                                                    str = str.replace(/$btnDisabled/g,'disabled').replace(/$hDisabled/g,'href=\"#\"');
                                                  }
                                                  return str;
                                                }
                                    }";
            break;
          default:
              $cols[$key] = "{data: '$key',";
              $format = $this->CI->koje_system->getArrayVal($value,'format');
              if($format) {
                switch ($format) {
                  case 'number' :
                    $cols[$key] .= "render: $.fn.dataTable.render.number( ',', '.', 0, '' ), className: 'dt-body-right',";
                    break;
                  case 'percentage' :
                    $cols[$key]   .= "render: $.fn.dataTable.render.percentBar(), className: 'dt-body-center',";
                    break;
                  case 'date' :
                    $cols[$key]   .= "render: $.fn.dataTable.render.moment('', 'DD-MMM-YYYY' ),";
                    break;
                  case 'time' :
                    $cols[$key]   .= "render: $.fn.dataTable.render.moment('', 'HH:mm' ),";
                    break;
                  case 'datetime' :
                    $cols[$key]   .= "render: $.fn.dataTable.render.moment('', 'DD-MMM-YYYY HH:mm' ),";
                    break;
                  case 'file' :
                    $url = site_url();
					$cols[$key]   .= "render: function(data,type,full,meta){if (data=='') return '-'; else return '<div><a href=\"#\" onclick=\"windowPopup(\'{$url}_system_/ShowFile/index?t_file_id='+data+'\',\'\',\'\')\">View</a></div>';},";
                  break;
                }
                unset($value['format']);
              }
              $visible = $this->CI->koje_system->getArrayVal($value,'visible', true);
              if(!$visible) {
                $cols[$key]   .= "className: 'never',";
              }

              foreach ($value as $key1 => $value1) {
                $cols[$key] .= "$key1:" . ($value1!==false?"'$value1'":'false') . ",";
              }
              if(!isset($value['title'])) {
                $cols[$key] .= 'title' . ":'" . $this->CI->koje_system->getTitleLabel($key, $value, 'title') . "',";
              }
              $cols[$key] .= "}";
              $strAjaxData .= "dt.{$id}ss_{$key} = $('#{$id}ss_{$key}').val();";
            break;
        }
      }
      $strColumns = implode(',',$cols);

      $strBtn = "var btn='';";
      if($button) {
        foreach ($button as $key => $value) {
          $strBtn .= "btn = btn + \"<a class='btn koje-btn btn-raised btn-primary btn-sm btn-round' href='{$value['url']}'>{$value['title']}</a> \";";
        }
      }

      $strOrder     = "";
      foreach ($params['order'] as $key => $value) {
        $strOrder .= "[$key,'$value'],";
      }
      $strOrder     = $this->CI->koje_system->removeLastChar($strOrder, ',');

      $strHeading = ! $heading ? "" : '$("table[name=\''.$id.'\'] thead").prepend(`'.$heading.'`);';
      $str .=
            "<script>
                \$(document).ready(function() {
                    var table_$id = $('#$id').DataTable( {
                        responsive : $responsive,
                        scrollX : $scrollX,
                        columns:  [
                                    $strColumns
                                  ],
                        ajax: {
                            type: 'POST',
                            url:'$strAjaxUrl',
                            data:function(dt){
                                    $strAjaxData
                                    return dt;
                                },
                            error: function(xhr, error, thrown){
                                $('.$id-grid-error').html('');
                                $('#$id').append('<tbody class=$id-grid-error><tr><th colspan=3>Error: ' + xhr.responseText + ' </th></tr></tbody>');
                                $('#$id-grid_processing').css('display','none');
                            }
                          },
                        order : [
                                  $strOrder
                                ],
                        'footerCallback' : function ( row, data, start, end, display ) {
                                              var api = this.api(), data;
                                              $strSummary
                                          }
                      });
                      $strBtn
                      $('.table-toolbar').html(btn);
                      datatablesInit('{$id}');
                      $jsPickList
                      $('#$id tbody1').on( 'click', 'tr', function () {
                        if ( $(this).hasClass('selected') ) {
                            $(this).removeClass('selected');
                        }
                        else {
                            table_$id.$('tr.selected').removeClass('selected');
                            $(this).addClass('selected');
                        }
                      });
					  $strHeading								 
                  });
      </script>";
      $str = '<div class="box box-widget widget-user-2"><h4 class="widget-user-header bg-blue">'.$title.'</h4><div class="content">'.$str.'</div></div>';
      return $str;
    }
	function renderDataTableView($params = array())
    {
      $db = isset($params['db']) ? '&db=billing':''; 
      $id           = $this->CI->koje_system->getArrayVal($params,'id',$this->pager_id);
      $title        = $this->CI->koje_system->getArrayVal($params,'title','');
      $primary_key  = $params['primary_key'];
      $button       = $params['toolbarButton'];
      $search       = $params['search'];
      $responsive   = $this->CI->koje_system->getArrayVal($params,'responsive',true) ? 'true' : 'false';
      $scrollX      = $responsive=='true' ? 'false' : $this->CI->koje_system->getArrayVal($params,'scrollX','true');
      $summaryCols  = $this->CI->koje_system->getArrayVal($params,'summary',array());
      $heading      = $this->CI->koje_system->getArrayVal($params,'heading',false);
      $strSummary   = "";
      foreach ($summaryCols as $key => $value) {
        $strSummary .= " DataTablesSummaryUpdate(api, $value);";
      }

      $f            = $this->CI->koje_system->base64Encode($params['query']['from']);
      $w            = $this->CI->koje_system->base64Encode($params['query']['where']);
      $strAjaxUrl  = base_url()."index.php/_system_/getJSON?pager_id=$id&f=".$f."&w=".$w.$db;
      $strAjaxData  = "";
      $str          = $this->renderHeader($params);

      $cols         = array();
      $jsPickList   = "";

      foreach ($params['columns'] as $key => $value) {
        switch ($key) {
          case '_KOJE_PICK_':
                      $jsPickList = "
                      $('#$id tbody').on('click', 'a.pickList', function (e) {
                        var data = table_$id.row( $(this).parents('tr') ).data();
                        passBack(data[0],data);
                      });
                      ";
                      $cols[$key] = "{
                                      data :'$key',
                                      name : '$primary_key',
                                      searchable:false,
                                      width: '1%',
                                      className: 'dt-body-center',
                                      orderable:false,
                                      render: function(data, type, row) {
                                        var str = '<a href=\"#\" class=\"pickList btn koje-btn btn-raised btn-primary btn-sm btn-round\">PICK</a>';
                                        return str;
                                      }
                                    }
                        ";
              break;
          case '_KOJE_CHECK_':
                $cols[$key] = "{
                                data :'$key',
                                name : '$primary_key',
                                searchable:false,
                                width: '1%',
                                className: 'dt-body-center',
                                orderable:false,
                                render: function(data, type, row) {
                                  var str = '<input type=\"checkbox\" class=\"checkList\" />';
                                  return str;
                                }
                              }
                  ";
            break;
          case '_KOJE_BUTTON_':
                      $btnGroup = $this->renderBtnGroup($value,$primary_key);
                      $visible = $this->CI->koje_system->getArrayVal($value,'btn_visibility',array());
                      $btnDisabled  = "XXXX";
                      $hDisabled    = "XXXX";
                      foreach ($visible as $k1 => $v1) {
                        $btnDisabled .= "|C_DISABLED$v1";
                        $hDisabled   .= "|H_DISABLED$v1";
                      }
                      $cols[$key] = "{
                                        data: '$key',
                                        name : '$primary_key',
                                        searchable:false,
                                        width: '1%',
                                        className: 'dt-body-center',
                                        orderable:false,
                                        render: function(data, type, row) {
                                                  var dt = data.replace('.','_');																				 
                                                  var str = (\"$btnGroup\");
                                                  if(typeof row.btn_visibility != 'undefined' && row.btn_visibility !=='') {
                                                    str = str.replace(/$btnDisabled/g,'disabled').replace(/$hDisabled/g,'href=\"#\"');
                                                  }
                                                  return str;
                                                }
                                    }";
            break;
          default:
              $cols[$key] = "{data: '$key',";
              $format = $this->CI->koje_system->getArrayVal($value,'format');
              if($format) {
                switch ($format) {
                  case 'number' :
                    $cols[$key] .= "render: $.fn.dataTable.render.number( ',', '.', 0, '' ), className: 'dt-body-right',";
                    break;
                  case 'percentage' :
                    $cols[$key]   .= "render: $.fn.dataTable.render.percentBar(), className: 'dt-body-center',";
                    break;
                  case 'date' :
                    $cols[$key]   .= "render: $.fn.dataTable.render.moment('', 'DD-MMM-YYYY' ),";
                    break;
                  case 'time' :
                    $cols[$key]   .= "render: $.fn.dataTable.render.moment('', 'HH:mm' ),";
                    break;
                  case 'datetime' :
                    $cols[$key]   .= "render: $.fn.dataTable.render.moment('', 'DD-MMM-YYYY HH:mm' ),";
                    break;
                  case 'file' :
                    $url = site_url();
					$cols[$key]   .= "render: function(data,type,full,meta){if (data=='') return '-'; else return '<div><a href=\"#\" onclick=\"windowPopup(\'{$url}_system_/ShowFile/index?t_file_id='+data+'\',\'\',\'\')\">View</a></div>';},";
                  break;
                }
                unset($value['format']);
              }
              $visible = $this->CI->koje_system->getArrayVal($value,'visible', true);
              if(!$visible) {
                $cols[$key]   .= "className: 'never',";
              }

              foreach ($value as $key1 => $value1) {
                $cols[$key] .= "$key1:" . ($value1!==false?"'$value1'":'false') . ",";
              }
              if(!isset($value['title'])) {
                $cols[$key] .= 'title' . ":'" . $this->CI->koje_system->getTitleLabel($key, $value, 'title') . "',";
              }
              $cols[$key] .= "}";
              $strAjaxData .= "dt.{$id}ss_{$key} = $('#{$id}ss_{$key}').val();";
            break;
        }
      }
      $strColumns = implode(',',$cols);

      $strBtn = "var btn='';";
      if($button) {
        foreach ($button as $key => $value) {
          $strBtn .= "btn = btn + \"<a class='btn koje-btn btn-raised btn-primary btn-sm btn-round' href='{$value['url']}'>{$value['title']}</a> \";";
        }
      }

      $strOrder     = "";
      foreach ($params['order'] as $key => $value) {
        $strOrder .= "[$key,'$value'],";
      }
      $strOrder     = $this->CI->koje_system->removeLastChar($strOrder, ',');

      $strHeading = ! $heading ? "" : '$("table[name=\''.$id.'\'] thead").prepend(`'.$heading.'`);';
      $str .=
            "<script>
                \$(document).ready(function() {
                    var table_$id = $('#$id').DataTable( {
                        responsive : $responsive,
                        scrollX : $scrollX,
                        columns:  [
                                    $strColumns
                                  ],
                        ajax: {
                            type: 'POST',
                            url:'$strAjaxUrl',
                            data:function(dt){
                                    $strAjaxData
                                    return dt;
                                },
                            error: function(xhr, error, thrown){
                                $('.$id-grid-error').html('');
                                $('#$id').append('<tbody class=$id-grid-error><tr><th colspan=3>Error: ' + xhr.responseText + ' </th></tr></tbody>');
                                $('#$id-grid_processing').css('display','none');
                            }
                          },
                        order : [
                                  $strOrder
                                ],
                        'footerCallback' : function ( row, data, start, end, display ) {
                                              var api = this.api(), data;
                                              $strSummary
                                          }
                      });
                      $strBtn
                      $('.table-toolbar').html(btn);
                      datatablesInit('{$id}');
                      $jsPickList
                      $('#$id tbody1').on( 'click', 'tr', function () {
                        if ( $(this).hasClass('selected') ) {
                            $(this).removeClass('selected');
                        }
                        else {
                            table_$id.$('tr.selected').removeClass('selected');
                            $(this).addClass('selected');
                        }
                      });
					  $strHeading								 
                  });
      </script>";
      $str = '<div class="box box-widget widget-user-2"><h4 class="widget-user-header bg-blue">'.$title.'</h4><div class="content">'.$str.'</div></div>';
      return $str;
    }
    function renderBtnGroup($params = array(), $primary_key=false)
    {
      $title = $this->CI->koje_system->getArrayVal($params,'title','');
      $buttons = $this->CI->koje_system->getArrayVal($params,'button');
      if(!$buttons) return;
      $str = "<div class='input-group margin'><div class='input-group-btn'><button type='button' class='btn koje-btn btn-sm btn-raised btn-primary dropdown-toggle' data-toggle='dropdown'>$title <span class='fa fa-caret-down'></span></button><ul class='dropdown-menu'>";
      $i=1;
      foreach ($buttons as $key => $value) {
        switch ($key) {
          case '_DIVIDER_':
            $str .= "<li class='divider'></li>";
            break;
          default:
            $url_btn = $this->CI->koje_system->URLAdd($value['url'],"{$primary_key}=\"+data+\"");
            $attr = str_replace(':data',"KOJE_BTN_GROUP_{$key}_\"+data+\"",$this->CI->koje_system->getArrayVal($value,'attr',''));
            $title_btn = $value['title'];
            $str .= "<li class='C_DISABLED{$key}'><a $attr H_DISABLED{$key} id='KOJE_BTN_GROUP_{$key}_\"+data+\"' name='KOJE_BTN_GROUP_{$key}[]' href='{$url_btn}'>{$title_btn}</a></li>";
            break;
        }
        $i++;
      }
      $str .= "</ul></div></div>";
      return $str;
    }
    function renderSearchForm($params=array())
    {
      $id = $params['id'];
      $search = $params['search'];
      if(!$search) {
        return false;
      }
      $str = implode("",
                array(
                      '<div id='.$id.'__koje_filter class="koje-filter form-group form-horizontal">',
                        $search,
                      '</div>',
              )
            );
    return $str;
    }

}
