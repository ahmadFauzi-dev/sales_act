<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/

class Welcome extends KOJE_Controller {
	public function index()
	{
				$this->load->template('welcome', array(), true, 'all');
	}
}
