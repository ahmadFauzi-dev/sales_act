<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
  defined('BASEPATH') OR exit('No direct script access allowed');

  $user = $this->ion_auth->user()->row();
  print heading('Welcome, '. $user->first_name, 2, array('id' => 'welcome', 'class' => 'text-green'));
  print "<br/><a href='".base_url()."data/sales.apk'><b>MOBILE VERSION</b></a>";
