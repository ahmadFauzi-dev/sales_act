var cDate = moment().format('YYYYMMDD');
/*
$(window).keydown(function (event) {
    if (event.keyCode == 13) {
        event.preventDefault();
        return false;
    }
});
*/
/* -------------------- function ------------------------------------------*/
function redirect(str)
{
  window.location.replace(str);
}

function windowPopup(url,win,attr) {
  if(win=="") {
    win="win_"+Math.random();
  }
  if(attr=="") {
    attr = "top=50,left=50,width=800,height=600,scrollbars=yes";
  }
  var w = window.open(url,win, attr);
}
function localStorage_get(name) {
  if (typeof (Storage) !== 'undefined') {
    return localStorage.getItem(name)
  } else {
    window.alert('Please use a modern browser to properly view this template!')
  }
}

function localStorage_store(name, val) {
    if (typeof (Storage) !== 'undefined') {
        localStorage.setItem(name, val)
    } else {
        window.alert('Please use a modern browser to properly view this template!')
    }
}

function stringToCurrency(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    //return x1 + x2;
    return x1;
}


function intVal( i ) {
    return typeof i === 'string' ?
        i.replace(/[\$\Rp,]/g, '')*1 :
        typeof i === 'number' ?
            i : 0;
};

/* ------------------- dataTables ------------------------------------------*/
var dtMaxRecordPerPage = 1000;
var dtSetMaxRecord = function(dt) {
  var recordsTotal = dt.page.info().recordsTotal;
  var pages        = dt.page.info().pages;
  len = dt.page.len();
  if(len<dtMaxRecordPerPage) {
    dt.page.len(dtMaxRecordPerPage).draw();
  }

  if (recordsTotal > pages * dtMaxRecordPerPage)
  {
    alert('the system will copy only the first ' + pages * dtMaxRecordPerPage + ' records.');
  }
  else {
    alert(recordsTotal + ' records generated.');
  }
  return len;
}

var setCurrentLen = function(dt, len) {
  dt.page.len(len).draw();
}

var doCOPY = function ( e, dt, node, config ){
              len = dtSetMaxRecord(dt);
              admin_vis = dt.column(':contains(ADMIN)').visible();
              dt.column(':contains(ADMIN)').visible(false);
              $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
              dt.column(':contains(ADMIN)').visible(admin_vis);
              setCurrentLen(dt,len);
            };

var doCSV = function ( e, dt, node, config ){
              len = dtSetMaxRecord(dt);
              admin_vis = dt.column(':contains(ADMIN)').visible();
              dt.column(':contains(ADMIN)').visible(false);
              $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, node, config);
              dt.column(':contains(ADMIN)').visible(admin_vis);
              setCurrentLen(dt,len);
            };

var doXLS = function ( e, dt, node, config ){
              len = dtSetMaxRecord(dt);
              admin_vis = dt.column(':contains(ADMIN)').visible();
              dt.column(':contains(ADMIN)').visible(false);
              $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
              dt.column(':contains(ADMIN)').visible(admin_vis);
              setCurrentLen(dt,len);
            };

var doPDF = function ( e, dt, node, config ){
            len = dtSetMaxRecord(dt);
            admin_vis = dt.column(':contains(ADMIN)').visible();
            dt.column(':contains(ADMIN)').visible(false);
            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
            dt.column(':contains(ADMIN)').visible(admin_vis);
            setCurrentLen(dt,len);
          };

var redrawAllowed = true;
function DataTablesRedraw(id) {
    if (redrawAllowed) {
      var table = $('#'+id).DataTable();
      table.draw();
    }
}

function datatablesInit(id) {
  var table = $('#'+id).DataTable();
  f = $('#'+id+'_filter').detach();
  f.appendTo($('#'+id+'ss___koje_filter_div'));
  $('input[id^="'+id+'ss_"]').change(function(){DataTablesRedraw(id)});
  $('select[id^="'+id+'ss_"]').change(function(){DataTablesRedraw(id)});

  $('#'+id+'_filter input').change(function(){DataTablesRedraw(id)});

  var searchbox = $('#'+id+'_filter input');
  searchbox.unbind();
  searchbox.on('change', function (e) {
      table.search(this.value);
      DataTablesRedraw(id);
  });
}

function DataTablesFilterClear(id) {
    redrawAllowed = false;
    var table = $('#'+id).DataTable();
    $( "[id*='"+id+"ss_']" ).val("").trigger('change');
    table.search("").draw();
    redrawAllowed = true;
}
$.extend( true, $.fn.dataTable.defaults, {
    searching   : true,
    ordering    : true,
    processing  : true,
    serverSide  : true,
    responsive  : true,
    dom         : 'B<"table-toolbar col-sm-6">frtip',
    colReorder  : true,
    stateSave   : true,
    fixedHeader : {header: true,footer: true},
    language    : {"emptyTable": "No matching records found for ", "search"    : "", },
    lengthMenu  : [[ 10, 25, 50, dtMaxRecordPerPage ],[ '10 rows per page', '25 rows per page', '50 rows per page', dtMaxRecordPerPage + ' rows per page' ]],
    buttons     : [
                    {extend: 'collection',
                     text: 'Export Data',
                     buttons: [ {extend: 'copy', filename:'Export_'+cDate, action: doCOPY, exportOptions: {columns: ':visible'}},
                                {extend: 'csv',  filename:'Export_'+cDate, action: doCSV, exportOptions: {columns: ':visible'}},
                                {extend: 'excel',filename:'Export_'+cDate, action: doXLS, exportOptions: {columns: ':visible'}},
                                {extend: 'pdf',  filename:'Export_'+cDate, action: doPDF, exportOptions: {columns: ':visible'}},
                              ]
                    },
                    {extend: 'collection',
                     text: 'Column Settings',
                     buttons: [ {extend: 'colvisGroup',text: 'Show All',show: ':hidden'},
                                {extend: 'colvis', text : 'Show Selected', postfixButtons: [ 'colvisRestore' ]},
                                {extend: 'pageLength', text : 'Set Rows Per Page'},
                              ]
                    },
                    {extend: 'reload'},
                  ],
    initComplete : function(settings, json) {
                    $('input[type="search"]').attr('placeholder', 'Search All...');
                  },
});

