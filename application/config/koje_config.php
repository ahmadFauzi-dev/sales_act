<?php
  date_default_timezone_set('Asia/Jakarta');
  require_once('koje_label.php');
  require_once('database.php');

  class PAGER {
      const P1						= "p1";
      const P2						= "p2";
      const P3						= "p3";
      const P4						= "p4";
      const P5						= "p5";
      const COL0          = 0;
      const COL1          = 1;
      const COL2          = 2;
      const COL3          = 3;
      const COL4          = 4;
      const COL5          = 5;
      const COL6          = 6;
      const COL7          = 7;
      const COL8          = 8;
      const COL9          = 9;
      const COL10         = 10;
  }
  class STYLE {
    const BUTTON_URL = "btn btn-raised btn-sm btn-round btn-primary";
	const BUTTON_URL_ACTIVE = "btn btn-raised btn-sm btn-round btn-active";
    const BUTTON_SUBMIT	 = "btn btn-raised btn-sm btn-round btn-primary";
    const BUTTON_BACK    = "btn btn-raised btn-sm btn-round btn-primary";
    const BUTTON_CANCEL  = "btn btn-raised btn-sm btn-round btn-info";
  }

  class TITLE_MSG {
    const ACCESS_NOT_ALLOWED		= 'YOU HAVE NO ACCESS RIGHT TO THIS MODULE. USE MENU.';
    const DIRECT_ACCESS_NOT_ALLOWED	= 'DIRECT ACCESS NOT ALLOWED. USE MENU.';
    const NO_DATA_FOUND				= 'Data Not Found.';
    const EXPORT_XLS				  = 'xls';
    const EXPORT_XLS_ALL		  = 'XLS';
    const EXPORT_PDF				  = 'pdf';
    const EXPORT_PDF_ALL			= 'pdf(all)';
    const BUTTON_SEARCH				= 'Find';
    const BUTTON_INSERT				= 'Add Record';
    const BUTTON_EDIT				  = 'Edit';
    const BUTTON_DELETE				= 'Delete';
    const BUTTON_DETAIL				= 'Detail';
    const BUTTON_RESET 				= "reset";
    const BUTTON_CANCEL 			= "Cancel";
    const BUTTON_BACK   			= "Done";
    const BUTTON_SUBMIT   		= "Submit";
    const BUTTON_AGAIN 				= "Again";
    const BUTTON_PICK_LOV 		= "&raquo";
    const BUTTON_PICK 				= 'pick';
    const BUTTON_CLEAR 				= 'clear';
    const BUTTON_EMPTY_PASSBACK 	= 'clear content';
    const LABEL_NO					= 'No.';
    const LABEL_ADMIN				= '';
    const LABEL_TOTAL				= 'T O T A L';
    const LABEL_PAGE 				= 'Page';
    const LABEL_RECORD 				= 'Record';
    const LABEL_ROW 				= 'row';
    const LABEL_FILTER 				= 'Filter';
    const LABEL_RESULT				= 'Result';
    const LABEL_SELECT_BELOW		= '';
    const LABEL_DETAIL				= 'Detail';
    const BUTTON_DROP 				= 'Drop';
    const BUTTON_CONVERT 			= 'Convert';
    const BUTTON_DUPLICATE 		= 'Duplicate';
    const BUTTON_NOTES 		    = 'Notes';
    const BUTTON_STATUS 		  = 'Status';
  }

/*-------------------------- base --------------------------------------*/
  $config['KOJE_APP_TITLE']       = "SALES ACTIVITY MONITORING";
  $config['KOJE_TITLE_LG']        = "SALES ACTIVITY MONITORING";
  $config['KOJE_TITLE_MINI']      = "S<b>A</b>M";
  $config['KOJE_FAVICON']         = "assets/icon.png";
  $config['KOJE_LOGO']            = "assets/logo.png";
  $config['KOJE_VERSION']         = "4.0";
  $config['KOJE_COPY_RIGHT']      = "Powered By Gratia Teknologi - 2018";
  $config['KOJE_FOLDER_UPLOAD']   = "data/upload/";
  $config['KOJE_BASEDIR']         = "C:/xampp/htdocs/salesactdev/";  
