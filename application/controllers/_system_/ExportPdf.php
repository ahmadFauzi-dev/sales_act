<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

  class ExportPdf extends KOJE_Controller {
    function index($params=false) {
      if(!$params) {
        $params = $_REQUEST;
      }
      $this->load->helper('koje_pdf');
      $lov = '_lov_/'.$params['lov'];
      $this->load->template($lov, $params, true, 'none');
  }
}