$.fn.dataTable.ext.buttons.reload = {
    text: 'Reload',
    action: function ( e, dt, node, config ) {
        dt.ajax.reload();
    }
};

$.fn.dataTable.ext.buttons.clear = {
    text: 'Clear Filter',
    action: function ( e, dt, node, config ) {
        id = dt.table().node().id;
        DataTablesFilterClear(id);
    }
};

$.extend( $.fn.dataTableExt.oStdClasses, {
    "sFilterInput": "form-control",
    "sLengthSelect": "form-control"
});


function DataTablesSummaryTotal(api,col) {
  return api.column(col).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
}

function DataTablesSummaryPage(api,col) {
  return api.column( col, { page: 'current'} ).data().reduce( function (a, b) {return intVal(a) + intVal(b);}, 0 );
}

function DataTablesSummaryUpdate(api, col)
{
  total = stringToCurrency(DataTablesSummaryTotal(api,col));
  pageTotal = stringToCurrency(DataTablesSummaryPage(api,col));
  if(pageTotal == total) {
    $( api.column( col ).footer() ).html(pageTotal);
  }
  else
  {
    $( api.column( col ).footer() ).html(pageTotal +' ('+ total +' total)');
  }
}

/* --------------------- CSS ---------------------------------------------*/
$.widget.bridge('uibutton', $.ui.button);
$.material.init();
/* --------------------- jQuery validation ---------------------------------------------*/

$.validator.setDefaults({
    errorElement: "span",
    errorClass: "help-block",

    highlight: function (element, errorClass, validClass) {
        if (!$(element).hasClass('novalidation')) {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if (!$(element).hasClass('novalidation')) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        }
    },
    errorPlacement: function (error, element) {
        if (element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        }
        else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
            error.insertAfter(element.parent().parent());
        }
        else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
            error.appendTo(element.parent().parent());
        }
        else {
            error.insertAfter(element);
        }
    }
});
/* ---------------------------- Inputmask -------------------------------------*/

Inputmask.extendAliases({
  numeric :{rightAlign: false},
});

/* ---------------------------- ChartJS -------------------------------------*/

var chartColor = Chart.helpers.color;
Chart.defaults.scale.ticks.beginAtZero = true;
Chart.plugins.register({
  beforeDraw: function(chartInstance, easing) {
    var ctx = chartInstance.chart.ctx;
    ctx.backgroundColor = chartColor(ctx.backgroundColor).alpha(0.5).rgbString()
  }
});


isMobile = false;
if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4)))
{
    isMobile = true;
}

$( document ).ready(function() {
/* -------------------- Sidebar ----------------------------------------*/
    $('.sidebar-toggle').on("click", function(e) {
      if($("body").hasClass('sidebar-collapse')) {
        localStorage_store('sidebar-collapse','no');
      }
      else {
        localStorage_store('sidebar-collapse','yes');
      }
    });
    if(!isMobile && localStorage_get('sidebar-collapse')=='yes') {
        $("body").addClass('sidebar-collapse');
    }
    else {
      $("body").removeClass('sidebar-collapse');
    }

    $('.table-responsive').on('shown.bs.dropdown', function (e) {
        var $table = $(this),
            $menu = $(e.target).find('.dropdown-menu'),
            tableOffsetHeight = $table.offset().top + $table.height(),
            menuOffsetHeight = $menu.offset().top + $menu.outerHeight(true);

        if (menuOffsetHeight > tableOffsetHeight)
            $table.css("padding-bottom", menuOffsetHeight - tableOffsetHeight);
    });

    $('.table-responsive').on('hide.bs.dropdown', function () {
        $(this).css("padding-bottom", 0);
    })

/* -------------------- components ----------------------------------------*/
    $('.date').datepicker({format: "yyyy-mm-dd",weekStart: 1,todayBtn: "linked",clearBtn: true,daysOfWeekHighlighted: "0,6",autoclose:true, showClear:true,todayHighlight: true});
    $('.datetime').datetimepicker({showClear:true});
    $("form").validate();
    //Mobile Problem !!!!
//    $("input").inputmask({rightAlignNumerics:false});
    $('.select2').select2({
                      dropdownAutoWidth : true,
                      width : '98%'
                  });
    $(".select2-selection").addClass("dt-select2-filter").addClass('col-sm-12');
    $(".select2-selection__arrow").addClass("material-icons").html("arrow_drop_down");
    $('.select2').on('change', function() {
        $(this).valid();
    });
    moment.updateLocale(moment.locale(), { invalidDate: "" });
});
