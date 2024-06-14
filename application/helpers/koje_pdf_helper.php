<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
  require_once(APPPATH.'third_party/tcpdf/tcpdf.php');

  global $config;
  $CI =& get_instance();
  $koje_pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $koje_pdf->SetCreator(PDF_CREATOR);
  $koje_pdf->SetAuthor('Yustanto');
  $koje_pdf->SetTitle($config['KOJE_APP_TITLE']);
  $koje_pdf->SetSubject($config['KOJE_APP_TITLE']);
  $koje_pdf->SetKeywords('koje');

  $koje_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
  $koje_pdf->setFooterData(array(0,64,0), array(0,64,128));

  $koje_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 10));
  $koje_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 10));

  $koje_pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

  $koje_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $koje_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $koje_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

  $koje_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $koje_pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $koje_pdf->setLanguageArray($l);
  }

  $koje_pdf->setFontSubsetting(true);
  $koje_pdf->SetFont('dejavusans', '', 8, '', true);
  $koje_pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

  $CI->koje_pdf = $koje_pdf;
