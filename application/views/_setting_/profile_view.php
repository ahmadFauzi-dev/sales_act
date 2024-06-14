<?php
/*
@version v5.0  01-December-2018 yustanto@gmail.com
@copyright (c) 2010-2018 PT. Gratia Teknologi Internusa. All rights reserved.
@license Commercial
Reproduction and distribution of this software without permission is prohibited.
*/
	$myView = new profile_view();
	/* START OF DO NOT MODIFY */
	if (!isset($METHOD)) $METHOD = $this->router->method;
	$myView->$METHOD($VARS);
	/* END OF DO NOT MODIFY */

	class profile_view {
		function index($params=false) {
			$myForm = new KOJE_Form('MY_FORM');
			$myForm->setTitle($params['FORMS']['title']);
			$myForm->setItem($params['FORMS']['item']);
			$myForm->setUrlBack(base_url());
			$myForm->setUrlCancel(base_url());
			$myForm->setDisplay($params['FORMS']['display']);
			$myForm->setResult($params['FORMS']['result']);
			$myForm->show($params);
		}
		function pict($params=false) {
			$myForm = new KOJE_Form('MY_FORM');
			$myForm->setTitle($params['FORMS']['title']);
			$myForm->setItem($params['FORMS']['item']);
			$myForm->setDisplay($params['FORMS']['display']);
			$myForm->setResult($params['FORMS']['result']);
			$myForm->setUrlBack($params['FORMS']);
			$myForm->setUrlCancel($params['FORMS']);
			$myForm->show($params);
		}
	}
	?>
