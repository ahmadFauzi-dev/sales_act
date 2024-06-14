<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
$quotaionInfo = $this->CI->app_lib->getDataQuotation($t_quotation_id);
  $quotaionInfo['approved_by_name'] = ucwords(strtolower($quotaionInfo['approved_by_name']));
*/
  $CI =& get_instance();
  $loginID = $this->CI->koje_system->getLoginID();
  $logo = img(array('src' => $this->CI->koje_system->getLogoURL(),'width'=>'100px', 'alt'=>'Logo'));
  $quotaionInfo = $this->CI->app_lib->getDataQuotation($t_quotation_id);
  $quotaionInfo['approved_by_name'] = ucwords(strtolower($quotaionInfo['approved_by_name']));
  $prospectInfo = $this->CI->app_lib->getDataProspect($quotaionInfo['t_prospect_id']);
  $deal = $this->CI->app_lib->getQuotationStatus('DEAL');
  $now = date("d-M-Y");
  $expired_date = dateFormat($quotaionInfo['expired_date']);
  $sum_amount   = 'Rp. '. $this->CI->koje_system->number_format($quotaionInfo['sum_amount']);
  $sum_total    = 'Rp. '. $this->CI->koje_system->number_format($quotaionInfo['sum_total']);

  $sql = "SELECT  row_number() OVER (ORDER BY t_quotation_item_id)||'. '|| name AS name,
                  utils_currency_fmt(amount) amount,
                  tax|| '%' tax,
                  utils_currency_fmt(total) total,
                  notes
          FROM v_quotation_item
          where t_quotation_id=$t_quotation_id
          ";
  $data = $this->CI->adodb->getAll($sql);
  $params = array(
    'cols'  => array('name','amount','tax','total','notes'),
    'heading' => array('<u>Item Name</u>', '<u>Amount</u>', '<u>Tax</u>', '<u>Sub Total</u>'),
    'data'  => $data,
    'script' => false,
  );

  $quotaionItem   = html_table_clear($params);

  $sql_alt = "select row_number() OVER (ORDER BY name0)||'. '||name0 as name, q1.*
              from(SELECT concat_ws(' ',product_name,speed,uom_desc) AS name0,
                  utils_currency_fmt(otc_charge) as otc_charge,
                  utils_currency_fmt(mtce_charge) as mtce_charge,
                  utils_currency_fmt(mtce_charge + otc_charge) as total
          FROM v_quotation_item_alt
          where t_quotation_id=$t_quotation_id and status<>'$deal'
          order by product_name,speed,uom_desc
          ) q1 order by name
          ";
  $data_alt = $this->CI->adodb->getAll($sql_alt);
  if($data_alt)
  {
    $params_alt = array(
      'cols'  => array('name','mtce_charge','otc_charge','total'),
      'heading' => array('<u>Item Name</u>', '<u>Monthly</u>', '<u>Installation</u>', '<u>Sub Total</u>'),
      'data'  => $data_alt
    );

    $quotaionItemAlt = "<center><strong>Alternative</strong></center><br/>".
                          html_table($params_alt);
  }
  else {
    $quotaionItemAlt = "";
  }

  $HTML = <<<EOF
  <html>
    <body><table width="100%">
        <tr>
          <td>
            {$logo}
          </td>
          <td align="center">
              <center><font size="14"><b>Q U O T A T I O N</b></font><br/></center>
              <center><b>Date: {$now}</b></center>
          </td>
        </tr>
      </table>
      <br /><br /><br />
      <table width="100%">
        <tr>
          <td width="35%">From
              <br /><strong>PT PGas Telekomunikasi Nusantara</strong>
              <br />Jl. Kyai H. Zainul Arifin No.20, Krukut, Tamansari
              <br />Kota Jakarta Barat
              <br />Daerah Khusus Ibukota Jakarta
              <br />11140
          </td>
          <td width="30%">To
            <br /><strong>{$prospectInfo['company_name']}</strong>
            <br />{$prospectInfo['address_street']}
            <br />{$prospectInfo['address_city']}
            <br />{$prospectInfo['address_zip']}
          </td>
          <td width="35%">
            <table width="100%">
              <tr><td width="28%">No</td><td width="2%">:</td><td width="70%">{$quotaionInfo['quotation_no']}</td></tr>
              <tr><td width="28%">Name</td><td width="2%">:</td><td width="70%">{$quotaionInfo['name']}</td></tr>
              <tr><td width="28%">Valid Until</td><td width="2%">:</td><td width="70%">{$expired_date}</td></tr>
            </table>
          </td>
        </tr>
      </table>
      <br /><br /><br />
      <center><strong>Detail Component</strong></center><br/>
      {$quotaionItem}
      <br /><br /><br />
      <table width="100%">
        <tr>
          <td>
          </td>
          <td>
            <table width="100%">
              <tr><td><u>Summary</u></td><td></td><td></td></tr>
              <tr><td width="30%">Amount</td><td width="2%">:</td><td width="68%">{$sum_amount}</td></tr>
              <tr><td width="30%">Total</td><td width="2%">:</td><td width="68%">{$sum_total}</td></tr>
            </table>
          </td>
        </tr>
      </table>
      {$quotaionItemAlt}
      <br /><br /><br /><br /><br />
      <table width="100%">
        <tr>
          <td>Approved By<br/><br/><br/><br/><br/></td>
          <td>Prepared By</td>
        </tr>
        <tr>
          <td>{$quotaionInfo['approved_by_name']}</td>
          <td>{$prospectInfo['sales_name']}</td>
        </tr>
      </table>
    </body>
  </html>
EOF;

 //print $HTML;exit;
  $CI->koje_pdf->setPrintHeader('Quotation');
  $CI->koje_pdf->setPrintFooter('PGasCom');
  $CI->koje_pdf->SetTitle('Quotation');
  $CI->koje_pdf->AddPage();
  $CI->koje_pdf->writeHTMLCell(0, 0, '', '', $HTML, 0, 1, 0, true, '', true);
  $CI->koje_pdf->Output('QT_'.$quotaionInfo['quotation_no'].'.pdf', 'I');
