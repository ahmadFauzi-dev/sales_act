<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'/third_party/tcpdf/tcpdf.php');
require_once(APPPATH.'/third_party/tcpdf/tcpdf_barcodes_1d.php');

define ('USR_PDF_MARGIN_TOP', 6);

/**
 * Bottom margin.
 */
define ('USR_PDF_MARGIN_BOTTOM', 5);

/**
 * Left margin.
 */
define ('USR_PDF_MARGIN_LEFT', 5);

/**
 * Right margin.
 */
define ('USR_PDF_MARGIN_RIGHT', 5);


class syscmrcsof2 extends KOJE_Controller {
	function __construct() {
		parent::__construct();
	}
	function index() {		
		$pdf = new koje_pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Yustanto');
		$pdf->SetTitle('SOF');
		$pdf->SetSubject('Gratia');
		$pdf->SetKeywords('Gratia');
		$pdf->SetHeaderData("assets/logo.png", PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
		$pdf->setFooterData(array(0,64,0), array(0,64,128));
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(USR_PDF_MARGIN_LEFT, USR_PDF_MARGIN_TOP, USR_PDF_MARGIN_RIGHT,PDF_MARGIN_BOTTOM);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setFontSubsetting(true);
		$pdf->setShowPageNo(false);

		$pdf->SetFont('helvetica', '', 10, '', true);
		$CI =& get_instance();
		$htmlheader =	'<html>
								<body style="font-size:8px">
									<table>
										<tr>
											<td width="3%">&nbsp;</td>
											<td width="90%" align="right"><img width="125" height="40" src="assets/logo.png" alt="LOGO"></td>
										</tr>
									</table>
								</body>
							</html>';
		
		
		$htmlFooter =	'<html>
								<body>
									<table style="font-size:8px;" width="100%">
										<tr>
											<td width="5%" align="left"></td>
											<td width="95%" align="right"><img width="750" height="90" src="assets/footer.png" alt="LOGO"></td>
										</tr>
									</table>
								</body>
						</html>';
		$htmlInfo = '';
		
		$pdf->setHeaderData2($htmlheader);
 		$pdf->setFooterData2($htmlFooter); 
		$pdf->setInfoData($htmlInfo);

		$pdf->startPageGroup();
		$pdf->AddPage();
			//Set Content PDF Disini
		$htmlstyle = '
			<html>
			<head>
				<style>
			.content{
				font-family:Tahoma;
				font-size:11px;
			}
			.content_1{
				font-family:Tahoma;
				font-size:11px;
				line-height: 0.5;
			}
			.small {
				line-height: 1px;
		  }
			.tableHeader{
				font-family:Times New Roman;
				font-size:14px;
			}
			.tdWO{
				border-bottom:#000000 1px solid;
			}
			.judul{
				font-family:Times New Roman;
				font-size:11px;
			}
			.judul1{
				font-family:Tahoma;
				font-size:18px;
			}
			.judul2{
				font-family:Times New Roman;
				font-size:12px;
			}
			.tableInfo{
				font-family:Times New Roman;
				font-size:10px;
			}
			.tablesign{
				font-family:Times New Roman;
				font-size:12px;
			}
			.tr1{
				font-family:Times New Roman;
				font-size:11px;
				background-color:#cccccc;
			}
			.td1{
				font-family:Times New Roman;
				font-size:10px;
				border:1px solid #000000;
			}
			.td2{
				font-family:Times New Roman;
				font-size:10px;
			}
			.tdSign{
				border-top:1px solid #666666;	
			}

			td {
				vertical-align: middle;
			}

			/* SQUARED TWO */
.squaredTwo {
	width: 28px;
	height: 28px;
	background: #fcfff4;

	background: -webkit-linear-gradient(top, #fcfff4 0%, #dfe5d7 40%, #b3bead 100%);
	background: -moz-linear-gradient(top, #fcfff4 0%, #dfe5d7 40%, #b3bead 100%);
	background: -o-linear-gradient(top, #fcfff4 0%, #dfe5d7 40%, #b3bead 100%);
	background: -ms-linear-gradient(top, #fcfff4 0%, #dfe5d7 40%, #b3bead 100%);
	background: linear-gradient(top, #fcfff4 0%, #dfe5d7 40%, #b3bead 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#fcfff4", endColorstr="#b3bead",GradientType=0 );
	margin: 20px auto;

	-webkit-box-shadow: inset 0px 1px 1px white, 0px 1px 3px rgba(0,0,0,0.5);
	-moz-box-shadow: inset 0px 1px 1px white, 0px 1px 3px rgba(0,0,0,0.5);
	box-shadow: inset 0px 1px 1px white, 0px 1px 3px rgba(0,0,0,0.5);
	position: relative;
}
.squaredTwo label {
	cursor: pointer;
	position: absolute;
	width: 20px;
	height: 20px;
	left: 4px;
	top: 4px;

	-webkit-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,1);
	-moz-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,1);
	box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,1);

	background: -webkit-linear-gradient(top, #222 0%, #45484d 100%);
	background: -moz-linear-gradient(top, #222 0%, #45484d 100%);
	background: -o-linear-gradient(top, #222 0%, #45484d 100%);
	background: -ms-linear-gradient(top, #222 0%, #45484d 100%);
	background: linear-gradient(top, #222 0%, #45484d 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#222", endColorstr="#45484d",GradientType=0 );
}

.squaredTwo label:after {
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
	filter: alpha(opacity=0);
	opacity: 0;
	content: "";
	position: absolute;
	width: 9px;
	height: 5px;
	background: transparent;
	top: 4px;
	left: 4px;
	border: 3px solid #fcfff4;
	border-top: none;
	border-right: none;

	-webkit-transform: rotate(-45deg);
	-moz-transform: rotate(-45deg);
	-o-transform: rotate(-45deg);
	-ms-transform: rotate(-45deg);
	transform: rotate(-45deg);
}

.squaredTwo label:hover::after {
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=30)";
	filter: alpha(opacity=30);
	opacity: 0.3;
}

.squaredTwo input[type=checkbox]:checked + label:after {
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
	filter: alpha(opacity=100);
	opacity: 1;
}

				</style>
			</head>
			<body>';
		
		
		$t_account_product_id = $_REQUEST["t_account_product_id"];
		$rowACP = $CI->adodb->getRow("select *, current_date as v_date from v_order_account_product_bk where t_account_product_id=".$t_account_product_id);
		$t_account_id = $rowACP["t_account_id"];
		$rowAC  = $CI->adodb->getRow("select account_no, company_name, address_info, d_segmentation_desc, website_company,
									npwp_company, telp, fax from v_account where t_account_id=".$t_account_id);
		$rowACB = $CI->adodb->getRow("select * from v_account_bill where t_account_id=".$t_account_id);
		$rowACT = $CI->adodb->getRow("select * from v_account_tech where t_account_id=".$t_account_id);
		

					$html = $htmlstyle;
					$html .= '<div id="content">
								<table width="100%" cellpadding="0" cellspacing="0" align="center">
								  <tr><td>&nbsp;<br>&nbsp;<br><br></td></tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td height="38" bgcolor="#99e6ff" class="judul1" width="90%" align="center" style="border: 3px double #eeee; padding: 5px;">
										<div style="font-size:6pt">&nbsp;</div>
										<strong>SALES ORDER FORM (SOF)</strong>
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="13%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;No SOF
									</td>
									<td class="content" height="20" width="32%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px double #eeee; padding: 5px;">
									<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;:
									</td>
									<td class="content" height="20" width="13%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;Customer ID
									</td>
									<td class="content" height="20" width="32%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px double #eeee; padding: 5px;">
									<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;:
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="13%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;SOF Date
									</td>
									<td class="content" height="20" width="32%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px double #eeee; padding: 5px;">
									<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;:
									</td>
									<td class="content" height="20" width="13%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;Customer Info
									</td>
									<td class="content" height="20" width="32%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px double #eeee; padding: 5px;">
									<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;New
									&nbsp;&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;Existing
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td height="25" class="content" width="90%" align="left" style="border: 3px none #eeee; padding: 5px;">
										<div style="font-size:2pt">&nbsp;</div>
										<strong>&nbsp;&nbsp;Isi dengan huruf cetak(SOF)</strong><i> Fill in with capital letter</i>
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="14%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;NEW
									</td>
									<td class="content" height="20" width="14%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px double #eeee; padding: 5px;">
									<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;UPGRADE
									</td>
									<td class="content" height="20" width="14%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;DOWNGRADE
									</td>
									<td class="content" height="20" width="14%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px double #eeee; padding: 5px;">
									<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;UPDATE
									</td>
									<td class="content" height="20" width="14%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;RENEWAL
									</td>
									<td class="content" height="20" width="20%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px double #eeee; padding: 5px;">
									<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;OTHERS&nbsp;&nbsp;........
									</td>
								  </tr>
								</table>
								<table width="100%" cellpadding="0" cellspacing="0" align="center">
								  <tr>
									<td width="5%">&nbsp;</td>
									<td height="32" class="content" width="90%" align="left" style="border: 3px none #eeee; padding: 5px;">
										<div style="font-size:10pt">&nbsp;</div>
										<strong>&nbsp;&nbsp;<img width="10" height="10" src="assets/images/bullet.png" >&nbsp;&nbsp;Informasi Perusahaan / </strong><i>Company Information</i>
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:4pt">&nbsp;</div>
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:4pt">&nbsp;</div>
									<strong>Nama Perusahaan /</strong><br/><i>Company Name</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:4pt">&nbsp;</div>
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									<div style="font-size:4pt">&nbsp;</div>
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Jenis Bidang Usaha /</strong><br/><i>Type of Business</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>No. NPWP /</strong><br/><i>Tax Registration Number</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>No. SKDP /</strong><br/><i>Company Domicile Number</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Alamat Perusahaan /</strong><br/><i>Company Address</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>No. Telp /</strong><br/><i>Telephone Number</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>No. Handphone /</strong><br/><i>Mobile Phone Number</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>No. Fax /</strong><br/><i>Fax Number</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Alamat Web /</strong><br/><i>Web Address</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Akta Terakhir/Surat Kuasa /</strong><br/><i>Latest of Article of Association/Power of Attorney</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>No. Perijinan (NAP/ISP/lainnya)/ /</strong><br/><i>No. License (NAP/ISP/other)</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" width="3%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" width="27%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" width="60%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								</table>
								<table width="100%" cellpadding="0" cellspacing="0" align="center">
								  <tr>
									<td width="5%">&nbsp;</td>
									<td height="32" class="content" width="90%" align="left" style="border: 3px none #eeee; padding: 5px;">
										<div style="font-size:10pt">&nbsp;</div>
										<strong>&nbsp;&nbsp;<img width="10" height="10" src="assets/images/bullet.png" >&nbsp;&nbsp;Penanggung Jawab Keuangan / </strong><i>Authorized Financial Personnel</i>
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="23" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									</td>
									<td class="content" height="23" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									<strong>Nama yang diberi wewenang /</strong><br/><i>Authorized Person</i>
									</td>
									<td class="content" height="23" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									</td>
									<td class="content" height="23" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Bagian / Jabatan /</strong><br/><i>Department / Job Title</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>No. Telp /</strong><br/><i>Telephone Number</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>No. Handphone /</strong><br/><i>Mobile Phone Number</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>No. Handphone /</strong><br/><i>Mobile Phone Number</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Alamat Email /</strong><br/><i>Email Address</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" width="3%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" width="27%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" width="60%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								</table>';
								
								$html .= '</div>
					</body>
					</html>';	
						
		$pdf->writeHTML($html, true, 0, true, 0);	
			
		$pdf->AddPage();
		$rowACPI = $CI->adodb->execute("select t_account_product_item_id, t_account_product_id, name, d_type_desc, t_product_item_id, amount, tax from v_account_product_item where t_account_product_id=".$t_account_product_id);
		
		$html = $htmlstyle;
		$html .= '<div id="content">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
						<tr><td>&nbsp;<br/>&nbsp;<br/></td></tr>
						<tr><td>&nbsp;<br>&nbsp;<br></td></tr>';
				$html .= '<tr><td width="5%">&nbsp;</td></tr>
								<table width="100%" cellpadding="0" cellspacing="0" align="center">
								  <tr>
									<td width="5%">&nbsp;</td>
									<td height="32" class="content" width="90%" align="left" style="border: 3px none #eeee; padding: 5px;">
										<div style="font-size:10pt">&nbsp;</div>
										<strong>&nbsp;&nbsp;<img width="10" height="10" src="assets/images/bullet.png" >&nbsp;&nbsp;Penanggung Jawab Teknis / </strong><i>Authorized Technical Personnel</i>
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="23" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									</td>
									<td class="content" height="23" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									<strong>Kontak Teknis /</strong><br/><i>Technical Contact Person</i>
									</td>
									<td class="content" height="23" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									</td>
									<td class="content" height="23" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Bagian / Jabatan /</strong><br/><i>Department / Job Title</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>No. Telp /</strong><br/><i>Telephone Number</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>No. Handphone /</strong><br/><i>Mobile Phone Number</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>No. Fax /</strong><br/><i>Fax Number</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Alamat Email /</strong><br/><i>Email Address</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" width="3%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" width="27%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" width="60%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								</table>	
								<table width="100%" cellpadding="0" cellspacing="0" align="center">
								  <tr>
									<td width="5%">&nbsp;</td>
									<td height="32" class="content" width="90%" align="left" style="border: 3px none #eeee; padding: 5px;">
										<div style="font-size:10pt">&nbsp;</div>
										<strong>&nbsp;&nbsp;<img width="10" height="10" src="assets/images/bullet.png" >&nbsp;&nbsp;Permintaan Jasa / </strong><i>Service Request</i>
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="23" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									</td>
									<td class="content" height="23" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									<strong>Tipe Layanan /</strong><br/><i>Type of Service</i>
									</td>
									<td class="content" height="23" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<br>
									<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;IPLC
									&nbsp;&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;DPLC&nbsp;&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;IEPL
									&nbsp;&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;DEPL&nbsp;&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;IP TRANSIT
									&nbsp;&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;INNERCITY
									<div style="font-size:6pt">&nbsp;</div>
									</td>
									<td class="content" height="23" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Varian Paket /</strong><br/><i>Package Varian</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;Silver
									&nbsp;&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;Gold&nbsp;&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;Platinum
									&nbsp;&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;Diamond
									<p style="line-height:10px;margin:0px;">
									<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;Fixed
									&nbsp;&nbsp;&nbsp;<img width="10" height="10" src="assets/images/xbox1.png" >&nbsp;Burstable
									</p>
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Kapasitas /</strong><br/><i>Capacity/ Bandwith</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Tipe Interface /</strong><br/><i>Interface Type</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Target Pemasangan /</strong><br/><i>Request Ready for Service Date (RFS)</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Catatan Teknis /</strong><br/><i>Technical Notes</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Jaminan Tingkat Layanan /</strong><br/><i>Service Level Guarantee (SLG)</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Waktu Pengiriman /</strong><br/><i>Delivery Time</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" width="3%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" width="27%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" width="60%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								</table>
								<table width="100%" cellpadding="0" cellspacing="0" align="center">
								  <tr>
									<td width="5%">&nbsp;</td>
									<td height="32" class="content" width="90%" align="left" style="border: 3px none #eeee; padding: 5px;">
										<div style="font-size:10pt">&nbsp;</div>
										<strong>&nbsp;&nbsp;<img width="10" height="10" src="assets/images/bullet.png" >&nbsp;&nbsp;Informasi Lokasi / </strong><i>Site Information</i>
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="23" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									</td>
									<td class="content" height="23" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									<strong>Lokasi Asal /</strong><br/><i>Origin</i>
									</td>
									<td class="content" height="23" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									</td>
									<td class="content" height="23" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Lokasi Tujuan /</strong><br/><i>Destination</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" width="3%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" width="27%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" width="60%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								</table>
								<table width="100%" cellpadding="0" cellspacing="0" align="center">
								</table>
								<table width="100%" cellpadding="0" cellspacing="0" align="center">
								  <tr>
									<td width="5%">&nbsp;</td>
									<td height="32" class="content" width="90%" align="left" style="border: 3px none #eeee; padding: 5px;">
										<div style="font-size:10pt">&nbsp;</div>
										<strong>&nbsp;&nbsp;<img width="10" height="10" src="assets/images/bullet.png" >&nbsp;&nbsp;Biaya / </strong><i>Charges</i>
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="23" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									</td>
									<td class="content" height="23" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									<strong>Biaya Instalasi /</strong><br/><i>Installation Fee </i>
									</td>
									<td class="content" height="23" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									</td>
									<td class="content" height="23" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px double #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									<div style="font-size:6pt">&nbsp;</div>
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Biaya Bulanan /</strong><br/><i>Monthly Fee</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Lainnya /</strong><br/><i>Others</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="27%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									<strong>Jangka Waktu Berlangganan /</strong><br/><i>Period of Subscription</i>
									</td>
									<td class="content" height="20" width="57%" align="left" style="border-bottom: 1px solid #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" height="20" width="3%" align="left" style="border-bottom: 3px none #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								  <tr>
									<td width="5%">&nbsp;</td>
									<td class="content" width="3%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" width="27%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px none #eeee; padding: 5px;">
									</td>
									<td class="content" width="60%" align="left" style="border-bottom: 3px double #eeee; border-top: 3px none #eeee; border-left: 3px none #eeee; border-right: 3px double #eeee; padding: 5px;">
									</td>
								  </tr>
								</table>
								<table width="100%" cellpadding="0" cellspacing="0" align="center">
							  </table>';
				$html .= '</div>
					</body>
					</html>';	
						
		$pdf->writeHTML($html, true, 0, true, 0);	
			
		$pdf->AddPage();
		$rowACPI = $CI->adodb->execute("select t_account_product_item_id, t_account_product_id, name, d_type_desc, t_product_item_id, amount, tax from v_account_product_item where t_account_product_id=".$t_account_product_id);
		
		$html = $htmlstyle;
		$html .= '<div id="content">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
						<tr><td>&nbsp;<br/>&nbsp;<br/></td></tr>
						<tr><td>&nbsp;<br>&nbsp;<br></td></tr>';
				$html .= '<tr><td width="5%">&nbsp;</td></tr>';
						  $html .= '<tr><td width="5%">&nbsp;</td></tr>';
						  $html .= '<tr>
									  <td width="5%">&nbsp;</td>
									  <td class="content" width="3%">&nbsp;</td>
									  <td class="content" width="35%" align="left" ><strong>Syarat dan Ketentuan / </strong><i>Terms and Condition</i></td>
									  <td class="content" width="50%" align="left" ></td>
								   </tr>';
						  $html .= '<tr><td width="3%">&nbsp;</td></tr>';
						  $html .= '<tr>
												  <td width="7%">&nbsp;</td>
												  <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><strong>1.</strong></td>
												  <td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>Harga belum termasuk PPN</strong></td>
												  <td width="10%">&nbsp;</td>
												  <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><i>1.</i></td>
												  <td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><i>The price shall exclude Tax</i></td>
											   </tr>';
						  $html .= '<tr>
												  <td width="7%">&nbsp;</td>
												  <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><strong>2.</strong></td>
												  <td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>Ketentuan Pembayaran adalah 1 Bulan di muka</strong></td>
												  <td width="10%">&nbsp;</td>
												  <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><i>2.</i></td>
												  <td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><i>Terms of Payment is 1 month in advance</i></td>
											   </tr>';					 
						  $html .= '<tr>
												  <td width="7%">&nbsp;</td>
												  <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><strong>3. </strong></td>
												  <td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>Masa kontrak minimum adalah 1 tahun
													  Jika melakukan pemutusan sebelum waktunya sementara PGASCOM dapat memenuhi SLG pelanggan memiliki kewajiban untuk membayar jumlah yang setara dengan 100% dari MRC untuk sisa periode kontrak
												  </strong></td>
												  <td width="10%">&nbsp;</td>
												  <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><i>3.</i></td>
												  <td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><i>Minimum contract term is 1 years
												  <br>In the event of pre-mature termination while  PGASCOM can meet the SLG, customer has obligation to pay a sum equal to 100% of the MRC for the remaining period of contract
												  </i></td>
											   </tr>';					 
						  $html .= '<tr>
												  <td width="7%">&nbsp;</td>
												  <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><strong>4.</strong></td>
												  <td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>Ketersediaan Kapasitas Tersedia</strong></td>
												  <td width="10%">&nbsp;</td>
												  <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><i>4. </i></td>
												  <td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><i>Capacity Availability is Available
												  </i></td>
											   </tr>';					 
						  $html .= '<tr>
												  <td width="7%">&nbsp;</td>
												  <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><strong>5.</strong></td>
												  <td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>SLA </strong></td>
												  <td width="10%">&nbsp;</td>
												  <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><i>5.</i></td>
												  <td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><i>SLA</i></td>
											   </tr>';					
				$html .= '<tr><td width="5%">&nbsp;</td></tr>';
				$html .= '<tr><td width="5%">&nbsp;</td></tr>';
				$html .= '<tr>
							<td width="5%">&nbsp;</td>
							<td class="content" width="3%">&nbsp;</td>
							<td class="content" width="60%" align="left" ><strong>Perjanjian Tingkat Layanan / </strong><i>Service Level Guarantee</i></td>
							<td class="content" width="50%" align="left" ></td>
						 </tr>';
				$html .= '<tr><td width="3%">&nbsp;</td></tr>';
				$html .= '<tr>
										<td width="7%">&nbsp;</td>
										<td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><strong>1.</strong></td>
										<td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>PGASCOM	akan menjamin Pelanggan dalam ketersediaan layanan dari layanan telekomunikasi</strong></td>
										<td width="10%">&nbsp;</td>
										<td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><i>1.</i></td>
										<td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><i>PGASCOM shall guarantee CUSTOMER in service availability of telecommunication service.</i></td>
									 </tr>';
				$html .= '<tr>
										<td width="7%">&nbsp;</td>
										<td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><strong>2.</strong></td>
										<td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>Jaminan tingkat layanan ditentukan dengan rumus berikut : </strong><p style="line-height:6px;margin:0px;"></p></td>
										<td width="10%">&nbsp;</td>
										<td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><i>2.</i></td>
										<td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><i>The service level guarantee is determined with the following formula : </i></td>
									 </tr>';					 
			 $html .= '<tr>
									 <td width="7%">&nbsp;</td>
									 <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;">&nbsp;</td>
									 <td class="content" width="38%" align="center" style="border: 1px solid #eeee; padding: 1px; background-color:#C0C0C0;">Jaminan Tingkat Layanan (%) :  <br/><u>([Jam Penggunaan Per Bulan - Waktu Henti] ) x 100</u><br/>Total Jam Per Bulan</td>
									 <td width="10%">&nbsp;</td>
									 <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;">&nbsp;</td>
									 <td class="content" width="38%" align="center" style="border: 1px solid #eeee; padding: 1px; background-color:#C0C0C0;">Service Level Guarantee (%) : <br/><u>(usage hours per month – down time) x 100</u><br/>Total  hours per month</td>
								  </tr>';						

				//Restitusi
				$html .= '<tr><td width="5%">&nbsp;</td></tr>';
				$html .= '<tr><td width="5%">&nbsp;</td></tr>';
				$html .= '<tr>
							<td width="5%">&nbsp;</td>
							<td class="content" width="3%">&nbsp;</td>
							<td class="content" width="60%" align="left" ><strong>Restitusi / </strong><i>Restitution</i></td>
							<td class="content" width="50%" align="left" ></td>
						 </tr>';
				$html .= '<tr><td width="3%">&nbsp;</td></tr>';
				$html .= '<tr>
										<td width="7%">&nbsp;</td>
										<td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><strong>1.</strong></td>
										<td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>Jika ketersediaan jaringan lebih rendah dari jaminan tingkat layanan yang dijelaskan di atas, PELANGGAN akan menerima penggantian dari biaya bulanan dengan membuat penyesuaian sebagai berikut : </strong><p style="line-height:6px;margin:0px;"></p></td>
										<td width="10%">&nbsp;</td>
										<td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><i>1.</i></td>
										<td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><i>If the network availability is lower than the service level guarantee described above, CUSTOMER will receive restitution from the monthly cost by making adjusment as follow :</i></td>
									 </tr>';
				$html .= '<tr>
									 <td width="7%">&nbsp;</td>
									 <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;">&nbsp;</td>
									 <td class="content" width="38%" align="center" style="border: 1px solid #eeee; padding: 1px; background-color:#C0C0C0;">[Agreed Service Level – Actual Service Level] x Monthly Cost.</td>
									 <td width="10%">&nbsp;</td>
									 <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;">&nbsp;</td>
									 <td class="content" width="38%" align="center" style="border: 1px solid #eeee; padding: 1px; background-color:#C0C0C0;">[Agreed Service Level – Actual Service Level] x Monthly Cost.</td>
								   </tr>';			
			 $html .= '<tr><td width="1%"></td></tr>';					   
			 $html .= '<tr>
										<td width="7%">&nbsp;</td>
										<td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><strong>2.</strong></td>
										<td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>Jumlah restitusi pada bulan berjalan tidak lebih dari 20% dari biaya bulanan</strong></td>
										<td width="10%">&nbsp;</td>
										<td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><i>2.</i></td>
										<td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><i>The amount of restitution in a current month shall not more than 20% of the monthly cost</i></td>
									 </tr>';					 
			$html .= '<tr>
									 <td width="7%">&nbsp;</td>
									 <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><strong>3.</strong></td>
									 <td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>Ketentuan restitusi diklaim oleh PELANGGAN secara tertulis, selambat-lambatnya 20 (dua puluh) hari kalender  dan disetujui oleh PGASCOM dengan   menandatangani berita acara persetujuan restitusi</strong></td>
									 <td width="10%">&nbsp;</td>
									 <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><i>3.</i></td>
									 <td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><i>The provision of restitution is claimed by CUSTOMER in written, no later than 20 (twenty) days from the date and approved by PGASCOM by signing the minute of restitution consent report</i></td>
								  </tr>';					 						 
			$html .= '<tr>
								  <td width="7%">&nbsp;</td>
								  <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><strong>4.</strong></td>
								  <td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>Ketentuan restitusi dilakukan dengan mengurangi jumlah yang harus dibayarkan untuk biaya bulanan berikutnya dengan jumlah restitusi yang telah disetujui
								  </strong><br></td>
								  <td width="10%">&nbsp;</td>
								  <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><i>4.</i></td>
								  <td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><i>The provision of restitution is performed by deducting the amount payable for the next following monthly cost with the amount of restitution that has been approved</i></td>
							   </tr>';					 						 					  
			//sales Tanda tangan
$html .= '<tr>
			<td width="7%">&nbsp;</td>
			<td bgcolor="#99e6ff" class="content" height="20" width="30%" align="center" style="border-bottom: 3px double #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px double #eeee; padding: 5px;">
			<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;<img width="10" height="10" ><Strong>&nbsp;Nama Sales /</Strong><i>Sales Name</i>
			</td>
			<td bgcolor="#99e6ff" class="content" height="20" width="30%" align="center" style="border-bottom: 3px double #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px double #eeee; padding: 5px;">
			<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;<img width="10" height="10" ><Strong>&nbsp;Tanda Tangan</Strong> / Signature
			</td>
			<td bgcolor="#99e6ff" class="content" height="20" width="30%" align="center" style="border-bottom: 3px double #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px double #eeee; padding: 5px;">
			<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;<img width="10" height="10" >&nbsp;<Strong>Tanggal</Strong> / Date
			</td>
			</tr>';					 						 					  				   
			$html .= '<tr>
			<td width="7%">&nbsp;</td>
			<td class="content" height="60" width="30%" align="center" style="border-bottom: 3px double #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px none #eeee; padding: 5px;">
			<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;<img width="10" height="10" >
			</td>
			<td class="content" height="60" width="30%" align="center" style="border-bottom: 3px double #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px double #eeee; padding: 5px;">
			<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;<img width="10" height="10" >
			</td>
			<td class="content" height="60" width="30%" align="center" style="border-bottom: 3px double #eeee; border-top: 3px double #eeee; border-left: 3px double #eeee; border-right: 3px double #eeee; padding: 5px;">
			<div style="font-size:2pt">&nbsp;</div>&nbsp;&nbsp;<img width="10" height="10" >
			</td>
			</tr>';					 						 					  				   			
$html .= '</table><br/><br/>'; 

			
		$html .= '</div>
			</body>
			</html>
			';	
		$pdf->writeHTML($html, true, 0, true, 0);	

		$pdf->AddPage();

		$html = $htmlstyle;
		$html .= '<div id="content">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
						<tr><td>&nbsp;<br/>&nbsp;<br/></td></tr>
						<tr><td>&nbsp;<br>&nbsp;<br></td></tr>';
				$html .= '<tr><td width="5%">&nbsp;</td></tr>';
		$html .= '<tr>
								  <td width="7%">&nbsp;</td>
								  <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><strong></strong></td>
								  <td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>Dengan menandatangani Formulir Berlangganan ini, Pelanggan menyatakan bahwa (i) Pelanggan telah setuju untuk berlangganan sesuai dengan permintaan yang diajukan diatas, (ii) Pelanggan telah memahami, menyetujui dan terikat atas seluruh syarat dan ketentuan berdasarkan Kontrak Berlangganan (terlampir) tanpa kecuali dan (iii) Pelanggan setuju untuk memberikan dokumen yang diperlukan sebagai syarat untuk berlangganan. 
								  </strong></td>
								  <td width="10%">&nbsp;</td>
								  <td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><i></i></td>
								  <td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><i>By signing this Subscription Form, the Customer states that (i) the Customer has agreed to subscribe according to the request submitted above, (ii) the Customer has understood, and agreed to be bound to any prevailing terms and conditions under this Subscription Agreement (attached) without exception and (iii) Customer agrees to provide the required documents as a requirement for subscribing.</i></td>
							   </tr>';
		$html .= '</table><br/><br/>'; 				
		$html .= '</div>
			</body>
			</html>
			';	
		$pdf->writeHTML($html, true, 0, true, 0);		   					 						 					  
		$rowPGASSig = $CI->adodb->getRow("select * from t_sales where d_status='SLSST-03'");
		$rowSig = $CI->adodb->getRow("select sign_name, d_sign_position, ref.description as sign_pos from t_account a  LEFT JOIN ref_domain ref ON a.d_sign_position::text = ref.val::text AND ref.group_name::text = 'ACCOUNT__POSITIONS'::text where t_account_id=".$t_account_id );

		$html = $htmlstyle;
		$html .= '<div id="content">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
					  <tr><td>&nbsp;&nbsp;<br/><br/><br/><br/></td></tr>
					  <tr><td>
						<table width="100%" border="0" cellpadding="2" cellspacing="2" align="left" class="tableInfo">';
							$html .= '<tr>
										<td width="10%">&nbsp;</td>
										<td class="tableInfo" width="41.5%" align="center" style="border: 1px none #eeee; padding: 1px;">&nbsp;</td>
										<td width="3%">&nbsp;</td>
										<td class="tableInfo" width="41.5%" align="center" style="border: 1px none #eeee; padding: 1px;">&nbsp;</td>
									 </tr>';
					
							$html .= '<tr><td width="10%">&nbsp;</td></tr>';
							$html .= '<tr>
										<td width="3%">&nbsp;</td>
										<td class="tablesign" width="41.5%" align="center" style="border: 1px none #eeee; padding: 1px;"><strong>Pelanggan / </strong><i> Customer </i><br/>'.$rowAC["company_name"].'</td>
										<td width="10%">&nbsp;</td>
										<td class="tablesign" width="41.5%" align="center" style="border: 1px none #eeee; padding: 1px;">PGNCOM<br/>PT. PGAS TELEKOMUNIKASI NUSANTARA</td>
									 </tr>';
							$html .= '<tr><td width="10%">&nbsp;</td></tr>';
							$html .= '<tr>
										<td width="3%">&nbsp;</td>
										<td class="content" width="41.5%" align="center" style="border: 1px none #eeee; padding: 1px;">Materai 6000 & Stamp</td>
										<td width="10%">&nbsp;</td>
										<td class="tablesign" width="41.5%" align="center" style="border: 1px none #eeee; padding: 1px;">&nbsp;</td>
									 </tr>';
							$html .= '<tr><td width="10%">&nbsp;</td></tr>';
							$html .= '<tr>
										<td width="3%">&nbsp;</td>
										<td class="tablesign" width="41.5%" align="center" style="border: 1px none #eeee; padding: 1px;">[ _________________________ ]</td>
										<td width="10%">&nbsp;</td>
										<td class="tablesign" width="41.5%" align="center" style="border: 1px none #eeee; padding: 1px;">[ General Manager Komersial ]</td>
									 </tr>';
						$html .= '<tr>
									<td width="7%">&nbsp;</td>
									<td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"></td>
									<td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><i>Nama, Tandatangan dan Cap Badan Hukum</i></td>
									<td width="10%">&nbsp;</td>
									<td class="content" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><i></i></td>
									<td class="content" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><i>Nama, Tandatangan dan Cap Badan Hukum</i></td>
								  </tr>';
						$html .= '<tr>
								  <td width="7%">&nbsp;</td>
								  <td class="content_1" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"></td>
								  <td class="content_1" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>Nama /</strong><i>Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</i></td>
								  <td width="10%">&nbsp;</td>
								  <td class="content_1" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><i></i></td>
								  <td class="content_1" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>Nama /</strong><i>Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</i></td>
								</tr>';	  		  
						$html .= '<tr>
								  <td width="7%">&nbsp;</td>
								  <td class="content_1" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"></td>
								  <td class="content_1" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>Jabatan /</strong><i>Title &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</i></td>
								  <td width="10%">&nbsp;</td>
								  <td class="content_1" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><i></i></td>
								  <td class="content_1" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>Jabatan /</strong><i>Title &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</i></td>
								</tr>';	  		  
						$html .= '<tr>
								<td width="7%">&nbsp;</td>
								<td class="content_1" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><strong></strong></td>
								<td class="content_1" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>Date /</strong><i>Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</i></td>
								<td width="10%">&nbsp;</td>
								<td class="content_1" width="2%" align="left" style="border: 1px none #eeee; padding: 1px;"><i></i></td>
								<td class="content_1" width="38%" align="justify" style="border: 1px none #eeee; padding: 1px;"><strong>Date /</strong><i>Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</i></td>
							  </tr>';	  		  		
							$html .= '</table></td>
					  </tr>              
					</table><br/><br/>'; 
		$html .= '</div>
			</body>
			</html>
			';	
			
		$pdf->writeHTML($html, true, 0, true, 0);	

		ob_clean();
		$pdf->Output('sof-'.date("my").'-'.$_REQUEST["t_account_product_id"].'.pdf', 'I');
	
	}
}

?>

<style>
.table{
font-family:"Times New Roman", Times, serif
}
</style>
<script language="javascript">
alert("aaaa");
myWindowSOF.close();
</script>